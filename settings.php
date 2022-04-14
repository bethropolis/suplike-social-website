<?php
require 'header.php';
require 'inc/dbh.inc.php';
if (!isset($_SESSION['userId'])) {
  header('Location: ./login.php');
  exit();
}
$query = "SELECT * FROM `users` WHERE `idusers`=" . $_SESSION['userId'] . "";
$result = $conn->query($query)->fetch_assoc();
if (!is_null($_SESSION['profile-pic'])) {
  $prmimg =  $_SESSION['profile-pic'];
} else {
  $prmimg = 'M.jpg';
}  ?>

<div class="row">
  <div class="col-sm-3 settings-sidebar">
    <a href="?profile">
      <div class="settings-option">
        <h3>profile</h3>
      </div>
    </a>
    <a href="?delete">
      <div class="settings-option">
        <h3>delete acc</h3>
      </div>
    </a>
    <a href="?password">
      <div class="settings-option hover">
        <h3>password</h3>
      </div>
    </a>
    <a href="api/developer/">
      <div class="settings-option">
        <h3>Developer</h3>
      </div>
    </a>
    <a href="?about">
      <div class="settings-option">
        <h3>About</h3>
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

    if (isset($_GET['about'])) {
      // about page
    ?>
      <div class="settings-content py-4">
        <h2>About</h2>
        <div class="row">
          <!-- place github url + developer portfolio as buttons-->
          <div class="col-sm-6">
            <div class="">
              <h3>Github</h3>
              <a href="https://github.com/bethropolis/suplike-social-website" target="_blank">
                <button class="btn btn-primary">
                  <i class="fa fa-github"></i>
                </button>
              </a>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="">
              <h3>Developer Portfolio</h3>
              <a href="https://bethropolis.github.io" target="_blank">
                <button class="btn btn-primary">
                  <i class="fa fa-globe"></i>
                </button>
              </a>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="">
              <h3>Developer</h3>
              <p>
                <strong>Name:</strong>
                <br>
                Bethuel Kipsang
              </p>
              <p>
                <strong>Email:</strong>
                <br>
                bethropolis@gmail.com
              </p>
            </div>
          </div>
        </div>
      </div>

    <?php
    } else if (isset($_GET['delete'])) {
      // delete account page
    ?>
      <div class="settings-header">
        <h2>delete account</h2>
      </div>
      <div class="settings-body">
        <form action="inc/delete.inc.php" method="POST">
          <div class="form-group">
            <label for="delete-user">type your username</label>
            <input type="password" class="form-control w-100 delete-user"  name="user" id="delete-user">
          </div>
          <button type="submit" name='delete_profile' class="btn btn-danger delete-btn" disabled>Delete</button>
        </form>
      </div>
    <?php
    } else if (isset($_GET['password'])) {
      // password page
    ?>
      <div class="settings-header">
        <h2>password</h2>
      </div>
      <?php 
      if(isset($_GET['err']) && $_GET['err'] == 'wrongpassword'){
        echo '<div class="alert alert-danger" role="alert">
        <strong>Error!</strong> wrong password.
      </div>';
      }
      if(isset($_GET['success']) && $_GET['success'] == 'passwordchanged'){
        echo '<div class="alert alert-success" role="alert">
        <strong>Success!</strong> Password changed successfully.
      </div>';
      }
      ?>
      <div class="settings-body">
        <form action="inc/settings.inc.php" method="POST">
          <div class="form-group">
            <label for="current-password">Current password</label>
            <input type="password" class="form-control w-100 submit" name="current" id="current-password">
          </div>
          <div class="form-group">
            <label for="new-password">New password</label>
            <input type="password" class="form-control w-100 submit" name="newpass" id="new-password">
          </div>
          <button type="submit" name="password_change" class="btn bg post-btn">Save</button>
        </form>
      </div>
    <?php
    } else {
      // settings page
    ?>
      <div class="settings-header">
        <h2>settings</h2>
      </div>
      <div class="settings-body">
        <form id="uploadimage" action="" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="profile-pic" class="profile-pic shadow-sm">
              <img src="img/<?php echo $prmimg; ?>" alt="profile-pic" class="img-profile img-thumbnail" onerror="this.error = null; this.src ='img/M.jpg' " style="background-size: cover; width: 120px;height: 120px; border-radius: 50%;">
            </label>
            <input type="file" title="change profile pic" accept=".png,.gif,.jpg,.webp" name="file" id="profile-pic" style="display: none;" multiple required /><br>
          </div>
          <button class="btn" type="submit" style="background:#6c5ce7;width: fit-content;margin: 0 auto;">change profile picture</button>
      </div>
      </form>
      <br><br>
      <form action="inc/settings.inc.php" method="POST" class="w-75 mx-auto">
        <div class="form-group">
          <label for="username">username</label>
          <input type="text" class="form-control w-100" name="username" id="username" value="<?= $_SESSION['userUid']; ?>">
        </div>
        <div class="form-group">
          <label for="fname">First name</label>
          <input type="text" class="form-control w-100" name="firstname" id="fname" value="<?php echo $result['usersFirstname']; ?>">
        </div>
        <div class="form-group">
          <label for="sname">Second name</label>
          <input type="text" class="form-control w-100" name="lastname" id="sname" value="<?php echo $result['usersSecondname']; ?>">
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control w-100" name="email" id="email" value="<?php echo $result['emailusers']; ?>">
        </div>
        <div class="form-group">
          <label for="bio">Bio</label>
          <textarea name="bio" id="bio" cols="30" rows="10" maxlength="200" class="form-control w-100"><?php echo $result['bio']; ?></textarea>
        </div>

        <button type="submit" name="profile_btn" class="btn bg post-btn btn-lg">Save</button>
      </form>

<?php
    }
?>

</div>
</div>
</div>
  </div>
  <br>
  <br>
  <br>
<?php
require 'footer.php';
?>
<script>
  $(document).ready(function() {
    // upload image on form #uploadimage submit to inc/profile-pic.inc.php
    $('#uploadimage').submit(function(e) {
      e.preventDefault();
      var image_name = $('#profile-pic').val();
      if (image_name == '') {
        alert("Please select an image");
        return false;
      } else {
        var extension = $('#profile-pic').val().split('.').pop().toLowerCase();
        if (jQuery.inArray(extension, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
          alert('Invalid Image File');
          $('#profile-pic').val('');
          return false;
        } else {
          var form_data = new FormData(this);
          $.ajax({
            url: "./inc/profile-pic.inc.php",
            type: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
              $('#uploaded_image').html("<label class='text-success'>Image Uploading...</label>");
            },
            success: function(data) {
              $('#uploaded_image').html(data);
            }
          });
        }
      }


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
  });
</script>