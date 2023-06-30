<?php
// coming soon


require 'dbh.inc.php';

// Load installed plugins
$installed_plugins = json_decode(get_option('installed_plugins'), true) ?: [];

// Install a plugin from a URL
function install_plugin($url) {
    $zip_file = file_get_contents($url);
    $temp_file = tempnam(sys_get_temp_dir(), 'plugin_');
    file_put_contents($temp_file, $zip_file);
    $zip = new ZipArchive;
    $res = $zip->open($temp_file);
    if ($res === TRUE) {
        $dir = __DIR__ . '/plugins';
        $zip->extractTo($dir);
        $zip->close();
        unlink($temp_file);
        // Update installed plugins option
        global $installed_plugins;
        $plugin_name = basename($url, '.zip');
        if (!in_array($plugin_name, $installed_plugins)) {
            $installed_plugins[] = $plugin_name;
            update_option('installed_plugins', json_encode($installed_plugins));
        }
        // Load plugin details
        $details_url = 'https://plugins.mysite.com/plugin_' . $plugin_name . '/details.json';
        $details = file_get_contents($details_url);
        $plugins_file = __DIR__ . '/plugins.json';
        $plugins = json_decode(file_get_contents($plugins_file), true) ?: [];
        $plugins[] = json_decode($details, true);
        file_put_contents($plugins_file, json_encode($plugins, JSON_PRETTY_PRINT));
    } else {
        echo "Failed to extract plugin\n";
    }
}

// Remove a plugin by name
function remove_plugin($name) {
    $dir = __DIR__ . '/plugins/' . $name;
    array_map('unlink', glob("$dir/*.*"));
    rmdir($dir);
    // Update installed plugins option
    global $installed_plugins;
    $index = array_search($name, $installed_plugins);
    if ($index !== false) {
        array_splice($installed_plugins, $index, 1);
        update_option('installed_plugins', json_encode($installed_plugins));
    }
    // Remove plugin from plugins.json
    $plugins_file = __DIR__ . '/plugins.json';
    $plugins = json_decode(file_get_contents($plugins_file), true) ?: [];
    foreach ($plugins as $index => $plugin) {
        if ($plugin['name'] == $name) {
            array_splice($plugins, $index, 1);
            break;
        }
    }
    file_put_contents($plugins_file, json_encode($plugins, JSON_PRETTY_PRINT));
}

// Get an option value by ID
function get_option($id) {
    global $conn;
    $sql = "SELECT value FROM options WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    if ($row) {
        return $row['value'];
    } else {
        return null;
    }
}

// Update an option value by ID
function update_option($id, $value) {
    global $conn;
    $sql = "UPDATE options SET value = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $value, $id);
    $stmt->execute();
    $stmt->close();
}

// Load plugins.json
$plugins_file = __DIR__ . '/plugins.json';
$plugins = json_decode(file_get_contents($plugins_file), true) ?: [];

// Display a list of installed plugins
echo "Installed plugins:\n";
foreach ($installed_plugins as $plugin_name) {
    echo "- $plugin_name\n";
}
// Install a plugin echo "\nInstalling plugin…\n"; installplugin('https://plugins.mysite.com/pluginexample.zip');

// Display the list of plugins after installation echo "\nInstalled plugins:\n"; foreach ($installedplugins as $pluginname) { echo "- $plugin_name\n"; }

// Remove a plugin echo "\nRemoving plugin…\n"; removeplugin('pluginexample');

// Display the list of plugins after removal echo "\nInstalled plugins:\n"; foreach ($installedplugins as $pluginname) { echo "- $plugin_name\n"; }


//  VERSION 2

class PluginSystem {
    private $plugins = [];
    private $installed_plugins = [];

    public function __construct() {
        // Load installed plugins
        $installed_plugins_option = get_option('installed_plugins');
        $this->installed_plugins = json_decode($installed_plugins_option, true) ?: [];
    }

    public function load_plugins() {
        $dir = __DIR__ . '/plugins';
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != '.' && $file != '..') {
                        $path = $dir . '/' . $file;
                        if (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) == 'php') {
                            $plugin = include $path;
                            if (is_callable($plugin)) {
                                $this->plugins[] = $plugin;
                            }
                        }
                    }
                }
                closedir($dh);
            }
        }
    }

    public function run_plugins() {
        foreach ($this->plugins as $plugin) {
            $plugin();
        }
    }

    public function install_plugin($url) {
        $zip_file = file_get_contents($url);
        $temp_file = tempnam(sys_get_temp_dir(), 'plugin_');
        file_put_contents($temp_file, $zip_file);
        $zip = new ZipArchive;
        $res = $zip->open($temp_file);
        if ($res === TRUE) {
            $dir = __DIR__ . '/plugins';
            $zip->extractTo($dir);
            $zip->close();
            unlink($temp_file);
            // Update installed plugins option
            $plugin_name = basename($url, '.zip');
            if (!in_array($plugin_name, $this->installed_plugins)) {
                $this->installed_plugins[] = $plugin_name;
                update_option('installed_plugins', json_encode($this->installed_plugins));
            }
            // Load plugin details
            $details_url = 'https://plugins.mysite.com/plugin_' . $plugin_name . '/details.json';
            $details = file_get_contents($details_url);
            $plugins_file = __DIR__ . '/plugins.json';
            $plugins = json_decode(file_get_contents($plugins_file), true) ?: [];
            $plugins[] = json_decode($details, true);
            file_put_contents($plugins_file, json_encode($plugins, JSON_PRETTY_PRINT));
        } else {
            echo "Failed to extract plugin\n";
        }
    }

    public function remove_plugin($name) {
        $dir = __DIR__ . '/plugins/' . $name;
        array_map('unlink', glob("$dir/*.*"));
        rmdir($dir);
        // Update installed plugins option
        $index = array_search($name, $this->installed_plugins);
        if ($index !== false) {
            array_splice($this->installed_plugins, $index, 1);
            update_option('installed_plugins', json_encode($this->installed_plugins));
        }
        // Remove plugin from plugins.json
        $plugins_file = __DIR__ . '/plugins.json';
        $plugins = json_decode(file_get_contents($plugins_file), true) ?: [];
        foreach ($plugins as $index => $plugin) {
            if ($plugin['name'] == $name) {
                array_splice($plugins, $index, 1);
                break;
            }
        }
        file_put_contents($plugins_file, json_encode($plugins, JSON_PRETTY_PRINT));
    }
}

// Usage example

require 'dbh.inc.php';

$plugin_system = new PluginSystem();
$plugin_system->load_plugins();
$plugin_system->run_plugins();

$plugin_system->install_plugin('https://plugins.mysite.com/plugin_example.zip');

$pluginsystem->removeplugin('plugin_example');

