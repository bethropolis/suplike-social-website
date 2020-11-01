<?php
// // Database connection info
// $dbDetails = array(
//  'host' => 'localhost',
//  'user' => 'root',
//  'pass' => '',
//  'db' => 'test'
// ); 
// // DB table to use
// $table = 'members';
// // Table's primary key
// $primaryKey = 'id';
// // Array of database columns which should be read and sent back to DataTables.
// // The `db` parameter represents the column name in the database.
// // The `dt` parameter represents the DataTables column identifier.
// $columns = array(
//  array( 'db' => 'first_name', 'dt' => 0 ),
//  array( 'db' => 'last_name', 'dt' => 1 ),
//  array( 'db' => 'email', 'dt' => 2 ),
//  array( 'db' => 'gender', 'dt' => 3 ),
//  array( 'db' => 'country', 'dt' => 4 ),
//  array(
//  'db' => 'created',
//  'dt' => 5,
//  'formatter' => function( $d, $row ) {
//  return date( 'jS M Y', strtotime($d));
//  }
//  ),
//  array(
//  'db' => 'status',
//  'dt' => 6,
//  'formatter' => function( $d, $row ) {
//  return ($d == 1)?'Active':'Inactive';
//  }
//  )
// );
// $searchFilter = array();
// if(!empty($_GET['search_keywords'])){
//  $searchFilter['search'] = array(
//  'first_name' => $_GET['search_keywords'],
//  'last_name' => $_GET['search_keywords'],
//  'email' => $_GET['search_keywords'],
//  'country' => $_GET['search_keywords']
//  );
// }
// if(!empty($_GET['filter_option'])){
//  $searchFilter['filter'] = array(
//  'gender' => $_GET['filter_option']
//  );
// }
// // Include SQL query processing class
// require 'ssp.class.php';
// // Output data as json format
// echo json_encode(
//  SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $searchFilter )
// );
// Database configuration

$dbHost = "localhost";
$dbUsername = "root";
$dbPassword = ""; 
$dbName = "test"; 
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
 $whrSQL = "WHERE (title LIKE '%".$searchKeyword."%' OR content LIKE '%".$searchKeyword."%')";
 }
}
// Get matched records from the database
$result = $db->query("SELECT * FROM posts $whrSQL ORDER BY id DESC");
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
 $title = !empty($searchKeyword)?highlightWords($row['title'], $searchKeyword):$row['title'];
 $contnet = !empty($searchKeyword)?highlightWords($row['content'], $searchKeyword):$row['content'];
?>
<div class="list-item">
 <h4><?php echo $title; ?></h4>
 <p><?php echo $contnet; ?></p>
</div>
<?php } }else{ ?>
<p>No post(s) found...</p>
<?php }  
?>

