<?php

namespace lib\framework;

$autoLoader = new AutoLoader();
spl_autoload_register(array($autoLoader, 'requireClassFile'));

/**
 * Find file of class to include, called by spl_autoload_register()
 *
 * The class can be called without manual include the file
 * !!! No unit test for this class !!!
 *
 * @package lib\framework
 */
class AutoLoader
{
    public function requireClassFile($className)
    {
        $phpFile = str_replace('\\', DIRECTORY_SEPARATOR, $className);
        $requirePath = __DIR__ . '/../../' . $phpFile . '.php';
        if (is_readable($requirePath)) {
            require_once $requirePath;
        }
    }
}
