<?php
require_once '../Auth/auth.php';
require_once '../errors/error.inc.php';
header('Content-Type: application/json');

$auth = new Auth();
$errorHandler = new Err();

function handleSetupRequest($conn, $auth, $errorHandler)
{

    $setupData = json_decode(file_get_contents("./setup.suplike.json"));

    if ($setupData->setup) {
        return [
            'success' => true,
            'msg' => 'Already set up'
        ];  
    }

    if (!isset($_POST["server"]) || !isset($_POST["name"]) || !isset($_POST["pwd"])) {
        $errorHandler->err('Setup', 1, 'Missing database configuration info');
        die();
    }

    $serverName = $_POST["server"];
    $dbUser = $_POST["name"];
    $dbPassword = $_POST["pwd"];
    $dbName = isset($_POST["db"]) && !empty($_POST["db"]) ? $_POST["db"] : 'sup';
    $dropDatabase = isset($_POST["drop"]) && $_POST["drop"] == "on";

    try {
        if (!$conn = @mysqli_connect($serverName, $dbUser, $dbPassword)) {
            $errorMessage = 'Could not connect to the database';

            if (isset($errorHandler)) {
                $errorHandler->err('Setup', 2, $errorMessage);
            }
            die();
        }
    } catch (Exception $e) {
        $errorMessage = 'Could not connect to the database';
        $errorHandler->err('Setup', 2, $errorMessage);
        die();
    }


    if ($dropDatabase) {
        if (!$conn->query("DROP DATABASE IF EXISTS $dbName")) {
            $errorHandler->err('Setup', 7, 'Error dropping the database');
            die();
        }
    }


    if (!$dropDatabase) {
        $checkDatabaseQuery = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbName'";
        $checkDatabaseResult = $conn->query($checkDatabaseQuery);

        if ($checkDatabaseResult && $checkDatabaseResult->num_rows > 0) {
            $errorMessage = "Database '$dbName' already exists";
            $errorHandler->err('Setup', 8, $errorMessage);
            die();
        }
    }

    if (!$conn->query("CREATE DATABASE IF NOT EXISTS $dbName")) {
        $errorHandler->err('Setup', 3, 'Error creating the database');
        die();
    }

    if (!$conn->select_db($dbName)) {
        $errorHandler->err('Setup', 4, 'Error selecting the database');
        die();
    }

    function createEnvFile($serverName, $dbUser, $dbPassword, $dbName)
    {
        $fileContent = <<<EOT
<?php
\$protocol = (!empty(\$_SERVER['HTTPS']) && \$_SERVER['HTTPS'] !== 'off' || \$_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

if (!defined('DB_DATABASE')) define('DB_DATABASE', '$dbName');
if (!defined('DB_HOST')) define('DB_HOST', '$serverName');
if (!defined('DB_USERNAME')) define('DB_USERNAME', '$dbUser');
if (!defined('DB_PASSWORD')) define('DB_PASSWORD', '$dbPassword');
if (!defined('DB_PORT')) define('DB_PORT', 3306);
if (!defined('APP_NAME')) define('APP_NAME', 'Suplike social network');
if (!defined('SETUP')) define('SETUP', true);
if (!defined('BASE_URL')) define('BASE_URL', \$protocol . \$_SERVER['HTTP_HOST']);
if (!defined('EMAIL_VERIFICATION')) define('EMAIL_VERIFICATION', false);
if (!defined('USER_SIGNUP')) define('USER_SIGNUP', true);
if (!defined('USER_POST')) define('USER_POST', true);
if (!defined('USER_COMMENTS')) define('USER_COMMENTS', true);
if (!defined('API_ACCESS')) define('API_ACCESS', true);
if (!defined('APP_EMAIL')) define('APP_EMAIL', '');
if (!defined('DEFAULT_THEME')) define('DEFAULT_THEME', 'light');
if (!defined('ACCENT_COLOR')) define('ACCENT_COLOR', '');
if (!defined('FILE_EXTENSIONS')) define('FILE_EXTENSIONS', array('jpeg', 'jpg', 'png', 'gif', 'webp'));
if (!defined('FILE_SIZE_LIMIT')) define('FILE_SIZE_LIMIT', 6291456);
if (!defined('RANDOM_BYTES_LENGTH')) define('RANDOM_BYTES_LENGTH', 4);
EOT;

        file_put_contents("env.php", $fileContent);
    }


    createEnvFile($serverName, $dbUser, $dbPassword, $dbName);

    function executeSqlFromFile($conn, $filename)
    {
        global $errorHandler;
        $sqlFileContent = file_get_contents($filename);

        $sqlQueries = explode(';', $sqlFileContent);

        foreach ($sqlQueries as $query) {
            if (trim($query) === '') {
                continue;
            }

            try {
                if (!$conn->query($query)) {
                    return false;
                }
            } catch (Exception $e) {
                $errorHandler->err('Setup', 6, "error in creating database: " . $e->getMessage());
                die();
            }
        }

        return true;
    }

    if (!executeSqlFromFile($conn, "../../sql/suplike.sql")) {
        $errorHandler->err('Setup', 5, 'Error executing SQL file');
        die();
    }


    function createUser($conn, $auth, $user, $email, $password, $isAdmin, $verify_mail = true)
    {
        global $errorHandler;

        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("SELECT uidusers FROM users WHERE uidusers = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();

        if ($stmt->get_result()->num_rows) {
            return false;
        }

        $stmt = $conn->prepare("INSERT INTO users (uidusers, emailusers, pwdUsers, isAdmin,email_verified) VALUES (?,?, ?, ?, ?)");
        $stmt->bind_param("sssii", $user, $email, $hashedPwd, $isAdmin, $verify_mail);
        $stmt->execute();

        $getIdStmt = $conn->prepare("SELECT `idusers` FROM `users` WHERE `uidusers` = ?");
        $getIdStmt->bind_param("s", $user);
        $getIdStmt->execute();
        $userId = $getIdStmt->get_result()->fetch_assoc()['idusers'];

        $stmt = $conn->prepare("INSERT INTO `auth_key` (`user`, `token`, `chat_auth`, `browser_auth`, `user_auth`,`api_key`) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $userId, $auth->token, $auth->chat_auth, $auth->browser_auth, $auth->user_auth, $auth->api_key);
        $stmt->execute();

        setcookie('token', $auth->token, time() + (86400 * 30), "/");
        return true;
    }

    $user = !empty($_POST['user']) ? $_POST['user'] : 'admin';
    $email = !empty($_POST['mail']) ? $_POST['mail'] : 'admin@localhost';
    $password = $_POST['pass'];
    if (!createUser($conn, $auth, $user, $email, $password, true)) {
        $errorHandler->err('Setup', 6, 'Error creating user');
        die();
    }
    $setupData->setup = true;
    $setupData->owner = $user;
    $setupData->setupDate = date("c");
    file_put_contents('./setup.suplike.json', json_encode($setupData));


    return [
        'success' => true,
        'msg' => 'Setup completed successfully'
    ];
}


// Check if the request method is POST and call the handler function
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = handleSetupRequest($conn, $auth, $errorHandler);
    echo json_encode($response);
}
