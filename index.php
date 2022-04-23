<?php
session_start();
if (isset($_SESSION['userId'])) {

    header("Location: home");
    exit();
}
else {

    header("Location: login");
    exit();
}