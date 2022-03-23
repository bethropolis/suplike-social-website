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
  <script src="./lib/vue/vue.js"></script>
  <script src="./lib/wavesurfer/wavesurfer.js"></script>
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
        <ul class="col-8 users row">
          <li class="text-left">@{{person.name}}</li>
          <div v-show="person.online" class="online mt-2 rounded-circle"></div>
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
          <ul v-for="(msg, index) in messages" :class="msg.to? 'offset-10 text  b':'text a'">
            <li class="msg" v-show="msg.type == 'txt'">{{msg.message}}</li>
            <div class="row" v-show="msg.type == 'mus'">
              <div id="waveform" class="col-10 msg"></div>
              <i @click="load(msg.message)" class="fa my-auto col-2" :class="this.player?'fa-pause':'fa-play'"></i>
            </div>
            <li class="offset-9 time">{{msg.time}}</li>
          </ul>
        </div>
        <form @submit.prevent="sendMessage" class="form-inline row message-form" method="post">
          <label for="imgUpload" class="col-1"><input class="hide" type="file" id="imgUpload"><i class="fa fa-image"></i></label>
          <label for="songUpload" class="col-1"><input class="hide" type="file" id="songUpload"><i class="fa fa-music"></i></label>
          <input type="text" class="col-9 form-input" placeholder="enter message..." id="msg-form" autofocus="true">
          <!-- <button type="submit" class="btn btn-send"><i class="fa fa-send"></i></button> -->
        </form>
      </div>
    </div>
  </div>

  <script>
    const app = new Vue({
      el: '#app',
      data: {
        chatwith: "<?= $to ?>" || null,
        user: "<?= $_SESSION['chat_token'] ?>",
        online: [],
        messages: [],
        player: null,
        wave: {
          container: '#waveform',
          waveColor: '#D9DCFF',
          barWidth: 3,
          height: 50,
          barWidth: 3,
          barHeight: 1,
          progressColor: '#6c5ce7',
          fillParent: true,
          hideScrollbar: true,
          responsive: true,
          barRadius: 3,
          barGap: 3,
          cursorColor: 'transparent',
        }
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
                    type: item.type,
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
              }, 1000)
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
                id: isOnline.chat_auth,
                online: isOnline.online
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
        wavesurfer: function() {
          this.player = WaveSurfer.create(this.wave)
        },
        load: async function(url) {
          await this.wavesurfer();
          url = "inc" + url;
          this.playUrl(url);
          this.player.play(); 
        },
        playUrl(url) {
          this.player.load(url);
        },
        playPause: function() {
          this.player.playPause();
        },
        startChat: function(index) {
          this.chatwith = this.online[index].id;
          history.pushState(null, null, "?id=" + this.online[index].id)
          document.title = this.online[index].name
        },
        sendUpload: function(data) {
          fetch(`inc/file.inc.php?from=${this.user}&to=${this.chatwith}&type=mus`, {
              method: "POST",
              body: data,
            })
            .then((response) => response.json())
            .then((data) => {
              console.log("File uploaded successfully");
              console.log(data);
            })
            .catch((error) => {
              alert("could not upload file");
            });
        },
        checkrequest: function() {
          // $.get('./inc/checkrequest.inc.php?user=' + this.user, function(data) {
          //   notification.setNotification(data.msg, data.type, data.id);
          // });
        },
        sendMessage: function(type) {
          $.post('./inc/message.inc.php', {
            message: $('#msg-form').val(),
            type: type || 'txt',
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
          if (this.chatwith == null) {
            document.title = "messages";
            history.pushState(null, null, "./message.php")
          }
          this.messages = [];
          this.getMessage();
        },
        messages: function() {
          this.switchMsgdata();
        }
      },
      computed: {
        cwave: function(url) {
          this.load(url);
          return
        }
      },
    })

    const handleImageUpload = (event) => {
      const files = event.target.files;
      const formData = new FormData();
      formData.append("uploadedFile", files[0]);
      console.log(files)
      app.sendUpload(formData);
    };
    document.querySelector("#songUpload").addEventListener("change", (event) => {
      console.log("Uploading file");
      handleImageUpload(event);
    });
  </script>
</body>

</html>