<?php
require_once "inc/setup/env.php";
session_start();
if (isset($_COOKIE['token']) && !isset($_SESSION['token']) && !isset($_GET['token'])) {
  header("Location: inc/autologin.php?login");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <?php require_once "template/meta.php" ?>
  <link rel="apple-touch-icon" href="img/icon/apple-touch-icon.png" />
  <link rel="shortcut icon" href="img/icon/favicon.ico" type="image/x-icon" />
  <link rel="stylesheet" href="./lib/font-awesome/css/all.min.css" defer>
  <link rel="stylesheet" href="./lib/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/style.min.css?g">
  <link rel="manifest" href="manifest.json">
  <link rel="stylesheet" href="./lib/lightbox/css/lightbox.min.css">
  <script type="text/javascript" src="./lib/jquery/jquery.js"></script>
  <script src="lib/lazyload/jquery.lazyload-any.js" defer></script>
  <script src="./js/online.min.js" defer></script>
  <script src="registerSW.js"></script>
  <script>
    // load css if localstorage  theme = dark
    let theme = localStorage.getItem('theme') || '<?= defined('DEFAULT_THEME') ? DEFAULT_THEME : 'light' ?>';

    if (theme === 'dark') {
      let css = `:root{--bg:#1f1f1f;--co:#fff;--ho: #a89ef5;--nav: #a89ef5;--option:grey;--light:#333; --dark:#f6f6f6;--white:#333;--icon-dark:var(--icon-light);--card:#292929;--comment-card: var(--card); --tab:#343a40;--muted-text:#dbdbdb;--purple:#a29bfe;--pink:#fd79a8;--yellow:#ffeaa7;--teal:#81ecec;--blue: #74b9ff; }`;
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

<body>
  <div class="loader">
    <progress-ring stroke="4" radius="60" progress="0"></progress-ring>
    <h2>suplike</h2>
  </div>
  <script src="js/loader.min.js"></script>
  <header style="position: sticky;top: 0;z-index: 20;">
    <nav class="navbar sticky-top nav-hide navbar-expand-md p-1">
      <a href="./">
        <svg width="200px" height="200px" viewBox="0 0 200 200" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg">
          <g id="Artboard-2" clip-path="url(#clip_1)">
            <g id="SL" fill="var(--ho)" transform="translate(0 -24)">
              <path d="M76.5906 180.853L75.9625 183.68Q86.9977 182.02 101.353 182.02C107.864 182.02 121.666 182.964 142.759 184.852Q152.134 185.73 158.091 185.73Q170.103 185.73 177.134 179.578Q184.166 173.426 184.166 164.93Q184.166 160.438 182.115 157.85Q180.064 155.262 177.134 155.262Q174.595 155.262 173.033 156.781Q171.47 158.301 171.47 160.95Q171.47 162.813 172.74 165.951Q174.107 169.285 174.107 171.148Q174.107 174.09 171.519 176.394Q168.931 178.699 165.22 178.699Q161.412 178.699 151.939 176.453Q141.002 173.914 136.607 173.279Q132.212 172.645 127.037 172.645Q118.736 172.645 104.771 175.672Q108.677 171.18 111.412 164.344Q115.231 154.773 122.575 125.574Q127.276 107.215 130.311 99.207Q134.994 87.3906 139.335 81.3848Q143.675 75.3789 147.48 73.2305Q150.212 71.668 153.041 71.668Q155.479 71.668 158.064 72.9375Q160.649 74.207 165.332 78.3086Q169.526 82.1172 172.453 82.1172Q174.696 82.1172 176.306 80.457Q177.916 78.7969 177.916 76.4531Q177.916 72.2539 172.642 68.3477Q167.369 64.4414 159.654 64.4414Q147.642 64.4414 135.875 73.0352Q124.107 81.6289 116.197 99.4023Q111.021 111.219 104.673 138.074Q99.8883 158.387 97.1051 164.881Q94.3219 171.375 90.8551 173.914Q87.772 176.172 79.3213 177.967Q81.0444 175.966 82.4707 173.798Q88.9648 163.927 88.9648 153.275Q88.9648 146.042 84.6802 138.42Q80.3955 130.797 66.6656 117.31Q59.1675 109.784 56.9763 105.581Q54.7852 101.378 54.7852 96.1995Q54.7851 86.5239 62.7739 78.7542Q70.7626 70.9844 81.4789 70.9844Q88.3957 70.9844 92.44 75.3301Q96.4844 79.6758 96.4844 87.1953Q96.4844 92.4688 95.4102 102.723L100.488 102.723Q102.539 86.0234 103.711 80.4082Q104.883 74.793 107.031 68.4453Q103.32 67.957 98.2422 66.9805Q89.3555 65.125 83.3008 65.125Q71.1914 65.125 61.6699 70.4511Q52.1484 75.7772 46.1914 84.7692Q40.2344 93.7612 40.2344 102.654Q40.2344 107.052 41.6992 111.499Q43.1641 115.946 46.1914 120.344Q48.3398 123.374 56.6406 131.583Q65.0391 139.988 67.4805 143.311Q70.9961 148.198 72.3145 152.058Q73.6328 155.918 73.6328 159.827Q73.6328 171.262 63.1836 180.694Q52.7344 190.125 38.7695 190.125Q34.082 190.125 30.127 188.221Q26.1719 186.316 23.9258 183.338Q21.6797 180.359 20.1172 173.719Q18.75 168.25 17.3828 166.688Q15.2344 164.051 11.9141 164.051Q8.69141 164.051 6.20117 166.883Q3.71094 169.715 3.71094 174.012Q3.71094 182.02 13.2324 189.246Q22.7539 196.473 36.6211 196.473Q50.6836 196.473 63.3301 190.071Q71.1307 186.122 76.5906 180.853Z" />
            </g>
          </g>
        </svg>
      </a>
      <button class="navbar-toggler collapsed" style="outline: none; border: none;" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
      </button>
      <div class="navbar-collapse justify-content-right collapse" id="navbarSupportedContent">
        <?php
        // repeat but a better readable code
        if (isset($_SESSION['token'])) {
        ?>
          <ul class="navbar-nav ml-auto mr-1">
            <?php
            if ($_SESSION['isAdmin'] == 1) {
              echo '<div id="header-addons">';
              echo '<a href="./dashboard/"><i title="dashboard" class="fas fa-user-shield fa-2x"></i></a> ';
              echo '</div>';
            }
            ?>
            <a href="message.php"><i title="messages" class="fa fa-envelope fa-2x"></i></a>
            <a href="settings.php?profile"><i title="settings" class="fa fa-cog fa-2x"></i></a>
            <a href="inc/logout.inc.php"><i id="logout" title="logout" class="fa fa-sign-in-alt fa-2x"></i></a>
          </ul>
        <?php
        } else { ?>
          <ul class="navbar-nav ml-auto mr-1 nav nav-pills">
            <a class="flex-sm-fill text-sm-center nav-link" href="./login">login</a>
            <a class="flex-sm-fill text-sm-center nav-link text-white bg" href="./signup">signup</a>
          </ul>
        <?php
        }
        ?>
      </div>
    </nav>

  </header>
  <?php
  if (isset($_SESSION['userId'])) {
  ?>
    <div class="text-right w-100 h4 nav-show px-1 mx-0">
      <div class="w-100 text-right">
        <?php
        $pages_array = ['settings', 'post', 'comment'];
        $pages_array_2 = ['home'];
        $pages_array_3 = ['social', 'message', 'notification', 'search'];
        $pages_array_4 = ['profile'];
        $current_page = basename($_SERVER['PHP_SELF']);
        $current_page = str_replace('.php', '', $current_page);
        $back_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : './';
        // if current page is in the array
        ?>

        <?php
        if (in_array($current_page, $pages_array)) {

        ?>

          <a href="<?= $back_url ?>" id="back">
            <i class="fa fa-arrow-left"></i>
          </a>

          <a href="home">
            <i class="fa fa-home"></i>
          </a>

        <?php
        } else if (in_array($current_page, $pages_array_2)) {
        ?>
          <a href="post.php"><i class="fas fa-plus mx-1"></i></a>
          <?php
          if ($_SESSION['isAdmin'] == 1) {
          ?>
            <a href="./dashboard/">
              <i class="fas fa-user-shield"></i>
            </a>
          <?php
          }
          ?>
          <a href="message" class="no-style"><i class="fas fa-envelope mx-1"></i></a>
        <?php
        } else if (in_array($current_page, $pages_array_3)) {
        ?>
          <a href='<?= $_SERVER['HTTP_REFERER'] ?? '#' ?>' id="back">
            <i class="fas fa-arrow-left"></i>
          </a>
        <?php
        } else if (in_array($current_page, $pages_array_4)) {
        ?>
          <a href="settings" class="no-style"><i class="fas fa-cog mx-1"></i></a>
          <a href="inc/logout.inc.php" class="no-style"><i class="fas fa-sign-in-alt mx-1"></i></a>
        <?php
        }
        ?>
      </div>
    </div>
  <?php
  }
  ?>