<?php

namespace Bethropolis\PluginSystem;

use Bethropolis\PluginSystem\Autoloader;


class System
{
    private static $plugins = array();
    private static $pluginsDir;

    private static $pluginsLoaded = array();

    private static $hooks = array();

    private static $events = array();

    private static $configFile = __DIR__ . '/config/config.json';

    private static $config;



    private static function pluginClassAutoloader($className, $pluginsDir, $folder)
    {
        return Autoloader::pluginClassAutoloader($className, $pluginsDir, $folder);
    }

    private static function pluginAutoloader($file)
    {
        return Autoloader::pluginAutoloader($file);
    }
    /**
     * Check if a plugin class exists.
     *
     * @param string $className The name of the class to check.
     * @return bool Returns true if the class exists, false otherwise.
     */
    private static function pluginClassExists($className)
    {
        return class_exists($className);
    }

    public static function setPluginsDir($dir)
    {
        self::$pluginsDir = $dir;
    }
    public static function getPlugins()
    {
        return self::$pluginsLoaded;
    }

    public static function getPluginsDir()
    {
        return self::$pluginsDir;
    }

    public static function getEvents()
    {
        return self::$events;
    }

    public static function getHooks()
    {
        return self::$hooks;
    }


    /**
     * Load plugins from a specified directory.
     *
     * @param string|null $dir The directory path to load plugins from. If null, uses the default plugins directory.
     * @return bool Returns true if the plugins are successfully loaded.
     */
    public static function loadPlugins($dir = null)
    {
        if ($dir) {
            self::setPluginsDir($dir);
        }
        $pluginsDir = self::$pluginsDir;

        foreach (new \DirectoryIterator($pluginsDir) as $fileInfo) {
            if (!$fileInfo->isDot()) {
                if ($fileInfo->isDir()) {
                    $pluginFile = $fileInfo->getPathname() . '/plugin.php';
                } else {
                    $pluginFile = $fileInfo->getPathname();
                }

                if (file_exists($pluginFile)) {
                    $classAutoloader = function ($className) use ($pluginsDir, $fileInfo) {
                        self::pluginClassAutoloader($className, $pluginsDir, $fileInfo->getFilename());
                    };

                    spl_autoload_register($classAutoloader);

                    self::pluginAutoloader($pluginFile);

                    if ($fileInfo->isDir()) {
                        $pluginClass = __NAMESPACE__ . '\\' . $fileInfo->getFilename() . 'Plugin\\Load';
                    } else {
                        $pluginClass =  pathinfo($fileInfo->getFilename(), PATHINFO_FILENAME);
                    }


                    self::$pluginsLoaded[] = $pluginClass;

                    if (self::pluginClassExists($pluginClass)) {
                        $pluginInstance = new $pluginClass();
                        $pluginInstance->initialize();
                        $pluginInstance->getInfo();
                    }

                    spl_autoload_unregister($classAutoloader);
                }
            }
        }
        self::initialize();
        return self::$pluginsLoaded;
    }


    private static function initialize()
    {
        if (file_exists(self::$configFile)) {
            $config = json_decode(file_get_contents(self::$configFile), true);
        } else {
            $config = array();
        }

        self::$config = $config;
    }


    /**
     * Link a plugin to a hook.
     *
     * @param mixed $hook The hook to link the plugin to.
     * @param mixed $callback The callback function to be executed when the hook is triggered.
     *
     * @return bool
     */
    public static function linkPluginToHook($hook, $callback)
    {
        if (!isset(self::$plugins[$hook])) {
            self::$hooks[$hook] = array();
            self::$plugins[$hook] = array();
        }

        self::$plugins[$hook][] = $callback;
        return true;
    }

    /**
     * Executes a hook by calling all registered callbacks associated with it.
     *
     * @param string $hook The name of the hook to execute.
     * @param string|null $pluginName The name of the plugin. Default is null.
     * @param mixed ...$args The arguments to pass to the callbacks.
     * @return array The return values from the callbacks.
     */
    public static function executeHook($hook, $pluginName = null, ...$args)
    {
        $returnValues = array();
        $plugin_status = isset(self::$config["activated_plugins"]) ? self::$config["activated_plugins"] : false;
        if (isset(self::$plugins[$hook])) {
            foreach (self::$plugins[$hook] as $callback) {
                $callbackPluginName = get_class($callback[0]);

                $checkName = stripslashes(strtolower($callbackPluginName));
                if ($plugin_status && isset(self::$config["activated_plugins"][$checkName]) && $plugin_status[$checkName] === false) {
                    continue;
                }

                if ($pluginName === null || $pluginName === $callbackPluginName) {
                    $args = $args[0] ?? [];
                    $returnValue = call_user_func($callback, $args);
                    if ($returnValue !== null &&  $pluginName === null) {
                        $returnValues[] = $returnValue;
                    } else {
                        $returnValues = $returnValue;
                    }
                }
            }
        }

        return $returnValues;
    }

    /**
     * Executes a series of hooks.
     *
     * @param array $hooks An array of hooks to execute.
     * @param string|null $pluginName The name of the plugin. Defaults to null.
     * @param mixed ...$args Additional arguments to pass to the hooks.
     * @return array An array of return values from the executed hooks.
     */
    public static function executeHooks(array $hooks, $pluginName = null, ...$args)
    {

        $returnValues = array();
        foreach ($hooks as $hook) {
            $returnValue = self::executeHook($hook, $pluginName, ...$args);
            if (!empty($returnValue)) {
                $returnValues[$hook] = $returnValue;
            }
        }
        return $returnValues;
    }

    /**
     * Registers an event.
     *
     * @param mixed $eventName The name of the event to register.
     */
    public static function registerEvent($eventName)
    {
        if (!isset(self::$events[$eventName])) {
            self::$events[$eventName] = array();
        }
    }

    # unlink all events 
    public static function clearEvents()
    {
        self::$events = array();
    }

    /**
     * Adds an action to the event specified by $eventName.
     *
     * @param mixed $eventName The name of the event.
     * @param mixed $callback The callback function to be executed when the event is triggered.
     * @return void
     */
    public static function addAction($eventName, $callback)
    {
        if (isset(self::$events[$eventName])) {
            self::$events[$eventName][] = $callback;
        }
    }


    /**
     * Triggers an event and calls all registered callbacks for that event.
     *
     * @param string $eventName The name of the event to trigger.
     * @param mixed ...$args Additional arguments to pass to the callbacks.
     * @return array The return values from the callbacks, if any.
     */
    public static function triggerEvent($eventName, ...$args)
    {
        $returnValues = array();
        if (isset(self::$events[$eventName])) {
            foreach (self::$events[$eventName] as $callback) {
                $returnValue = call_user_func_array($callback, $args);
                if ($returnValue !== null) {
                    $returnValues[] = $returnValue;
                }
            }
        }
        return $returnValues;
    }
}
