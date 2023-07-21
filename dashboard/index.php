<?php
session_start();
if (!isset($_SESSION['token']) || !$_SESSION['isAdmin']) {
    // not authorised http status code
    header('HTTP/1.1 401 Unauthorized');
    exit();
}

require_once '../inc/extra/date.func.php';
require_once '../inc/setup/env.php';
$setupData = json_decode(file_get_contents("../inc/setup/setup.suplike.json"));

$date = $setupData->setupDate ? format_date($setupData->setupDate) : '';

?>
<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, Bootstrap contributors And Bethuel Kipsang">
    <meta name="generator" content="Jekyll v4.1.1">
    <title>Admin Dashboard</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/dashboard/">
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link rel="stylesheet" href="../lib/font-awesome/css/all.css">
    <!-- Bootstrap core CSS -->
    <link href="../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../lib/jquery/jquery.dataTables.min.css">
    <!-- Custom styles for this template -->
    <link href="dashboard.css?v1.5" rel="stylesheet">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <script src="../lib/jquery/jquery.js"></script>
    <script src="../lib/chartjs/chart.js"></script>
    <script src="../lib/jquery/jquery.dataTables.min.js"></script>
    <script src="../lib/vue/vue.min.js"></script>
    <script>
        // load css if localstorage  theme = dark
        function darkMode() {
            let css = `:root{--bg:#1a1a1a;--co:#f8f9fc;--ho:#a080ff;--top:#a080ff;--card:#333;--card-top:#444;--icon:#eee;--ac:#bcabf7;--nav:#333;--lighter:#6c5ce7;--box-shadow:0 0 5px var(--light)}`;
            let style = document.createElement('style');
            style.type = 'text/css';
            style.setAttribute("data-name", "theme");
            style.appendChild(document.createTextNode(css));
            document.head.appendChild(style);
        }
        let theme = localStorage.getItem('theme') || null;
        if (theme === 'dark') {
            darkMode()
        }
    </script>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-dark sticky-top nav-color flex-md-nowrap p-0 top-nav shadow">
            <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3 bg" href="#">
                Suplike<sup class="beta">BETA</sup>
            </a>
            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <input class="form-control form-control-dark" type="text" placeholder="Search..." aria-label="Search">
            <ul class="navbar-nav px-3">
                <li class="nav-item text-nowrap">
                    <input class="form-check-input" type="checkbox" id="darkModeToggle" v-model="darkMode" hidden>
                    <label class="form-check-label" for="darkModeToggle"><i v-if="darkMode" class="fa fa-sun"></i><i v-if="!darkMode" class="fa fa-moon"></i> </label>
                </li>
            </ul>

            <ul class="navbar-nav px-3">
                <a class="co" title="home" href="../">
                    <li class="nav-item text-nowrap"><i class="fa fa-home"></i>
                    </li>
                </a>
            </ul>

            <ul class="navbar-nav  px-3">
                <li class="nav-item text-nowrap">
                    <a class="co" :title="user" href="../profile.php"><img :src="'../img/'+'<?= $_SESSION["profile-pic"] ?>'||'default.jpg'" class="profile-img rounded-circle " width="21px" height="21px" alt="profile"></a>
                </li>
            </ul>
        </nav>
        <div class="container-fluid mx-0">

            <div class="row">
                <?php include_once("./components/nav.php"); ?>

                <!-- the main focus -->
                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 main mt-2">

                    <!--------------------------------------- stage 0 the dashboard ------------------------------------------------->
                    <div v-show="stage == 0">
                        <?php include_once("./components/dashboard.php"); ?>
                    </div>

                    <!--------------------------------------- the users page ---------------------------------------------------------->
                    <div v-show="stage == 1">
                        <?php include_once("./components/users.php"); ?>
                    </div>

                    <!---------------------------- the post page ----------------------------------------------->
                    <div class="" v-show="stage == 2">
                        <?php include_once("./components/posts.php"); ?>
                    </div>


                    <!----------------------------------- the visits page ----------------------------------------->
                    <div v-show="stage == 3" style="min-width: 250px">
                        <?php include_once("./components/visits.php"); ?>
                    </div>


                    <!-------------------------------------------------- the social engagement page ----------------------------->
                    <div class="" v-show="stage == 4">
                        <?php include_once("./components/social.php"); ?>
                    </div>


                    <!---------------------------------- the report page ---------------------------------->
                    <div class="" v-show="stage == 5">
                        <?php include_once("./components/moderation.php"); ?>
                    </div>


                    <!--------------------------------- the integration page  ------------------------------->
                    <div class="" v-show="stage == 6">
                        <?php include_once("./components/integrations.php"); ?>
                    </div>

                    <!-------------------------------- the settings section ----------------->
                    <div class="settings co" v-show="stage == 10">
                        <?php include_once("./components/settings.php"); ?>
                    </div>

                    <?php include_once("./components/extended.php"); ?>
                    <!-------------------------- Logs --------------------------------------------->
                    <div class="about co" v-show="stage == 11">
                        <h1 class="mt-2">Error Logs</h1>
                        <textarea name="hi" id="log-textarea" class="col-12 mx-auto wra form-control bg-dark text-light" style="height: 60vh; font-family: monospace; white-space: pre; overflow-x: scroll;" wrap="off"><?php require "./../inc/errors/error.log.txt"; ?></textarea>
                        <div class="row m-2">
                            <button class="btn btn-danger mx-2 p-1 px-2" @click="clearLog"><i class="fas fa-trash-alt"></i> Clear</button>
                            <button class="btn btn-primary bg mx-2 p-1 px-2" @click="saveLog"><i class="fas fa-file-download"></i> Save</button>
                        </div>
                    </div>


                    <!--------------------------- the about section -------------------------------- -->
                    <div class="about co" v-show="stage == 12">
                        <?php include_once("./components/about.php"); ?>
                    </div>
                </main>
            </div>
        </div>
    </div>
    <script>
        const envConfig = {
            dbDatabase: '<?= DB_DATABASE ?>',
            dbHost: '<?= DB_HOST ?>',
            dbUsername: '<?= DB_USERNAME ?>',
            dbPassword: '',
            dbPort: <?= DB_PORT ?>,
            emailVerification: <?= EMAIL_VERIFICATION ? 'true' : 'false' ?>,
            appEmail: '<?= APP_EMAIL ?>',
            appName: '<?= defined('APP_NAME') ? APP_NAME : '' ?>',
            fileSizeLimit: <?= defined('FILE_SIZE_LIMIT') ? FILE_SIZE_LIMIT : 0 ?>,
            apiAccess: <?= defined('API_ACCESS') ? API_ACCESS ? 'true' : 'false' : 'false' ?>,
            defaultTheme: '<?= defined('DEFAULT_THEME') ? DEFAULT_THEME : 'light' ?>',
            accentColor: '<?= defined('ACCENT_COLOR') ? ACCENT_COLOR : '' ?>',
            userSignup: <?= defined('USER_SIGNUP') ? USER_SIGNUP ? 'true' : 'false' : 'false' ?>,
            userPost: <?= defined('USER_POST') ? USER_POST ? 'true' : 'false' : 'false' ?>,
            userComments: <?= defined('USER_COMMENTS') ? USER_COMMENTS ? 'true' : 'false' : 'false' ?>
        };
    </script>
    <script src="dashboard.js?opf"></script>
    <script src="../lib/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>