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
  <link rel="stylesheet" href="./lib/font-awesome/css/all.min.css">
  <link rel="stylesheet" href="./css/chat.css?kk">
  <link rel="stylesheet" href="./lib/lightbox/css/lightbox.min.css">
  <script type="text/javascript" src="./lib/jquery/jquery.js"></script>
  <script src="./lib/lazyload/lazysizes.min.js" defer></script>
  <script src="./lib/vue/vue.min.js"></script>
  <script>
    if (localStorage.getItem('theme') == 'dark') {
      let css = `:root{--bg:#1a1a1a!important;--co:#f8f9fc!important;--ho:#a080ff;--ac:rgba(50, 159, 192, 0.844)!important;--inp:rgb(41, 38, 38)!important;--light:#f8f9fa!important;--dark:#333!important;--msg-message:#969eaa!important;--chat-text-bg:#ededf8!important;--chat-text-owner:var(--ho)!important;--theme-color:#00ffff!important;--msg-date:#c0c7d2!important;--theme-1:#1a1a1a!important;--theme-2:#212121!important;--theme-3:#333333!important;--theme-4:#444444!important;--theme-5:#555555!important;--theme-6:#666666!important;--theme-7:#777777!important;--theme-8:#888888!important;--theme-9:#999999!important}
                .co{color: var(--co) !important}.st-1{background-color:var(--theme-1)!important;color:var(--co)}.st-2{background-color:var(--theme-2)!important;color:var(--co)}.st-3{background-color:var(--theme-3)!important;color:var(--co)}.st-4{background-color:var(--theme-4)!important;color:var(--co)}.st-5{background-color:var(--theme-5)!important;color:var(--co)}.st-6{background-color:var(--theme-6)!important;color:var(--co)}.st-7{background-color:var(--theme-7)!important;color:var(--co)}.st-8{background-color:var(--theme-8)!important;color:var(--co)}.st-9{background-color:var(--theme-9)!important;color:var(--co)}`
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
        <a :href="'profile.php?id='+chatwith_detail.token">
          <img class="msg-profile" :src="'img/'+chatwith_detail.profile_picture" alt="" style="width:32px; height: 32px;" onerror="this.error = null; this.src ='img/M.jpg' "></a>
        <div>{{chatwith_detail.full_name}}</div>
        <div id="status" class=" text-success ml-1">{{status}}</div>
      </div>
      <div class="nav-content">
        <i @click="goBack" class="fa fas co fa-arrow-left toShow fa-xl" v-show="chatwith != null"></i>
        <a href="./" class="home">
          <i class="fa fas co  fa-home fa-xl"></i>
        </a>
        <a href="inc/logout.inc.php" class="log-out">
          <i class="fa fas co  fa-sign-out fa-xl"></i>
        </a>
      </div>
    </nav>


    <!--    list of people  -->
    <div v-if="chatwith==null" class=" st-3 conversation-area">
      <h4>users</h4>
      <div v-for="(user,index) in online" @click="startChat(index)" class="msg st-4" :class="user.online? 'online': ''">
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
    <div class="message-box box row st-3 p-0 m-0" v-if="chatwith != null">
      <div class="col-3 yellow center toHide p-0">
        <ul class="center col-12 side-list p-0 m-0">
          <div class='mt-2 py-1'>
          </div>
          <div v-for="(user,index) in online" @click="startChat(index)" class="msg st-3" :class="user.online? 'online': ''" tabindex="0">
            <img class="msg-profile" :src="'img/'+user.profile_picture" alt="" onerror="this.error = null; this.src ='img/M.jpg' ">
            <div class="msg-detail col-9 p-0">
              <div class="msg-username text-left">{{user.full_name}}</div>
              <div class="msg-content col-12 small p-0" :class="user.type !== '' ? 'justify-content-between' : ''">
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
                <span class="text-muted" v-if="user.type !== ''">{{user.time}}</span>
              </div>
            </div>
          </div>
        </ul>
      </div>

      <div class="col-9 px-0 chat-area st-4 direct-message">
        <div class="chat-area-main p-0 st-4 messages mx-0">
          <div v-for="(msg, index) in messages" class="chat-msg mt-2" :class="msg.to? 'owner':''">
            <div class="chat-msg-profile">
              <div class="chat-msg-date">{{msg.time}}</div>
            </div>
            <div class="chat-msg-content">
              <div v-if="msg.type == 'txt'">
                <div class="chat-msg-text">{{msg.message}}</div>
              </div>
              <div v-if="msg.type == 'mus'" class="row chat-msg-text center mx-1">
                <div class="progress col-10" style="background-color: transparent;">
                  <!-- <div class="progress-bar progress-bar-striped progress-bar-animated" style="background-color: var(--pink);" :id="'p-'+msg.audio_id" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"> -->
                  <progress class="progress-bar progress-bar-striped progress-bar-animated" style="color: var(--pink);" :id="'p-'+msg.audio_id" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></progress>
                  <!-- </div> -->
                </div>

                <i @click="play(msg.audio_id)" class="fa col-2 my-auto " :class="msg.audio_id == playing ?'fa-pause':'fa-play'"></i>
              </div>
              <div v-if="msg.type == 'img'" class="msg-image-wrapper ">
                <a :href="'inc/'+msg.message" :data-lightbox="index">
                  <img :data-src="'inc/'+msg.message" class="lazyload msg-image " alt="msg.message" loading="lazy">
                </a>
              </div>
            </div>
          </div>
          <span class="text-muted" id="upload_status"></span>
          <br><br><br><br><br>
        </div>
        <audio id='audioPlayer'></audio>
        <form @submit.prevent="sendMessage" class="form-inline row st-4 light message-form" method="post">
          <!-- improve this form, it contains an image, audio and text input fields -->
          <label for="imgUpload" class="col-1" tabindex='0'><input class="hide" type="file" id="imgUpload" accept="image/*"><i class="fa fa-image"></i></label>
          <label for="songUpload" class="col-1" tabindex='0'><input class="hide" type="file" id="songUpload" accept="audio/*"><i class="fa fa-music "></i></label>
          <div class="col-8 p-0">
            <input type="text" class="form-input co" placeholder="enter message..." id="msg-form" autocomplete="off" autofocus="true">
          </div>
          <div class="col-1 p-0">
            <button type="submit" class="btn btn-send p-0"><i class="fas fa-paper-plane"></i></button>
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

    $(document).ready(function() {
      $('#msg-form').keypress(function(e) {
        if (e.which == 13) {
          $('#msg-form').submit();
        }
      });

      $("body").on("keypress", "[tabindex='0']", function(e) {
        if (e.keyCode == 13 || e.keyCode == 32) {
          // 13=enter, 32=spacebar
          $(this).click();
          return false;
        }
      })

    });
  </script>
  <script src="./js/chat.js"></script>
  <script src="./lib/lightbox/js/lightbox.min.js" defer></script>
</body>

</html>