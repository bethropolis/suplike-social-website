<?php
/*
 * This script is the main script for the API
 *
 * This script sets headers for Cross-Origin Resource Sharing (CORS)
 * and includes helper scripts for database connection, authentication,
 * error handling, and input sanitization. It also checks a user token and,
 * if provided, a session ID.
 */

// Set CORS headers
header('Access-Control-Allow-Origin: *');
header('content-type: application/json');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');

// Include helper scripts
require_once "dbh.inc.php";
require_once "auth.php";
require_once "notification.class.php";
require "bot/bot.php";
require_once "error.inc.php";
require_once "xss-clean.func.php";

// Set error log path
$error->_set_log("../error.log.txt");

// Check user token
require_once "token.php";
checkUserToken();

// Check session ID
if (!defined('SESSION_UNVERIFY')) {
    if (isset($_GET["uuid"])) {
        checkSessionId($_GET["uuid"]);
    } else {
        $error->err("API access", 22, "User authentication failed");
    }
}

if (isset($_POST['user_token']) || isset($_GET['user_token'])) {
    $tk = isset($_POST['user_token']) ? $_POST['user_token'] : $_GET['user_token'];
    authentication_check($un_ravel->_getUser($tk));
}
