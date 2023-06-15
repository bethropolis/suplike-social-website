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
        comments: null,
        share: null,
        follows: null,
        post: null,
        newUser: null,
        data: null,
        reports: [],
        user: sessionStorage.getItem('name') || 'Unknown',
        token: sessionStorage.getItem('user') || 'Unknown'

    },
    methods: {
        load: function () {
            this.getUsers();
            this.getData();
        },
        getData: function () {
            $.get('../inc/data/data.inc.php?key', (data) => {
                this.chat = data.chat.length ||0;
                this.post = data.posts.length ||0;
                this.follows = data.following.length ||0;
                this.likes = data.likes.length ||0;
                this.newUser = data.users.length ||0;
                this.share = data.share.length ||0;
                this.comments = data.comments.length ||0; 
            })

            $.get('../inc/data/data.a.inc.php?type=all&key=' + this.token, (data) => {
                this.data = data;
            })

            $.get('../inc/online.inc.php?all', (data) => {
                this.online = data;
            })
        },
        day: function (day, type = 'users', m = null) {
            if (m === null && this.data?.[type]?.[day]) {
                return this.data[type][day].length;
            } else if (m && this.online[day] !== null) {
                return this.online[day]?.length;
            } else {
                return
            }
        },
        getReports: function (a) {
            this.reports = null;
            url ='../inc/report.inc.php?report&type=' + a;
            $.get(url, (data) => {
                this.reports = data; 
            })
        },
        sendReport: function (a) {
            var r = this.reports[a].post_id ;
           var c = this.reports[a].comment_id;
            if (this.reports[a].is_comment) {
                $.post('../inc/report.inc.php', {
                    delc: caches
                }, function (b) {
                    console.log(b)
                })
            } else {
                $.post('../inc/report.inc.php', {
                    del: r
                }, function (b) {
                    console.log(b)
                })
            }


            this.getReports('false');
        },
        getUsers: function () {
            $.get('../inc/users.inc.php?key', (users) => {
                users.forEach(user => {
                    let name = user.usersFirstname + ' ' + user.usersSecondname;
                    this.users.push({
                        id: user.idusers,
                        username: user.uidusers,
                        name: name,
                        status: user.last_online,
                        joined: user.date_joined
                    })
                })
            })
        },
        chart: function () {
            let myChart = new Chart($('#myChart'), {
                type: 'line',
                data: {
                    labels: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                    datasets: [{
                        data: [this.day('Sunday', null, true), this.day('Monday', null, true), this.day('Tuesday', null, true), this.day('Wednesday', null, true), this.day('Thursday', null, true), this.day('Friday', null, true), this.day('Saturday', null, true)],
                        lineTension: 0,
                        backgroundColor: 'transparent',
                        borderColor: '#6c5ce7',
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
        doughnut: function (elem, label, data, bg, hbg) {
            let myPieChart = new Chart(elem, {
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
        visitline: function (el, l, t = 'line', type = 'users', bg = ['#90f0ff', "#00b0ff"], b) {
            let myVisitsChart = new Chart(el, {
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
        line: function () {
            var myLineChart = new Chart($('#totalChart'), {
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
                        data: [this.likes, this.chat, this.follows, this.comments,this.share],
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
        stage: function () {
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
                    setTimeout(() => {
                        this.line();
                        this.doughnut($("#myPieChart"), ['likes', 'comments', 'share'], [this.likes, this.share, this.comments], ['#4e73df', '#1cc88a', '#36b9cc'], ['#2e59d9', '#17a673', '#2c9faf']);
                        this.doughnut($("#myPieChart1"), ['chat', 'posts'], [this.chat, this.post], ['#00e000', '#60c0ff'], ['#00c000', '#2060ff']);
                        this.doughnut($("#myPieChart2"), ['message', 'follows'], [this.chat, this.follows], ['#e0ff40', '#8040ff'], ['#ffe020', '#8020ff']);
                    }, 0);

                    break;
                case 5:
                    this.getReports('false');
                    break;
                default:
                    console.log('hey');
                    break;
            }
        },
        data: function () {
            this.dataLoaded = true;
            console.log('data loaded')
        },
        online: function () {
            this.chart();
        }
    },
    computed: {
        postDayM: function () {
            if (this.dataLoaded) {
                return this.day('Monday', 'posts') || 'Null';
            }
            return 'loading..'
        },
        postDayT: function () {
            if (this.dataLoaded) {
                return this.day('Tuesday', 'posts') || 'Null';
            }
            return 'loading..'
        },
        postDayW: function () {
            if (this.dataLoaded) {
                return this.day('Wednesday', 'posts') || 'Null';
            }
            return 'loading..'
        },
        postDayTh: function () {
            if (this.dataLoaded) {
                return this.day('Thursday', 'posts') || 'Null';
            }
            return 'loading..'
        },
        postDayF: function () {
            if (this.dataLoaded) {
                return this.day('Friday', 'posts') || 'Null';
            }
            return 'loading..'
        },
        postDayWeek: function () {

            if (this.dataLoaded) {

                return ((this.day('Sunday', 'posts') || 0) + (this.day('Saturday', 'posts') || 0) || 'Null');
            }
            return 'loading..'
        },
        userOnline: function () {
            if (this.online) {
                this.day(this.online.today, 'happy', true)
                return this.day(this.online.today, 'happy', true) || 'Null';
            }
            return 'loading..'
        }

    }
})

$(document).on('error', function () {
    console.log('error alert');
})