<?php
include './inc/dbh.inc.php';
include './inc/Auth/auth.php';
require_once 'header.php';

if (!isset($_GET['id'])) {
    echo "<h1>Comment could not be found. Go <a href='./'>back</a></h1>";
    die();
}

$post_id = $_GET['id'];
$sql = "SELECT `users`.`uidusers`, `users`.`profile_picture`, `users`.`usersFirstname`, `users`.`usersSecondname`, `comments`.* 
        FROM `users`, `comments` 
        WHERE (`comments`.`post_id` ='$post_id' AND (`uidusers` = `comments`.`user`)) 
        ORDER BY `comments`.`date` DESC";

?>

<link rel="stylesheet" href="css/comment.css">
<div class="row mob-m-0">
    <div class="col-sm-3 nav-hide sidebar-sticky pt-3">
        <?php
        require "./template/nav.php";
        ?>
    </div>

    <div class="col-sm-9">
        <div id="app">
            <div class="box mt-4">
                <input id="comm" class="text-dark" placeholder="Write a comment...">
                <button type="submit" class="btn" style="background: var(--ho); color: var(--white);"
                    id="submit">Submit</button>
            </div>
            <main id="comments-section">
                <?php
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    if ($row) {
                        ?>
                        <hr>
                        <div class="d-flex flex-row row mb-2">
                            <img src="img/<?= $row['profile_picture'] ?? 'M.jpg' ?>" class="image">
                            <div class="d-flex flex-column col-8 ml-2">
                                <span class="name">
                                    <?= $row["usersFirstname"] . ' ' . $row['usersSecondname'] ?>
                                </span>
                                <small class="comment-text co">
                                    <?= $row['comment'] ?>
                                </small>
                                <div class="d-flex flex-row align-items-center status">
                                    <a href="./inc/report.inc.php?comment=<?= $row['id'] ?>" class='col-2 mx-2'>
                                        <small>Report</small>
                                    </a>
                                    <?php
                                    if ($row["user"] == $_SESSION['userUid']) {
                                        echo '<a href="#delete" onclick="deleteComment(' . $row['post_id'] . ')" class="col-2 mx-2"><small>Delete</small></a>';
                                    }
                                    ?>
                                    <a href="#reply" onclick="showReplyForm(<?= $row['id'] ?>)"
                                        class="col-2 mx-2"><small>Reply</small></a>
                                    <small class="text-muted text-right ml-5 col-8">
                                        <?= $row['date'] ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div id="reply-form-<?= $row['id'] ?>" class="reply-form box w-75 ml-5 mt-2" style="display: none;">
                            <input id="reply-comm-<?= $row['id'] ?>" class="text-dark" placeholder="Write a reply...">
                            <button type="submit" class="btn" style="background: var(--ho); color: var(--white);"
                                onclick="postReply(<?= $row['id'] ?>)">Submit</button>
                        </div>
                        <?php
                    }
                }
                ?>
            </main>
        </div>
    </div>
</div>

<script src="./lib/jquery/jquery.js"></script>
<script>
    const post_id = '<?= $post_id ?>';

    $(document).ready(function () {
        $('#submit').click(postComment);
        <?php
        if (isset($_GET['act'])) {
            echo 'alert("' . $_GET['act'] . '")';
        }
        ?>
    });

    function postComment() {
        $.ajax({
            type: 'POST',
            url: 'inc/comment.inc.php',
            data: {
                user: sessionStorage.getItem('name'),
                comment: $('#comm').val(),
                id: post_id
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: postSuccess,
            error: postError
        });
    }

    function postSuccess(data, textStatus, jqXHR) {
        $('#comm').val("");
        window.location.reload();
    }

    function postError(jqXHR, textStatus, errorThrown) {
        alert('Could not post comment');
    }

    function deleteComment(id) {
        $.ajax({
            type: 'POST',
            url: 'inc/comment.inc.php',
            data: {
                comment_id: id
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function (data, textStatus, jqXHR) {
                window.location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Could not delete comment');
            }
        });
    }

    function showReplyForm(commentId) {
        $('#reply-form-' + commentId).toggle();
    }

    function postReply(parentId) {
        var replyInputId = '#reply-comm-' + parentId;
        var replyText = $(replyInputId).val();

        $.ajax({
            type: 'POST',
            url: 'inc/comment.inc.php',
            data: {
                user: sessionStorage.getItem('name'),
                comment: replyText,
                id: post_id,
                parent_id: parentId
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: postSuccess,
            error: postError
        });
    }
</script>

<?php
require_once 'footer.php';
?>