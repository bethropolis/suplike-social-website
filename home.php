<?php
require "header.php";


$name = '';
$pic = 'M.jpg';
$user = '';

if (isset($_SESSION['userUid'])) {
    $name = $_SESSION['firstname'] . " " . $_SESSION['lastname'];
    $pic = $_SESSION['profile-pic'] ?? 'M.jpg';
    $user = $_SESSION['userUid'];
}


?>
<script>
    if (sessionStorage.getItem('user') == null) {
        sessionStorage.setItem('user', "<?= isset($_SESSION['token']) ? $_SESSION['token'] : null ?>");
        sessionStorage.setItem('name', "<?= isset($_SESSION['userUid']) ? $_SESSION['userUid'] : null ?>");
    };
</script>
<main>
    <link rel="stylesheet" href="./css/post.css">
    <link rel="stylesheet" href="./css/story.css?v.15">
    <div class="p-0">
        <div class="row mob-m-0">
            <div class="col-sm-3 nav-hide sidebar-sticky pt-3">
                <?php
                require "./template/nav.php";
                ?>
            </div>

            <!-- ------------------------------------------- this is the posting area  --------------------------------------------------------------->
            <div class="cont">
                <i class="fa fa-times fa-2x" id="stop_it"></i>
                <div data-slide="slide" class="slide">
                    <div class="slide-items">
                    </div>
                    <p class="content-text">heeeeelo</p>
                    <i class="far fa-heart fa-2x " id="like-btn"></i>
                    <nav class="slide-nav">
                        <div class="slide-thumbs"></div>
                        <button class="slide-prev">Previous</button>
                        <button class="slide-next">Next</button>
                    </nav>
                </div>
            </div>
            <div class="col-sm-6 p-0">
                <?php require "./story.php" ?>
                <div class="container-lg nav-hide">
                    <form action="inc/post.inc.php"
                        class="mx-auto ca p-2 my-2 col-12 form bg-light text-center postform" method="POST"
                        enctype="multipart/form-data">
                        <img src="" id="imagedisp" alt="" class="mx-auto my-3"
                            style="max-height: 172px; width: auto; margin: 7px;">
                        <input type="hidden" value="txt" id="type" name="type">
                        <input type="file" name="image" id="image_post" style="display: none;">
                        <!--also this -->
                        <textarea id="text" cols="40" class="textinput p-2" rows="4" name="postText"
                            placeholder="what would you like to post about.."
                            oninvalid="this.setCustomValidity('Please you have to write something about this, text cannnot be empty')"
                            oninput="setCustomValidity('')" required></textarea>
                        <div class=" ml-auto mr-1 post-options">
                            <label for="image_post"><i class="fa fa-image fa-2x"></i></label>
                            <button class="btn bg post-btn" name="upload">post</button>
                        </div>
                    </form>

                </div>
                <!--  ----------------------------------------------------------------this is the 3rd view or something-------------------------------------- -->
                <div id="main-post">
                    <noscript style="color:red">this site requires javascript to function</noscript>
                </div>
            </div>
            <div class="col-sm-3 nav-hide sidebar-sticky flex-column pt-3 sticky-top"  style="width: 94%; height: 20em; border: none; position: sticky; top: 4.4em; z-index: 1;">
                <div class=" card card-profile border-0 white rounded text-center profile-card sidebar-content"
                  >
                    <a href="./profile.php">
                        <img class="profile-pic img-profile shadow-sm" <?php echo 'src="img/' . $pic . '"'; ?> title=" "
                            alt="profile picture" style="width: 70px;height: 70px; border-radius: 50%;">
                    </a>
                    <a href="./profile.php" class="nameanchor">
                        <h4 id="profile-name">
                            <?php echo $name; ?>
                        </h4>
                       
                    </a>
                    <h5 class="text-center userName co">
                        <?php echo "@" . $user; ?>
                    </h5>
                    <ul class="profile-opt mb-5">
                        <a href="profile.php" style="color: #252130;"><i class="fa fa-user fa-2x"></i></a>
                        <a href="post.php" style="color: #252130;" style="color: #252130;"><i
                                class="fa fa-edit fa-2x"></i></a>
                        <a href="settings.php?profile" style="color: #252130;"><i class="fas fa-user-cog fa-2x"></i></a>
                    </ul>

                </div>
                <div class="shadow-sm mt-5 sidebar-content">
                    <h5 class='co'>Popular users</h5>
                    <div id="popular-users" style='overflow-y: auto; height: 33vh'>

                    </div>
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
<script src="./js/story.js?u"></script>
<!--------- main script----->
<script defer>
    active_page(0);
    mainload();
    get_popular_users();
</script>