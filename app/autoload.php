<?php

namespace App {
    spl_autoload_register(function ($class) {
        $appDir = '/app/';
        $fileExtension = '.php';
        if (strpos($class, __NAMESPACE__ . '\\') !== false) {
            $class = str_replace(__NAMESPACE__ . '\\', '', $class);
            $className = str_replace('\\', DIRECTORY_SEPARATOR, $class);
            $filePath = __DIR__ . $appDir . $className . $fileExtension;

            if (file_exists($filePath)) {
                require_once $filePath;
                return true;
            }
        }

        return false;
    });
}
