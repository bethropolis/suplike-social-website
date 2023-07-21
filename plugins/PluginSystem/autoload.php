<?php
/**
 * Autoloads PHP classes dynamically based on the class name.
 *
 * @param string $class The fully qualified class name.
 * @return void
 */
spl_autoload_register(function ($class) {
    // Split the class name by namespace separator
    $parts = explode('\\', $class);

    // Get the class name (last part of the namespace)
    $className = end($parts);

    // Convert the class name to a file path
    $classPath = __DIR__ . '/' . $className . '.php';

    // Check if the file exists
    if (file_exists($classPath)) {
        require_once $classPath;
    }
});
