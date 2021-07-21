<?php 

require 'inc/dbh.inc.php';
require 'header.php'; 
include_once 'inc/Auth/auth.php'; 

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
<!--<link rel="stylesheet" type="text/css" href="css/search.css?k">  -->


<!-- Search form -->
<form method="post" class="mx-auto my-4" action="">  
    <div class="search_input mx-auto row my-2">  
        <input type="text" class="search_box mx-1 col-8" name="keyword" value="<?php echo $searchKeyword; ?>" placeholder="Search by keyword...">
        <input type="submit" class="search_button mx-1 col-3 bg btn" name="searchSubmit" value="Search"> 
    </div>
</form>  

<?php   

if($result){  


 while($row = $result->fetch_assoc()){ 
   
 $title = !empty($searchKeyword)?highlightWords($row['uidusers'], $searchKeyword):$row['uidusers']; 
 $contnet = !empty($searchKeyword)?highlightWords($row['usersFirstname'], $searchKeyword):$row['usersFirstname'];
if (!empty($searchKeyword)) {  
   $follow = 'follow'; 
    $query = "SELECT * FROM `following` WHERE user=".$_SESSION['userId']." AND `following`=".$row['idusers']."";   
   $answer = $conn->query($query)->fetch_assoc(); 
   if (!is_null($answer)) { 
      $follow = 'following';  
   } 
 } 
 
?>
<div class="search-list-item bg-light my-4 mx-auto shadow py-2 w-75 row">       
    <div class="col-md-6 text-left">   
        <a href="profile.php?id=<?=$un_ravel->_queryUser($row['idusers'],4)?>" class="prof-link"> 
            <h4><?php echo $title; ?></h4>
        </a>
        <p><?php echo '@'.$contnet; ?></p>
    </div>
    <div class="col-md-6 text-right pr-4">         
    <button id="<?=$row['idusers']?>" class="btn col-5 p-2 bg follow-btn"><?=$follow?></button>    
    </div> 
</div>
<?php } }else{ ?>
<p>No user(s) found...</p>
<?php }   
?>

<?php require 'footer.php'; ?>
<script>
 let user = <?=$_SESSION['userId']?> ; 
 follow(user);   
</script>
