<?php


namespace Bethropolis\PluginSystem;

class Autoloader
{
    /**
     * Autoloads a plugin file.
     *
     * @param string $file The path of the file to be autoloaded.
     * @throws \Exception If an error occurs during autoloading.
     * @return bool Returns true if the file exists and is successfully autoloaded, false otherwise.
     */
    public static function pluginAutoloader($file)
    {
        try {
            if (file_exists($file)) {
                require_once $file;
                return true;
            }
            return false;
        } catch (\Exception $e) {
            // Handle the exception here
            return false;
        }
    }

    /**
     * Autoloads the plugin class file.
     *
     * @param string $className The name of the class to autoload.
     * @param string $pluginsDir The directory where the plugins are located.
     * @param string $folder The folder within the plugins directory where the class file is located.
     * @return bool
     */
    public static function pluginClassAutoloader($className, $pluginsDir, $folder)
    {
        $classFile = $pluginsDir . $folder . '/' . $className . '.php';
        try {
            if (file_exists($classFile)) {
                require_once $classFile;
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            // Handle the exception here
            return false;
        }
    }
}
