<?php

namespace lib\framework;

use app\controller\ControllerFactory;
use RuntimeException;

/**
 * Class Router
 * @package lib\framework
 */
class Router
{
    private $webBasePath;

    public function __construct()
    {
        $this->webBasePath = '';
    }

    /**
     * http://ip/WebBasePath/more/request/uri
     *
     * @param $path
     */
    public function setWebBasePath($path) {
        $this->webBasePath = $path;
    }

    public function dispatch($serverRequestUri)
    {
        $parameters = Helper::getParametersFromRequestUri($serverRequestUri, $this->webBasePath);

        $class = array_shift($parameters);
        $methodName = array_shift($parameters);

        try {
            $controller = ControllerFactory::makeController($class);
        } catch (RuntimeException $e) {
            throw new RuntimeException($e->getMessage());
        }
        if (is_callable(array($controller, $methodName))) {
            $result = call_user_func_array(array($controller, $methodName), $parameters);
            return $result;
        } else {
            throw new RuntimeException('No Action found');
        }
    }
}