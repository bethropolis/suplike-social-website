<?php
     $file = $_POST['file']; 
     $css = "../../../reset/css/dashboard-style.css";
     $main = "../../../dashboard/dashboard.css";
    $url = "../../intergrations/" ;

    if ( is_file ($file)){
        $r = file_get_contents($file);
        $ql = $r["name"];
        $qr = $r["action"]["css-root"];
        $qc = file_get_contents($url.$ql."/".$qr); 

        $s = file_get_contents($css);
        $main = file_get_contents($main);
        $main = $r.$qc; 
    }