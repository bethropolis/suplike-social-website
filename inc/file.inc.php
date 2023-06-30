<?php
session_start();
include_once 'dbh.inc.php';
include_once 'Auth/auth.php';
header('Content-Type: application/json');
$result = array();
$auth = new Auth();

$type = $_GET['type'];
$from = $auth->_getUser($_GET['from']);
$to = $auth->_getUser($_GET['to']);

$fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
$fileName = $_FILES['uploadedFile']['name'];
$fileSize = $_FILES['uploadedFile']['size'];
$fileType = $_FILES['uploadedFile']['type'];
$fileNameCmps = explode(".", $fileName);
$fileExtension = strtolower(end($fileNameCmps));

$newFileName = md5(time() . $fileName) . '.' . $fileExtension;

$allowedSongfileExtensions = array('wav', 'mp3');
$allowedImagefileExtensions = FILE_EXTENSIONS;

$uploadFileDir = '';

if (in_array($fileExtension, $allowedSongfileExtensions)) {
    $uploadFileDir = './music/';
} else if (in_array($fileExtension, $allowedImagefileExtensions)) {
    $uploadFileDir = './image/';
} else {
    print_r(
        json_encode(
            [
                'code' => 2,
                'msg' => 'unknown format',
                'type' => 'error'
            ]
        )
    );
    exit();
}

$dest_path = $uploadFileDir . $newFileName;
$message = $dest_path;

if ($from === $to) {
    print_r(
        json_encode(
            [
                'code' => 6,
                'msg' => "cannot message yourself",
                'type' => 'error'
            ]
        )
    );
    exit();
}

if (move_uploaded_file($fileTmpPath, $dest_path)) {
    if (!empty($message) && !empty($from)) {
        $query = "INSERT INTO chat (`message`,`type`, `who_from`, `who_to` ) VALUES ('$message','$type','$from', '$to')";
        $conn->query($query);
        print_r(
            json_encode(
                [
                    'code' => 21,
                    'msg' => 'message sent',
                    'type' => 'success'
                ]
            )
        );
    } else {
        print_r(
            json_encode(
                [
                    'code' => 2,
                    'msg' => 'Missing data',
                    'type' => 'error'
                ]
            )
        );
    }
} else {
    print_r(
        json_encode(
            [
                'code' => 2,
                'msg' => 'message not sent',
                'type' => 'error'
            ]
        )
    );
}
