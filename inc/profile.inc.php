<?php
require 'dbh.inc.php';
require 'Auth/auth.php';
require 'errors/error.inc.php';    
# this is an api that gives the whole of user data on request
# from the database.



$answer = array();

$id = isset($_GET['id'])? $_GET['id']: null; 
$user = isset($_GET['user'])? $_GET['user']: null; 

if (!is_null($id)) {
   $id =  $un_ravel->_getUser($id); 
}

if (!is_null($user)) { 
   $user = $un_ravel->_getUser($user); 
}



if(!empty($id)){
$query = "SELECT `users`.*,`token` FROM `users`,`auth_key` WHERE `users`.`idusers` = $id AND `auth_key`.`user` ='$id' ";  
$answer['user'] = $conn->query($query)->fetch_assoc();
$query = "SELECT * FROM `posts` WHERE `userid`='$id'";    
$result = $conn->query($query); 
$i = 0;
while ($row = mysqli_fetch_assoc($result)) {  
	$answer['posts'][$i] = $row;   
    $answer['posts'][$i]['user']= [ 'id'=> $answer['user']['idusers'],  
                                    'name'=>$answer['user']['uidusers'] 
                          ]; 
            $post_id= $row['id'];                                       
            if ($user != null) {
            $sql = "SELECT * FROM `likes` WHERE `post_id`='$post_id' AND `user_id`='$user'";    
            $r = $conn->query($sql)->fetch_assoc();  
            if (!is_null($r)){ 
                $answer['posts'][$i]['liked'] = true; 
            }else{
              $answer['posts'][$i]['liked'] = false;        
            } 
        }
	$i++;                     
} 
print_r(json_encode($answer));  


}else{
  $err = new Err(15);
  $err->err($user);  
}

header('content-type: application/json');
