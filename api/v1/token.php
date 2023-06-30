<?php
function checkUserToken()
{
  global $conn;
  global $error;
  // Check for Authorization header
  if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $error->err("API access", 20, "No valid API token");
    die();
  }

  // Extract token from Authorization header
  $auth_header = $_SERVER['HTTP_AUTHORIZATION'];
  $token = str_replace('Bearer ', '', $auth_header);

  // Check if token exists in database
  $stmt = $conn->prepare("SELECT id FROM api WHERE `key` = ?");
  $stmt->bind_param("s", $token);
  $stmt->execute();
  $stmt->store_result();

  $num_rows = $stmt->num_rows;

  $stmt->close();

  if ($num_rows > 0) {
    return true;
  } else {
    $error->err("API access", 21, "API token does not exist");
    die();
  }
}

function checkSessionId($uuid)
{
  global $conn, $error;
  $user_id = "";
  $stmt = $conn->prepare("SELECT user_id FROM `session` WHERE `session_id` = ?");
  $stmt->bind_param("s", $uuid);
  $stmt->execute();
  $stmt->bind_result($user_id);
  $stmt->fetch();
  $stmt->close();

  $session_id = $user_id ? $user_id : null;
  if ($session_id) {
    define('SESSION_ID', $session_id);
  } else {
    $error->err("API access", 23, "User authentication failed");

  }
}
function authentication_check($user)
{
  global $error;
  // print_r("user:".$user);
  // print_r("SESSION_ID:".constant('SESSION_ID'));

  // compare user to constant('SESSION_ID')
  if (defined('SESSION_ID')) {
    if ($user == constant('SESSION_ID')) {
      return true;
    }
  }
  $error->err("API access", 23, "User authentication failed");
  die();
}

function create_session_token($user)
{
  global $conn;

  function generate_session_token()
  {
    $length = 32;
    $bytes = random_bytes($length);
    return substr(bin2hex($bytes), 0, $length);
  }
  // generate session token
  $session_token = generate_session_token();

  // insert session into database
  $stmt = $conn->prepare("INSERT INTO session (session_id, user_id) VALUES (?, ?)");
  $stmt->bind_param("si", $session_token, $user);
  $stmt->execute();

  return $session_token;
}
