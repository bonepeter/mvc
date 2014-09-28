<?php

namespace lib\framework;

use app\view\ViewFactory;

/**
 * Class Controller
 * @package lib\framework
 */
class Controller
{
    protected function getView($name = '')
    {
        $name = empty($name) ? $this->getName() : $name;
        return ViewFactory::makeView($name);
    }

    protected function getName()
    {
        $parts = explode('\\', get_class($this));
        $lastPart = $parts[count($parts) - 1];
        return preg_replace('/Controller$/', '', $lastPart);
    }
}
