<?php

namespace Bethropolis\PluginSystem;

use Bethropolis\PluginSystem\System;
use Bethropolis\PluginSystem\Error;
use Bethropolis\PluginSystem\LifeCycle;

class Manager
{
    private static $pluginsDir;
    private static $configFile = __DIR__ . '/config/config.json';
    private static $config;

    private static $lifeCycle;
    public static function initialize()
    {
        $pluginsDir = System::getPluginsDir();
        self::setPluginsDir($pluginsDir);
        self::$config = self::loadConfig();
        self::$lifeCycle = new LifeCycle();
    }
    public static function setPluginsDir($dir)
    {
        self::$pluginsDir = $dir;
    }

    public static function installPlugin($pluginUrl)
    {
        // Check if the plugins directory is writable
        if (!is_writable(self::$pluginsDir)) {
            return;
        }

        // Get the plugin file name and create temporary file and directory paths
        $pluginFileName = basename($pluginUrl);
        $tempDir = sys_get_temp_dir();
        $tempFilePath = $tempDir . '/' . $pluginFileName;
        $extractedDirPath = $tempDir . '/' . pathinfo($pluginFileName, PATHINFO_FILENAME);

        // Copy the plugin file to the temporary directory
        if (!copy($pluginUrl, $tempFilePath)) {
            return;
        }

        // Extract the plugin file
        $zip = new \ZipArchive();
        if ($zip->open($tempFilePath) === true) {
            if (!$zip->extractTo($extractedDirPath)) {
                return;
            }
            $zip->close();
        } else {
            return;
        }

        // Get the plugin directory path
        $pluginDirPath = self::$pluginsDir . '/' . basename($extractedDirPath);

        // Rename the extracted directory to the plugin directory if it doesn't already exist
        if (is_dir($pluginDirPath)) {
            return;
        }

        if (!rename($extractedDirPath, $pluginDirPath)) {
            return;
        }

        // Register the plugin
        $pluginName = basename($pluginDirPath);
        self::registerPlugin($pluginName);
        self::$lifeCycle->onInstallation($pluginName);

        // Clean up the temporary file and register the plugin
        unlink($tempFilePath);
        return true;
    }

    public static function uninstallPlugin($pluginName)
    {
        $pluginNamespace = __NAMESPACE__ . '\\' . $pluginName . "Plugin\\Load";
        $pluginNamespace = stripslashes(strtolower($pluginNamespace));


        if (!self::pluginExists($pluginNamespace)) {
            Error::handleException(new \Exception("Plugin does not exist."));
            return;
        }

        self::deactivatePlugin($pluginNamespace); // hard coding it for now

        if (self::isPluginActive($pluginNamespace)) {
            Error::handleException(new \Exception("Cannot uninstall an active plugin. Deactivate it first."));
            return;
        }

        $pluginDir = self::$pluginsDir . '/' . $pluginName;
        if (!is_dir($pluginDir)) {
            Error::handleException(new \Exception("Plugin directory not found."));
            return;
        }

        if (!self::deleteDirectory($pluginDir)) {
            Error::handleException(new \Exception("Unable to remove the plugin directory."));
            return;
        }

        self::unregisterPlugin($pluginNamespace);
        self::$lifeCycle->onUninstallation($pluginName);

        self::saveConfig();
        return true;
    }

    private static function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!self::deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }


    public static function registerPlugin($pluginName)
    {
        $pluginName = __NAMESPACE__ . '\\' . $pluginName . "Plugin\\Load";
        $pluginName = stripslashes(strtolower($pluginName));
        self::$config['plugins'][$pluginName] = [];
        self::$config['activated_plugins'][$pluginName] = true;
        self::saveConfig();
    }

    public static function unregisterPlugin($pluginName)
    {
        unset(self::$config['plugins'][$pluginName]);
        unset(self::$config['activated_plugins'][$pluginName]);
        self::saveConfig();
    }

    public static function activatePlugin($pluginName)
    {

        self::$config['activated_plugins'][$pluginName] = true;
        self::saveConfig();
    }

    public static function deactivatePlugin($pluginName)
    {

        self::$config['activated_plugins'][$pluginName] = false;
        self::saveConfig();
    }

    public static function pluginExists($pluginName)
    {

        return isset(self::$config['plugins'][$pluginName]);
    }

    public static function isPluginActive($pluginName)
    {
        return isset(self::$config['activated_plugins'][$pluginName]) && self::$config['activated_plugins'][$pluginName];
    }

    public static function getPluginMetadata($pluginName)
    {
        return self::$config['plugins'][$pluginName] ?? null;
    }

    public static function updatePlugin($pluginName, $pluginUrl)
    {
        if (!self::pluginExists($pluginName)) {
            throw new \Exception("Plugin does not exist.");
        }

        self::uninstallPlugin($pluginName);

        self::installPlugin($pluginUrl);
    }

    private static function loadConfig()
    {
        if (file_exists(self::$configFile)) {
            $config = json_decode(file_get_contents(self::$configFile), true);
        } else {
            $config = [];
        }

        // Check if the configuration is empty or doesn't exist
        if (empty($config)) {
            $config = self::initializeConfig();
            self::saveConfig($config);
        }

        return $config;
    }

    private static function initializeConfig()
    {
        $plugins = System::getPlugins();
        $plugins = array_map(function ($plugin) {
            return stripslashes(strtolower($plugin));
        }, $plugins);


        $activatedPlugins = array_fill_keys($plugins, true);



        return [
            'plugins' => array_fill_keys($plugins, []),
            'activated_plugins' => $activatedPlugins
        ];
    }

    private static function saveConfig($config = null)
    {
        $config = $config ?? self::$config;
        file_put_contents(self::$configFile, json_encode($config, JSON_PRETTY_PRINT));
    }
}
