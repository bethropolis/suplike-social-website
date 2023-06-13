<?php

require 'dbh.inc.php';
require 'Auth/auth.php';
require 'extra/xss-clean.func.php';
header('content-type: application/json');

if (isset($_GET['user'])) {

    # STAGE 1: GETTING THE USERS
    $result_array = [];
    $user = $un_ravel->_getUser($_GET['user']);
    $arr = [];
    $fol_arr = [];
    $query = "SELECT * FROM `following` WHERE `user`=$user";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $fol_arr[] = $row["following"];    
    } 
    $fol_arr[] = $user;
    for ($i = 0; $i < count($fol_arr); $i++) {
        $f = $fol_arr[$i];
        $sql = "SELECT s.post_id, s.type, s.text, s.image, s.time_created, u.uidusers as username, u.isAdmin, u.profile_picture as pic 
        FROM stories s
        INNER JOIN users u ON s.userid = u.idusers 
        WHERE s.time_created >= DATE_SUB(NOW(), INTERVAL 1 DAY) AND u.idusers= $f;
        ";
        $resp = $conn->query($sql);
        while ($row = mysqli_fetch_assoc($resp)) {
            $arr[$row["username"]][] = $row;
        }
    } 
    $result_array['stories'] = $arr;
    echo json_encode($result_array);
} 



