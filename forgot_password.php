<?php
require "header.php";
?>
<head>
  <title>Reset Password</title>
  <style>
    :root {
      --border: 12px;
      --border-width: 1px;
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
      color: #7E4F8A;
    }

    input[type="email"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: none;
      border-bottom: 2px solid #D9D9D9;
      font-size: 16px;
    }

    button[type="submit"] {
      display: block;
      margin: auto;
      padding: 10px 20px;
      border-radius: 30px;
      color: #FFFFFF;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover {
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

    body form button {
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
  <form method="post" action="#">
    <h1>Reset Password</h1>
    <p>Please enter your email address to receive a password reset link.</p>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="enter your email address" required>
    <br>
    <button type="submit">Reset Password</button><br />
    <p>remembered password? <a href="login.php" class="ho">login</a></p>
  </form>
  <!-- Add these script tags at the end of the <body> tag -->
  <script src="./lib/jquery/jquery.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    $(document).ready(function () {
      $('form').submit(function (event) {
        event.preventDefault();
        $.ajax({
          url: 'inc/forgot_password.inc.php',
          type: 'POST',
          data: {
            forgot_password: $('#email').val()
          },
          dataType: 'json',
          success: function (response) {
            if (response.type == 'success') {
              Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message,
                showConfirmButton: false,
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: response.message
              });
            }
          },
          error: function () {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Something went wrong!'
            });
          }
        });
      });
    });
  </script>
  <?php
  if (isset($_GET['error'])) {
    $error_message = $_GET['error'];
    echo '<script>Swal.fire({
    icon: "error",
    title: "Oops...",
    text: "' . $error_message . '"
  })</script>';
  }
  ?>

</body>
