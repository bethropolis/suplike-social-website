<?php
require 'header.php';
require 'inc/dbh.inc.php';
if (!isset($_SESSION['userId'])) {
  header('Location: ./login.php');
  exit();
}
$query = "SELECT * FROM `users` WHERE `idusers`=" . $_SESSION['userId'] . "";
$result = $conn->query($query)->fetch_assoc();
if(!is_null($_SESSION['profile-pic'])){ 
  $prmimg =  $_SESSION['profile-pic'];
   }else{ 
    $prmimg = 'M.jpg';
  }  ?>

<div class="row">
  <div class="col-sm-3 settings-sidebar">
    <a href="?profile">
      <div class="settings-option sticky-top">
        <h3>profile</h3>
      </div>
    </a>
    <a href="?delete">
      <div class="settings-option">
        <h3>delete acc</h3>
      </div>
    </a>
    <a href="?password">
      <div class="settings-option">
        <h3>password</h3>
      </div>
    </a>
    <a href="inc/logout.inc.php" onclick="sessionStorage.clear();sessionStorage.setItem('load', true)">
      <div class="settings-option">
        <h3>logout</h3>
      </div>
    </a>
  </div>
  <div class="col-sm-9 settings-main">



    <?php

    if (isset($_GET['profile'])) {
      echo '
        <div class="card profile-card">   
        <form id="uploadimage" action="" method="post" enctype="multipart/form-data"> 
            <label for="profile-pic" class="profile-pic shadow-sm" id="profile-pic-label" style="background:url(\'img/'.$prmimg.'\'); background-size: cover; width: 70px;height: 70px; border-radius: 50%;" ></label>  
                <input type="file" title="change profile pic" accept=".png,.gif,.jpg,.webp"  name="file" id="profile-pic" style="display: none;" multiple required/><br> 
            <button class="btn" type="submit" style="background:#6c5ce7;width: fit-content;margin: 0 auto;">change profile picture</button>  
           </form>    
        <h4 id="profile-name">' . $_SESSION['firstname'] . " " . $_SESSION['lastname'] . '</h4>  
        <h5 class="text-center userName">' . "@" . $_SESSION['userUid'] . '</h5>
        </div>   
        <form action="inc/settings.inc.php" method="POST" class="setting-form">        
        change username:<input type="text"  class="input" value="' . $_SESSION['userUid'] . '" name="username"> <br/> 
        change email: <input type="text"  class="input" value="' . $result['emailusers'] . '" name="email"><br/>  
        change first name: <input type="text"  class="input" name="firstname" value="' . $_SESSION['firstname'] . '"><br/>
        change last name: <input type="text"  class="input" name="lastname" value="' . $_SESSION['lastname'] . '"><br/> 
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
      <form action="./inc/delete.inc.php" method="post">
      <input type="text" class="input delete-user" name="user" placeholder="enter username..."> <br> 
      <button type="submit" class="btn delete-btn" name="delete_profile" style="background:red;" disabled>delete</button > 
     </form> 
     </div>
     ';
    }
    ?>


  </div>
</div>
<?php
require 'footer.php';
?>
<script>
  $(document).ready(function() {
    $("#uploadimage").on('submit', (function(e) {
      e.preventDefault();
      // m = URL.createObjectURL(event.target.files[0]);
      // $('#profile-pic-label').css({
      //   "background": "url(" + m + ")"
      // });
      $.ajax({
        url: './inc/profile-pic.inc.php', // Url to which the request is send
        type: "POST", // Type of request to be send, called as method
        data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        contentType: false, // The content type used when sending data to the server.
        cache: false, // To unable request pages to be cached
        processData: false, // To send DOMDocument or non processed data file it is set to false
        success: function(data) // A function to be called if request succeeds
        {
          alert('profile image changed')
          console.log(data); 
        }
      });
    }));

  });



  jQuery(document).ready(function() {
    $('.input').each(function() {
      this.addEventListener('input', showBtn)

    })

    function showBtn() {
      $('.submit').attr('disabled', false);
    }
  });

  username = '<?= $_SESSION['userUid'] ?>';
  $('.delete-user').on('input', function() {
    if ($('.delete-user').val() == username) {
      $('.delete-btn').attr('disabled', false);
    } else {
      $('.delete-btn').attr('disabled', true);
    }
  })
</script>