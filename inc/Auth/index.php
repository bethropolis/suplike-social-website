<?php
require "../dbh.inc.php";
require "../errors/error.inc.php";
session_start();

if (!isset($_SESSION['userId'])) {
    $error->err('unkown', 7);
    header('Location: ./login.php');
    exit();
}

if (isset($_GET["auth"])) {
    $auth = $_GET["auth"];
} else {
    $error->err($_SESSION['userId'], 7);
    die("<h1> Cannot authenticate app </h1>");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>suplike Auth</title>
    <style>
        body{
            line-height: 1.6;
        }
        .logo {
            background: url("../../img/logo.png");
            background-repeat: no-repeat;
            border-radius: 50%;
            width: 160px;
            height: 160px;
            margin: 20px auto;

        }

        label {
            font-style: bold;
            font-size: 1.2rem;
            font-family: arial-black, monospace;
        }

        #user {
            text-align: center;
        }

        .container {
            width: 80%;
            max-width: 600px;
            margin: 1em auto;
        }

        .allow_button {
            width: 100%;
            text-align: center;
        }

        details {
            font-size: 20px;
        }

        details li {
            font-size: 18px;
        }

        p {
            font-size: 16px;
        }

        .allow {
            padding: 10px;
            width: 70%;
            max-width: 15rem;
            color: white;
            background: hsl(256, 80%, 58%);
            border: none;
            border-radius: 1.2rem;
        }
        .allow:hover,.allow:focus{
            transform: scale(.9);
            opacity: .7;
        }
    </style>
</head>

<body>
    <div class="logo"></div>
    <div class="container">
        <div id="user">
            <h3>account: {{account}} <br></h3>
        </div>
        <label for="request">the app requests The following access</label>
        <ul>
            <details open>
                <summary>account access</summary>
                <li>user acount</li>
                <li>see your email</li>
                <li>user acount</li>
            </details>

        </ul><br /><br />
        <br />
        <p>we recommend that you click the button below only if you trust the app</p>
        <div class="allow_button">
            <button class="allow">authorise {{app}}</button>
        </div>
    </div>

</body>

</html>