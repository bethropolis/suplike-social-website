<?php
header('Content-Type: application/json');
session_start();
include_once 'dbh.inc.php';
include_once 'Auth/email.php';
include_once 'errors/error.inc.php';



function response($msg, $type, $code)
{
  $response = array(
    "msg" => $msg,
    "type" => $type,
    "code" => $code
  );
  print_r(json_encode($response));
}

if (!EMAIL_VERIFICATION) {
  $error->err('Email disabled', 21, "email has been disabled by site's admin");
  die();
}
// Handle the forgot password form submission
if (isset($_POST['forgot_password'])) {
  $email = $_POST['forgot_password'];

  // Check if the email exists in the users table
  $sql = "SELECT * FROM users WHERE emailusers=?;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    // If there's an error with the SQL statement, display an error message and exit
    $error->err("Server error", 21, "There was a server error.");
    //header('Location: forgot_password.php');
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$row = mysqli_fetch_assoc($result)) {
      // If the email doesn't exist in the users table, display an error message
      $error->err("Email error", 32, "There is no user with that email address.");
      //header('Location: ../forgot_password.php');
      exit();
    }
  }
  $sql = "SELECT * FROM password_reset WHERE email=? AND expires_at > DATE_SUB(NOW(), INTERVAL 3 MINUTE)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows !== 0) {
    // If a reset email has been sent in the last 3 minutes, display an error message and exit
    $error->err("Reset sent", 35, "A password reset email has already been sent. Please check your email (including your spam folder) and wait at least 3 minutes before requesting another reset.");
    exit();
  }
  // Generate a unique password reset token and store it in the password_reset table
  $token = bin2hex(random_bytes(32)); // Generate a random token
  $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour')); // Set the expiration date/time to 1 hour from now
  $sql = "INSERT INTO password_reset (email, token, expires_at) VALUES (?, ?, ?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    // If there's an error with the SQL statement, display an error message and exit
    $error->err("Server error", 21, "There was a server error.");
    
    //header('Location: ../forgot_password.php');
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "sss", $email, $token, $expires_at);
    mysqli_stmt_execute($stmt);
  }

  // Send an email to the user with a link to reset their password
  $reset_password_url = BASE_URL."/forgot_password.inc.php?token=" . $token;
  $message = "<html><body>";
  $message .= "<p>Hi,</p>";
  $message .= "<p>You recently requested to reset your password for your account with us. Click the button below to reset it.</p>";
  $message .= '<a href="' . $reset_password_url . '" style="background:#6c5ce7 ;background-color: '.ACCENT_COLOR.'; border: none; color: white; padding: 12px 28px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; border-radius: 5px;">Reset your password</a>';
  $message .= "<p>If you did not request a password reset, please ignore this email or contact us if you have any questions.</p>";
  $message .= "<p>Best regards,<br>Suplike</p>";
  $message .= "</body></html>";

  send_email($email, 'Suplike: reset password', $message);
  response("An email has been sent to your email address with instructions on how to reset your password.", "success", 11);
  exit();
}


if (isset($_GET["token"])) {
  // Check if the reset token is valid
  $token = $_GET["token"];
  $sql = "SELECT * FROM password_reset WHERE token=? AND expires_at > NOW()";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $token);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 0) {
    header("Location: ../forgot_password.php?error=Invalid token. Please try again.");
    exit();
  }

  // Get the user ID associated with the reset token
  $row = $result->fetch_assoc();
  $user_id = $row['email'];

  // Check if the submitted form is valid
  if (isset($_POST['reset-password-submit'])) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($password) || empty($confirm_password)) {
      response("password field cannot be empty", "error", 36);
      exit();
    } else if ($password !== $confirm_password) {
      response("the two passwords don't match", "error", 34);
      exit();
    }

    // Hash the new password and update the user's password in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET pwdUsers=? WHERE emailusers=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hashed_password, $user_id);
    $stmt->execute();

    // Delete the reset token from the database
    $sql = "DELETE FROM password_reset WHERE token=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    response("password has been reset", "error", 31);
    header("Location: ../login.php?success=passwordreset");
    exit();
  }

  if (isset($_GET["error"])) {
    echo "<h1 style='color:red;'>There was an error, pls contact admin or try again <h1>";
    die();
  }
  header('Content-Type: text/html; charset=utf-8');
?>

  <!DOCTYPE html>
  <html>

  <head>
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
      :root {
        --border: 12px;
        --border-width: 1px;
        --bg: #fff;
        --co: #000;
        --ho: #6c5ce7;
        --card: #fff;
        --muted-text: #6d6d6e;
        --purple: rgba(91, 55, 183, 1);
      }

      form {
        max-width: 400px;
        margin: auto;
        padding: 20px;
        border: var(--border-width) solid var(--tab);
        border-radius: calc(var(--border) + var(--border-width));
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: #FFFFFF;
      }

      h1 {
        text-align: center;
        color: #7E4F8A;
      }

      label {
        display: block;
        margin-bottom: 10px;
        color: var(--muted-text);
      }

      input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: none;
        border-bottom: 2px solid #D9D9D9;
        font-size: 16px;
      }

      input[type="submit"] {
        display: block;
        margin: auto;
        padding: 10px 20px;
        border-radius: 30px;
        color: #FFFFFF;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
      }

      input[type="submit"]:hover {
        background-color: #995FA3;
      }

      html body form {
        font-family: Helvetica;
        font-size: 18px;
        line-height: 30px;
        font-weight: 300;
        font-style: normal;
        background-color: var(--bg);
        margin-top: 40px;
        padding: 2em;
      }

      body form input[type="submit"] {
        background-color: var(--purple);
        border-style: solid;
        border-color: var(--tab);
        text-transform: none;
        text-align: center;
        width: 75%;
      }

      div.loader {
        filter: grayscale(1%);
      }

      body form h1 {
        color: var(--ho);
      }

      body form p {
        color: var(--co);
      }

      body form label,
      a {
        color: var(--ho);
      }

      @media screen and (max-width: 660px) {
        form {
          border: none !important;
        }
      }
    </style>
  </head>

  <body>
    <h1>Reset Password</h1>
    <form method="post" action="./forgot_password.inc.php?token=<? echo $token ?>">
      <label for="new_password">New Password</label>
      <input type="password" name="password" id="new_password" placeholder="new password..." required>

      <label for="confirm_password">Confirm Password</label>
      <input type="password" name="confirm_password" id="confirm_password" placeholder="confirm password..." required>

      <input type="submit" name="reset-password-submit">
    </form>
  </body>

  </html>
<?php
}
?>