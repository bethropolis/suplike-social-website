<?php 
require 'header.php';
require 'inc/dbh.inc.php'; 

$query = "SELECT `emailusers` FROM `users` WHERE `idusers`=".$_SESSION['userId'].""; 
$result = $conn->query($query)->fetch_assoc();  
  
 ?>  
<div class="row">
	<div class="col-sm-3 settings-sidebar">
		<a href="?profile"><div class="settings-option sticky-top">
			<h3>profile</h3>
		</div></a>
    <a href="?delete"><div class="settings-option">
      <h3>delete acc</h3>
    </div></a> 
		<a href="?password"><div class="settings-option">
			<h3>password</h3>
		</div></a> 
		<a href="?about"><div class="settings-option">
			<h3>about</h3>
		</div></a>  
	</div>
	<div class="col-sm-9 settings-main">   
  


<?php  
  
if (isset($_GET['profile'])) {
 	echo '
        <div class="card profile-card">
         <img class="profile-pic shadow-sm" src="img/M.jpg" title="prpfile pic" alt="profile picture" style="width: 70px;height: 70px; border-radius: 50%;">
        <h4 id="profile-name">'.$_SESSION['firstname']." ".$_SESSION['lastname'].'</h4>  
        <h5 class="text-center userName">'."@".$_SESSION['userUid'].'</h5>
        <i class="fa fa-edit fa-2x"></i>      
        </div>   
        <form action="inc/settings.inc.php" method="POST" class="setting-form">        
        change username:<input type="text"  class="input" value="'.$_SESSION['userUid'].'" name="username"> <br/> 
        change email: <input type="text"  class="input" value="'.$result['emailusers'].'" name="email"><br/>  
        change first name: <input type="text"  class="input" name="firstname" value="'.$_SESSION['firstname'].'"><br/>
        change last name: <input type="text"  class="input" name="lastname" value="'.$_SESSION['lastname'].'"><br/> 
           bio:<br/><textarea name="bio"  class="input" placeholder="enter bio....." cols="30" rows="4"></textarea> <br/> 
        <button type="submit" class="btn submit" name="profile_btn" disabled>save</button > 
         </form>   
  
 	';   
 } 

  if (isset($_GET['password'])) {
  	echo '
          <form action="inc/settings.inc.php" method="POST" class="setting-form">
  	      <h1>change password</h1><br> 
          <input type="text" class="input" name="current" placeholder="current password..."><br/>
          <input type="text"  class="input" name="newpass" placeholder="new password"> <br/>
          <button type="submit" name="password_change" class="btn submit" disabled>change</button><br>
          </form>   

  	';
 } 


 
  if (isset($_GET['delete'])) {
  	 echo '<div class="setting-form">   
      <h1>delete account</h1>
      <p>enter username to delete acc</p> 
      <input type="text" class="input delete-user" name="delete" placeholder="enter username..."> <br> 
      <button class="btn delete-btn" name="delete_profile" style="background:red;" disabled>delete</button > 
     </div>';  
  }  
?>


	</div>
	
</div>
<?php  
require 'footer.php';
?>
<script> 
	jQuery(document).ready(function() {
    $('.input').each(function() {   
		this.addEventListener('input', showBtn)
 
	})  
	function showBtn(){
    $('.submit').attr('disabled',false);  
	}
});

username = '<?=$_SESSION['userUid']?>'; 
$('.delete-user').on('input',function() {  
     if($('.delete-user').val() == username){    
      $('.delete-btn').attr('disabled',false);
     }
  })  
</script>