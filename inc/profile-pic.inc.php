<?php
require 'dbh.inc.php';
header("Content-Type: application/json");
session_start();
$user = $_SESSION['userId'];
if (isset($_FILES['file'])) {
    $errors = array();
    $file_name = $_FILES['file']['name'];
    $file_size = $_FILES['file']['size'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_type = $_FILES['file']['type'];
    $expolode = explode('.', $file_name);
    $file_ext = strtolower(end($expolode));
    $expensions = array("jpeg", "jpg", "png");
    $random_string = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
    $file_path =  $random_string . "." . $file_ext;
    $sql = "UPDATE `users` SET `profile_picture` = '$file_path' WHERE `idusers` = '$user'";
    if (in_array($file_ext, $expensions) === false) {
        die(json_encode(array('error' => 'Extension not allowed, please choose a JPEG or PNG file.')));
    }

    if ($file_size > 2097152) {
        die(json_encode(array('error' => 'File size must be excately 2 MB')));
    }

    if (empty($errors) == true) {
        move_uploaded_file($file_tmp, "../img/" . $file_path);
        $_SESSION['profile-pic'] = $file_path;
        $conn->query($sql);
        die(json_encode(array('success' => 'Successfully uploaded')));
    } else {
        print_r(json_encode($errors));
    }
}
