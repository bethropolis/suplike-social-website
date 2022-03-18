<?php
$servername = "localhost";
$dBUsername = "root";
$dBPassword = "";
$dBName = "suplike";
$timeZone = new DateTimeZone('Africa/Nairobi');

try {
  $conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName) or print_r('Unable to connect to DB');;
  if (!$conn) {
    $isSetup = file_get_contents('./setup/setup.suplike.json');
    $s = json_decode($isSetup);
    if (!$s->setup) {
      print_r(file_get_contents('./setup/setup.html'));
      die();
    }
  }

  $conn->set_charset('utf8mb4');
} catch (\Throwable $th) {
    $isSetup = file_get_contents('./setup/setup.suplike.json');
    $s = json_decode($isSetup);
    if (!$s->setup) {
      print_r(file_get_contents('./setup/setup.html'));
      die();
    }

  echo $th;
}
