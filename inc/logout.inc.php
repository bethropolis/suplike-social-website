<?php

session_start();
session_unset();
session_destroy();
if(isset($_GET['acc_deleted'])) {
header('Location: ../login.php?acc_deleted');
exit();
}

header('Location: ../login.php');  
