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
    let theme = localStorage.getItem('theme') || '<?= defined('DEFAULT_THEME') ? DEFAULT_THEME : 'light' ?>';
    if (theme == 'dark') {
      let css = `:root{--bg:#1a1a1a!important;--co:#f8f9fc!important;--ho: #a89ef5;--ac:rgba(50, 159, 192, 0.844)!important;--inp:rgb(41, 38, 38)!important;--light:#f8f9fa!important;--dark:#333!important;--msg-message:#969eaa!important;--chat-text-bg:#ededf8!important;--chat-text-owner:var(--ho)!important;--theme-color:#00ffff!important;--msg-date:#c0c7d2!important;--theme-1:#1a1a1a!important;--theme-2:#212121!important;--theme-3:#333333!important;--theme-4:#444444!important;--theme-5:#555555!important;--theme-6:#666666!important;--theme-7:#777777!important;--theme-8:#888888!important;--theme-9:#999999!important}
                .co{color: var(--co) !important}.st-1{background-color:var(--theme-1)!important;color:var(--co)}.st-2{background-color:var(--theme-2)!important;color:var(--co)}.st-3{background-color:var(--theme-3)!important;color:var(--co)}.st-4{background-color:var(--theme-4)!important;color:var(--co)}.st-5{background-color:var(--theme-5)!important;color:var(--co)}.st-6{background-color:var(--theme-6)!important;color:var(--co)}.st-7{background-color:var(--theme-7)!important;color:var(--co)}.st-8{background-color:var(--theme-8)!important;color:var(--co)}.st-9{background-color:var(--theme-9)!important;color:var(--co)}`
      let style = document.createElement('style');
      style.type = 'text/css';
      style.appendChild(document.createTextNode(css));
      document.head.appendChild(style);
    }

    let defaultAccentColor = '<?= (defined('ACCENT_COLOR') && ACCENT_COLOR ? ACCENT_COLOR : null) ?? null  ?>';
    if (localStorage.getItem('color') || defaultAccentColor) {
      let setColor = localStorage.getItem('color') || defaultAccentColor;
      document.documentElement.style.setProperty('--ho', setColor);
      document.documentElement.style.setProperty('--nav', setColor);
    }
  </script>
</head>

<body class="st-1">
  <div id="app">
    <nav class="st-4" style="height:40px;">
      <a href="./">
        <svg width="40px" height="40px" viewBox="0 0 200 200" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg">
          <g id="Artboard-2" clip-path="url(#clip_1)">
            <g id="SL" fill="var(--ho)" transform="translate(0 -24)">
              <path d="M76.5906 180.853L75.9625 183.68Q86.9977 182.02 101.353 182.02C107.864 182.02 121.666 182.964 142.759 184.852Q152.134 185.73 158.091 185.73Q170.103 185.73 177.134 179.578Q184.166 173.426 184.166 164.93Q184.166 160.438 182.115 157.85Q180.064 155.262 177.134 155.262Q174.595 155.262 173.033 156.781Q171.47 158.301 171.47 160.95Q171.47 162.813 172.74 165.951Q174.107 169.285 174.107 171.148Q174.107 174.09 171.519 176.394Q168.931 178.699 165.22 178.699Q161.412 178.699 151.939 176.453Q141.002 173.914 136.607 173.279Q132.212 172.645 127.037 172.645Q118.736 172.645 104.771 175.672Q108.677 171.18 111.412 164.344Q115.231 154.773 122.575 125.574Q127.276 107.215 130.311 99.207Q134.994 87.3906 139.335 81.3848Q143.675 75.3789 147.48 73.2305Q150.212 71.668 153.041 71.668Q155.479 71.668 158.064 72.9375Q160.649 74.207 165.332 78.3086Q169.526 82.1172 172.453 82.1172Q174.696 82.1172 176.306 80.457Q177.916 78.7969 177.916 76.4531Q177.916 72.2539 172.642 68.3477Q167.369 64.4414 159.654 64.4414Q147.642 64.4414 135.875 73.0352Q124.107 81.6289 116.197 99.4023Q111.021 111.219 104.673 138.074Q99.8883 158.387 97.1051 164.881Q94.3219 171.375 90.8551 173.914Q87.772 176.172 79.3213 177.967Q81.0444 175.966 82.4707 173.798Q88.9648 163.927 88.9648 153.275Q88.9648 146.042 84.6802 138.42Q80.3955 130.797 66.6656 117.31Q59.1675 109.784 56.9763 105.581Q54.7852 101.378 54.7852 96.1995Q54.7851 86.5239 62.7739 78.7542Q70.7626 70.9844 81.4789 70.9844Q88.3957 70.9844 92.44 75.3301Q96.4844 79.6758 96.4844 87.1953Q96.4844 92.4688 95.4102 102.723L100.488 102.723Q102.539 86.0234 103.711 80.4082Q104.883 74.793 107.031 68.4453Q103.32 67.957 98.2422 66.9805Q89.3555 65.125 83.3008 65.125Q71.1914 65.125 61.6699 70.4511Q52.1484 75.7772 46.1914 84.7692Q40.2344 93.7612 40.2344 102.654Q40.2344 107.052 41.6992 111.499Q43.1641 115.946 46.1914 120.344Q48.3398 123.374 56.6406 131.583Q65.0391 139.988 67.4805 143.311Q70.9961 148.198 72.3145 152.058Q73.6328 155.918 73.6328 159.827Q73.6328 171.262 63.1836 180.694Q52.7344 190.125 38.7695 190.125Q34.082 190.125 30.127 188.221Q26.1719 186.316 23.9258 183.338Q21.6797 180.359 20.1172 173.719Q18.75 168.25 17.3828 166.688Q15.2344 164.051 11.9141 164.051Q8.69141 164.051 6.20117 166.883Q3.71094 169.715 3.71094 174.012Q3.71094 182.02 13.2324 189.246Q22.7539 196.473 36.6211 196.473Q50.6836 196.473 63.3301 190.071Q71.1307 186.122 76.5906 180.853Z" />
            </g>
          </g>
        </svg>
      </a>
      <div v-if="chatwith_detail && chatwith" id="title" class="row center" style="align-items: center;">
        <a :href="'profile.php?id='+chatwith_detail.token">
          <img class="msg-profile" :src="'img/'+chatwith_detail.profile_picture" alt="" style="width:32px; height: 32px;" onerror="this.error = null; this.src ='img/default.jpg' "></a>
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
        <img class="msg-profile" :src="'img/'+user.profile_picture" alt="" onerror="this.error = null; this.src ='img/default.jpg' ">
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
            <img class="msg-profile" :src="'img/'+user.profile_picture" alt="" onerror="this.error = null; this.src ='img/default.jpg' ">
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