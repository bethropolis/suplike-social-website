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
  <link rel="stylesheet" href="./css/chat.min.css">
  <script type="text/javascript" src="./lib/jquery/jquery.js"></script>
  <script src="./lib/vue/vue.min.js"></script>
</head>

<body>
  <div id="app">
    <nav>
      <a href="./">
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
    <div v-show="chatwith==null" class="conversation-area">
      <h4>users</h4>
      <div v-for="(user,index) in online" @click="startChat(index)" class="msg" :class="user.online? 'online': ''">
        <img class="msg-profile" :src="'img/'+user.profile_picture" alt="" onerror="this.error = null; this.src ='img/M.jpg' ">
        <div class="msg-detail">
          <div class="msg-username">{{user.full_name}}</div>
          <div class="msg-content">
            <span class="msg-message ellipsis" v-if="user.type == 'txt'">{{user.last_msg||'[empty message]'}}</span>
            <!-- v-else-if if type == 'img' -->
            <span class="msg-message ellipsis" v-else-if="user.type == 'img'">[image]</span>
            <!-- v-else-if if type == 'song' -->
            <span class="msg-message ellipsis" v-else-if="user.type == 'mus'">[song]</span>
            <!-- v-else-if if type == 'video' -->
            <span class="msg-message ellipsis" v-else-if="user.type == 'vid'">[video]</span>
            <!-- v-else-if if type == 'file' -->
            <span class="msg-message ellipsis" v-else-if="user.type == 'file'">[file]</span>
            <!-- v-else-if if type == 'location' -->
            <span class="msg-message ellipsis" v-else-if="user.type == 'loc'">[location]</span>
            <!-- v-else -->
            <span class="msg-message ellipsis" v-else>[empty message]</span>
            <span class="msg-date">{{user.time}}</span>
          </div>
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

      <div class="col-11 pt-2  chat-area direct-message">
        <div class="chat-area-main p-0 messages">
          <div v-for="(msg, index) in messages" class="chat-msg" :class="msg.to? 'owner':''">
            <div class="chat-msg-profile">
              <div class="chat-msg-date">{{msg.time}}</div>
            </div>
            <div class="chat-msg-content">
              <div v-if="msg.type == 'txt'">
                <div class="chat-msg-text">{{msg.message}}</div>
              </div>
              <div v-if="msg.type == 'mus'" class="row chat-msg-text center">
                <div class="progress col-10" style="background-color: transparent;">
                  <div class="progress-bar progress-bar-striped progress-bar-animated" style="background-color: var(--pink);" :id="'p-'+msg.audio_id" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                  </div>
                </div>

                <i @click="play(msg.audio_id)" class="fa col-2 my-auto " :class="msg.audio_id == playing ?'fa-pause':'fa-play'"></i>
              </div>
              <div v-if="msg.type == 'img'" class="msg-image-wrapper ">
                <img :src="'inc/'+msg.message" alt="msg.message" class="msg-image ">
              </div>
            </div>
          </div>
          <br><br><br><br><br>
        </div>
        <audio id='audioPlayer'></audio>
        <form @submit.prevent="sendMessage" class="form-inline row light message-form" method="post">
          <!-- improve this form, it contains an image, audio and text input fields -->
          <label for="imgUpload" class="col-1"><input class="hide" type="file" id="imgUpload"><i class="fa fa-image"></i></label>
          <label for="songUpload" class="col-1"><input class="hide" type="file" id="songUpload"><i class="fa fa-music"></i></label>
          <div class="col-9">
            <input type="text" class="col-12 form-input" placeholder="enter message..." id="msg-form" autofocus="true">
          </div>
          <!-- <button type="submit" class="btn btn-send"><i class="fa fa-send"></i></button> -->
        </form>
      </div>
    </div>
  </div>

  <script>
    _id_user = "<?= $to ?>" || null;
    _token = "<?= $_SESSION['token'] ?>";
    // app.player should be an array representing the each audio element
    const app = new Vue({
      el: '#app',
      data: {
        chatwith: _id_user,
        user: _token,
        online: [],
        messages: [],
        player: {},
        playing: null,
        progress: 0,
        audio_meta: {},
        file_type: null,
      },
      methods: {
        getMessage: function() {
          const vm = this;
          let start = 0;

          async function sendRequest() {
            await $.get('./inc/message.inc.php?start=' + start + "&from=" + vm.user + "&to=" + vm.chatwith, function(data) {
              if (data.data) {
                data.data.forEach(item => {
                  start = item.id;
                  const t = new Date(item.time);
                  const time = `${t.getHours()}:${t.getMinutes() < 10? '0': ''}${t.getMinutes()}`;
                  let audio_id = null;
                  if (item.type == 'mus') {
                    audio_id = Math.random().toString(36).substr(2, 5);
                    vm.player[audio_id] = {
                      src: item.message,
                      playing: false,
                    };
                  }
                  vm.messages.push({
                    message: item.message,
                    id: item.who_to,
                    type: item.type,
                    to: false,
                    time: time,
                    audio_id: audio_id,
                  })
                })
                $('.messages').animate({
                  scrollTop: $('.messages')[0].scrollHeight
                });
              }
            });
            if (vm.chatwith != null) {
              setTimeout(sendRequest, 1800);
            }
          }

          sendRequest();
        },
        WhoIsOnline: function() {
          //meant to get users who are online but the plan changed 
          $.get('./inc/social.inc.php?user=' + this.user, function(areOnline) {
            app.online = [];
            let _users = areOnline.users;
            let users = _users.sort(function(a, b) {
              if (a.last_msg == '') return 1;
              if (b.last_msg == '') return -1;
              return 0;
            })
            users.forEach(user => {
              app.online.push({
                id: user.chat_auth,
                full_name: user.full_name,
                profile_picture: user.profile_picture,
                last_msg: user.last_msg,
                time: user.time,
                type: user.type,
                online: user.online
              })
            })
          })

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
          history.pushState({}, '', '?id=' + this.chatwith);
          document.title = this.online[index].full_name;
        },
        sendUpload: function(data) {
          if (!this.file_type) return;
          fetch(`inc/file.inc.php?from=${this.user}&to=${this.chatwith}&type=${this.file_type}`, {
              method: "POST",
              body: data,
            })
            .then((response) => response.json())
            .then((data) => {
              app.file_type = null;
              console.log("File uploaded successfully");
              console.log(data);
            })
            .catch((error) => {
              app.file_type = null;
              alert("could not upload file");
            });
        },
        playerUpdate: function(data) {
          this.player.push(data);
        },
        checkrequest: function() {
          // $.get('./inc/checkrequest.inc.php?user=' + this.user, function(data) {
          //   notification.setNotification(data.msg, data.type, data.id);
          // });
        },
        sendMessage: function(type = 'txt') {
          console.log('sending')
          // send message to database
          const vm = this;
          const msg = $('#msg-form').val();
          const time = new Date();
          const timeString = `${time.getHours()}:${time.getMinutes() < 10? '0': ''}${time.getMinutes()}`;
          const data = {
            message: msg,
            from: this.user,
            to: this.chatwith
          }
          $.post('./inc/message.inc.php', data, function(data) {
            $('#msg-form').val('');
            $('.messages').animate({
              scrollTop: $('.messages')[0].scrollHeight
            });
          });
        },
        goBack: function() {
          this.chatwith = null
        },
        play: function(id) {
          vm = this;
          const player = document.getElementById('audioPlayer');
          console.log(id)
          if (this.playing != id) {
            player.src = 'inc/' + this.player[id].src;
            this.playing = id;
          }
          if (this.player[id].playing) {
            player.pause();
            this.player[id].playing = false;
          } else {
            player.play();
            this.player[id].playing = true;
            $('#audioPlayer').on('timeupdate', function() {
              vm.progress = (this.currentTime / this.duration) * 100;
              $(('#p-' + vm.playing)).css('width', vm.progress + '%');
            });
          }
        },
        openModal: function(id) {
          // this is a popup which displays
        },
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
      mounted: function() {
        if (_id_user) {
          this.getMessage();
        }
        this.WhoIsOnline();
        this.checkrequest();
        // add eventlistener to #songUpload to upload song by calling handleImageUpload

      }
    })

    const handleUpload = (type, event) => {
      app.file_type = type;
      const files = event.target.files;
      const formData = new FormData();
      formData.append("uploadedFile", files[0]);
      console.log(files, event, type)
      app.sendUpload(formData);
    };
    // document ready
    $(document).ready(function() {
      $('#songUpload').on('change', function(event) {
        handleUpload('mus', event);
      });
      $('#imgUpload').on('change', function(event) {
        handleUpload('img', event);
      });
    });
  </script>
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>