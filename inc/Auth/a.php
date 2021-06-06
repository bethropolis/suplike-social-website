<?php
  require '../dbh.inc.php';
  require '../errors/error.inc.php'; 
  require 'auth.php';
  

  $er = new Err();
  $er->_set_log('../errors/error.log.txt');
  $er->err(2,3,"The testing is not completely ready");



  



  