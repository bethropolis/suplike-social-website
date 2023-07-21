<?php

namespace Bethropolis\PluginSystem;

use Bethropolis\PluginSystem\System;

class Info
{
    private $configFilePath;
    private $pluginDir;
    private $config;

    public function __construct()
    {
        $this->configFilePath = __DIR__ . '/config/plugins.json';
        $this->pluginDir = System::getPluginsDir();
        $this->loadConfig();
    }

    private function loadConfig()
    {
        if (file_exists($this->configFilePath)) {
            $configContents = file_get_contents($this->configFilePath);
            $this->config = json_decode($configContents, true);
        } else {
            $this->config = [
                'plugins' => [],
            ];
            $this->saveConfig();
        }
    }

    public function refreshPlugins()
    {
        $this->config['plugins'] = [];
        $plugins = $this->scanPluginsDirectory();

        foreach ($plugins as $pluginName) {
            $pluginConfigFile = $this->pluginDir . $pluginName . '/plugin.json';
            if (file_exists($pluginConfigFile)) {
                $pluginConfig = json_decode(file_get_contents($pluginConfigFile), true);
                $this->config['plugins'][$pluginName] = $pluginConfig;
            }
        }

        $this->saveConfig();
    }

    private function scanPluginsDirectory()
    {
        $plugins = [];
        if (is_dir($this->pluginDir)) {
            $dirContent = scandir($this->pluginDir);
            foreach ($dirContent as $item) {
                if ($item !== '.' && $item !== '..' && is_dir($this->pluginDir . $item)) {
                    $plugins[] = $item;
                }
            }
        }
        return $plugins;
    }

    public function addPlugin($pluginName, $data)
    {
        if (isset($this->config['plugins'][$pluginName])) {
            return;
        }

        $this->config['plugins'][$pluginName] = $data;
        $this->saveConfig();
        return true;
    }

    public function removePlugin($pluginName)
    {
        unset($this->config['plugins'][$pluginName]);
        $this->saveConfig();
    }

    public function modifyPluginData($pluginName, $data)
    {
        if (isset($this->config['plugins'][$pluginName])) {
            $this->config['plugins'][$pluginName] = array_merge($this->config['plugins'][$pluginName], $data);
            $this->saveConfig();
        }
    }

    public function getPlugins()
    {
        $plugins = $this->config['plugins'] ?? [];
        return $plugins;
    }

    private function saveConfig()
    {
        $configContents = json_encode($this->config, JSON_PRETTY_PRINT);
        file_put_contents($this->configFilePath, $configContents);
    }
}
