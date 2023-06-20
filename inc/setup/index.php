<?php
$setup = json_decode(file_get_contents('./setup.suplike.json'));
if ($setup->setup) {
    die("already setup");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="shortcut icon" href="../../img/icon/favicon.ico" type="image/x-icon">
    <title>Suplike setup</title>
    <style>
        body {
            margin: 0;
            font-family: Roboto, sans-serif;
            background-color: #f8f8f8;
        }

        form {
            position: relative;
            top: 20px;
            margin: auto;
            background: white;
            padding: 20px;
            width: 70%;
            display: grid;
            grid-gap: 10px;
            grid-template-columns: 1fr;
            justify-content: center;
            margin: 0 auto;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        input,details {
            width: 90%;
            padding: 12px;
            border: none;
            margin: auto;
            font-size: 1.1em;
            font-weight: 700;
            border-radius: 3px;
        }
        input{   
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #f8f8f8;
        }

        .submit {
            padding: 10px;
            background-color: #8d55e8;
            color: white;
            font-size: 1.3em;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .submit:hover {
            opacity: 0.8;
        }

        h3 {
            margin: 0;
            font-size: 1.2em;
            font-weight: 700;
            color: #8d55e8;
        }
    </style>
</head>

<body>
    <form action="setup.inc.php" method="post">
        <h3>Enter database credentials (MYSQL, MariaDB)</h3>
        <input type="text" placeholder="Server name... (eg localhost)" value="" name="server" required/>
        <input type="text" placeholder="Database Username..." value='' name="name" />
        <input type="password" placeholder="Database Password..." title="Leave empty if none" name="pwd" />
        <details>
            <summary>advanced (optional) </summary>
          <input type="text" name="user" placeholder="database name... (default suplike)" required>
        </details>
        <h3>Create admin account</h3>
        <input type="text" name="user" placeholder="Username..." required>
        <input type="email" name="mail" placeholder="Email...">
        <input type="password" name="pass" placeholder="Password..." required>
        <input type="submit" class="submit" value="Submit" />
    </form>
</body>

</html>