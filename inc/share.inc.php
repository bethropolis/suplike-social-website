<?php
require 'dbh.inc.php';
require 'errors/error.inc.php';
include_once '../plugins/load.php';
require_once "extra/ratelimit.class.php";

use Bethropolis\PluginSystem\System;


try {
    $user = $_SESSION['userId'];

    $sql = "INSERT INTO `share`(`user`) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
} catch (\Throwable $th) {
    $error->err($_SESSION['userId'], 26, 'Share could not be updated.');
}

// Trigger plugin event for sharing
System::triggerEvent("post_shared", ['user_id' => $user]);
