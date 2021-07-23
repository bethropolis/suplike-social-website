<?php
include_once '../errors/error.inc.php';

$error->_set_log("../errors/error.log.txt");

$check_setup = file_get_contents("./setup.suplike.json");
$setup_data = json_decode($check_setup); 

if ($setup_data->setup) { 
    die("already setup");
}

$servername = $_POST["server"];
$dBUsername = $_POST["name"];
$dBPassword = $_POST["pwd"]; 

$conn = mysqli_connect($servername, $dBUsername, $dBPassword) or $error->err(0,null,"failed to connect");
     

$sql_file = "../../sql/suplike.sql";

// Temporary variable, used to store current query
$templine = '';
// Read in entire file
$lines = file($sql_file);
// Loop through each line
foreach ($lines as $line) {
// Skip it if it's a comment
    if (substr($line, 0, 2) == '--' || $line == '')
        continue;

// Add this line to the current segment
    $templine .= $line;
// If it has a semicolon at the end, it's the end of the query
    if (substr(trim($line), -1, 1) == ';') {
        // Perform the query
        mysqli_query($conn,$templine) or $error->err(0,null,"failed to connect to db"); 
        // Reset temp variable to empty
        $templine = '';
    }
}

$setup_data->setup = true;
$setup_data->setupDate = date("c"); 


file_put_contents('./setup.suplike.json',json_encode($setup_data)); 

print_r(
	json_encode(
       array(
       	'code' => 21, 
        "type"=>'success'         
       )   
	)
);

