<?php
class Auth
{
  public $token;
  public $chat_auth;
  public $browser_auth;
  public $user_auth;
  public $api_key;
  public $user;
  private $conn;
  public $email;

  public function __construct()
  {
    global $conn;
    $this->conn = $conn;
    $this->token = bin2hex(openssl_random_pseudo_bytes(21));
    $this->chat_auth = bin2hex(openssl_random_pseudo_bytes(8));
    $this->browser_auth = bin2hex(openssl_random_pseudo_bytes(16));
    $this->user_auth = bin2hex(openssl_random_pseudo_bytes(14));
    $this->api_key = bin2hex(openssl_random_pseudo_bytes(32));
    $this->email = bin2hex(openssl_random_pseudo_bytes(24));
  }

  public function _getUser($str)
  {
    $length =  strlen($str);
    $sql = '';
    switch ($length) {
      case 42:
        //token
        $sql = "SELECT `user` FROM `auth_key` WHERE `token` = '$str'";

        break;
      case 16:
        //chat token
        $sql = "SELECT `user` FROM `auth_key` WHERE `chat_auth` = '$str'";
        break;
      case 32:
        //browser token
        $sql = "SELECT `user` FROM `auth_key` WHERE `browser_auth` = '$str'";
        break;

      case 28:
        //user token 
        $sql = "SELECT `user` FROM `auth_key` WHERE `user_auth` = '$str'";
        break;

      case 64:
        //api key 
        $sql = "SELECT `user` FROM `auth_key` WHERE `api_key` = '$str'";
        break;
      default:
        die(print_r(
          json_encode(
            [
              'code' => 21,
              'msg' => 'auth Error',
              'type' => 'error'
            ]
          )
        ));
    }
    $this->user  = (mysqli_fetch_assoc($this->conn->query($sql)))['user'];
    return $this->user;
  }

  public function _queryUser($id, $type)
  {
    $sql = '';
    $ty = '';
    switch ($type) {
      case 1:
        //token
        $sql = "SELECT `token` FROM `auth_key` WHERE `user` = '$id'";
        $ty = "token";
        break;
      case 2:
        //chat token
        $sql = "SELECT `chat_auth` FROM `auth_key` WHERE `user` = '$id'";
        $ty = "chat_auth";
        break;
      case 3:
        //browser token
        $sql = "SELECT `browser_auth` FROM `auth_key` WHERE `user` = '$id'";
        $ty = "browser_auth";
        break;

      case 4:
        //user token  
        $sql = "SELECT `user_auth` FROM `auth_key` WHERE `user` = '$id'";
        $ty = "user_auth";
        break;

      case 5:
        //api key 
        $sql = "SELECT `api_key` FROM `auth_key` WHERE `user` = '$id'";
        $ty = "api_key";
        break;
      default:
        die(print_r(
          json_encode(
            [
              'code' => 21,
              'msg' => 'auth Error',
              'type' => 'error'
            ]
          )
        ));
    }
    try {
      $this->user  = (mysqli_fetch_assoc($this->conn->query($sql)))[$ty];
      return $this->user;
    } catch (Exception $e) {
      die(print_r(
        json_encode(
          [
            'code' => 21,
            'msg' => 'auth Error',
            'type' => 'error'
          ]
        )
      ));
    }
  }
  public function _username($id)
  {
    $sql = "SELECT `uidusers` FROM `users` WHERE `idusers` = '$id'";
    $this->user  = (mysqli_fetch_assoc($this->conn->query($sql)))['uidusers'];
    return $this->user;
  }
  public function _userid($id)
  {
    $sql = "SELECT `idusers` FROM `users` WHERE `uidusers` = '$id'";
    $us  = (mysqli_fetch_assoc($this->conn->query($sql)))['idusers'];
    return $us;
  }

  public function _isValid($var)
  {
    $length =  strlen($var);
    $auth = '';
    switch ($length) {
      case 42:
        //token
        $auth = true;

        break;
      case 16:
        //chat token
        $auth = true;
        break;
      case 32:
        //browser token
        $auth = true;
        break;

      case 28:
        //user token 
        $auth = true;
        break;

      case 64:
        //api key 
        $auth = true;
        break;
      default:
        $auth = false;
    }
    return $auth;
  }
  public function _isFollowing($user, $following)
  {
    $sql = "SELECT `user` FROM `following` WHERE `user` = '$user' AND `following` = '$following'";
    $result = mysqli_fetch_assoc($this->conn->query($sql));
    if ($result) {
      return true;
    } else {
      return false;
    }
  }
  public function  _isFollower($user, $follower)
  {
    $sql = "SELECT `user` FROM `following` WHERE `user` = '$follower' AND `following` = '$user'";
    if (mysqli_fetch_assoc($this->conn->query($sql))) {
      return true;
    } else {
      return false;
    }
  }
  public function _follow($user, $following)
  {
    $sql = "INSERT INTO `following` (`user`, `following`) VALUES ('$user', '$following')";
    $this->conn->query($sql);
  }

  public function _no_followers($user)
  {
    // check if user is following anyone
    $sql = "SELECT `user` FROM `following` WHERE `user` = '$user'";
    $result = mysqli_fetch_assoc($this->conn->query($sql));
    if (!$result) {
      return true;
    }
    return false;
  }
  public function _isAdmin($user)
  {
    $sql = "SELECT `isAdmin` FROM `users` WHERE `idusers` = '$user'";
    $result = $this->conn->query($sql)->fetch_assoc();

    if ($result["isAdmin"]) {
      return true;
    } else {
      return false;
    }
  }
  public function _isEmail_verified($user)
  {
    $sql = "SELECT `email_verified` FROM `users` WHERE `idusers` = $user";
    $result = mysqli_fetch_assoc($this->conn->query($sql));
    if ($result) {
      return true;
    } else {
      return false;
    }
  }
  public function _isBot()
  {
    $sql = "SELECT `isBot` FROM `users` WHERE `idusers` = $this->user ";
    $result = $this->conn->query($sql)->fetch_assoc();
    if ($result['isBot']) {
      return true;
    } else {
      return false;
    }
  }
  public function _profile_picture($user)
  {
    $sql = "SELECT `profile_picture` FROM `users` WHERE `idusers` = '$user'";
    $result = $this->conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['profile_picture'];
  }
  public function _increment_page_visit($user)
  {
    $sql = "UPDATE `users` SET `page_visit` = `page_visit` + 1 WHERE `idusers` = '$user'";
    $this->conn->query($sql);
  }
}

$un_ravel = new Auth();
