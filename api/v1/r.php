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
require_once __DIR__ . "/../../inc/dbh.inc.php";
require_once __DIR__ . "/../../inc/Auth/auth.php";
require_once __DIR__ . "/../../inc/extra/notification.class.php";
require_once __DIR__ . "/../../inc/errors/error.inc.php";
require_once __DIR__ . "/../../inc/extra/xss-clean.func.php";
require "bot/bot.php";

// Set error log path
$error_log_path = __DIR__ . "/../../inc/errors/error.log.txt";
$error->_set_log($error_log_path);


// check if api access is activated
if (!defined("API_ACCESS") or !API_ACCESS) {
    $error->err("API access", 33, "API access has been disabled");
}


// Check user token and authorization
require_once "token.php";
checkUserToken();



// Check session ID
if (!defined('SESSION_UNVERIFY')) {
    if (isset($_GET["uuid"])) {
        checkSessionId($_GET["uuid"]);
        if (defined("SESSION_ID")) {
            if ($un_ravel->_isStatus(SESSION_ID, 'blocked')) {
                $error->err("API access", 22, "account has been blocked. contact admin");
                die();
            }
        }
    } else {
        $error->err("API access", 22, "User authentication failed");
    }
}

if (isset($_POST['user_token']) || isset($_GET['user_token'])) {
    $tk = isset($_POST['user_token']) ? $_POST['user_token'] : $_GET['user_token'];
    authentication_check($un_ravel->_getUser($tk));
}
