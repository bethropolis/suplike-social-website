<?php
require 'dbh.inc.php';
session_start();

$user = $_SESSION['userId']  ;


if(isset($_FILES["file"]["type"]))
{
$validextensions = array("jpeg", "jpg", "png");
$temporary = explode(".", $_FILES["file"]["name"]); 
$file_extension = end($temporary);
$file_name = bin2hex(openssl_random_pseudo_bytes(7));
if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")
) && ($_FILES["file"]["size"] < 100000)//Approx. 100kb files can be uploaded.
&& in_array($file_extension, $validextensions)) {
if ($_FILES["file"]["error"] > 0)
{
echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
}
else
{
if (file_exists("img/" . $file_name)) {
echo $file_name . " <span id='invalid'><b>already exists.</b></span> ";
}
else
{
$sql = "UPDATE `users` SET `profile_picture` = '".$file_name.".".$file_extension."' WHERE `users`.`idusers` =".$user; 
$conn->query($sql);
print_r($sql); 
$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
$targetPath = "../img/".$file_name.".".$file_extension; // Target path where file is to be stored
move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file



echo "<span id='success'>Image Uploaded Successfully...!!</span><br/>";
echo "<br/><b>File Name:</b> " . $file_name . "<br>";
echo "<b>Type:</b> " . $_FILES["file"]["type"] . "<br>";
echo "<b>Size:</b> " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
echo "<b>Temp file:</b> " . $_FILES["file"]["tmp_name"] . "<br>";
}
}
}
else
{
echo "<span id='invalid'>***Invalid file Size or Type***<span>";
}
}


// $file_tmp = $_POST['image']; 
// $image = rand(12, 2000) . "." . 'jpeg';
//  $target = "../img/" .$image ; 

// move_uploaded_file($file_tmp, $target);
// print_r(
// json_encode(
//       array(
//                 'code' => 21,
//                 "type" => 'successful'

//             )
//         )
//     );
