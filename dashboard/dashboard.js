let app = new Vue({
    el: "#app",
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
        settings: 1,
        successMessage: '',
        plugins: {
            activeTab: 'installed',
            installedPlugins: [],
            marketplacePlugins: [],
        },
        reports: [],
        config: { ...envConfig },
        darkMode: localStorage.getItem('theme') === 'dark',
        user: sessionStorage.getItem("name") || "Unknown",
        token: sessionStorage.getItem("user") || "Unknown",
    },
    methods: {
        getData: async function () {
            await $.get("../inc/data/data.inc.php?key", (data) => {
                this.chat = data.chat.length || 0;
                this.post = data.posts.length || 0;
                this.follows = data.following.length || 0;
                this.likes = data.likes.length || 0;
                this.newUser = data.users.length || 0;
                this.share = data.share.length || 0;
                this.comments = data.comments.length || 0;
            });

            await $.get("../inc/data/data.a.inc.php?type=all&key=" + this.token, (data) => {
                this.data = data;
            });

            await $.get("../inc/data/users.inc.php?online", (data) => {
                this.online = data;
            });

            await $.get("../plugins/?get", (data) => {
                this.plugins.installedPlugins = data;
            });

            return
        },
        day: function (day, type = "users", m = null) {
            if (m === null && this.data?.[type]?.[day]) {
                return this.data[type][day].length;
            } else if (m && this.online[day] !== null) {
                return this.online[day]?.length;
            } else {
                return;
            }
        },
        changeStage: function (index) {
            sessionStorage.setItem("dashStage", index);
            this.stage = index;
        },
        getReports: function (a) {
            this.reports = null;
            url = "../inc/report.inc.php?report&type=" + a;
            $.get(url, (data) => {
                this.reports = data;
            });
        },
        sendReport: function (reportIndex) {
            const postId = this.reports[reportIndex].post_id;
            const commentId = this.reports[reportIndex].comment_id;
            const isComment = parseInt(this.reports[reportIndex].is_comment);

            $.post("../inc/report.inc.php", {
                del: isComment ? commentId : postId,
            });

            this.getReports("false");
        },
        clearLog: function () {
            $.ajax({
                url: '../inc/errors/error.inc.php',
                data: { page: 'clear' },
                method: 'POST'
            });
            location.reload();
        },
        saveLog: function () {
            const logText = $('#log-textarea').val();
            const blob = new Blob([logText], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'error.log.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        },
        getUsers: async function () {
            this.users = [];
            await $.get("../inc/data/users.inc.php?key", (users) => {
                users.forEach((user) => {
                    this.users.push({
                        id: user.idusers,
                        username: user.uidusers,
                        status: user.status,
                        online: user.last_online,
                        joined: user.date_joined,
                        admin: parseInt(user.isAdmin),
                        bot: parseInt(user.isBot),
                        token: user.token,
                    });
                });
            });
        },
        toggleAdmin(user) {
            if (user.admin == 1) {
                $.post("../inc/data/users.inc.php", { revoke: user.id }, (data) => {
                    if (data.status == "error") alert(data.message);
                    return this.getUsers();
                });
            } else {
                $.post("../inc/data/users.inc.php", { admin: user.id }, (data) => {
                    if (data.status == "error") alert(data.message);
                    return this.getUsers();
                });
            }
        },
        blockUser(user) {
            $.post("../inc/data/users.inc.php", { block: user.id, set: user.status == "blocked" }, (data) => {
                if (data.status == "error") alert(data.message);
                return this.getUsers();
            });
        },
        deleteUser(user) {

            let confirmation = confirm(`are you sure you want to delete user ${user.username}`);
            if (confirmation) {
                $.post("../inc/delete.inc.php", { user: user.id, delete_profile: true }, (data) => {
                    if (data.status == "success") alert(`user ${user.username} has been deleted`);
                    if (data.status != "success") alert("could not delete the user");
                    this.getUsers();
                    $("#dataTable").DataTable();
                });
            }
        },
        checkLatestRelease: function (version) {
            $("#updates-spinner").show();
            // Make the GET request to the GitHub API
            $.get(`https://api.github.com/repos/bethropolis/suplike-social-website/releases/latest`)
                .done(function (data) {
                    $("#updates-spinner").hide();
                    let latestTag = data.tag_name;
                    let htmlUrl = data.html_url;
                    $('#versionText').text('Latest version: ' + latestTag);
                    $('#latestReleaseInfo').show();
                    if (latestTag != version) {
                        $(".update-field").html(`<a href="${htmlUrl}" target="_blank"> <p class='text-info h3'>New version available</p></a>`)
                        return
                    }
                    $(".update-field").html("<p class='text-success h3'>Version is upto date</p>")
                })
                .fail(function () {
                    $("#updates-spinner").hide();

                    alert('Failed to fetch the latest release.');
                });
        },
        changeTab(tab) {
            this.plugins.activeTab = tab;
        },
        installPlugin(plugin) {
            $.post("../plugins/?install", { url: plugin.install_url }, (data) => {
                if (data) {
                    this.successMessage = `Plugin "${plugin.name}" has been installed successfully.`;
                } else {
                    alert(`Plugin "${plugin.name}" could not be installed.`);
                }
            })
        },
        uninstallPlugin(plugin) {
            let agree = confirm(`Are you sure you want to uninstall plugin "${plugin.name}"?`);
            if (!agree) return;
            $.post("../plugins/?uninstall", { name: plugin.id }, (data) => {
                if (data) {
                    this.successMessage = `Plugin "${plugin.name}" has been uninstalled successfully.`;
                } else {
                    alert(`Plugin "${plugin.name}" could not be uninstalled.`);
                }
            })
        },
        saveConfig() {
            $.post('../inc/setup/save_config', this.config, function (data) {
                $('.success-banner').fadeIn().delay(2000).fadeOut();
            });
        },
        chart: function () {
            // Create chart with data
            const labelNames = [
                "Sunday",
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday",
            ];
            const dataPoints = [
                this.day("Sunday", null, true) || 0,
                this.day("Monday", null, true) || 0,
                this.day("Tuesday", null, true) || 0,
                this.day("Wednesday", null, true) || 0,
                this.day("Thursday", null, true) || 0,
                this.day("Friday", null, true) || 0,
                this.day("Saturday", null, true) || 0,
            ];
            const chartData = {
                labels: labelNames,
                datasets: [
                    {
                        label: "online users",
                        data: dataPoints,
                        lineTension: 0.3,
                        backgroundColor: "transparent",
                        borderColor: "#6c5ce7",
                        borderWidth: 3,
                        pointRadius: 0.7,
                        pointBackgroundColor: "#6c5ce7",
                    },
                ],
            };
            const chartOptions = {
                scales: {
                    y: {
                        beginAtZero: true
                    },
                },
                interaction: {
                    intersect: false,
                },
                legend: {
                    display: false,
                },
            };
            const myChart = new Chart($("#myChart"), {
                type: "line",
                data: chartData,
                options: chartOptions,
            });
        },
        doughnut: function (elem, label, data, bg, hbg) {
            let myPieChart = new Chart(elem, {
                type: "doughnut",
                data: {
                    labels: label,
                    datasets: [
                        {
                            data: data,
                            backgroundColor: bg,
                            hoverBackgroundColor: hbg,
                            hoverBorderColor: "rgba(234, 236, 244, 1)",
                            borderColor: "transparent"
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: "#dddfeb",
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                    legend: {
                        display: true,
                    },
                    cutoutPercentage: 80,
                },
            });
        },
        visitline: function (
            chartElement,
            label,
            type = "bar",
            dataKey = "users",
            backgroundColor = ["#90f0ff", "#00b0ff"],
        ) {
            const myVisitsChart = new Chart(chartElement, {
                type: type,
                data: {
                    labels: [
                        "Sunday",
                        "Monday",
                        "Tuesday",
                        "Wednesday",
                        "Thursday",
                        "Friday",
                        "Saturday",
                    ],
                    datasets: [
                        {
                            label: label,
                            data: [
                                this.day("Sunday", dataKey),
                                this.day("Monday", dataKey),
                                this.day("Tuesday", dataKey),
                                this.day("Wednesday", dataKey),
                                this.day("Thursday", dataKey),
                                this.day("Friday", dataKey),
                                this.day("Saturday", dataKey),
                            ],
                            backgroundColor: backgroundColor[0],
                            borderColor: backgroundColor[1],

                        },
                    ],
                },
                options: {
                    scales: {

                    },
                    title: {
                        display: true,
                        text: label,
                    },
                    tooltips: {
                        mode: "index",
                        intersect: true,
                    },
                    responsive: true,
                },
            });
        },
        createLineChart() {
            const chartLabels = ["likes", "chat", "follows", "shares", "comment"];
            const chartData = [
                this.likes,
                this.chat,
                this.follows,
                this.share,
                this.comments,
            ];

            const myLineChart = new Chart($("#totalChart"), {
                type: "line",
                data: {
                    labels: chartLabels,
                    datasets: [
                        {
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
                            data: chartData,
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    layout: {
                        padding: { left: 0, right: 0, top: 25, bottom: 0 },
                    },
                    scales: {
                        xAxes: [
                            {
                                time: { unit: "date" },
                                gridLines: { display: false, drawBorder: false },
                                ticks: { maxTicksLimit: 7 },
                            },
                        ],
                        yAxes: [
                            {
                                ticks: { maxTicksLimit: 5, padding: 10 },
                                gridLines: {
                                    color: "rgb(234, 236, 244)",
                                    zeroLineColor: "rgb(234, 236, 244)",
                                    drawBorder: false,
                                    borderDash: [2],
                                    zeroLineBorderDash: [2],
                                },
                            },
                        ],
                    },
                    legend: { display: false },
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        titleMarginBottom: 10,
                        titleFontColor: "#6e707e",
                        titleFontSize: 14,
                        borderColor: "#dddfeb",
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        intersect: false,
                        mode: "index",
                        caretPadding: 10,
                    },
                },
            });
        },
    },
    watch: {
        stage: function () {
            const { stage } = this;

            switch (stage) {
                case 1:
                    $("#dataTable").DataTable();
                    break;
                case 2:
                    this.visitline(
                        $("#postsChart"),
                        "week's post rate",
                        "bar",
                        "posts",
                        ["#8080ff", "#6020ff"],
                        1
                    );
                    break;
                case 3:
                    this.visitline($("#visitsChart"), "new signup");
                    break;
                case 4:
                    setTimeout(() => {
                        this.createLineChart();
                        this.doughnut(
                            $("#myPieChart"),
                            ["likes", "comments", "share"],
                            [this.likes, this.comments, this.share],
                            ["#4e73df", "#1cc88a", "#36b9cc"],
                            ["#2e59d9", "#17a673", "#2c9faf"]
                        );
                        this.doughnut(
                            $("#myPieChart1"),
                            ["chat", "posts"],
                            [this.chat, this.post],
                            ["#00e000", "#60c0ff"],
                            ["#00c000", "#2060ff"]
                        );
                        this.doughnut(
                            $("#myPieChart2"),
                            ["message", "follows"],
                            [this.chat, this.follows],
                            ["#e0ff40", "#8040ff"],
                            ["#ffe020", "#8020ff"]
                        );
                    }, 100);

                    break;
                case 5:
                    this.getReports("false");
                    break;
                case 6:
                    $.get("../,./../../compresed/marketplace.json", (data) => {
                        this.plugins.marketplacePlugins = data.filter(plugin => !this.plugins.installedPlugins.some(installedPlugin => installedPlugin.id === plugin.id));
                    });
                default:
                    break;
            }
        },
        darkMode: function () {
            if (this.darkMode) {
                localStorage.setItem('theme', 'dark');
                darkMode();
            } else {
                localStorage.setItem('theme', 'light');
                $("style[data-name='theme']").remove();
            }
        },
        data: function () {
            this.dataLoaded = true;
        },
        online: function () {
            this.chart();
        },
    },
    computed: {
        postDayM: function () {
            if (this.dataLoaded) {
                return this.day("Monday", "posts") || 0;
            }
            return "loading..";
        },
        postDayT: function () {
            if (this.dataLoaded) {
                return this.day("Tuesday", "posts") || 0;
            }
            return "loading..";
        },
        postDayW: function () {
            if (this.dataLoaded) {
                return this.day("Wednesday", "posts") || 0;
            }
            return "loading..";
        },
        postDayTh: function () {
            if (this.dataLoaded) {
                return this.day("Thursday", "posts") || 0;
            }
            return "loading..";
        },
        postDayF: function () {
            if (this.dataLoaded) {
                return this.day("Friday", "posts") || 0;
            }
            return "loading..";
        },
        postDayWeek: function () {
            if (this.dataLoaded) {
                return (
                    (this.day("Sunday", "posts") || 0) +
                    (this.day("Saturday", "posts") || 0) || 0
                );
            }
            return "loading..";
        },
        userOnline: function () {
            if (this.online) {
                this.day(this.online.today, "happy", true);
                return this.day(this.online.today, "happy", true) || 0;
            }
            return "loading..";
        },
        averageOnlineUsers: function () {
            return Math.round(this.data?.average?.averageUsers)
        },
        averageOnlineUsersPercentage: function () {
            return Math.round((Math.round(this.data?.average?.averageUsers) / this.users?.length) * 100)
        },
        newUserPercentage: function () {
            return Math.round((this.newUser / this.users?.length) * 100)
        }
    },
    mounted: async function () {
        await this.getData();
        await this.getUsers();
        this.stage = parseInt(sessionStorage.getItem("dashStage")) || 0;
    },
});
