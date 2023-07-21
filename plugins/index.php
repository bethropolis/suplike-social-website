<?php
header('Content-Type: application/json');

require_once __DIR__ . "/load.php";

use Bethropolis\PluginSystem\Info;
use Bethropolis\PluginSystem\Manager;

$info = new Info();

if (isset($_GET["refresh"])) {
    $info->refreshPlugins();
}

if (isset($_GET["plugins"])) {
    $plugins = $info->getPlugins();
    $plugs = [];
    foreach ($plugins as $plugin) {
        $plugs[] = $plugin;
    }
    print_r(json_encode($plugs));
}


if (isset($_POST["install"])) {
    $pluginUrl =  $_POST["url"];
    Manager::installPlugin($pluginUrl);
    $info->refreshPlugins();
}

// uninstall
if (isset($_POST["uninstall"])) {
    $pluginName =  $_POST["name"];
    Manager::uninstallPlugin($pluginName);
    $info->refreshPlugins();
}

//update

if (isset($_POST["update"])) {
    $pluginName =  $_POST["name"];
    $pluginUrl =  $_POST["url"];
    Manager::updatePlugin($pluginName, $pluginUrl);
    $info->refreshPlugins();
}
