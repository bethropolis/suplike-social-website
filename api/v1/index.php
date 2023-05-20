
header('Access-Control-Allow-Origin: *');
header('content-type: application/json');

require '../../inc/dbh.inc.php';
require '../../inc/Auth/auth.php';
require '../../inc/errors/error.inc.php';
$error->_set_log("../../inc/errors/error.log.txt");
require 'login.api.php';
require 'following.api.php';
require 'chat.api.php';
<?php
require_once 'dbh.inc.php';

$stmt = $conn->prepare("SELECT id FROM posts WHERE post_id = ''");
$stmt->execute();
$stmt->store_result();

$num_rows = $stmt->num_rows;

if ($num_rows > 0) {
  $stmt->bind_result($id);
  while ($stmt->fetch()) {
    $post_id = bin2hex(random_bytes(4));
    $stmt2 = $conn->prepare("UPDATE posts SET post_id = ? WHERE id = ?");
    $stmt2->bind_param("si", $post_id, $id);
    $stmt2->execute();
    $stmt2->close();
  }
}

$stmt->close();