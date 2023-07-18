<?php

namespace Bethropolis\PluginSystem;


use Bethropolis\PluginSystem\System;

class LifeCycle
{
    private $pluginDir;

    public function __construct()
    {
        $this->pluginDir = System::getPluginsDir();
    }

    public function onInstallation($pluginName)
    {
        $pluginConfigFile = $this->getPluginConfigPath($pluginName);

        if (file_exists($pluginConfigFile)) {
            $pluginConfig = json_decode(file_get_contents($pluginConfigFile), true);

            if (isset($pluginConfig['files'])) {
                foreach ($pluginConfig['files'] as $file) {
                    if (isset($file['target']) && isset($file['require'])) {
                        $targetFile = $this->resolveAbsolutePath(__DIR__ . '/' . $file['target']);
                        $requireFile = $this->resolveRelativePath($file['require'], $this->getPluginPath($pluginName));
                        if (file_exists($targetFile) && file_exists($requireFile)) {
                            // Append the require statement to the target file
                            $this->appendRequireStatement($targetFile, $requireFile);
                        }
                    }
                }
            }
        }
    }

    public function onUninstallation($pluginName)
    {
        $pluginConfigFile = $this->getPluginConfigPath($pluginName);

        if (file_exists($pluginConfigFile)) {
            $pluginConfig = json_decode(file_get_contents($pluginConfigFile), true);

            if (isset($pluginConfig['files'])) {
                foreach ($pluginConfig['files'] as $file) {
                    if (isset($file['target']) && isset($file['require'])) {
                        $targetFile = $this->resolveAbsolutePath(__DIR__ . '/' . $file['target']);
                        $requireFile = $this->resolveRelativePath($file['require'], $this->getPluginPath($pluginName));

                        if (file_exists($targetFile) && file_exists($requireFile)) {
                            // Remove the appended require statement from the target file
                            $this->removeRequireStatement($targetFile, $requireFile);
                        }
                    }
                }
            }
        }
    }

    private function getPluginConfigPath($pluginName)
    {
        return $this->getPluginPath($pluginName) . '/plugin.json';
    }

    private function getPluginPath($pluginName)
    {
        return $this->pluginDir . $pluginName;
    }

    private function appendRequireStatement($targetFile, $requireFile)
    {
        $content = file_get_contents($targetFile);
        $requireStatement = 'require "' . str_replace('\\', '/', $requireFile) . '";';

        // Append the require statement to the target file if it doesn't exist already
        if (strpos($content, $requireStatement) === false) {
            $content .= PHP_EOL . $requireStatement . PHP_EOL;
            file_put_contents($targetFile, $content);
        }
    }

    private function removeRequireStatement($targetFile, $requireFile)
    {
        $content = file_get_contents($targetFile);
        $requireStatement = 'require "' . str_replace('\\', '/', $requireFile) . '";';

        // Remove the appended require statement from the target file
        $content = str_replace(PHP_EOL . $requireStatement . PHP_EOL, '', $content);
        file_put_contents($targetFile, $content);
    }


    private function resolveAbsolutePath($path)
    {
        // Resolve the absolute path from a given path
        return realpath($path);
    }

    private function resolveRelativePath($path, $basePath)
    {
        // Resolve the relative path from a given base path
        print_r($basePath . '/' . $path);
        return realpath($basePath . '/' . $path);
    }
}
