<?php
require "header.php";




$topic = '';
// get t 
if (isset($_GET['t'])) {
    $topic = $_GET['t'];
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
            <div class="col-sm-6 p-0 ">
                <div class="row p-0 m-0 mt-2 ">
                    <h3 class="col-10 text-muted"><?= !empty($topic) ? '#' . $topic : 'trending' ?></h3>
                    <div class="col-2 row justify-content-center align-items-center   rounded-circle">
                        <?php
                        if (!empty($topic)) {
                        ?>
                            <a href="post.php?tag=<?= $topic ?>"><i class="fa fa-plus"></i></a>
                        <?php } ?>
                    </div>
                </div>
                <!--  ----------------------------------------------------------------this is the 3rd view or something-------------------------------------- -->
                <div id="main-post">
                    <noscript style="color:red">this site requires javascript to function</noscript>
                </div>
            </div>
            <div class="col-sm-3 nav-hide sidebar-sticky flex-column pt-3 sticky-top" style="width: 94%; height: 20em; border: none; position: sticky; top: 4.4em; z-index: 1;">
                <div class=" card card-profile border-0 white rounded text-center profile-card sidebar-content">
                    <h4 class="co">topic</h4>
                    <h2 class="text-muted"><?= $topic ?></h2>

                    </a>
                    <h5 class="text-center small co">
                        <span id='topic-no'></span> posts
                    </h5>
                    <ul class="profile-opt mb-5">
                       <a href="post.php?tag=<?= $topic ?>" class="btn bg text-white nav-link no-h w-100">add a post</a> 
                    </ul>

                </div>
                <div class="shadow-sm mt-5 sidebar-content">
                    <h5 class='co'>Popular topics</h5>
                    <div id="popular-tags" style='overflow-y: auto; height: 33vh'>

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
<!--------- main script----->
<script defer>
    let no_posts = mainload('./inc/search.inc.php?type=post-tags&query=<?= $topic ?>&user=')
    get_popular_tags();
    no_posts.then(data=>{
    console.log(data)
    $('#topic-no').text(data);
    })
</script>