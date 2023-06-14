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
  $prmimg = $_SESSION['profile-pic'];
} else {
  $prmimg = 'M.jpg';
} ?>

<style>
  @media screen and (min-width: 600px) {
    body {
      overflow-y: hidden;
    }

    .settings-main {
      overflow-y: scroll;
      height: calc(100vh - 50px);
    }
  }
</style>

<div class="row co">
  <div class="col-sm-3 settings-sidebar   sidebar-sticky">
    <a href="?profile">
      <div class="settings-option">
        <h3 class="co">profile</h3>
      </div>
    </a>
    <a href="?appearance">
      <div class="settings-option">
        <h3 class="co">appearance</h3>
      </div>
    </a>
    <a href="?delete">
      <div class="settings-option">
        <h3 class="co">delete acc</h3>
      </div>
    </a>
    <a href="?password">
      <div class="settings-option hover">
        <h3 class="co">password</h3>
      </div>
    </a>
    <a href="?about">
      <div class="settings-option">
        <h3 class="co">About</h3>
      </div>
    </a>
    <a href="inc/logout.inc.php" onclick="sessionStorage.clear();sessionStorage.setItem('load', true)">
      <div class="settings-option">
        <h3 class="co">logout</h3>
      </div>
    </a>
  </div>
  <div class="col-sm-9 settings-main">


    <?php

    if (isset($_GET['appearance'])) {
      // this is where user can change app themes and other settings
      ?>
      <div class="settings-header mt-4">
        <h2 class="co">appearance</h2>
      </div>
      <div class="settings-body pt-2">
        <h3 class="co">app theme</h3>
        <div class="row col-12 w-100">
          <label for="chat-theme" class="col-6 co">select theme</label>
          <select id="chat-theme" class="col-4" name="chat-theme">
            <option value="none" disabled selected>default</option>
            <option value="light">light</option>
            <option value="dark">dark</option>
          </select>
        </div>

      <?php

    } else if (isset($_GET['about'])) {
      // about page
      ?>
          <div class="settings-content py-5">
            <h2 class="co">About</h2>
            <div class="row">
              <!-- place github url + developer portfolio as buttons-->
              <div class="col-sm-6">
                <div class="">
                  <h3 class="co">Github</h3>
                  <a href="https://github.com/bethropolis/suplike-social-website" target="_blank">
                    <button class="btn h4 btn-primary">
                      <i class="fab fa-github"></i>
                    </button>
                  </a>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="">
                  <h3 class="co">Developer Portfolio</h3>
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
                  <h3 class="co">Developer</h3>
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
            <div class="settings-header py-4">
              <h2 class="co">delete account</h2>
            </div>
            <div class="settings-body">
              <form action="inc/delete.inc.php" method="POST">
                <div class="form-group">
                  <label for="delete-user">type your username</label>
                  <!-- use bootstrap tooltips -->

                  <input type="password" class="form-control w-100 delete-user" name="user" id="delete-user">
                </div>
                <button type="submit" name='delete_profile' class="btn btn-danger delete-btn" disabled>Delete</button>
              </form>
            </div>
        <?php
    } else if (isset($_GET['password'])) {
      // password page
      ?>
              <div class="settings-header py-4">
                <h2 class="co">password</h2>
              </div>
          <?php
          if (isset($_GET['err']) && $_GET['err'] == 'wrongpassword') {
            echo '<div class="alert alert-danger" role="alert">
        <strong>Error!</strong> wrong password.
      </div>';
          }
          if (isset($_GET['success']) && $_GET['success'] == 'passwordchanged') {
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
              <div class="settings-header py-4">

                <h2 class="co">settings</h2>
              </div>
              <div class="settings-body">
                <form id="uploadimage" action="" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <label for="profile-pic" class="profile-pic shadow-sm">
                      <img src="img/<?php echo $prmimg; ?>" alt="profile-pic" class="img-thumbnail"
                        onerror="this.error = null; this.src ='img/M.jpg' "
                        style="background-size: cover; width: 120px;height: 120px; border-radius: 50%;">
                    </label>
                    <input type="file" title="change profile pic" accept=".png,.gif,.jpg,.webp" name="file" id="profile-pic"
                      style="display: none;" data-toggle="tooltip" data-placement="top" title="click to select image" multiple
                      required /><br>
                  </div>
                  <button class="btn profile-btn" for="profile-pic" type="submit"
                    style="background:#6c5ce7;width: fit-content;margin: 0 auto;" data-toggle="tooltip" data-placement="bottom"
                    title="click above image to upload" disabled>change profile picture</button>

                </form>
                <br><br>
                <form action="inc/settings.inc.php" method="POST" class="w-75 mx-auto">
                  <div class="form-group">
                    <label for="username">username</label>
                    <input type="text" class="form-control w-100" name="username" id="username"
                      value="<?= $_SESSION['userUid']; ?>">
                  </div>
                  <div class="form-group">
                    <label for="fname">First name</label>
                    <input type="text" class="form-control w-100" name="firstname" id="fname"
                      value="<?php echo $result['usersFirstname']; ?>">
                  </div>
                  <div class="form-group">
                    <label for="sname">Second name</label>
                    <input type="text" class="form-control w-100" name="lastname" id="sname"
                      value="<?php echo $result['usersSecondname']; ?>">
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control w-100" name="email" id="email"
                      value="<?php echo $result['emailusers']; ?>">
                  </div>
                  <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea name="bio" id="bio" cols="30" rows="10" placeholder="enter your bio here...."
                      title="not more than 200 words" maxlength="200"
                      class="form-control w-100"><?php echo $result['bio']; ?></textarea>
                  </div>

                  <button type="submit" name="profile_btn" class="btn bg post-btn btn-lg">Save</button>
                </form>
              </div>


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
  $(document).ready(function () {
    // upload image on form #uploadimage submit to inc/profile-pic.inc.php

    // generate a preview of the image
    $('#profile-pic').change(function () {
      var input = this;
      $('.img-thumbnail').attr('disabled', false);
      $('#uploadimage').submit();
      var url = $(this).val();
      var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
      if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg" || ext == "webp")) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $('.img-thumbnail').attr('src', e.target.result);
          $('.img-thumbnail').css('background-image', 'none');
        }
        reader.readAsDataURL(input.files[0]);
      }
    });

    $('#uploadimage').submit(function (e) {
      e.preventDefault();
      // set profile-btn to loading
      $('.profile-btn').html('preparing...');
      // #profile-pic file is not selected
      if ($('#profile-pic').val() == '') {
        $('#img-thumbnail').css('border', '1px solid red');
        alert('Please select a file');
        $('.profile-btn').html('upload failed');
        // set back to danger
        $('.profile-btn').css('background', '#dc3545');
        return false;
      }
      // 
      //
      var image_name = $('#profile-pic').val();
      var extension = $('#profile-pic').val().split('.').pop().toLowerCase();
      if (jQuery.inArray(extension, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
        $('.profile-btn').html('invalid image');
        $('.profile-btn').css('background', '#dc3545');
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
          beforeSend: function () {
            $('.profile-btn').html('uploading...');
          },
          success: function (data) {
            $('.profile-btn').html('uploaded');
            setTimeout(function () {
              $('.profile-btn').css('background', '#28a745');
            }, 1000);

          }
        });
      }


    });


    jQuery(document).ready(function () {
      $('.input').each(function () {
        this.addEventListener('input', showBtn)

      })

      function showBtn() {
        $('.submit').attr('disabled', false);
      }
    });
    // use: JQuery when chat theme is changed store value into local storage
    $('#chat-theme').change(function () {
      var theme = $(this).val();
      localStorage.setItem('theme', theme);
      window.location.reload();
    });

    username = '<?= $_SESSION['userUid'] ?>';
    $('.delete-user').on('input', function () {
      if ($('.delete-user').val() == username) {
        $('.delete-btn').attr('disabled', false);
      } else {
        $('.delete-btn').attr('disabled', true);
      }
    })
  });
</script>