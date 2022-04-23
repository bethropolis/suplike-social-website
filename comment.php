<?php
include './inc/dbh.inc.php';
include './inc/Auth/auth.php';
session_start();


if (!isset($_GET['id'])) {
    echo "<h1>comment could not be found. go <a href='./'>back</a></h1>";
    die();
}
$post_id = $_GET['id'];
$sql = "SELECT `users`.`uidusers`, `users`.`profile_picture`, `users`.`usersFirstname`, `users`.`usersSecondname`, `comments`.* 
FROM `users` , `comments` WHERE (`comments`.`post_id` ='$post_id' AND ( `uidusers` = `comments`.`user`)) ORDER BY `comments`.`date` DESC";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>comments</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="./lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./lib/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="css/comment.min.css">
    <style>
        .rounded-image {
            border-radius: 50% !important;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50px;
            width: 50px
        }

        .name {
            font-weight: 600
        }

        .comment-text {
            font-size: 12px
        }

        .status small {
            margin-right: 10px;
            color: blue
        }
    </style>
</head>

<body>
    <div id="app">
        <nav><a href="./">
                <img title="go to homepage" src="img/logo.png" alt="logo" style="width:35px; height: 35px;">
            </a>
            <div class="nav-content">

                <a href="" id="back"><i class="fa fas fa-arrow-left toShow fa-2x"></i></a>
                <a href="./" class="home">
                    <i class="fa fas fa-home fa-2x"></i>
                </a>
                <a href="inc/logout.inc.php" class="log-out">
                    <i class="fa fas fa-sign-out fa-2x"></i>
                </a>
            </div>
        </nav>
        <div class="box">
            <input id="comm" placeholder="write a comment...">
            <button type="submit" class="btn" style="background: var(--ho);color: var(--white);" id="submit">submit</button>
        </div>
        <main>

            <?php
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                if ($row) {
            ?> <hr>
                    <div class="d-flex flex-row mb-2"> <img src="img/<?= $row['profile_picture'] ?? 'M.jpg'?>" width="40" class="rounded-image">
                        <div class="d-flex flex-column ml-2">
                            <span class="name"><?= $row["usersFirstname"] . ' ' . $row['usersSecondname'] ?></span>
                            <small class="comment-text"><?= $row['comment'] ?></small>
                            <div class="d-flex flex-row align-items-center status">
                                <a href="./inc/report.inc.php?comment=<?=$row['id'] ?>">
                                    <small>Report</small>
                                </a>
                                <!-- delete -->
                                <?php
                                if ($row["user"] ==  $_SESSION['userUid']) {
                                    echo  '<a href="#delete" onclick=\'deleteComment("'.$row['post_id'].'")\'><small>delete</small></a>';
                                } ?>

                                <small class="text-muted ml-5"><?= $row['date'] ?> </small>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>       
        </main>
    </div>
    <script src="./lib/jquery/jquery.js"></script>
    <script>
        const post_id = '<?= $post_id ?>';
        $('#submit').click(() => {
            postComment()
        })

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
            $('#comm').val(" ")
            window.location.reload();
        }

        function postError(jqXHR, textStatus, errorThrown) {
            alert('could not post comment');
        }
       function deleteComment(id){
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
                alert('could not delete comment');
            }
        });
         }

        $('#back').attr('href', '<?= $_SERVER['HTTP_REFERER'] ?>');

        <?php if (isset($_GET['act'])) {
            echo 'alert("' . $_GET['act'] . '")';
        } ?>
    </script>

</body>

</html>