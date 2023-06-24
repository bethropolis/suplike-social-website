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

  /**
   * Retrieves the user associated with the given token, chat token, browser token,
   * user token, or API key.
   *
   * @param string $str The token, chat token, browser token, user token, or API key.
   * @throws Exception If an error occurs while querying the database.
   * @return string|null The user associated with the given token, chat token, browser token,
   * user token, or API key, or null if no user is found.
   */
  public function _getUser($str)
  {
    $length = strlen($str);
    $sql = '';
    switch ($length) {
      case 42:
        //token
        $sql = "SELECT `user` FROM `auth_key` WHERE `token` = ?";
        break;
      case 16:
        //chat token
        $sql = "SELECT `user` FROM `auth_key` WHERE `chat_auth` = ?";
        break;
      case 32:
        //browser token
        $sql = "SELECT `user` FROM `auth_key` WHERE `browser_auth` = ?";
        break;

      case 28:
        //user token 
        $sql = "SELECT `user` FROM `auth_key` WHERE `user_auth` = ?";
        break;

      case 64:
        //api key 
        $sql = "SELECT `user` FROM `auth_key` WHERE `api_key` = ?";
        break;
      default:
        die("auth Error");
    }
    $stmt = mysqli_prepare($this->conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $str);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $this->user);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $this->user;
  }


  public function _queryUser($id, $type = 1)
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
        die("auth Error");
    }

    $this->user = (mysqli_fetch_assoc($this->conn->query($sql)))[$ty];

    return $this->user;
  }
  public function _username($id)
  {
    $sql = "SELECT `uidusers` FROM `users` WHERE `idusers` = '$id'";
    $this->user = (mysqli_fetch_assoc($this->conn->query($sql)))['uidusers'];
    return $this->user;
  }
  public function _isValid($var)
  {
    $length = strlen($var);
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

  public function _isAuth()
  {
    if (isset($_SESSION['userId'])) {
      if (!$this->_isStatus($_SESSION['userId'], "blocked")) {
        return true;
      }
    }

    die(json_encode(
      [
        'code' => 4,
        'msg' => "You are not logged in",
        'type' => 'error'
      ]
    ));
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
  public function _isFollower($user, $follower)
  {
    $sql = "SELECT `user` FROM `following` WHERE `user` = '$follower' AND `following` = '$user'";
    $result = mysqli_fetch_assoc($this->conn->query($sql));
    if ($result) {
      return true;
    } else {
      return false;
    }
  }
  public function _follow($user, $following)
  {
    $sql = "INSERT INTO `following` (`user`, `following`) VALUES ('$user', '$following')";
    $this->conn->query($sql);
    // update following count in users table
    $sql = "UPDATE `users` SET `following` = `following` + 1 WHERE `idusers` = '$user'";
    $this->conn->query($sql);
    // update followers count in users table
    $sql = "UPDATE `users` SET `followers` = `followers` + 1 WHERE `idusers` = '$following'";
    $this->conn->query($sql);
  }
  public function _isAdmin($user)
  {
    $is_admin = '';
    $sql = "SELECT `isAdmin` FROM `users` WHERE `idusers` = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $user);
    $stmt->execute();
    $stmt->bind_result($is_admin);
    $stmt->fetch();
    $stmt->close();
    return (bool) $is_admin;
  }
  public function _isStatus($user, $status)
  {
    $db_status = '';
    $sql = "SELECT `status` FROM `users` WHERE `idusers` = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $user);
    $stmt->execute();
    $stmt->bind_result($db_status);
    $stmt->fetch();
    $stmt->close();
    return $db_status == $status;
  }
  public function _isEmail_verified($user)
  {
    $emailVerified = '';
    $sql = "SELECT `email_verified` FROM `users` WHERE `idusers` = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $user);
    $stmt->execute();
    $stmt->bind_result($emailVerified);
    $stmt->fetch();
    $stmt->close();

    return (bool) $emailVerified;
  }

  public function _isBot($to = null)
  {
    if ($to) {
      $this->user = $to;
    }
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
