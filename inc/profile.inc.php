<?php
require 'dbh.inc.php'; 
# this is an api that gives the whole of user data on request
# from the database.



$answer = array();
$id = isset($_GET['id'])? $_GET['id']: null; 
$user = isset($_GET['user'])? $_GET['user']: null; 
if(!empty($id)){
$query = "SELECT * FROM `users` WHERE `idusers`='$id'"; 
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


}

header('content-type: application/json');
