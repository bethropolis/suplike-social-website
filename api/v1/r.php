<?php
header('Access-Control-Allow-Origin: *');
header('content-type: application/json');
require "../../../inc/dbh.inc.php";
require "../../../inc/Auth/auth.php";
require "../../../inc/errors/error.inc.php";
$error->_set_log("../../../inc/errors/error.log.txt");
