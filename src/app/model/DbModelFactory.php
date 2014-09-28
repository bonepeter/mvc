<?php
/**
 * Project: framework
 * User: peter
 * Date: 20140914
 */

namespace app\model;

use lib\framework\Helper;
use RuntimeException;

require_once __DIR__ . '/../../config/config.php';

/**
 * New Model needed to create from this factory
 * @package app\model
 */
class DbModelFactory
{
    public static function makeDbModel($name) {
        $db = Helper::createDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        switch (strtolower($name)) {
            case 'test':
                return new TestDbModel($db);
            default:
                throw new RuntimeException('No Object found');
        }
    }
}