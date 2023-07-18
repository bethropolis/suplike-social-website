<?php

namespace Bethropolis\PluginSystem;

use Bethropolis\PluginSystem\System;
use Bethropolis\PluginSystem\Error;

abstract class Plugin
{
    protected $name = "unnamed plugin";
    protected $version;
    protected $author;
    protected $description;
    public function getInfo()
    {
        return array(
            'name' => $this->name,
            'version' => $this->version,
            'author' => $this->author,
            'description' => $this->description
        );
    }

    public function linkHook($hook, $callback = null)
    {
        System::linkPluginToHook($hook, $callback);
    }
    public function linkEvent($event, $callback = null)
    {
        System::addAction($event, $callback);
    }
    public function error($errorMessage, $errorLevel = "ERROR")
    {
        Error::handleError($errorMessage,$this->name, $errorLevel);
    }
    public function exception($exception)
    {
        Error::handleException($exception);
    }
}

