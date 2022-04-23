<?php
include_once '../errors/error.inc.php';
include_once '../Auth/auth.php';
$oauth = new Auth();
$error->_set_log("../errors/error.log.txt");

$check_setup = file_get_contents("./setup.suplike.json");
$setup_data = json_decode($check_setup);

if ($setup_data->setup) {
    die("already setup");
}

$servername = $_POST["server"];
$dBuser = $_POST["name"];
$dBPassword = $_POST["pwd"];



$conn = mysqli_connect($servername, $dBuser, $dBPassword) or null;


if (!$conn) {
echo 'could not connect to db';    
    print_r(file_get_contents('./setup/setup.html'));
    die();
}
file_put_contents("env.php", "<?php");
$fp =fopen("env.php","a");
$conf = "\n if (!defined('DB_DATABASE'))           define('DB_DATABASE', 'suplike');";
fwrite($fp,$conf);
$conf = "\n if (!defined('DB_HOST'))              define('DB_HOST','$servername');";
fwrite($fp,$conf);
$conf = "\n if (!defined('DB_USERNAME'))          define('DB_USERNAME', '$dBuser');";
fwrite($fp,$conf);
$conf = "\n if (!defined('DB_PASSWORD'))           define('DB_PASSWORD', '$dBPassword');";
fwrite($fp,$conf);
$conf = "\n if (!defined('DB_PORT'))              define('DB_PORT',3306);";
fwrite($fp,$conf);
fclose($fp);




$sql_file = "../../sql/suplike.sql";

// Temporary variable, used to store current query
$templine = '';
// Read in entire file
$lines = file($sql_file);
// Loop through each line
foreach ($lines as $line) {
    // Skip it if it's a comment
    if (substr($line, 0, 2) == '--' || $line == '')
        continue;

    // Add this line to the current segment
    $templine .= $line;
    // If it has a semicolon at the end, it's the end of the query
    if (substr(trim($line), -1, 1) == ';') {
        // Perform the query
        $conn->query($templine);
        // Reset temp variable to empty
        $templine = '';
    }
}
$conn = mysqli_connect($servername, $dBuser, $dBPassword,'suplike');
$user =  $_POST['user'];
$email =  $_POST['mail'];
$password =  $_POST['pass'];
$admin = true;
$sql = "SELECT uidusers FROM users WHERE uidusers='$user'";
$r = $conn->query($sql);
if($r->num_rows){
    die(" username is already in use");
}
$hashedpwd = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (uidusers, emailusers, pwdUsers, isAdmin) VALUES ('$user','$email', '$hashedpwd','$admin')";

$conn->query($sql);

$getId = "SELECT `idusers` FROM `users` WHERE `uidusers`='$user'";
$response = (mysqli_fetch_assoc($conn->query($getId)))['idusers'];
$outhsql = "INSERT INTO `auth_key` (`user`,`user_auth`,`chat_auth`,`browser_auth`,`token`,`api_key`) VALUES ($response,'$oauth->user_auth','$oauth->chat_auth','$oauth->browser_auth','$oauth->token','$oauth->api_key') ";
$conn->query($outhsql);
setcookie('token', $oauth->token, time() + (86400 * 30), "/");

$setup_data->setup = true;
$setup_data->setupDate = date("c");
file_put_contents('./setup.suplike.json', json_encode($setup_data));
header('location: ../../login.php?dbSet=success');
