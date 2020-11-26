<?php 
if ($_FILES['profile-pic']['name']) { 
 $image = $_FILES['profile-pic']['name'];
 $file_size =$_FILES['profile-pic']['size'];
 $file_tmp =$_FILES['profile-pic']['tmp_name'];
 $dot = explode('.', $_FILES['profile-pic']['name']); 
 $file_ext=strtolower(end($dot));  
 $extensions= array("jpeg","jpg","png", ""); 
 
 if(!in_array($file_ext, $extensions)){
    print_r(json_encode(
      array(
        'code' => 33,
        'message' => 'extension is not supported',  
      );  
  ));
    exit();
 }
  if($file_size > 2097152){   
    header("Location: ../signup.php?error=imgtoobig&uid=".$username."&mail=".$email); 
    exit();
  }else{  
   $target = "../img/".$image;  
   move_uploaded_file($file_tmp, $target);  
  }
}
 ?>