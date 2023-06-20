<?php
require_once '../Auth/auth.php';
require_once '../errors/error.inc.php';

$auth = new Auth();
$errorHandler = new Err();

$setupData = json_decode(file_get_contents("./setup.suplike.json"));

if ($setupData->setup) {
    die("<h4>already setup</h4>");
}

if (!isset($_POST["server"]) || !isset($_POST["name"]) || !isset($_POST["pwd"])) {
    $errorHandler->err('Setup', 1, 'Missing database configuration info');
    die();
}

$serverName = $_POST["server"];
$dbUser = $_POST["name"];
$dbPassword = $_POST["pwd"];
$dbName = $_POST["db"] ?? 'suplike';

if (!$conn = mysqli_connect($serverName, $dbUser, $dbPassword)) {
    $errorHandler->err('Setup', 2, 'Could not connect to database');
    die();
}

function createEnvFile($serverName, $dbUser, $dbPassword, $dbName = 'suplike')
{
    $fileContent = <<<EOT
<?php
if (!defined('DB_DATABASE')) define('DB_DATABASE', '$dbName');
if (!defined('DB_HOST')) define('DB_HOST', '$serverName');
if (!defined('DB_USERNAME')) define('DB_USERNAME', '$dbUser');
if (!defined('DB_PASSWORD')) define('DB_PASSWORD', '$dbPassword');
if (!defined('DB_PORT')) define('DB_PORT', 3306);
if (!defined('SETUP')) define('SETUP', true);
EOT;

    file_put_contents("env.php", $fileContent);
}

createEnvFile($serverName, $dbUser, $dbPassword, $dbName);

function executeSqlFromFile($conn, $filename)
{
    $sqlFileContent = file_get_contents($filename);

    $sqlQueries = explode(';', $sqlFileContent);

    foreach ($sqlQueries as $query) {
        if (trim($query) === '') {
            continue;
        }

        if (!$conn->query($query)) {
            return false;
        }
    }

    return true;
}

if (!executeSqlFromFile($conn, "../../sql/suplike.sql")) {
    $errorHandler->err('Setup', 3, 'Error executing SQL file');
    die();
}

function createUser($conn, $auth, $user, $email, $password, $isAdmin)
{
    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT uidusers FROM users WHERE uidusers = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();

    if ($stmt->get_result()->num_rows) {
        die("Username is already in use");
    }

    $stmt = $conn->prepare("INSERT INTO users (uidusers, emailusers, pwdUsers, isAdmin) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $user, $email, $hashedPwd, $isAdmin);
    $stmt->execute();

    $getIdStmt = $conn->prepare("SELECT `idusers` FROM `users` WHERE `uidusers` = ?");
    $getIdStmt->bind_param("s", $user);
    $getIdStmt->execute();
    $userId = $getIdStmt->get_result()->fetch_assoc()['idusers'];

    $stmt = $conn->prepare("INSERT INTO `auth_key` (`user`, `user_auth`, `chat_auth`, `browser_auth`, `token`, `api_key`) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bindparam("isssss", $userId, $auth->userauth, $auth->chatauth, $auth->browserauth, $auth->token, $auth->api_key);
    $stmt->execute();
    setcookie('token', $auth->token, time() + (86400 * 30), "/");
    return true;
}

$user = $POST['user'];
$email = $POST['mail'];
$password = $_POST['pass'];
if (!createUser($conn, $auth, $user, $email, $password, true)) {
    $errorHandler->err('Setup', 4, 'Error creating user');
    die();
}
$setupData->setup = true;
$setupData->setupDate = date("c");
file_put_contents('./setup.suplike.json', json_encode($setupData));
header('location: ../../login.php?dbSet=success');
