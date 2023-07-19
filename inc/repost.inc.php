<?php
require 'dbh.inc.php';
require 'Auth/auth.php';
session_start();
include_once '../plugins/load.php';
use Bethropolis\PluginSystem\System;

// Auth check
$un_ravel->_isAuth();

$id = $_GET['id'];
$user = $_SESSION['userId'];

// Fetch the original post
$stmt = $conn->prepare("SELECT * FROM posts WHERE post_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (empty($row)) {
    header('Location: ../home?error=notexist');
    exit();
}

if ($row['userid'] == $user) {
    header('Location: ../home?error=yrpost');
    exit();
}

// Check if already reposted
$stmt = $conn->prepare("SELECT id FROM posts WHERE repost = ? AND userid = ?");
$stmt->bind_param("ss", $id, $user);
$stmt->execute();
$result = $stmt->get_result();

if (!empty($result->fetch_assoc())) {
    header('Location: ../home?error=reposting');
    exit();
}

$d = new DateTime('now', $timeZone);
$date = $d->format('j M');
$day = $d->format('l');
$image = $row['image'];
$image_text = $row['image_text'];
$bin = bin2hex(openssl_random_pseudo_bytes(4));

$type = $row['type'];
$stmt = $conn->prepare("INSERT INTO posts (post_id, image, image_text, repost, type, userid, date_posted, day) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $bin, $image, $image_text, $id, $type, $user, $date, $day);
$stmt->execute();

// Trigger plugin events for reposting
System::triggerEvent("post_reposted",null, ['post_id' => $bin, 'user_id' => $user]);

header('Location: ../home?success=reposted');
exit();