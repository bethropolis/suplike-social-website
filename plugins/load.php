<?php
require_once "../inc/dbh.inc.php";
require_once "../inc/Auth/auth.php";
require_once "../inc/errors/error.inc.php";
require __DIR__."/PluginSystem/autoload.php";

use Bethropolis\PluginSystem\System;

$dir = __DIR__."/intergrations/";
System::loadPlugins($dir);