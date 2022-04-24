<?php
require 'dbh.inc.php';
require 'errors/error.inc.php';
session_start();

try {
    $user = $_SESSION['userId'];

    $sql = "INSERT INTO `share`(`user`) VALUE ($user)";
    $result = $conn->query($sql);
    if ($result) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "error"));
    }
} catch (\Throwable $th) {
    $error->err($_SESSION['userId'], 26, "Share could not be updated.");
}
