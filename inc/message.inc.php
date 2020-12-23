<?php 
include_once 'dbh.inc.php';
$result = array();

if (isset($_POST['from'])){ 
$message = $_POST['message'];
$from =  $_POST['from'];
 $to =  $_POST['to']; 
if (!empty($message) && !empty($from)) {
	$query = "INSERT INTO chat (`message`, `who_from`, `who_to` ) VALUES ('$message','$from', '$to')";    
	 $conn->query($query);  
	print_r(
		json_encode(
			[
			  'code'=> 21,
              'msg'=> 'message sent',
              'type'=> 'success'			  
		  ]
	    )
	); 
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
 } 
 
if (isset($_GET['start'])) {

$start = intval($_GET['start']); 
$from =  $_GET['from']; 
$to = $_GET['to'];  
$items = $conn->query("SELECT * FROM `chat` WHERE `id`>".$start." AND (`who_to`='$to' OR `who_to`='$from')  AND (`who_from`='$from' OR `who_from`='$to');");  


while ($row = $items->fetch_assoc()) { 
	$result['items'][]= $row;  
}   
}   
  
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
print_r(json_encode($result)) ;        
  