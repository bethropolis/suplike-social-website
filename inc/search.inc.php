<?php
/*


this page is totaly not even once used in 
any part of the code, so don't even bother
about it.

I left it for may be future improvements 
of the search page and this is only a reminder


*/
 

$dbHost = "localhost";
$dbUsername = "root";
$dbPassword = ""; 
$dbName = "logtut";  
// Create database connection
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
// Check connection
if ($db->connect_error) {
 die("Connection failed: " . $db->connect_error);
}

// If the search form is submitted
$searchKeyword = $whrSQL = '';
if(isset($_POST['searchSubmit'])){
 $searchKeyword = $_POST['keyword'];
 if(!empty($searchKeyword)){
 // SQL query to filter records based on the search term
 $whrSQL = "WHERE (uidusers LIKE '%".$searchKeyword."%' OR usersFirstname LIKE '%".$searchKeyword."%')";
 }
}
// Get matched records from the database
$result = $db->query("SELECT * FROM users $whrSQL ORDER BY idusers DESC");
// Highlight words in text
function highlightWords($text, $word){
 $text = preg_replace('#'. preg_quote($word) .'#i', '<span class="hlw">\\0</span>', $text);
 return $text;
}

?>


<!-- Search form -->
<form method="post" action=""> 
 <div class="input-group">
 <input type="text" name="keyword" value="<?php echo $searchKeyword; ?>" placeholder="Search by keyword..." >
 <input type="submit" name="searchSubmit" value="Search">
 </div>
</form>

<?php 
if($result->num_rows > 0){
 while($row = $result->fetch_assoc()){
 $title = !empty($searchKeyword)?highlightWords($row['usersFirstname'], $searchKeyword):$row['usersFirstname'];
 $contnet = !empty($searchKeyword)?highlightWords($row['uidusers'], $searchKeyword):$row['uidusers'];
?>
<div class="list-item">
 <h4><?php echo $title; ?></h4>
 <p><?php echo $contnet; ?></p>
</div>
<?php } }else{ ?>
<p>No post(s) found...</p>
<?php }  
?>

