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
            case 'auth':
                $user = new UserModel($db);
                $session = new \app\model\session\PhpSession();
                return new AuthModel($user, $session);
            case 'user':
                return new UserModel($db);
            case 'log':
                return new LogModel($db);
            default:
                throw new RuntimeException('No Object found');
        }
    }
}