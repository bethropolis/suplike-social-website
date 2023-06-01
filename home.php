<?php
require "header.php";
?>
<script>
    if (sessionStorage.getItem('user') == null) {
        sessionStorage.setItem('user', "<?= isset($_SESSION['token']) ? $_SESSION['token'] : null ?>");
        sessionStorage.setItem('name', "<?= isset($_SESSION['userUid']) ? $_SESSION['userUid'] : null ?>");
    };
</script>
<main>
    <link rel="stylesheet" href="./css/post.css">
    <div class="row mob-m-0">
        <div class="col-sm-3 nav-hide sidebar-sticky pt-3">
            <div class="card card-profile p-4 shadow white rounded text-center profile-card sidebar-content" style="width: 94%; height: 20em;">
                <a href="./profile.php">
                    <img class="profile-pic img-profile shadow-sm" <?php if (!is_null($_SESSION['profile-pic'])) {
                                                                        echo 'src="img/' . $_SESSION['profile-pic'] . '"';
                                                                    } else {
                                                                        echo 'src="img/M.jpg"';
                                                                    }  ?> title=" " alt="profile picture" style="width: 70px;height: 70px; border-radius: 50%;">
                </a>
                <a href="./profile.php" class="nameanchor">
                    <h4 id="profile-name"><?php echo $_SESSION['firstname'];
                                            echo " " . $_SESSION['lastname']; ?></h4>
                </a>
                <h5 class="text-center userName co"><?php echo "@" . $_SESSION['userUid'] ?></h5>
                <ul class="profile-opt" style="margin: 0;padding: 0;">
                    <a href="profile.php" style="color: #252130;"><i class="fa fa-user fa-2x"></i></a>
                    <a href="post.php" style="color: #252130;" style="color: #252130;"><i class="fa fa-edit fa-2x"></i></a>
                    <a href="settings.php?profile" style="color: #252130;"><i class="fas fa-user-cog fa-2x"></i></a>
                </ul>
            </div>
        </div>

        <!-- ------------------------------------------- this is the posting area  --------------------------------------------------------------->

        <div class="col-sm-9">

            <div class="container-lg nav-hide">
                <form action="inc/post.inc.php" class="mx-auto ca p-2 my-2 col-12 form bg-light text-center postform" method="POST" enctype="multipart/form-data">
                    <img src="" id="imagedisp" alt="" style="height: 172px; width: auto; margin: 7px;">
                    <input type="hidden" value="txt" id="type" name="type">
                    <input type="file" name="image" id="image_post" style="display: none;">
                    <!--also this -->
                    <textarea id="text" cols="40" class="textinput" rows="4" name="posttext" placeholder="what would you like to post about.." oninvalid="this.setCustomValidity('Please you have to write something about this, text cannnot be empty')" oninput="setCustomValidity('')" required></textarea>
                    <div class=" ml-auto mr-1 post-options">
                        <label for="image_post"><i class="fa fa-image fa-2x"></i></label>
                    </div>
                    <button class="btn bg post-btn" name="upload">post</button>
                </form>

            </div>
            <!--  ----------------------------------------------------------------this is the 3rd view or something-------------------------------------- -->
            <div id="main-post">
                <noscript style="color:red">this site requires javascript to function</noscript>
            </div>
        </div>
    </div>
</main>
<br><br><br>
<div class="mobile nav-show">
    <br><br><br>
</div>


<?php
require "mobile.php";
require "footer.php";
?>

<!--------- main script----->
<script defer>
    active_page(0);
    mainload();
</script>