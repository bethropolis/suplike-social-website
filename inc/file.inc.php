<?php
session_start();
include_once 'dbh.inc.php';
include_once 'Auth/auth.php';
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$result = array();
$auth =  new Auth();

$type = $_GET['type'];
$from =  $auth->_getUser($_GET['from']);
$to =  $auth->_getUser($_GET['to']);
// get details of the uploaded file
$fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
$fileName = $_FILES['uploadedFile']['name'];
$fileSize = $_FILES['uploadedFile']['size'];
$fileType = $_FILES['uploadedFile']['type'];
$fileNameCmps = explode(".", $fileName);
$fileExtension = strtolower(end($fileNameCmps));
// print_r($_FILES);
// print_r($_GET);
// sanitize file-name
$newFileName = md5(time() . $fileName) . '.' . $fileExtension;

// check if file has one of the following extensions
$allowedSongfileExtensions = array('wav', 'mp3');
$allowedImagefileExtensions = array('jpg', 'jpeg', 'png', 'gif');

if (in_array($fileExtension, $allowedSongfileExtensions)) {
    // directory in which the uploaded file will be moved
    $uploadFileDir = './music/';
    $dest_path = $uploadFileDir . $newFileName;
    $message = $dest_path;
    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        if ($from === $to) {
            die(json_encode(
                [
                    'code' => 6,
                    'msg' => "cannot message yourself",
                    'type' => 'error'
                ]
            ));
        }


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
}else if(in_array($fileExtension, $allowedImagefileExtensions)){
    // directory in which the uploaded file will be moved
    $uploadFileDir = './image/';
    $dest_path = $uploadFileDir . $newFileName;
    $message = $dest_path;
    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        if ($from === $to) {
            die(json_encode(
                [
                    'code' => 6,
                    'msg' => "cannot message yourself",
                    'type' => 'error'
                ]
            ));
        }
    if(!empty($message) && !empty($from)){
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
    }else{
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
    }else{
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
}else{
    print_r(
        json_encode(
            [
                'code' => 2,
                'msg' => 'unknown format',
                'type' => 'error'
            ]
        )
    );
}

