<?php
require '../../inc/dbh.inc.php';
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../../index.php?error=notloggedin");
    exit();
}
$id = $_SESSION['userId'];
$sql = "SELECT `key` FROM `api` WHERE `user` = $id";
if (mysqli_num_rows(mysqli_query($conn, $sql)) > 0) {
    $row = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    $token = $row['key'];
} else {
    $token = 'generate new api key';
}

// Fake data for bots
$bots = [
    [
        "name" => "Suplike Bot",
        "username" => "@suplikebot",
        "icon" => "../../img/aliu.svg",
        "description" => "A bot that likes your posts on Suplike"
    ],
    [
        "name" => "Supchat Bot",
        "username" => "@supchatbot",
        "icon" => "../../img/admin.svg",
        "description" => "A bot that chats with you on Suplike"
    ]
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Developer Dashboard</title>
    <link rel="stylesheet" href="../../lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../lib/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="row m-0">
        <div class="sidebar col-sm-2 nav-hide sidebar-sticky pt-3">
            <h2>Dashboard</h2>
            <ul class="lead">
                <li>
                    <a href="./" class="<?= empty($_GET) ? 'active' : '' ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                </li>
                <li>
                    <a href="?api" class="<?= isset($_GET['api']) ? 'active' : '' ?>"><i class="fas fa-key"></i> API</a>
                </li>
                <li>
                    <a href="?bots" class="<?= isset($_GET['bots']) || isset($_GET['new'])  ? 'active' : '' ?>"><i class="fas fa-robot"></i> Bots</a>
                </li>
            </ul>
        </div>
        <div class="col-sm-10 p-0">
            <nav class="navbar navbar-expand-lg">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item mx-1">
                            <a class="nav-link text-muted" href="../../">Home</a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link text-muted" href="#">Docs</a>
                        </li>
                        <li class="nav-item mx-1">
                            <a href="../../profile.php">
                                <img src="../../img/<?= $_SESSION['profile-pic'] ?? 'M.jpg' ?>" alt="" width="35px" height="35px" class="shadow rounded-circle">
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>



            <div class="main w-75 mx-auto shadow-sm">
                <?php if (isset($_GET['api'])) { ?>
                    <!-- API Token Section -->
                    <div class="token-section">
                        <h4>API Token</h4>
                        <input type="text" value="<?= $token ?>" class="token-field" readonly>
                        <button type="button" class="generate-btn btn btn-primary"><i class="fa fa-refresh"></i> Generate New Token</button>
                    </div>
                <?php } elseif (isset($_GET['bots'])) { ?>
                    <!-- Bots Section -->
                    <div class="bots-section">
                        <h4>Bots</h4>
                        <ul class="bot-list">
                            <?php foreach ($bots as $bot) { ?>
                                <li class="bot-item border-bottom ">
                                    <img src="<?= $bot['icon'] ?>" alt="Bot Icon">
                                    <div class="bot-info">
                                        <h5><?= $bot['name'] ?></h5>
                                        <p><?= $bot['username'] ?></p>
                                        <p><?= $bot['description'] ?></p>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                        <a href="?new" class="create-bot-btn btn btn-primary"><i class="fa fa-plus"></i> Add Bot</a>
                    </div>
                <?php } elseif (isset($_GET['new'])) { ?>
                    <!-- Create Bot Section -->
                    <div class="create-bot-section">
                        <h4>Create Bot</h4>
                        <form action="#" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        @
                                    </span>
                                </div>
                                <input type="text" id="username" name="username" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="icon">Icon</label>
                                <input type="file" id="icon" name="icon" class="form-control-file" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" class="form-control" required></textarea>
                            </div>
                            <button type="submit" class="create-bot-submit btn btn-primary"><i class="fa fa-check"></i> Create</button>
                        </form>
                    </div>
                <?php } else { ?>
                    <!-- Default Dashboard Content -->
                    <h1>Welcome to the Developer Dashboard!</h1>
                    <p>Choose an option from the sidebar.</p>
                <?php } ?>
            </div>
        </div>

        <script src="../../lib/jquery/jquery.js"></script>
        <script src="../../lib/bootstrap/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.generate-btn').click(function() {
                    $.ajax({
                        url: '../../inc/Auth/a.php',
                        type: 'GET',
                        success: function(data) {
                            if (data.code == 1) {
                                $('.token-field').val(data.token);
                            } else {
                                alert(data.msg);
                            }
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                });
            });
        </script>
</body>

</html>