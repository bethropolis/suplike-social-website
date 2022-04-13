<?php 
require '../r.php';
// get $_GET['id'] from url, use to serch for user in database

if(isset($_GET['id'])){
    $id =$_GET['id'];
    $id = $un_ravel->_getUser($id);
    $sql = "SELECT *,token,chat_auth FROM users,auth_key WHERE idusers = $id AND users.idusers = auth_key.user";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $arr = array(
        'id' => $row['uidusers'],
        'name' => $row['usersFirstname'] . ' ' . $row['usersSecondname'],
        'email' => $row['emailusers'],
        'token' => $row['token'],
        'chat_key' => $row['chat_auth'],
        'gender' => $row['gender'],
        'bio' => $row['bio'],
        'date_joined' => $row['date_joined'],
    );
    print_r(json_encode($arr));
}




