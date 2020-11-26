<?php 

$db = new mysqli("localhost", "root","","test");
  
if ($db->connect_error) {   
	die('connection failed: '.$db->connect_error);
}

$result = array();
$message = isset($_POST['message']) ? $_POST['message']: null;
$from = isset($_POST['from']) ? $_POST['from']: null ;
 $to =  isset($_POST['to']) ? $_POST['to']: null ;
 
if (!empty($message) && !empty($from)) {
	$query = "INSERT INTO chat (`message`, `who_from`, `who_to` ) VALUES ('".$message."','".$from."', '".$to."')";   
	$result['send_status'] = $db->query($query); 
}
 
$start = isset($_GET['start'])? intval($_GET['start']): 0; 
$from = isset($_GET['from'])? $_GET['from']: 'wolfy'; 
$to = isset($_GET['to'])? $_GET['to']: 'admin';   
$items = $db->query("SELECT * FROM `chat` WHERE `id`>".$start." AND (`who_to`='$to' OR `who_to`='$from')  AND (`who_from`='$from' OR `who_from`='$to');");           
while ($row = $items->fetch_assoc()) { 
	$result['items'][]= $row;  
}   
   
  
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
print_r(json_encode($result)) ;       
  