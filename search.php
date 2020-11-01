<?php 
require 'inc/dbh.inc.php';

if ($conn->connect_error) {
 die("Connection failed: " . $conn->connect_error);
}

$result = []; 
// If the search form is submitted
$searchKeyword = $whrSQL = '';
if(isset($_POST['searchSubmit'])){

 $searchKeyword = $_POST['keyword']; 
 if(!empty($searchKeyword)){
 // SQL query to filter records based on the search term
 $whrSQL = "WHERE (uidusers LIKE '%".$searchKeyword."%' OR usersFirstname LIKE '%".$searchKeyword."%')";
 
     // Get matched records from the database
    $result = $conn->query("SELECT * FROM users $whrSQL");  
   // Highlight words in text
    function highlightWords($text, $word){
    $text = preg_replace('#'. preg_quote($word) .'#i', '<span class="hlw">\\0</span>', $text);
    return $text;
    }

  }	
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

if($result){     
 while($row = $result->fetch_assoc()){
 $title = !empty($searchKeyword)?highlightWords($row['uidusers'], $searchKeyword):$row['uidusers']; 
 $contnet = !empty($searchKeyword)?highlightWords($row['usersFirstname'], $searchKeyword):$row['usersFirstname'];

?>
<div class="list-item">
 <h4><?php echo $title; ?></h4>
 <p><?php echo $contnet; ?></p>
</div>
<?php } }else{ ?>
<p>No post(s) found...</p>
<?php }  
?>
 