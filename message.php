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
  <link rel="shortcut icon" href="img/icon/favicon.ico" type="image/x-icon" />
  <link rel="stylesheet" href="./lib/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="./lib/font-awesome/font-awesome.min.css">
  <link rel="stylesheet" href="./css/chat.css?kk">
  <script type="text/javascript" src="./lib/jquery/jquery.js"></script>
  <script src="./lib/vue/vue.min.js"></script>
  <script>
    if (localStorage.getItem('theme') == 'dark') {
      let css = `:root{--bg:#333!important;--co:#fff!important;--ho:#a89ef5;--ac:rgba(50, 159, 192, 0.844)!important;--inp:rgb(214, 211, 211)!important;--light:#f8f9fa!important;--dark:#333!important;--msg-message:#969eaa!important;--chat-text-bg:#f1f2f6!important;--chat-text-owner:hsl(249, 85%, 71%)!important;--theme-color:#0086ff!important;--msg-date:#c0c7d2!important;--theme-1:#1a1d21!important;--theme-2:#212529!important;--theme-3:#343a40!important;--theme-4:#495057!important;--theme-5:#6c757d!important;--theme-6:#adb5bd!important;--theme-7:#ced4da!important;--theme-8:#dee2e6!important;--theme-9:#f8f9fa!important}.st-1{background-color:var(--theme-1)!important;color:var(--co)}.st-2{background-color:var(--theme-2)!important;color:var(--co)}.st-3{background-color:var(--theme-3)!important;color:var(--co)}.st-4{background-color:var(--theme-4)!important;color:var(--co)}.st-5{background-color:var(--theme-5)!important;color:var(--co)}.st-6{background-color:var(--theme-6)!important;color:var(--co)}.st-7{background-color:var(--theme-7)!important;color:var(--co)}.st-8{background-color:var(--theme-8)!important;color:var(--co)}.st-9{background-color:var(--theme-9)!important;color:var(--co)}`
      let style = document.createElement('style');
      style.type = 'text/css';
      style.appendChild(document.createTextNode(css));
      document.head.appendChild(style);
    }
  </script>
</head>

<body class="st-1">
  <div id="app">
    <nav class="st-4" style="height:40px;">
      <a href="./">
        <img title="go to homepage" src="img/logo.png" alt="logo" style="width:35px; height: 35px;">
      </a>
      <div v-if="chatwith_detail && chatwith" id="title" class="row center" style="align-items: center;">
        <img class="msg-profile" :src="'img/'+chatwith_detail.profile_picture" alt="" style="width:32px; height: 32px;"
          onerror="this.error = null; this.src ='img/M.jpg' ">
        <div class="msg-username">{{chatwith_detail.full_name}}</div>
      </div>
      <div class="nav-content">
        <i @click="goBack" class="fa fas text-dark fa-arrow-left toShow fa-2x" v-show="chatwith != null"></i>
        <a href="./" class="home">
          <i class="fa fas text-dark  fa-home fa-2x"></i>
        </a>
        <a href="inc/logout.inc.php" class="log-out">
          <i class="fa fas text-dark  fa-sign-out fa-2x"></i>
        </a>
      </div>
    </nav>


    <!--    list of people  -->
    <div v-if="chatwith==null" class=" st-3 conversation-area">
      <h4>users</h4>
      <div v-for="(user,index) in online" @click="startChat(index)" class="msg st-4" :class="user.online? 'online': ''">
        <img class="msg-profile" :src="'img/'+user.profile_picture" alt=""
          onerror="this.error = null; this.src ='img/M.jpg' ">
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
    <div class="message-box box row st-5 p-0" v-if="chatwith != null">
      <div class="col-3 yellow center toHide p-0">
        <ul class="center col-12 p-0 side-list">
          <i @click="goBack" class="fa fas fa-arrow-left fa-2x"></i>
          <div v-for="(user,index) in online" @click="startChat(index)" class="msg  st-4"
            :class="user.online? 'online': ''">
            <img class="msg-profile" :src="'img/'+user.profile_picture" alt=""
              onerror="this.error = null; this.src ='img/M.jpg' ">
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
                <span class="msg-date" v-if="user.type !== ''">{{user.time}}</span>
              </div>
            </div>
          </div>
        </ul>
      </div>

      <div class="col-9 pl-0 pt-2  chat-area st-4 direct-message">
        <div class="chat-area-main p-0 st-4 messages">
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
                  <!-- <div class="progress-bar progress-bar-striped progress-bar-animated" style="background-color: var(--pink);" :id="'p-'+msg.audio_id" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"> -->
                  <progress class="progress-bar progress-bar-striped progress-bar-animated" style="color: var(--pink);"
                    :id="'p-'+msg.audio_id" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                    aria-valuemax="100"></progress>
                  <!-- </div> -->
                </div>

                <i @click="play(msg.audio_id)" class="fa col-2 my-auto "
                  :class="msg.audio_id == playing ?'fa-pause':'fa-play'"></i>
              </div>
              <div v-if="msg.type == 'img'" class="msg-image-wrapper ">
                <img :src="'inc/'+msg.message" alt="msg.message" class="msg-image " loading="lazy">
              </div>
            </div>
          </div>
          <span class="text-muted" id="upload_status"></span>
          <br><br><br><br><br>
        </div>
        <audio id='audioPlayer'></audio>
        <form @submit.prevent="sendMessage" class="form-inline row st-4 light message-form" method="post">
          <!-- improve this form, it contains an image, audio and text input fields -->
          <label for="imgUpload" class="col-1"><input class="hide" type="file" id="imgUpload" accept="image/*"><i
              class="fa fa-image"></i></label>
          <label for="songUpload" class="col-1"><input class="hide" type="file" id="songUpload" accept="audio/*">
            <i class="fa fa-music "></i></label>
          <div class="col-8 p-0">
            <input type="text" class="form-input text-dark" placeholder="enter message..." id="msg-form"
              autocomplete="off" autofocus="true">
          </div>
          <div class="col-1 p-0">
            <button type="submit" class="btn btn-send"><i class="fa fa-send"></i></button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    _id_user = "<?= $to ?>" || null;
    _token = "<?= $_SESSION['token'] ?>";
    // check local storage for chat-theme, if it's dark set body to dark use JQuery
    if (localStorage.getItem('chat-theme') == 'dark') {
      $('body').addClass('dark-theme');
    }

    $(document).ready(function () {
      $('#msg-form').keypress(function (e) {
        if (e.which == 13) {
          $('#msg-form').submit();
        }
      });
    });

  </script>
  <script src="./js/chat.js"></script>
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>