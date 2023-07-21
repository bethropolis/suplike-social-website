<?php
require 'dbh.inc.php';
require './Auth/auth.php';
require './errors/error.inc.php';
header("Content-Type: application/json");
session_start();

//auth check
$un_ravel->_isAuth();

$user = $_SESSION['userId'];
if (isset($_FILES['file'])) {
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
        $errors->err('profile_picture', 3, 'Extension not allowed, please choose a JPEG or PNG file.');
        die();
    }

    if ($file_size > 2097152) {
        
        die(json_encode(array('error' => 'File size must be excately 2 MB')));
    }
    move_uploaded_file($file_tmp, "../img/" . $file_path);
    $_SESSION['profile-pic'] = $file_path;
    $conn->query($sql);
    die(json_encode(array('success' => 'Successfully uploaded')));
}
