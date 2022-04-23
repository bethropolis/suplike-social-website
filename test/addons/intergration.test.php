<?php 
$f = file_get_contents('./index.es6');    
print_r($f);    

file_put_contents('es6.php',$f);