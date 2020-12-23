<?php 
include_once 'dbh.inc.php';
header('Content-Type: application/json'); 
if (isset($_POST['id'])){ 
	if (!empty($_POST['id'])){ 
	  $id = $_POST['id']; 
      $sql = "INSERT INTO `reports` (`post_id`) VALUE ('$id')";   
      $conn->query($sql); 
      print_r(json_encode("reported"));
    exit(); 
	 }else{
	print_r(json_encode("error could not be reported")); 	 	
	 }
}
 
if (isset($_POST['del'])) {
	$id = $_POST['del'];
	$sql = "UPDATE `reports` SET `delt`=true WHERE `post_id`=$id";
	$conn->query($sql);	
	 
	$sql = "DELETE FROM `posts` WHERE `id`=$id";
	$conn->query($sql);
      print_r(json_encode("deleted"));
    exit(); 	
}

if (isset($_GET['report'])) {
	$type = isset($_GET['type'])?$_GET['type']:false;
	$arr = [];  
	$sql = "SELECT * FROM `reports` WHERE `delt`='$type'";   
	$rsp = $conn->query($sql);  
	while ($row = $rsp->fetch_assoc()) {
	$arr[] = $row;	 
	}
	print_r(json_encode($arr));  
} else {
	print 'you are not allowed to access ths file';
}
