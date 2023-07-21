<?php

namespace Bethropolis\PluginSystem;

class Error
{

    private static $errorLogFile = __DIR__ . "/errors/errors.log.txt";

    public function __construct($errorLogFile = null)
    {
        self::$errorLogFile = $errorLogFile;

    }

    public static function setErrorLog($file)
    {
        self::$errorLogFile = $file;
    }

    public static function logError($errorMessage)
    {
        $errorLog = "[" . date('Y-m-d H:i:s') . "] " . $errorMessage . "\n";

        file_put_contents(self::$errorLogFile, $errorLog, FILE_APPEND | LOCK_EX);
    }


    public static function handleError($errorMessage,$name, $errorLevel)
    {
        $formattedError = "[Error Level: {$errorLevel}] {$errorMessage} From {$name} in {$_SERVER['SCRIPT_FILENAME']}.";

        self::logError($formattedError);
    }

    public static function handleException($exception)
    {
        $formattedException = "[Exception] {$exception->getMessage()} in {$exception->getFile()} on line {$exception->getLine()}";

        self::logError($formattedException);
    }
}
