<?php
require 'header.php';
# check if logged in
if (!isset($_SESSION['userId'])) {
    header("Location: ../login.php?error=notloggedin");
    exit();
}

# this page is for the user to see all the notifications and he can delete them or mark them as seen 

?>
<script src="lib/vue/vue.min.js"></script>
<style>
    /* css for elements below */

    body {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }

    .notification-item {
        padding: 10px;
        border-bottom: 1px solid #ccc;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .notification-item:hover, .notification-item:hover .notify-text{
        color: #000 !important;
    }
    .notification-item .notify-text {
        font-size: 14px;
        font-weight: bold;
    }

    .notification-item .notify-type {
        font-size: 12px;
        color: #ccc;
    }

    .notification-item .notify-time {
        font-size: 12px;
        color: grey;
    }

    .notification-item .notify-time:hover {
        color: #000;
    }

    .notification-item .notify-delete,
    .notification-item .notify-mark {
        float: right;
        font-size: 12px;
        color: #ccc;
        cursor: pointer;
    }
    no-style:hover{
  color: var(--co) !important;
  text-decoration: none;
}
no-style{
  color: var(--co) !important;
}

</style>
<div id="app">
    <!-- bootstrap tabs -->
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="#notify" @click.prevent="page = 1" data-toggle="tab">Notifications</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#seen" @click.prevent="page = 2" data-toggle="tab">Seen</a>
        </li>
    </ul>
    <div class="container notification w-100 p-0" v-if='page == 1'>
        <h2 class="co">notifications</h2>
        <div v-if="notifications" class="btns mb-3">
            <button class="markAll btn text-dark" @click.prevent="markAll">Mark All as seen</button>
            <button class="deleteAll btn btn-danger" @click.prevent="deleteAll" :disabled="!notifications">Delete All</button>
        </div>
        <div class="notification-item w-100 p-0" v-for='notify in notifications' >
            <div class="content col-7">
                <div class="notify-text co" v-html="notify.text"></div>
                <div class="notify-type">{{notify.notification_id}}</div>
                <div class="notify-time">{{notify.date}}</div>
            </div>
            <div class="col-md-5 p-0">
                <!-- mark as read button -->
                <button class="notify-mark  text-dark btn mx-1" v-on:click='mark_read(notify.notification_id)'>seen</button>
                <!-- delete button -->
                <button class="notify-delete btn-danger btn" v-on:click='delete_notify(notify.notification_id)'>Delete</button>
            </div>
        </div>
        <div class="pt-4 mx-auto text-muted text-center h3" v-if="!notifications">
            <p class="flow-text">you have no new notifications...</p>
        </div>
    </div>
    <div class="container  notification w-100" v-if='page == 2'>
        <h2 class="co">seen</h2>
        <div class="notification-item w-100 p-0" v-for='notify in seen'>
            <div class="content col-10">
                <div class="notify-text co" v-html="notify.text"></div>
                <div class="notify-type">{{notify.notification_id}}</div>
                <div class="notify-time">{{notify.date}}</div>
            </div>
            <div class="col-2 p-0">
                <!-- delete button -->
                <button class="notify-delete btn-danger btn" v-on:click='delete_notify(notify.notification_id)'>Delete</button>
            </div>

        </div>
        <div class="pt-4 mx-auto text-muted text-center h3" v-if="!seen">
            <p class="flow-text">you have no seen notifications...</p>
        </div>


    </div>

</div>

<br><br><br>
<div class="mobile nav-show">
<br><br><br>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data: {
            url: 'inc/notification.inc.php',
            page: 1,
            notifications: [],
            seen: [],
            seenall: [],
            fetch: [],
            user: '<?php echo $_SESSION['userId']; ?>',
            text: '',
            type: '',
            notify_id: '',
            error: '',
            success: '',
        },
        methods: {
            get_url: function(s) {
                //  a string can have anchor tag with url, so we need to remove it and return the url if it has one
                url = s[0].text.split('<a href="')[1];
                if (url) {
                    console.log(url.text);
                } else {
                    return false;
                }
                return false
            },
            get_notify: function() {
                this.fetch = [];
                $.get(this.url + '?fetch=true').then(response => {
                    console.log(response);
                    $url = app.get_url(response.data);
                    this.fetch = response.data;
                    this.notifications = this.fetch;

                });
            },
            open_page: function(url) {
                if (url) {
                    window.location.href = url;
                } else {
                    alert('no url found');
                }
            },
            delete_notify: function(id) {
                $.get(this.url + '?delete=' + id).then(response => {
                    this.get_notify();
                    this.get_seen();
                });
            },
            mark_read: function(id) {
                $.get(this.url + '?seen=' + id).then(response => {
                    this.get_notify();
                });
            },
            get_seen: function() {
                this.fetch = [];
                $.get(this.url + '?fetch_seen=true').then(response => {
                    this.fetch = response.data;
                    this.seen = this.fetch;
                });
            },
            update_seen: function(id) {
                $.get(this.url + '?seen=' + id).then(response => {
                    this.success = this.notify;
                    this.get_notify();
                });
            },
            update_seenall: function() {
                $.get(this.url + '?seenall=' + this.user).then(response => {
                    this.notify = response.body;
                    this.success = this.notify;
                    this.get_notify();
                });
            },
            notify: function() {
                $.post(this.url + '?notify=true', {
                    text: this.text,
                    type: this.type,
                }).then(response => {
                    this.notify = response.body;
                    this.success = this.notify;
                    this.get_notify();
                });
            },
            markAll: function() {
                $.get(this.url + '?seenall=' + this.user).then(response => {
                    this.success = this.notify;
                    this.get_notify();
                });
            },
            deleteAll: function() {
                $.get(this.url + '?delete_all=' + this.user).then(response => {
                    this.success = this.notify;
                    this.get_notify();
                });
            },

        },
        watch: {
            page: function() {
                if (this.page == 1) {
                    this.get_notify();
                }
                if (this.page == 2) {
                    this.get_seen();
                }
            }
        },
        mounted: function() {
            this.get_notify();
        },

    })

// document ready
$(document).ready(function() {
    active_page(3);
});
</script>
    
<?php
  require 'mobile.php';
 include 'footer.php';
  ?>