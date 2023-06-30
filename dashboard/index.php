<?php
session_start();
if (!$_SESSION['isAdmin']) {
    header('Location: ../index.php?error=notAuth');
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
    <link href="dashboard.css?b4" rel="stylesheet">
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
                </li></a>
            </ul>

            <ul class="navbar-nav  px-3">
                <li class="nav-item text-nowrap">
                    <a class="co" :title="user" href="../profile.php"><img :src="'../img/'+'<?= $_SESSION["profile-pic"] ?>'||'default.jpg'" class="profile-img rounded-circle " alt="profile"></a>
                </li>
            </ul>
        </nav>
        <div class="container-fluid mx-0">

            <div class="row">
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block nav-color sidebar collapse">
                    <!---------------------------------side nav--------------------------------------------------->
                    <div class="sidebar-sticky pt-3">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="#" :class="stage == 0? 'active':''" @click.prevent="changeStage(0)">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <span class="ml-2">Dashboard</span> <span class="sr-only">(current)</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" :class="stage == 1? 'active':''" @click.prevent="changeStage(1)">
                                    <i class="fas fa-users"></i>
                                    <span class="ml-2">Users</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" :class="stage == 2? 'active':''" @click.prevent="changeStage(2)">
                                    <i class="fas fa-file-alt"></i>
                                    <span class="ml-2">Posts</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" :class="stage == 3? 'active':''" @click.prevent="changeStage(3)">
                                    <i class="fas fa-eye"></i>
                                    <span class="ml-2">Visits</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" :class="stage == 4? 'active':''" @click.prevent="changeStage(4)">
                                    <i class="fas fa-chart-bar"></i>
                                    <span class="ml-2">Social Engagement</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" :class="stage == 5? 'active':''" @click.prevent="changeStage(5)">
                                    <i class="fas fa-flag"></i>
                                    <span class="ml-2">Moderation</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" :class="stage == 6? 'active':''" @click.prevent="changeStage(6)">
                                    <i class="fas fa-layer-group"></i>
                                    <span class="ml-2">Integrations</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" :class="stage == 10? 'active':''" @click.prevent="changeStage(10)" href="#">
                                    <i class="fas fa-cog"></i>
                                    <span class="ml-2">Settings</span>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav flex-column mb-2">
                            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                                <span>ADMIN: {{user}}</span>
                            </h6>
                            <li class="nav-item">
                                <a class="nav-link" :class="stage == 11? 'active':''" @click.prevent="changeStage(11)" href="#">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span class="ml-2">Logs</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" :class="stage == 12? 'active':''" @click.prevent="changeStage(12)" href="#">
                                    <i class="fas fa-info-circle"></i>
                                    <span class="ml-2">About</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>







                <!-- the main focus -->
                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 main mt-2">

                    <!--------------------------------------- stage 0 the dashboard ------------------------------------------------->
                    <div v-show="stage == 0">
                        <div class="row mt-4">
                            <div class="col-lg-4 col-md-6 mb-2">
                                <div class="card shadow border-radius-md">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total users</p>
                                                    <h5 class="font-weight-bolder mb-0">
                                                        {{users.length }}
                                                        <span class="text-success text-sm font-weight-bolder"></span>
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="col-4 text-start">
                                                <div class="icon icon-shape bg-gradient-primary text-center border-radius-md">
                                                    <i class="fas fa-users text-lg opacity-10" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 mb-2">
                                <div class="card shadow border-radius-md">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Users online</p>
                                                    <h5 class="font-weight-bolder mb-0">
                                                        {{ averageOnlineUsers || 0}}/day
                                                        <span class="text-success text-sm font-weight-bolder"> {{averageOnlineUsersPercentage||0}}%</span>
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="col-4 text-start">
                                                <div class="icon icon-shape bg-gradient-primary text-center border-radius-md">
                                                    <i class="fas fa-user-check text-lg opacity-10" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 mb-2">
                                <div class="card shadow border-radius-md">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">New users</p>
                                                    <h5 class="font-weight-bolder mb-0">
                                                        {{newUser}}
                                                        <span class="text-success text-sm font-weight-bolder">+{{ newUserPercentage || 0}}%</span>
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="col-4 text-start">
                                                <div class="icon icon-shape bg-gradient-primary text-center border-radius-md">
                                                    <i class="fas fa-user-plus text-lg opacity-10" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h4></h4>
                            <div class="btn-toolbar mb-2 mb-md-0">
                                <div class="btn-group mr-2">
                                </div>
                                <button type="button" id="w" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                                    <span data-feather="calendar"></span>
                                    This week
                                </button>
                            </div>
                        </div>
                        <canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas>
                    </div>
                    <!--------------------------------------- the user page ---------------------------------------------------------->
                    <div v-show="stage == 1">
                        <div class="container-fluid mt-2">
                            <!-- DataTales Example -->
                            <div class="card mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive co">
                                        <table class="table " id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center align-middle">id</th>
                                                    <th class="text-center align-middle">username</th>
                                                    <th class="text-center align-middle">last online</th>
                                                    <th class="text-center align-middle">data joined</th>
                                                    <th class="text-center align-middle">status</th>
                                                    <th class="text-center align-middle">action</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-center align-middle">id</th>
                                                    <th class="text-center align-middle">username</th>
                                                    <th class="text-center align-middle">last online</th>
                                                    <th class="text-center align-middle">data joined</th>
                                                    <th class="text-center align-middle">status</th>
                                                    <th class="text-center align-middle">action</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <tr v-for="user in users">
                                                    <td class="text-center align-middle">{{user.id}}</td>
                                                    <td class="text-center align-middle justify-content-around"> <span class="mx-1">{{user.username}}</span> <i v-if="user.admin" class="fa fa-user-shield c-ho"></i> <i v-if="user.bot" class="fa fa-robot c-ho"></i></td>
                                                    <td class="text-center align-middle">{{user.online}}</td>
                                                    <td class="text-center align-middle">{{user.joined}}</td>
                                                    <td class="text-center align-middle">
                                                        <span v-if="user.status == 'active'" class="badge px-3 py-1  bg-gradient-success">ACTIVE</span>
                                                        <span v-if="user.status == 'blocked'" class="badge px-3 py-1  badge-dark">BLOCKED</span>
                                                    <td class="text-center align-middle">
                                                        <a class="dropdown-toggle co" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fa fa-ellipsis-v"></i></a>
                                                        <div class="dropdown-menu cob dropdown-menu-right shadow animated--fade-in bga" aria-labelledby="dropdownMenuLink">
                                                            <a class="dropdown-item" :href="'../profile.php?id='+user.token">visit profile</a>
                                                            <a v-if="user.id != 1" class="dropdown-item" href="#" @click="toggleAdmin(user)">{{user.admin == 1 ? "revoke admin" : "make admin"}}</a>
                                                            <a v-if="user.id != 1" class="dropdown-item" href="#" @click="blockUser(user)">{{user.status == "blocked" ? "unblock user" : " block user"}}</a>
                                                            <a v-if="user.id != 1" class="dropdown-item text-danger" href="#" @click="deleteUser(user)">delete user</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!---------------------------- the post page ----------------------------------------------->
                    <div class="" v-show="stage == 2">
                        <div class="row justify-content-center p-0">
                            <div class="card col-md-7 shadow my-3">
                                <div class="card-body">
                                    <canvas id="postsChart"></canvas>
                                </div>
                            </div>
                            <div class="card mx-4 col-md-4 p-0">
                                <h4 class="text-primary text-center">posts</h4>
                                <div class="card-body mx-auto w-100">
                                    <div class="col-12 row p-2 w-100 m-auto flexcenter page-link">
                                        <div class="col-8">
                                            <h6>Monday</h6>
                                        </div>
                                        <div class="col-4 text-left">{{ postDayM }}</div>
                                    </div>
                                    <div class="col-12 row p-2 w-100 m-auto flexcenter page-link">
                                        <div class="col-8">
                                            <h6>Tuesday</h6>
                                        </div>
                                        <div class="col-4 text-left">{{postDayT}}</div>
                                    </div>
                                    <div class="col-12 row p-2 w-100 m-auto flexcenter page-link">
                                        <div class="col-8">
                                            <h6>Wednesday</h6>
                                        </div>
                                        <div class="col-4 text-left">{{postDayW}}</div>
                                    </div>
                                    <div class="col-12 row p-2 w-100 m-auto flexcenter page-link">
                                        <div class="col-8">
                                            <h6>Thursday</h6>
                                        </div>
                                        <div class="col-4 text-left">{{postDayTh}}</div>
                                    </div>
                                    <div class="col-12 row p-2 w-100 m-auto flexcenter page-link">
                                        <div class="col-8">
                                            <h6>Friday</h6>
                                        </div>
                                        <div class="col-4 text-left">{{postDayF}}</div>
                                    </div>
                                    <div class="col-12 row p-2 w-100 m-auto flexcenter page-link">
                                        <div class="col-8">
                                            <h6>weekend</h6>
                                        </div>
                                        <div class="col-4 text-left">{{postDayWeek}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!----------------------------------- the visits page ----------------------------------------->
                    <div class="" v-show="stage == 3" style="min-width: 250px">
                        <div class="row p-0">
                            <div class="card col-md-7 shadow my-3">
                                <div class="card-body">
                                    <canvas id="visitsChart" style="width: 250px"></canvas>
                                </div>
                            </div>
                            <div class="col-md-5 my-3 flex-column ">
                                <div class="col-12 my-1">
                                    <div class="card my-1 border-left shadow">
                                        <div class="card-header my-1 border-0 ">
                                            <h6 class="m-0 font-weight-bold text-primary">analysis</h6>
                                        </div>
                                        <div class="row p-2 m-1">
                                            <div class="col-7">
                                                <i class="fa fa-user fa-2x"></i>
                                                <p>new users</p>
                                            </div>
                                            <div class="col-5 text-right">
                                                <h4 class="text-muted co">{{newUser}}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 my-1">
                                    <div class="card border-left shadow">
                                        <div class="card-header border-0">
                                            <h6 class="m-0 font-weight-bold text-primary">online today</h6>
                                        </div>
                                        <div class="row p-2 m-1">
                                            <div class="col-8">
                                                <i class="fa fa-users fa-2x"></i>
                                                <p>users online</p>
                                            </div>
                                            <div class="col-4 text-right">
                                                <h4 class="text-muted co">{{userOnline}}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-------------------------------------------------- the social engagement page ----------------------------->
                    <div class="" v-show="stage == 4">
                        <div class="row mb-4">
                            <!-- first canvas  -->
                            <div class="col-md-4 mt-3 mb-4" style="height:180px;">
                                <canvas id="myPieChart"></canvas>
                                <div class="mt-4 text-center small">
                                    <span class="mr-2">
                                        <i class="fa fa-circle text-primary"></i>
                                        Likes

                                    </span>
                                    <span class="mr-2">
                                        <i class="fa fa-circle text-success"></i>
                                        Comments

                                    </span>
                                    <span class="mr-2">
                                        <i class="fa fa-circle text-info"></i>
                                        Share

                                    </span>
                                </div>
                            </div>
                            <!-- second canvas  -->
                            <div class="col-md-4 mt-3 mb-4" style="height:180px;">
                                <canvas id="myPieChart1"></canvas>
                                <div class="mt-4 text-center small">
                                    <span class="mr-2">
                                        <i class="fa fa-circle" style="color: #00e000"></i>
                                        Chat

                                    </span>
                                    <span class="mr-2">
                                        <i class="fa fa-circle" style="color: #60c0ff"></i>
                                        Post

                                    </span>
                                </div>
                            </div>
                            <!-- third canvas  -->
                            <div class="col-md-4 mt-3 mb-4" style="height:180px;">
                                <canvas id="myPieChart2"></canvas>
                                <div class="mt-4 text-center small">
                                    <span class="mr-2">
                                        <i class="fa fa-circle" style="color:#8040ff"></i>
                                        Follows

                                    </span>
                                    <span class="mr-2">
                                        <i class="fa fa-circle" style="color: #e0ff40"></i>
                                        Messages

                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- the lower part -->
                        <div class="row mt-2">
                            <div class="col-xl-8 col-lg-7 mt-2">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h4 class="m-0 font-weight-bold text-primary">this week's analysis</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-md-12 row p-1 w-100 m-auto my-1 border flexcenter" style="height: 70px;">
                                            <div class="col-4">
                                                <h6>likes</h6>
                                            </div>
                                            <div class="offset-4 col-4" style="text-align: right;">{{likes}}</div>
                                        </div>
                                        <div class="col-md-12 row p-1 w-100 m-auto my-1 border flexcenter" style="height: 70px;">
                                            <div class="col-4">
                                                <h6>posts</h6>
                                            </div>
                                            <div class="offset-4 col-4" style="text-align: right;">{{post}}</div>
                                        </div>
                                        <div class="col-md-12 row p-1 w-100 m-auto my-1 border flexcenter" style="height: 70px;">
                                            <div class="col-4">
                                                <h6>chat</h6>
                                            </div>
                                            <div class="offset-4 col-4" style="text-align: right;">{{chat}}</div>
                                        </div>
                                        <div class="col-md-12 row p-1 w-100 m-auto my-1 border flexcenter" style="height: 70px;">
                                            <div class="col-4">
                                                <h6>follows</h6>
                                            </div>
                                            <div class="offset-4 col-4" style="text-align: right;">{{follows}}</div>
                                        </div>
                                        <div class="col-md-12 row p-1 w-100 m-auto my-1 border flexcenter" style="height: 70px;">
                                            <div class="col-4">
                                                <h6>comments</h6>
                                            </div>
                                            <div class="offset-4 col-4" style="text-align: right;">{{comments}}</div>
                                        </div>
                                        <div class="col-md-12 row p-1 w-100 m-auto my-1 border flexcenter" style="height: 70px;">
                                            <div class="col-4">
                                                <h6>shares</h6>
                                            </div>
                                            <div class="offset-4 col-4" style="text-align: right;">{{share}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- line Chart -->
                            <div class="col-xl-4 col-lg-5 mt-2">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h4 class="m-0 font-weight-bold text-primary">social Rates</h4>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="totalChart" width="280" height="240" class="chartjs-render-monitor" style="display: block; width: 280px; height: 240px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!---------------------------------- the report page ---------------------------------->
                    <div class="" v-show="stage == 5">
                        <h1 class="h2 co">reports</h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group mr-2">
                                <button type="button" @click="getReports(false)" class="btn btn-sm btn-outline-secondary co">unsolved</button>
                                <button type="button" @click="getReports(true)" class="btn btn-sm btn-outline-secondary co">solved</button>
                            </div>
                        </div>
                        <div class="row co m-0">
                            <div v-for="(report, index) in reports" class="col-12 row border-bottom p-2">
                                <h4 class="col-2">{{parseInt(report.post_id)||parseInt(report.comment_id)}}</h4>
                                <div class="col-3 text-muted">
                                    <a class="text-muted" :href="parseInt(report.is_comment) ? '../comment?id=' + report.slug + '&comment=' + report.comment_id + '#comment-'+ report.comment_id : '../post?id=' + report.slug">
                                        <i class="fas fa-eye fa-2x"></i>
                                    </a>
                                </div>
                                <span class="col-4">type: {{parseInt(report.is_comment) ? 'comment' : 'post'}}</span>
                                <div class="col-3 text-right">
                                    <button class="btn btn-danger" @click="sendReport(index)">
                                        <i class="fa fa-trash text-light fa-2x"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!--------------------------------- the integration page  ------------------------------->
                    <div class="" v-show="stage == 6">
                        <h6 class="alert text">integrations to come soon</h6>
                    </div>

                    <!-------------------------------- the settings section ----------------->
                    <div class="settings co" v-show="stage == 10">
                        <h1 class="mt-2">Settings</h1>

                        <div class="success-banner" style="display: none;">
                            <div class="alert alert-success" role="alert">
                                Success! The form has been submitted.
                            </div>
                        </div>
                        <ul class="nav nav-tabs mb-2">
                            <li class="nav-item">
                                <a class="nav-link c-ho" @click.prevent="settings = 1" :class="settings == 1 ? 'active':''" href="#">general</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link c-ho" @click.prevent="settings = 3" :class="settings == 3 ? 'active':''" href="#">application</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link c-ho" @click.prevent="settings = 2" :class="settings == 2 ? 'active':''" href="#">API</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link c-ho" @click.prevent="settings = 4" :class="settings == 4 ? 'active':''" href="#">config</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link c-ho" @click.prevent="settings = 5" :class="settings == 5 ? 'active': ''" href="#">updates</a>
                            </li>
                        </ul>
                        <!-- dark mode toggle -->
                        <div v-if="settings == 5" class="container">
                            <form>
                                <div class="form-group">
                                </div>
                                <button type="button" class="btn btn-primary bg" @click="checkLatestRelease(<?= $setupData->version ?>)"> Check Latest Release
                                    <i class="fa fa-spinner fa-spin" id="updates-spinner" style="display: none"></i></button>

                                <div id="latestReleaseInfo" class="mt-4" style="display: none;">
                                    <h2>Latest Version Information</h2>
                                    <p id="versionText"></p>
                                    <p>Installed Version: <?= $setupData->version ?> </p>

                                    <div class="update-field">

                                    </div>
                                </div>
                            </form>
                        </div>
                        <div v-if="settings == 4" class="container mb-4">
                            <form @submit.prevent="saveConfig">
                                <div class="form-group">
                                    <label for="dbDatabase">Database Name</label>
                                    <input type="text" class="form-control" id="dbDatabase" v-model="config.dbDatabase">
                                </div>
                                <div class="form-group">
                                    <label for="dbHost">Database Host</label>
                                    <input type="text" class="form-control" id="dbHost" v-model="config.dbHost">
                                </div>
                                <div class="form-group">
                                    <label for="dbUsername">Database Username</label>
                                    <input type="text" class="form-control" id="dbUsername" v-model="config.dbUsername">
                                </div>
                                <div class="form-group">
                                    <label for="dbPassword">Database Password</label>
                                    <input type="password" class="form-control" id="dbPassword" v-model="config.dbPassword">
                                </div>
                                <div class="form-group">
                                    <label for="dbPort">Database Port</label>
                                    <input type="number" class="form-control" id="dbPort" v-model="config.dbPort">
                                </div>
                                <button type="submit" class="btn btn-primary bg">Save Config</button>
                            </form>
                        </div>

                        <div v-if="settings === 3" class="container">
                            <form @submit.prevent="saveConfig">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="emailVerification" v-model="config.emailVerification">
                                    <label class="form-check-label" for="emailVerification">Enable/Disable Email Verification</label>
                                </div>
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="userSignup" v-model="config.userSignup">
                                    <label class="form-check-label" for="userSignup">Enable/Disable User Signup</label>
                                </div>
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="userPost" v-model="config.userPost">
                                    <label class="form-check-label" for="userPost">Enable/Disable User Post</label>
                                </div>
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="userComments" v-model="config.userComments">
                                    <label class="form-check-label" for="userComments">Enable/Disable User Comments</label>
                                </div>
                                <div class="form-group">
                                    <label for="appEmail">App Email <span class="small text-muted">(the email used to send verifications)</span></label>
                                    <input type="email" class="form-control" id="appEmail" v-model="config.appEmail" placeholder="eg. test@<?= $_SERVER['HTTP_HOST'] ?>">
                                </div>
                                <button type="submit" class="btn btn-primary bg">Save Settings</button>
                            </form>
                        </div>

                        <div v-if="settings === 2" class="container">
                            <form @submit.prevent="saveConfig">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="apiAccess" v-model="config.apiAccess">
                                    <label class="form-check-label" for="apiAccess">Enable API Access</label>
                                </div>
                                <button type="submit" class="btn btn-primary bg">Save Settings</button>
                            </form>
                        </div>

                        <div v-if="settings === 1" class="container">
                            <form @submit.prevent="saveConfig">
                                <div class="form-group">
                                    <label for="appName">Application Name</label>
                                    <input type="text" id="appName" class="form-control" v-model="config.appName">
                                </div>
                                <div class="form-group">
                                    <label for="fileSizeLimit">File Size Limit (<span id="mbCalc">{{ (config.fileSizeLimit/(1024*1024)).toFixed(3) }}</span> MB)</label>
                                    <input type="number" id="fileSizeLimit" class="form-control" v-model="config.fileSizeLimit">
                                </div>
                                <div class="form-group">
                                    <label for="defaultTheme">Default Theme</label>
                                    <select class="form-control" id="defaultTheme" v-model="config.defaultTheme">
                                        <option value="light">Light</option>
                                        <option value="dark">Dark</option>
                                        <option value="install" disabled>Install New Themes</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="accentColor">Accent Color</label>
                                    <input type="color" id="accentColor" class="border-0 mx-3" v-model="config.accentColor">
                                    <button class="btn" @click="config.accentColor = ''">reset</button>
                                </div>
                                <button type="submit" class="btn btn-primary bg">Save Settings</button>
                            </form>
                        </div>
                    </div>
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
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 offset-md-3">
                                    <h1 class="text-center">About</h1>
                                    <form>
                                        <div class="form-group">
                                            <label for="app-name">System App Name</label>
                                            <input type="text" class="form-control" id="app-name" value="<?= $setupData->name ?>" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="app-version">Version</label>
                                            <input type="text" class="form-control" id="app-version" value="<?= $setupData->version ?>" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="app-date">Setup Date</label>
                                            <input type="text" class="form-control" id="app-date" value="<?= $date ?>" disabled>
                                        </div>
                                    </form>
                                    <div class="row ml-1">
                                        <a href="https://github.com/bethropolis/suplike-social-website" target="_blank" class="mx-2" style="color:#8d55e8;">
                                            <i class="fab fa-github fa-2x"></i></a>
                                        <a href="https://twitter.com/bethropolis" target="_blank" class="mx-2" style="color:#8d55e8;">
                                            <i class="fab fa-twitter fa-2x"></i></a>

                                    </div>
                                    <div class="text-center">
                                        <a href="../LICENSE">
                                            <p class="text-muted">License MIT</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

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