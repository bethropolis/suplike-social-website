<?php
$setup = json_decode(file_get_contents('./setup.suplike.json'));
if($setup->setup){
    die("already setup");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>setup suplike</title>
    <style>
        form {
            position: relative;
            top: 20px;
            margin: auto;
            background: #eee;
            padding: 10px 5px;
            width: 70%;
            display: grid;
            grid-gap: 8px;
            grid-template-columns: 50%;
            justify-content: center;
            margin: 0 auto;
        }

        input {
            width: 200px;
            padding: .7rem;
            border: none;
            font-size: 1.1em;
            font-weight: 700;
        }

        .submit {
            padding: 9px;
            background-color: #8d55e8;
            color: white;
            font-size: 1.3em;
        }

        .submit:active {
            opacity: .7;
        }
    </style>
</head>

<body>
    <form action="setup.inc.php" method="post">
        <h3>Enter database credentials</h3>
        <input type="text" placeholder="server name..." value="localhost" name="server" />
        <input type="text" placeholder="username..." value='root' name="name" />
        <input type="text" placeholder="password..." value='' title="leave empty if none" name="pwd" />
        <h3>Create admin account</h3>
        <input type="text" name="user" placeholder="username...">
        <input type="email" name="mail" placeholder="email...">
        <input type="password" name="pass" placeholder="password...">
        <input type="submit" class="submit" value="submit" />
    </form>
</body>

</html>