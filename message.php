<?php
session_start();
if (!isset($_SESSION['token'])) { 
  header('Location: ./login.php');
  exit();
}

if (isset($_GET['id'])) {
  $_GET['id'] == '' ? $to = 'null' : $to = $_GET['id'];
} else {
  $to = null; 
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>live chat</title> 
  <link rel="icon" type="image/png" href="img/logo.png">   
  <link rel="stylesheet" href="./lib/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="./lib/font-awesome/font-awesome.min.css">
  <link rel="stylesheet" href="./css/chat.css?hs"> 
  <script type="text/javascript" src="./lib/jquery/jquery.js"></script> 
  <script src="./lib/vue/vue.min.js"></script>    
</head>

<body onload="app.WhoIsOnline();app.getMessage()">
  <div id="app">
    <nav><a href="./">
        <img title="go to homepage" src="img/logo.png" alt="logo" style="width:35px; height: 35px;">
      </a>
      <div class="nav-content">
  
        <i @click="goBack" class="fa fas fa-arrow-left toShow fa-2x" v-show="chatwith != null"></i> 
        <a href="./" class="home">
          <i class="fa fas fa-home fa-2x"></i>
        </a>
        <a href="inc/logout.inc.php" class="log-out">
          <i class="fa fas fa-sign-out fa-2x"></i>
        </a>
      </div>
    </nav>


    <!--    list of people  -->
    <div v-show="chatwith==null" class="container text-center"> 
      <h4>users</h4>
      <div v-for="(person, index) in online" class="list row" :key="index">
        <ul class="col-8 users">
          <li class="text-left">@{{person.name}}</li>
        </ul>
        <div class="col-4">
          <button class="btn bg" :disabled="user == person.name" @click="startChat(index)">chat</button>
        </div>
      </div>
    </div> 

    <!--  messaging box -->
    <div class="message-box box row" v-show="chatwith != null">

      <div class="col-1 yellow center toHide"> 
        <ul class="center col-12">
          <i @click="goBack" class="fa fas fa-arrow-left fa-2x"></i>
        </ul>

      </div> 

      <div class="col-11 direct-message">
        <div class="messages purple lighten-4">
          <ul v-for="(msg, index) in messages" :class="msg.to? 'offset-10 text b':'text a'"> 
            <li class="msg">{{msg.message}}</li>
            <li class="offset-9 time">{{msg.time}}</li>
          </ul>
        </div>
        <form @submit.prevent="sendMessage" class="form-inline message-form" method="post">
          <input type="text" class="col-11 form-input" placeholder="enter message..." id="msg-form" autofocus="true">
          <button class="btn btn-send"><i class="fa fa-send"></i></button>
        </form>
      </div>
    </div>
  </div>

  <script>
    const app = new Vue({
      el: '#app',
      data: {
        chatwith: "<?= $to ?>"||null ,  
        user: "<?= $_SESSION['chat_token'] ?>",    
        online: [],
        messages: []
      },
      methods: {
        getMessage: function() {
          const vm = this;
          let start = 0;

          function sendRequest() {

            $.get('./inc/message.inc.php?start=' + start + "&from=" + vm.user + "&to=" + vm.chatwith, function(data) {
              if (data.items) {
                data.items.forEach(item => {
                  start = item.id;
                  const t = new Date(item.time);
                  const time = `${t.getHours()}:${t.getMinutes() < 10? '0': ''}${t.getMinutes()}`;
                  vm.messages.push({
                    message: item.message,
                    id: item.who_to,
                    to: false,
                    time: time
                  })
                }) 
                $('.messages').animate({
                  scrollTop: $('.messages')[0].scrollHeight
                });
              }
            });

            if (vm.chatwith != null) {
              setTimeout(function() {
                sendRequest()
              }, 5000)
            };
          }

          sendRequest(); 
        },
        WhoIsOnline: function() {
          //meant to get users who are online but the plan changed 
          $.get('./inc/social.inc.php?user=' + this.user, function(areOnline) {
            app.online = [];
            areOnline.forEach(function(isOnline) { 
              app.online.push({
                name: isOnline.uidusers,
                id: isOnline.chat_auth  
              })
            })
          });
        },
        switchMsgdata: function() {
          let vm = this;
          this.messages.forEach(message => {
            if (vm.chatwith == message.id) {
              message.to = true
            }
          })
        },
        startChat: function(index) {
          this.chatwith = this.online[index].id;
          // $.get('./inc/setrequest.inc.php?user=' + this.user + "&to=" + this.online[index].id, function(data) {
          //   notification.setNotification(data.msg, data.type)
          // });
        },
        checkrequest: function() {
          // $.get('./inc/checkrequest.inc.php?user=' + this.user, function(data) {
          //   notification.setNotification(data.msg, data.type, data.id);
          // });
        },
        sendMessage: function() {
          $.post('./inc/message.inc.php', {
            message: $('#msg-form').val(),
            from: this.user,
            to: this.chatwith
          }, function(data) {
            if (data.type == 'error') {
              alert('there was an error')
            };
          });
          $('#msg-form').val(' ');
        },
        goBack: function() {
          this.chatwith = null
        }
      },
      watch: {
        chatwith: function() {
          this.messages = [];
          this.getMessage()
        },
        messages: function() {
          this.switchMsgdata();
        }
      }
    })
  </script>
</body>

</html>