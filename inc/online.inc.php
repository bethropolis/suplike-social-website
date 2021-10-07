<?php
require 'dbh.inc.php';
require_once 'Auth/auth.php';
header('content-type: application/json');
if (isset($_POST['user'])) {
  $user = $un_ravel->_getUser($_POST['user']);
  $sql = "UPDATE `users` SET `last_online`=CURRENT_TIMESTAMP WHERE `idusers`='$user'";
  $conn->query($sql);
  die('set');
}

if (isset($_GET['all'])) {
  $arr = [];
  $date = new DateTime(null, $timeZone);
  $date->format("Y-m-d H:i:s");
  $arr['today'] = $date->format('l');
  $date = $date->modify("-7 days");
  $date = $date->format('Y-m-d');
  $sql = "SELECT `idusers`,`last_online` FROM `users` WHERE `last_online`>'$date'";
  $result = $conn->query($sql);
  while ($row = $result->fetch_assoc()) {
    $dt = new DateTime($row['last_online'], $timeZone);
    $dt = $dt->format('l');
    $arr[$dt][] = $row;
  }
  print_r(json_encode($arr));
}
