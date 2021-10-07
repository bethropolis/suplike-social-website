<?php
$d = new DateTime(null);
$r = "2021-07-21 18:29:11";
$t = new DateTime($r);
$tm = $d->modify("- $r");

print_r($tm);
