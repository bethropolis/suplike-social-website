<?php
require 'inc/setup/env.php';
session_start();

if (isset($_SESSION['userId'])) {

    header("Location: home");
    exit();
} else {
    if (defined('SETUP')) {
        if (!SETUP) {
            header("Location: ./inc/setup/");
            exit();
        }
    }
    header("Location: login");
    exit();
}
