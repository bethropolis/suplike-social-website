<?php
session_start();
if (!isset($_SESSION['token']) || !$_SESSION['isAdmin']) {
    // not authorised http status code
    header('HTTP/1.1 401 Unauthorized');
    exit();
}

header('Content-Type: application/json');


require_once __DIR__ . "/load.php";

use Bethropolis\PluginSystem\Info;
use Bethropolis\PluginSystem\Manager;

$info = new Info();

Manager::initialize();


if (isset($_GET["refresh"])) {
    $info->refreshPlugins();
}

if (isset($_GET["get"])) {
    $plugins = $info->getPlugins();
    $plugs = [];
    foreach ($plugins as $plugin) {
        $plugs[] = $plugin;
    }
    print_r(json_encode($plugs));
}


if (isset($_GET["install"])) {
    $pluginUrl =  $_POST["url"];
    $status = Manager::installPlugin($pluginUrl);
    $info->refreshPlugins();
    return print_r(json_encode($status));
}

// uninstall
if (isset($_GET["uninstall"])) {
    $pluginName =  $_POST["name"];
    $status = Manager::uninstallPlugin($pluginName);
    $info->refreshPlugins();
    return print_r(json_encode($status));
}

//update

if (isset($_GET["update"])) {
    $pluginName =  $_POST["name"];
    $pluginUrl =  $_POST["url"];
    Manager::updatePlugin($pluginName, $pluginUrl);
    $info->refreshPlugins();
}
