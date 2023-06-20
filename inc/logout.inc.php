<?php

session_start();
session_unset();
session_destroy();

// delete the cookie
setcookie('token', '', time() - 3600, '/');


if (isset($_GET['acc_deleted'])) {
    header('Location: ../login.php?acc_deleted');
    exit();
}


header('Location: ../login.php');
