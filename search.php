<?php 

require 'inc/dbh.inc.php';
require 'header.php'; 
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
<link rel="stylesheet" type="text/css" href="css/search.css?k">  


<!-- Search form -->
<form method="post" action="">  
 <div class="search_input"> 
   <input type="text" class="search_box " name="keyword" value="<?php echo $searchKeyword; ?>" placeholder="Search by keyword..." > 
   <input type="submit" class="search_button btn" name="searchSubmit" value="Search">
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
<div class="search-list-item">

<a href="profile.php?id=<?=$row['idusers']?>" class="prof-link">    
 <h4><?php echo $title; ?></h4></a> 
 <p><?php echo '@'.$contnet; ?></p>
 <button id="<?=$row['idusers']?>" class="btn search-btn follow-btn"><?=$follow?></button> 
</div> 
<?php } }else{ ?>
<p>No user(s) found...</p>
<?php }   
?>
  
<?php require 'footer.php'; ?> 
<script type="text/javascript">
   
$('.follow-btn').click(function () {   
        var key;
        /*---------------------improvise-------------*/  
        if ($(this).text() == 'follow') {
            key = 'true';            
        }
        if ($(this).text() == 'following') {  
            key = 'false';   
        } 
        if (key === 'true') { 
           $(this).text('following')
         }else{
           $(this).text('follow')         
         }

      url = "./inc/follow.inc.php?user="+<?=$_SESSION['userId']?>+"&following="+this.id+"&key="+key;   
      console.log(url)  
       var settings = {
                 "async": true,
                "crossDomain": false,  
                "url": url,       
                "method": "GET",    
              }     
              
          $.ajax(settings).done(function (follow) {
            console.log(follow);   
        })
     })

</script>