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
    protected function getDbModel($name = '')
    {
        $name = empty($name) ? $this->getName() : $name;
        return DbModelFactory::makeDbModel($name);
    }
} 