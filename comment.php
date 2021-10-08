<?php
include './inc/dbh.inc.php';
session_start();


if (!isset($_GET['id'])) {
    echo "<h1>comment could not be found. go <a href='./'>back</a></h1>";
    die();
}
$post_id = $_GET['id'];

$sql = "SELECT * FROM `comments` WHERE `comments`.`post_id` ='$post_id'";

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
    <link rel="stylesheet" href="css/comment.css">

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
            <button type="submit" class="sbtn" id="submit">submit</button>
        </div>
        <main>

            <?php
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                if ($row) {
                    echo       '<div class="comment">' .
                        '<a href="./profile.php?id=' . $row["user_token"] . '">' .
                        '<h5>' . $row['user'] . '</h5>' .
                        '</a>' .
                        '<p>' . $row['comment'] . '</p> ' .
                        '<div class="comment-action">' .
                        '<ul>';
                    if ($row["user"] ==  $_SESSION['userUid']) {
                        echo  ' <li><a href="./inc/report.inc.php?del='.$row['id'].'">delete</a></li>';
                    }
                    echo  '<li><a href="./inc/report.inc.php?comment='. $row['id'].'">report</a></li>' . 
                        '</ul>' .
                        ' </div>' .
                        '</div>';
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
                url: './inc/comment.inc.php',
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
            alert('sent');
        }

        function postError(jqXHR, textStatus, errorThrown) {
            alert('could not post comment');
            console.log(jqXHR);
        }

        $('#back').attr('href', document.referrer);
        <?php if(isset($_GET['act'])) {echo 'alert("'.$_GET['act'].'")';} ?>    
    </script>

</body>

</html>