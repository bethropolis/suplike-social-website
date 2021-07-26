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

  public function __construct()
  {
    global $conn; 
    $this->conn = $conn; 
    $this->token = bin2hex(openssl_random_pseudo_bytes(21));
    $this->chat_auth = bin2hex(openssl_random_pseudo_bytes(8));
    $this->browser_auth = bin2hex(openssl_random_pseudo_bytes(16));
    $this->user_auth = bin2hex(openssl_random_pseudo_bytes(14));
    $this->api_key = bin2hex(openssl_random_pseudo_bytes(32));
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
      die("auth Error"); 
    }
     $this->user  = (mysqli_fetch_assoc($this->conn->query($sql)))['user'];    
     return $this->user;
  }

  public function _queryUser($id, $type){ 
    $sql = ''; 
    $ty = '';
      switch ($type) {
        case 1:
          //token
          $sql = "SELECT `token` FROM `auth_key` WHERE `user` = '$id'";
            $ty ="token";
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

   $this->user  = (mysqli_fetch_assoc($this->conn->query($sql)))[$ty];  
   
   return $this->user; 
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

}
 
$un_ravel = new Auth(); 
