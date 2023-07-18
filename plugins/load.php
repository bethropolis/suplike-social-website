<?php

require __DIR__."/PluginSystem/autoload.php";

use Bethropolis\PluginSystem\System;

$dir = __DIR__."/intergrations/";
System::loadPlugins($dir);