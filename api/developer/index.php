<?php
require '../../inc/dbh.inc.php';
require "../v1/bot/bot.php";

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
    $token = '';
}




$bots = $bot->getUserBots($id);


$title = "Developer dashboard";

if (isset($_GET['bots']) || isset($_GET['new'])) {
    $bots = $bot->getUserBots($id);
    $title = "Bots";
}
if (isset($_GET['view'])) {
    $bots = $bot->getUserBots($id, $_GET['view'])[0];
    $img = $bots["icon"] ? $bots["icon"]  : 'M.jpg';
    $bot_token = $bots["bot_token"];
    $title = "<a href='../../profile.php?id=$bot_token' style='text-decoration: none;'>
    <img src='../../img/$img' alt=' width='35px' height='35px' class='shadow rounded-circle'>
    <b class='co'>$bots[username]</b>
</a>";
}

if (isset($_GET['api'])) {
    $title = "Api";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Developer Dashboard</title>
    <link rel="shortcut icon" href="../../img/icon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../lib/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script>
        if (localStorage.getItem('theme') == 'dark') {
            let css = `:root{--bg:#1a1a1a !important;--co:#f8f9fc!important;--ho:#a080ff;--ac:rgba(50, 159, 192, 0.844)!important;--inp:rgb(41, 38, 38)!important;--light:#f8f9fa!important;--dark:#333!important;--msg-message:#969eaa!important;--chat-text-bg:#ededf8!important;--chat-text-owner:var(--ho)!important;--theme-color:#00ffff!important;--msg-date:#c0c7d2!important;--theme-1:#1a1a1a!important;--theme-2:#212121!important;--theme-3:#333333!important;--theme-4:#444444!important;--theme-5:#555555!important;--theme-6:#666666!important;--theme-7:#777777!important;--theme-8:#888888!important;--theme-9:#999999!important}
                .co{color: var(--co) !important}.st-1{background-color:var(--theme-1)!important;color:var(--co)}.st-2{background-color:var(--theme-2)!important;color:var(--co)}.st-3{background-color:var(--theme-3)!important;color:var(--co)}.st-4{background-color:var(--theme-4)!important;color:var(--co)}.st-5{background-color:var(--theme-5)!important;color:var(--co)}.st-6{background-color:var(--theme-6)!important;color:var(--co)}.st-7{background-color:var(--theme-7)!important;color:var(--co)}.st-8{background-color:var(--theme-8)!important;color:var(--co)}.st-9{background-color:var(--theme-9)!important;color:var(--co)}`
            let style = document.createElement('style');
            style.type = 'text/css';
            style.appendChild(document.createTextNode(css));
            document.head.appendChild(style);
        }
    </script>
</head>

<body class="st-4">

    <div class="row m-0">
        <div class="sidebar col-sm-2  sidebar stick">
            <div class="sidebar-sticky">
                <a href="#">
                    <h2>Suplike dev</h2>
                </a>
                <hr>
                <ul class="lead" id="nav-list">
                    <li class="">
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
        </div>
        <div class="col-sm-10 p-0 ">

            <nav class="navbar navbar-expand-lg stick st-4">
                <h4><?= $title ?></h4>
                <button class="navbar-toggler co" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <i class="fa fa-bars"></i>
                    </span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item mx-1">
                            <a class="nav-link text-muted co" href="../../">Home</a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link text-muted co" href="#">Docs</a>
                        </li>
                        <li class="nav-item mx-1">
                            <a href="../../profile.php">
                                <img src="../../img/<?= $_SESSION['profile-pic'] ?? 'M.jpg' ?>" alt="" width="35px" height="35px" class="shadow rounded-circle">
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>



            <div class="main col-md-10 mx-auto my-3 shadow-sm st-5">
                <?php if (isset($_GET['api'])) { ?>
                    <!-- API Token Section -->
                    <div class="token-section">
                        <h4>API Token</h4>
                        <input type="text" value="<?= $token ?>" class="token-field" readonly>
                        <button type="button" class="generate-btn btn mt-2 p-2 btn-primary"><i class="fa fa-refresh"></i> Generate New Token</button>
                        <button class="copy-token-btn btn mt-2 p-2 btn-info"><i class="fa fa-clipboard"></i> Copy</button>
                        <button class="delete-btn btn mt-2 btn-danger p-2"><i class="fa fa-trash"></i> Delete</button>
                    </div>

                    <article class="mt-4">
                        <h4>API</h4>
                        <p>Use the API token above to authenticate your requests to the API. The API endpoint is located at <code>{INSTANCE_URL}/api/v1</code>.</p>
                        <p>With the API, you can perform various actions such as retrieving data, sending messages, and more.</p>
                        <p>For detailed information about the available endpoints, request parameters, and response formats, please refer to the <a href="https://your-api-docs-url" target="_blank">API documentation</a>.</p>
                    </article>
                <?php } elseif (isset($_GET['bots'])) { ?>
                    <!-- Bots Section -->
                    <div class="bots-section">
                        <h3>Your Bots</h3>
                        <ul class="bot-list">
                            <?php foreach ($bots as $bot) {
                                $button_text = $bot['status'] == "active" ? "disable" : "enable";
                                $button_icon = $bot['status'] == "active" ? "fa fa-ban" : "fa fa-check";
                            ?>
                                <li class="bot-item border-bottom row">
                                    <div class="col-md-8">
                                        <a href="../../profile.php?id=<?= $bot['bot_token'] ?>"><img src="../../img/<?= $bot['icon'] ?>" alt="Bot Icon"></a>
                                        <div class="bot-info">
                                            <h5><?= $bot['name'] ?></h5>
                                            <div class="row justify-content-between  mt-1 mx-2">
                                                <span>@<?= $bot['username'] ?></span> <span><?= $bot['status'] ?></span>
                                            </div>
                                            <p><?= $bot['description'] ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-1">
                                        <a id="<?= $bot['bot_id'] ?>" data-status="<?= $bot['status'] ?>" class="disable-btn" href="#"><button class="delete-bot btn"><i class="fa <?= $button_icon ?>"></i> <?= $button_text ?></button></a>
                                        <a href="?view=<?= $bot['bot_id'] ?>"><button class="edit-bot btn bg"><i class="fa fa-eye"></i> view</button></a>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                        <a href="?new" class="create-bot-btn btn btn-primary"><i class="fa fa-plus"></i> Add Bot</a>
                    </div>
                <?php } elseif (isset($_GET['new'])) {

                ?>
                    <!-- Create Bot Section -->
                    <div class="create-bot-section" class="mb-5">
                        <h4>Create Bot</h4>
                        <?php
                        if ($token == "") {
                        ?>
                            <div class="alert alert-warning">
                                <h5>you need to generate an api key to create a bot</h5>
                            </div>
                        <?php } ?>
                        <div class="success-banner" style="display: none;">
                            <div class="alert alert-success" role="alert">
                                Success! The form has been submitted.
                            </div>
                        </div>


                        <form action="#" method="POST" enctype="multipart/form-data" class="mb-5" id="bot-form">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="username">Botname</label>
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
                                <label for="username">webhook endpoint</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-globe"></i>
                                        </span>
                                    </div>
                                    <input type="url" id="webhook" name="webhook" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name">Password</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="icon">Icon</label>
                                <input type="file" id="icon" name="icon" class="form-control-file">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="bio" name="bio" class="form-control" maxlength="200" required></textarea>
                            </div>
                            <button type="submit" class="create-bot-submit btn btn-primary"><i class="fa fa-check"></i> Create</button>
                        </form>
                    </div>
                <?php } elseif (isset($_GET['view'])) {

                    $button_text = $bots['status'] == "active" ? "disable" : "enable";
                    $button_icon = $bots['status'] == "active" ? "fa fa-ban" : "fa fa-check";
                ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-9 mx-auto">
                                <form>
                                    <div class="form-group">
                                        <label for="app-name">Bot Name</label>
                                        <input type="text" class="form-control" id="app-name" value="<?= $bots['name'] ?>" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="app-version">access token <sup class="text-danger">*</sup> </label>
                                        <div class="input-group">
                                            <input type="url" id="webhook" name="webhook" class="form-control" value="<?= $bots['auth_token'] ?>" disabled data-toggle="tooltip" data-placement="right" title="this is required to access your account through the API">
                                            <div class="input-group-prepend">
                                                <button class="input-group-text" id="copy-btn" data-toggle="tooltip" data-placement="bottom" title="Copy to clipboard">
                                                    <i class="fa fa-clipboard text-muted fa-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="app-version">chat token </label>
                                        <div class="input-group">
                                            <input type="url" id="chat-token" name="chat-token" class="form-control" value="<?= $bots['chat_token'] ?>" disabled data-toggle="tooltip" data-placement="right" title="this is required to chat through the API">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="app-version">webhook url</label>
                                        <div class="input-group">
                                            <input type="url" id="webhook-url" class="form-control" value="<?= $bots['webhook'] ?>" disabled data-toggle="tooltip" data-placement="right" title="this is the endpint url">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="app-date">Description</label>
                                        <textarea type="text" class="form-control" id="app-date" disabled><?= $bots['description'] ?></textarea>
                                    </div>
                                </form>
                                <div class="p-2 my-2">
                                    <button class="btn btn-outline-primary" id="add-to-sidebar" data-name="<?= $bots['username'] ?>" data-id="<?= $bots['bot_id'] ?>">add to sidebar</button>
                                    <a id="<?= $bots['bot_id'] ?>" data-status="<?= $bots['status'] ?>" class="disable-btn" href="#"><button class="delete-bot btn btn-outline-info "><i class="fa <?= $button_icon ?>"></i> <?= $button_text ?></button></a>
                                    <button class="btn  btn-outline-danger" id="delete-bot">delete bot</button>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        <?php } else { ?>
            <!-- Default Dashboard Content -->
            <div class="text-center">
                <h1>Welcome to the Developer Dashboard!</h1>
                <p>Choose an option from the sidebar.
                </p>
            </div>
        <?php } ?>
        </div>
    </div>

    <script src="../../lib/jquery/jquery.js"></script>
    <script src="../../lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            let token = "<?= $token ?>";
            let sidebarOptions = JSON.parse(localStorage.getItem("sidebarOptions")) || [];



            for (let option of sidebarOptions) {
                $("#nav-list").append(`
                <li class="small">
                    <a class="nav-link" href="?view=${option.id}">
                     <i class="fa fa-angle-right fa-lg"></i> ${option.name}
                    </a>
                </li>
                `);
            }

            $("#add-to-sidebar").click(function() {
                let newObject = {
                    name: $("#add-to-sidebar").attr("data-name"),
                    id: $("#add-to-sidebar").attr("data-id"),
                };

                let sidebarOptions = JSON.parse(localStorage.getItem("sidebarOptions")) || [];

                if (!sidebarOptions.some((option) => option.id === newObject.id)) {
                    sidebarOptions.push(newObject);
                    localStorage.setItem("sidebarOptions", JSON.stringify(sidebarOptions));
                    location.reload();
                }
            });



            $('.generate-btn').click(function() {
                $.ajax({
                    url: '../../inc/Auth/a',
                    type: 'POST',
                    data: {
                        generate: true
                    }, // Pass generate parameter as true
                    dataType: 'json',
                    success: function(data) {
                        if (data.code === 1) {
                            $('.token-field').val(data.token);
                        } else {
                            alert(data.msg);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
            $('.delete-btn').click(function() {
                $.ajax({
                    url: '../../inc/Auth/a',
                    type: 'POST',
                    data: {
                        delete: true
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.code === 1) {
                            $('.token-field').val('');
                            alert(data.msg);
                        } else {
                            alert(data.msg);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('.copy-token-btn').click(function() {
                var tokenField = $('.token-field');
                tokenField.select();
                document.execCommand('copy');
                tokenField.blur();
                $('.copy-token-btn').text('Copied!');
                setTimeout(function() {
                    $('.copy-token-btn').text('Copy');
                }, 2000);
            });



            $(".disable-btn").click(function() {
                // set your auth token
                const authToken = `Bearer ${token}`;
                const status = ($(this).attr('data-status') == "blocked");

                $.ajax({
                    url: '../v1/bot/',
                    headers: {
                        'Authorization': authToken
                    },
                    type: 'POST',
                    data: {
                        block: $(this).attr('id'),
                        set: status
                    },
                    success: () => {
                        // reload the page
                        location.reload();
                    },
                    error: function() {
                        alert("could not disable")
                    }
                });
            })


            $('form').submit(function(e) {
                e.preventDefault();

                // Get form data
                let formData = new FormData(this);

                $.ajax({
                    url: '../v1/bot/',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('Authorization', 'Bearer ' + token); // Set the authorization header
                    },
                    success: function(response) {
                        console.log(response);

                        // Clear the form
                        $('form')[0].reset();

                        // Display success banner
                        $('.success-banner').fadeIn().delay(2000).fadeOut();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });


            $(function() {
                $('[data-toggle="tooltip"]').tooltip()
            })


            $("#copy-btn").click(function() {
                let textToCopy = $("#webhook").val();
                let textarea = $("<textarea></textarea>");
                textarea.val(textToCopy);
                $("body").append(textarea);
                textarea.select();
                document.execCommand("copy");
                textarea.remove();
                $(this).attr("data-original-title", "Copied!").tooltip("show");
            });

            $("#copy-btn").on("hidden.bs.tooltip", function() {
                $(this).attr("data-original-title", "Copy to clipboard").tooltip("dispose");
                $(this).tooltip({
                    title: "Copy to clipboard",
                    trigger: "hover"
                });
            });


        });
    </script>
</body>

</html>