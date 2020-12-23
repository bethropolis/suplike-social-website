<?php 
   session_start();   
   if (!$_SESSION['isAdmin']){
    header('Location: ../index.php?error=notAuth'); 
    exit();   
  }

?>
<!doctype html>
<!----
the dashboard is from the bootstrap demo doc 
url: https://getbootstrap.com/docs/4.5/examples/dashboard/
----->
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
        <link rel="stylesheet" href="../lib/font-awesome/font-awesome.min.css">
        <!-- Bootstrap core CSS -->
        <link href="../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="http://localhost/lib/datatables/dataTables.bootstrap4.min.css">
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
        <script src="http://localhost/lib/chart.js/Chart.min.js"></script>
        <script src="http://localhost/lib/datatables/jquery.dataTables.js"></script>
        <script src="http://localhost/lib/datatables/dataTables.bootstrap4.js "></script>
        <script src="http://localhost/lib/vue/vue.js"></script>
    </head>
    <body onload="app.load()">
        <div id="app">
            <nav class="navbar navbar-dark sticky-top nav-color flex-md-nowrap p-0 shadow">
                <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3 bg" href="#">
                    Suplike<sup class="beta">BETA</sup>
                </a>
                <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <input class="form-control form-control-dark w-100" type="text" placeholder="Search..." aria-label="Search">
                <ul class="navbar-nav px-3">
                    <li class="nav-item text-nowrap">
                        <a class="co" :title="user" href="#">Sign out</a>
                    </li>
                </ul>
            </nav>
            <div class="container-fluid">
                <div class="row">
                    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block nav-color sidebar collapse">
                        <!---------------------------------side nav--------------------------------------------------->
                        <div class="sidebar-sticky pt-3">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="#" :class="stage == 0? 'active':''" @click.prevent="stage = 0">
                                        <span data-feather="home"></span>
                                        Dashboard <span class="sr-only">(current)</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#" :class="stage == 1? 'active':''" @click.prevent="stage = 1">
                                        <span data-feather="file"></span>
                                        users
                                
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#" :class="stage == 2? 'active':''" @click.prevent="stage = 2">
                                        <span data-feather="shopping-cart"></span>
                                        posts
                                
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#" :class="stage == 3? 'active':''" @click.prevent="stage = 3">
                                        <span data-feather="users"></span>
                                        visits
                                
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#" :class="stage == 4? 'active':''" @click.prevent="stage = 4">
                                        <span data-feather="file-text"></span>
                                        social engagement
                                
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#" :class="stage == 5? 'active':''" @click.prevent="stage = 5">
                                        <span data-feather="bar-chart-2"></span>
                                        Reports
                                
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#" :class="stage == 6? 'active':''" @click.prevent="stage = 6">
                                        <span data-feather="layers"></span>
                                        Integrations
                                
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav flex-column mb-2">
                                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                                    <span>{{user}}</span>
                                </h6>
                                <li class="nav-item">
                                    <a class="nav-link" :class="stage == 10? 'active':''" @click.prevent="stage = 10" href="#">
                                        <span data-feather="file-text"></span>
                                        settings
                                
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    <!-- the main focus -->
                    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 main">
                        <!--------------------------------------- stage 0 the dashboard ------------------------------------------------->
                        <div v-show="stage == 0">
                            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                                <h1 class="h2">Dashboard</h1>
                                <div class="btn-toolbar mb-2 mb-md-0">
                                    <div class="btn-group mr-2">
                                        <button type="button" class="btn btn-sm btn-icon-split btn-outline-secondary"> 
                                            <span class="icon text-white-50">
                                                  <i class="fa fa-download"></i>    
                                            </span>
                                            <span class="text">Export</span>
                                        </button>
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
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>id</th>
                                                        <th>username</th>
                                                        <th>name</th>
                                                        <th>last online</th>
                                                        <th>data joined</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>id</th>
                                                        <th>username</th>
                                                        <th>name</th>
                                                        <th>last online</th>
                                                        <th>data joined</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    <tr v-for="user in users">
                                                        <td>{{user.id}}</td>
                                                        <td>{{user.username}}</td>
                                                        <td>{{user.name}}</td>
                                                        <td>{{user.status}}</td>
                                                        <td>{{user.joined}}</td>
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
                            <div class="row my-2">
                                <div class="card col-lg-7 shadow my-3">
                                    <div class="card-body">
                                        <canvas id="postsChart" style="width: 250px"></canvas>
                                    </div>
                                </div>
                                <div class="card mx-4 col-lg-4 p-0">
                                    <h4 class="text-primary text-center">posts</h4>
                                    <div class="card-body ml-4 w100">
                                        <div class="col-12 row p-1 w-100 m-auto flexcenter" style="height: 20px;">
                                            <div class="col-8">
                                                <h6>Monday</h6>
                                            </div>
                                            <div class="col-4 text-left">{{ postDayM }}</div>
                                        </div>
                                        <div class="col-12 row p-1 w-100 m-auto flexcenter" style="height: 20px;">
                                            <div class="col-8">
                                                <h6>Tuesday</h6>
                                            </div>
                                            <div class="col-4 text-left">{{postDayT}}</div>
                                        </div>
                                        <div class="col-12 row p-1 w-100 m-auto flexcenter" style="height: 20px;">
                                            <div class="col-8">
                                                <h6>Wednesday</h6>
                                            </div>
                                            <div class="col-4 text-left">{{postDayW}}</div>
                                        </div>
                                        <div class="col-12 row p-1 w-100 m-auto flexcenter" style="height: 20px;">
                                            <div class="col-8">
                                                <h6>Thursday</h6>
                                            </div>
                                            <div class="col-4 text-left">{{postDayTh}}</div>
                                        </div>
                                        <div class="col-12 row p-1 w-100 m-auto flexcenter" style="height: 20px;">
                                            <div class="col-8">
                                                <h6>Friday</h6>
                                            </div>
                                            <div class="col-4 text-left">{{postDayF}}</div>
                                        </div>
                                        <div class="col-12 row p-1 w-100 m-auto flexcenter" style="height: 20px;">
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
                            <div class="row">
                                <div class="card col-md-7 shadow my-3">
                                    <div class="card-body">
                                        <canvas id="visitsChart" style="width: 250px"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-5 my-3">
                                    <div class="col-12 my-1">
                                        <div class="card my-1 border-left shadow">
                                            <div class="card-header my-1">
                                                <h6 class="m-0 font-weight-bold text-primary">analysis</h6>
                                            </div>
                                            <div class="row p-2 m-1">
                                                <div class="col-7">
                                                    <i class="fa fa-user fa-2x"></i>
                                                    <p>new users</p>
                                                </div>
                                                <div class="col-5 text-right">
                                                    <h4 class="text-muted">{{newUser}}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 my-1">
                                        <div class="card border-left shadow">
                                            <div class="card-header">
                                                <h6 class="m-0 font-weight-bold text-primary">online today</h6>
                                            </div>
                                            <div class="row p-2 m-1">
                                                <div class="col-8">
                                                    <i class="fa fa-users fa-2x"></i>
                                                    <p>users online</p>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <h4 class="text-muted">{{userOnline}}</h4>
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
                                                <div class="offset-4 col-4" style="text-align: right;">null</div>
                                            </div>
                                            <div class="col-md-12 row p-1 w-100 m-auto my-1 border flexcenter" style="height: 70px;">
                                                <div class="col-4">
                                                    <h6>shares</h6>
                                                </div>
                                                <div class="offset-4 col-4" style="text-align: right;">null</div>
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
                            <h1 class="h2">reports</h1>
                            <div class="btn-toolbar mb-2 mb-md-0">
                                <div class="btn-group mr-2">
                                    <button type="button" @click="getReports('true')" class="btn btn-sm btn-outline-secondary">unsolved</button>
                                    <button type="button" @click="getReports('false')" class="btn btn-sm btn-outline-secondary">solved</button>
                                </div>
                            </div>
                            <div class="row">
                                <h2>Posts</h2>
                                <div v-for="(report, index) in reports" class="col-12 row border p-2">
                                    <h4 class="col-8">{{report.post_id}}</h4>
                                    <div class="col-4 text-right">
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
                    </main>
                </div>
            </div>
        </div>
        <script src="../lib/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script>
            Vue.config.productionTip = false;
            let app = new Vue({
                el: '#app',
                data: {
                    dataLoaded: false,
                    stage: 0,
                    users: [],
                    online: null,
                    likes: null,
                    chat: null,
                    follows: null,
                    post: null,
                    newUser: null,
                    data: null,
                    reports: [],
                    user: sessionStorage.getItem('name') || 'Unknown',

                },
                methods: {
                    load: function() {
                        this.getUsers();
                        this.getData();
                    },
                    getData: function() {
                        $.get('../inc/data/data.inc.php?key', (data)=>{
                            this.chat = data.chat.length;
                            this.post = data.posts.length;
                            this.follows = data.following.length;
                            this.likes = data.likes.length;
                            this.newUser = data.users.length;
                        }
                        )

                        $.get('../inc/data/data.a.inc.php?type=all&key=OytsaB78Ze5Y912027', (data)=>{
                            this.data = data;
                        }
                        )

                        $.get('../inc/online.inc.php?all', (data)=>{
                            this.online = data;
                        }
                        )
                    },
                    day: function(day, type='users', m=null) {
                        if (m == null && this.data[type][day]) {
                            return this.data[type][day].length;
                        } else if (m && this.online[day] != null) {
                            return this.online[day].length;
                        } else {
                            return
                        }
                    },
                    getReports: function(a) {
                        this.reports = null;
                        $.get('../inc/report.inc.php?report&type=' + a, (data)=>{
                            this.reports = data;
                        }
                        )
                    },
                    sendReport: function (a) {
                        a = this.reports[a].post_id; 
                        $.post('../inc/report.inc.php',{
                            del: a
                        },function(b){
                            console.log(b) 
                        })
                        this.getReports('false'); 
                    },
                    getUsers: function() {
                        $.get('../inc/users.inc.php?key', (users)=>{
                            users.forEach(user=>{
                                let name = user.usersFirstname + ' ' + user.usersSecondname;
                                this.users.push({
                                    id: user.idusers,
                                    username: user.uidusers,
                                    name: name,
                                    status: user.last_online,
                                    joined: user.date_joined
                                })
                            }
                            )
                        }
                        )
                    },
                    chart: function() {
                        let myChart = new Chart($('#myChart'),{
                            type: 'line',
                            data: {
                                labels: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                                datasets: [{
                                    data: [this.day('Sunday', null, true), this.day('Monday', null, true), this.day('Tuesday', null, true), this.day('Wednesday', null, true), this.day('Thursday', null, true), this.day('Friday', null, true), this.day('Saturday', null, true)],
                                    lineTension: 0,
                                    backgroundColor: 'transparent',
                                    borderColor: 'rgba(67, 22, 228, 0.844)',
                                    borderWidth: 4,
                                    pointBackgroundColor: 'rgb(214, 211, 211)'
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: false
                                        }
                                    }]
                                },
                                legend: {
                                    display: false
                                }
                            }
                        })

                    },
                    doughnut: function(elem, label, data, bg, hbg) {
                        let myPieChart = new Chart(elem,{
                            type: 'doughnut',
                            data: {
                                labels: label,
                                datasets: [{
                                    data: data,
                                    backgroundColor: bg,
                                    hoverBackgroundColor: hbg,
                                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                                }],
                            },
                            options: {
                                maintainAspectRatio: false,
                                tooltips: {
                                    backgroundColor: "rgb(255,255,255)",
                                    bodyFontColor: "#858796",
                                    borderColor: '#dddfeb',
                                    borderWidth: 1,
                                    xPadding: 15,
                                    yPadding: 15,
                                    displayColors: false,
                                    caretPadding: 10,
                                },
                                legend: {
                                    display: false
                                },
                                cutoutPercentage: 80,
                            },
                        });

                    },
                    visitline: function(el, l, t='line', type='users', bg=['#90f0ff', "#00b0ff"], b) {
                        let myVisitsChart = new Chart(el,{
                            type: t,
                            data: {
                                labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                                datasets: [{
                                    label: "total",
                                    data: [this.day('Sunday', type), this.day('Monday', type), this.day('Tuesday', type), this.day('Wednesday', type), this.day('Thursday', type), this.day('Friday', type), this.day('Saturday', type)],
                                    backgroundColor: bg[0],
                                    borderColor: bg[1],
                                    borderWidth: b || 3
                                }]
                            },
                            options: {
                                responsive: false,
                                layout: {
                                    padding: {
                                        left: 10,
                                        right: 25,
                                        top: 25,
                                        bottom: 0
                                    }
                                },
                                scales: {
                                    xAxes: [{
                                        time: {
                                            unit: 'date'
                                        },
                                        gridLines: {
                                            display: false,
                                            drawBorder: false
                                        },
                                        ticks: {
                                            maxTicksLimit: 7
                                        }
                                    }],
                                    yAxes: [{
                                        ticks: {
                                            maxTicksLimit: 5,
                                            padding: 10,
                                            // Include a dollar sign in the ticks
                                        },
                                        gridLines: {
                                            color: "rgb(234, 236, 244)",
                                            zeroLineColor: "rgb(234, 236, 244)",
                                            drawBorder: false,
                                            borderDash: [2],
                                            zeroLineBorderDash: [2]
                                        }
                                    }],
                                },
                                legend: {
                                    display: false
                                },
                                responsive: true,
                                title: {
                                    display: true,
                                    text: l
                                },
                                tooltips: {
                                    mode: "index",
                                    intersect: true
                                }
                            }
                        });
                    },
                    line: function() {
                        var myLineChart = new Chart($('#totalChart'),{
                            type: 'line',
                            data: {
                                labels: ["likes", "chat", "follows", "shares", "comment"],
                                datasets: [{
                                    label: "total",
                                    lineTension: 0.3,
                                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                                    borderColor: "rgba(78, 115, 223, 1)",
                                    pointRadius: 3,
                                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                                    pointBorderColor: "rgba(78, 115, 223, 1)",
                                    pointHoverRadius: 3,
                                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                                    pointHitRadius: 10,
                                    pointBorderWidth: 2,
                                    data: [this.likes, this.chat, this.follows, 0, 0],
                                }],
                            },
                            options: {
                                maintainAspectRatio: false,
                                layout: {
                                    padding: {
                                        left: 10,
                                        right: 25,
                                        top: 25,
                                        bottom: 0
                                    }
                                },
                                scales: {
                                    xAxes: [{
                                        time: {
                                            unit: 'date'
                                        },
                                        gridLines: {
                                            display: false,
                                            drawBorder: false
                                        },
                                        ticks: {
                                            maxTicksLimit: 7
                                        }
                                    }],
                                    yAxes: [{
                                        ticks: {
                                            maxTicksLimit: 5,
                                            padding: 10,
                                        },
                                        gridLines: {
                                            color: "rgb(234, 236, 244)",
                                            zeroLineColor: "rgb(234, 236, 244)",
                                            drawBorder: false,
                                            borderDash: [2],
                                            zeroLineBorderDash: [2]
                                        }
                                    }],
                                },
                                legend: {
                                    display: false
                                },
                                tooltips: {
                                    backgroundColor: "rgb(255,255,255)",
                                    bodyFontColor: "#858796",
                                    titleMarginBottom: 10,
                                    titleFontColor: '#6e707e',
                                    titleFontSize: 14,
                                    borderColor: '#dddfeb',
                                    borderWidth: 1,
                                    xPadding: 15,
                                    yPadding: 15,
                                    displayColors: false,
                                    intersect: false,
                                    mode: 'index',
                                    caretPadding: 10
                                }
                            }
                        });
                    }
                },
                watch: {
                    stage: function() {
                        switch (this.stage) {
                        case 1:
                            $('#dataTable').DataTable();
                            break;
                        case 2:
                            this.visitline($('#postsChart'), "week's post rate", 'bar', 'posts', ['#8080ff', '#6020ff'], 1);
                            break;
                        case 3:
                            this.visitline($('#visitsChart'), "new signup");
                            break;
                        case 4:
                            setTimeout(()=>{
                                this.line();
                                this.doughnut($("#myPieChart"), ['likes', 'comments', 'share'], [this.likes, null, null], ['#4e73df', '#1cc88a', '#36b9cc'], ['#2e59d9', '#17a673', '#2c9faf']);
                                this.doughnut($("#myPieChart1"), ['chat', 'posts'], [this.chat, this.post], ['#00e000', '#60c0ff'], ['#00c000', '#2060ff']);
                                this.doughnut($("#myPieChart2"), ['message', 'follows'], [this.chat, this.follows], ['#e0ff40', '#8040ff'], ['#ffe020', '#8020ff']);
                            }
                            , 0);

                            break;
                        case 5:
                            this.getReports('false');
                            break;
                        default:
                            console.log('hey');
                            break;
                        }
                    },
                    data: function() {
                        this.dataLoaded = true;
                        console.log('data loaded')
                    },
                    online: function() {
                        this.chart();
                    }
                },
                computed: {
                    postDayM: function() {
                        if (this.dataLoaded) {
                            return this.day('Monday', 'posts') || 'Null';
                        }
                        return 'loading..'
                    },
                    postDayT: function() {
                        if (this.dataLoaded) {
                            return this.day('Tuesday', 'posts') || 'Null';
                        }
                        return 'loading..'
                    },
                    postDayW: function() {
                        if (this.dataLoaded) {
                            return this.day('Wednesday', 'posts') || 'Null';
                        }
                        return 'loading..'
                    },
                    postDayTh: function() {
                        if (this.dataLoaded) {
                            return this.day('Thursday', 'posts') || 'Null';
                        }
                        return 'loading..'
                    },
                    postDayF: function() {
                        if (this.dataLoaded) {
                            return this.day('Friday', 'posts') || 'Null';
                        }
                        return 'loading..'
                    },
                    postDayWeek: function() {

                        if (this.dataLoaded) {

                            return ((this.day('Sunday', 'posts') || 0) + (this.day('Saturday', 'posts') || 0) || 'Null');
                        }
                        return 'loading..'
                    },
                    userOnline: function() {
                        if (this.online) {
                            this.day(this.online.today, 'happy', true)
                            return this.day(this.online.today, 'happy', true) || 'Null';
                        }
                        return 'loading..'
                    }

                }
            })

            $(document).on('error', function() {
                console.log('error alert');
            })
        </script>
    </body>
</html>
