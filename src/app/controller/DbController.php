<?php
/**
 * Project: framework
 * User: peter
 * Date: 20140908
 */

namespace app\controller;

use app\model\DbModelFactory;
use lib\framework\Controller;

abstract class DbController extends Controller
{
    private $model = array();

    protected function makeModel($modelName)
    {
        if (! isset($this->model[$modelName]))
        {
            $this->model[$modelName] = DbModelFactory::makeDbModel($modelName);
        }
        return $this->model[$modelName];
    }

    protected function getDbModel($name = '')
    {
        $name = empty($name) ? $this->getName() : $name;
        return DbModelFactory::makeDbModel($name);
    }

    protected function log($type, $message)
    {
        $log = DbModelFactory::makeDbModel('Log');
        $log->log($type, $message);
    }

} 