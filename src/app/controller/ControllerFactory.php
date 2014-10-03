<?php
/**
 * Project: framework
 * User: peter
 * Date: 20140912
 */

namespace app\controller;

use RuntimeException;

/**
 * New Controller needed to create from this factory
 * @package app\controller
 */
class ControllerFactory
{
    public static function makeController($name) {
        switch (strtolower($name)) {
            case 'index':
                return new IndexController();
            case 'auth':
                return new AuthController();
            default:
                throw new RuntimeException('No Object found');
        }
    }
} 