<?php

// Create database connection to database
require 'dbh.inc.php';
require 'Auth/auth.php';

// Initialize message variable 
// If upload button is clicked ...

if (isset($_POST['upload'])) {
    session_start(); 
    $type = $_POST['type'];
    $user = $_SESSION['userId'];    
    $d = new DateTime(null, $timeZone); 
    $image_text = mysqli_real_escape_string($conn, $_POST['posttext']);

    if ($_POST['type'] == 'img') { 
        // mentioning all my variables that I will use 
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $dot = explode('.', $_FILES['image']['name']);
        $file_ext = strtolower(end($dot));
        $extensions = array("jpeg", "jpg", "png", "gif","webp"); //the allowed extensions
        $image = rand(12, 2000) . "." . $file_ext; 
        if (in_array($file_ext, $extensions)) {
            if ($file_size < 6291456) {
                $target = "../img/" . $image;
                $sql = "INSERT INTO posts (`image`, `image_text`, `type`, `userid`, `date_posted`, `day`) VALUES (?, ?, ?, ?, ?,?)";
                move_uploaded_file($file_tmp, $target);
                $stmt = $conn->prepare($sql);  
                $stmt->bind_param("ssssss", $image, $image_text, $type, $user, $d->format('j M'), $d->format('l')); 
                $stmt->execute();
                header("Location: ../index.php?post=success");
                die();
            } else {
                header('Location: ../index.php?upload=filetobig');
            } 
        } else {
            header('Location: ../index.php?upload=extnotallowed');
        }
    } else if ($_POST['type'] == 'txt') { 
          if ($_POST['upload'] == 'post'){ 
          if ($image_text === ""){  
          header("Location: ../post.php?error=emptystr");
           die();
        }       
        }  
        if ($image_text === ""){  
            header("Location: ../index.php?error=emptystr");
            die(); 
            }             
        // variables  
        $sql = "INSERT INTO posts (`image_text`, `userid`,`type` ,`date_posted`, `day`) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);   
        $date = $d->format('j M');
        $day = $d->format('l'); 
        $stmt->bind_param("sssss", $image_text, $user, $type, $date, $day);         
        $stmt->execute(); 
        if ($_POST['upload'] == 'post'){ 
         header("Location: ../post.php?upload=success");           
        } 
        else{       
        header("Location: ../index.php?upload=success");
        }
        die();
    }
}

#-------------------GET POSTs------------------#

if (isset($_GET['user'])) {
    header('content-type: application/json');
    $result = [];
    $user = $_GET['user']; 
    $param = ['user' => $user]; 
    $url = 'https://' . $_SERVER['SERVER_NAME'] . '/inc/social.inc.php?' . http_build_query($param);  
    $following = file_get_contents($url);
    $info = json_decode($following);
    $i = 0;
    foreach ($info as $key) { 
        $acc = $key->idusers; 
        $usr= $key->uidusers; 
        $sql = "SELECT * FROM `posts` WHERE `userid`='$acc' ORDER BY `posts`.`id` DESC";
        $ans = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($ans)) {
            //$foo = (int) $row['id'];    
            $result[$i] = $row;
            $result[$i]['user'] = ['id'=>$acc,'name'=>$usr]; 
            $id= $row['id'];
            $sql = "SELECT * FROM `likes` WHERE `post_id`='$id' AND `user_id`='$user'";  
            $r = $conn->query($sql)->fetch_assoc();  
            
            if (!is_null($r)){
                $result[$i]['liked'] = true;
            }else{
              $result[$i]['liked'] = false;      
            } 
            
            $i++;
        }
    }

         $sql = "SELECT * FROM `posts` WHERE `userid`='$user' ORDER BY `posts`.`id` DESC ";
        $ans = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_assoc($ans)) { 
            $result[$i] = $row; 
            $result[$i]['user'] = true;  
            $id= $row['id']; 
            $sql = "SELECT * FROM `likes` WHERE `post_id`='$id' AND `user_id`='$user'";  
            $r = $conn->query($sql)->fetch_assoc();  
            if (!is_null($r)){
                $result[$i]['liked'] = true;
            }else{
              $result[$i]['liked'] = false;      
            } 
              $i++; 
        }
    if ($result == null) {
       print_r(json_encode(null));     
       die(); 
    }

    print_r(json_encode($result)); 
}

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $arr = [];
  $sql = "SELECT * FROM `posts` WHERE `id`='$id'";  
  $rsp = $conn->query($sql);
  // if ($rsp->fetch_assoc() != null){
     $arr = $rsp->fetch_assoc();   
  // }
    print_r(json_encode($arr));      
} 
