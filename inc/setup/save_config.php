<?php
header('content-type: application/json');
require '../dbh.inc.php';
require '../Auth/auth.php';
require_once "../extra/date.func.php";

session_start();
$un_ravel->_isAuth();

if (!$un_ravel->_isAdmin($_SESSION['userId'])) {
    header('HTTP/1.1 403 Forbidden');
}

// Retrieve the form data
$dbDatabase = $_POST['dbDatabase'];
$dbHost = $_POST['dbHost'];
$dbUsername = $_POST['dbUsername'];
$dbPassword = $_POST['dbPassword'];
$dbPort = $_POST['dbPort'];
$emailVerification = $_POST['emailVerification'] === 'true' ? true : false;
$appEmail = $_POST['appEmail'];
$appName = $_POST['appName'];
$fileSizeLimit = $_POST['fileSizeLimit'];
$apiAccess = $_POST['apiAccess'] === 'true'   ? true : false;
$defaultTheme = $_POST['defaultTheme'];
$accentColor = $_POST['accentColor'];
$userSignup = $_POST['userSignup'] === 'true' ? true : false;
$userPost = $_POST['userPost'] === 'true' ? true : false;
$userComments = $_POST['userComments'] === 'true' ? true : false;

// Read the existing env.php file
$envContent = file_get_contents('env.php');

// Update the values if they have changed and are not empty
if (!empty($dbDatabase) && $dbDatabase !== DB_DATABASE) {
    $envContent = preg_replace("/define\('DB_DATABASE',\s*'[^']*'\);/", "define('DB_DATABASE', '$dbDatabase');", $envContent);
}
if (!empty($dbHost) && $dbHost !== DB_HOST) {
    $envContent = preg_replace("/define\('DB_HOST',\s*'[^']*'\);/", "define('DB_HOST', '$dbHost');", $envContent);
}
if (!empty($dbUsername) && $dbUsername !== DB_USERNAME) {
    $envContent = preg_replace("/define\('DB_USERNAME',\s*'[^']*'\);/", "define('DB_USERNAME', '$dbUsername');", $envContent);
}
if (!empty($dbPassword) && $dbPassword !== DB_PASSWORD) {
    $envContent = preg_replace("/define\('DB_PASSWORD',\s*'[^']*'\);/", "define('DB_PASSWORD', '$dbPassword');", $envContent);
}
if (!empty($dbPort) && $dbPort !== DB_PORT) {
    $envContent = preg_replace("/define\('DB_PORT',\s*\d+\);/", "define('DB_PORT', $dbPort);", $envContent);
}

if ($emailVerification !== EMAIL_VERIFICATION) {

    $envContent = preg_replace("/define\('EMAIL_VERIFICATION',\s*(true|false)\);/", "define('EMAIL_VERIFICATION', " . ($emailVerification ? 'true' : 'false') . ");", $envContent);
}
if (!empty($appEmail) && $appEmail !== APP_EMAIL) {
    $envContent = preg_replace("/define\('APP_EMAIL',\s*'[^']*'\);/", "define('APP_EMAIL', '$appEmail');", $envContent);
}
// Update the values if they have changed and are not empty
if (!empty($appName) && $appName !== APP_NAME) {
    $envContent = preg_replace("/define\('APP_NAME',\s*'[^']*'\);/", "define('APP_NAME', '$appName');", $envContent);
}
if (!empty($fileSizeLimit) && $fileSizeLimit !== FILE_SIZE_LIMIT) {
    $envContent = preg_replace("/define\('FILE_SIZE_LIMIT',\s*\d+\);/", "define('FILE_SIZE_LIMIT', $fileSizeLimit);", $envContent);
}
if ($apiAccess !== API_ACCESS) {
    $envContent = preg_replace("/define\('API_ACCESS',\s*(true|false)\);/", "define('API_ACCESS', " . ($apiAccess ? 'true' : 'false') . ");", $envContent);
}
if (!empty($defaultTheme) && $defaultTheme !== DEFAULT_THEME) {
    $envContent = preg_replace("/define\('DEFAULT_THEME',\s*'[^']*'\);/", "define('DEFAULT_THEME', '$defaultTheme');", $envContent);
}
if ($accentColor !== ACCENT_COLOR) {
    $envContent = preg_replace("/define\('ACCENT_COLOR',\s*'[^']*'\);/", "define('ACCENT_COLOR', '$accentColor');", $envContent);
}

if ($userSignup !== USER_SIGNUP) {
    $envContent = preg_replace("/define\('USER_SIGNUP',\s*(true|false)\);/", "define('USER_SIGNUP', " . ($userSignup ? 'true' : 'false') . ");", $envContent);
}
if ($userPost !== USER_POST) {
    $envContent = preg_replace("/define\('USER_POST',\s*(true|false)\);/", "define('USER_POST', " . ($userPost ? 'true' : 'false') . ");", $envContent);
}
if ($userComments !== USER_COMMENTS) {
    $envContent = preg_replace("/define\('USER_COMMENTS',\s*(true|false)\);/", "define('USER_COMMENTS', " . ($userComments ? 'true' : 'false') . ");", $envContent);
}

// Save the updated content to env.php file
$file = fopen('env.php', 'w');
if ($file) {
    fwrite($file, $envContent);
    fclose($file);
    die(json_encode(['status' => 'success', 'message' => 'Config saved successfully']));
} else {
    die(json_encode(['status' => 'error', 'message' => 'Error saving config. Please try again']));
}
