<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 7/9/14
 * Time: 下午12:02
 */

namespace lib\framework;

use PDO;

/**
 * Class Helper
 * @package lib\framework
 */
class Helper {
    const URL_SEPARATOR = '/';

    public static function explodeSlash($string)
    {
        return explode(self::URL_SEPARATOR, $string);
    }

    public static function removeStartStringWithSlash($originString, $removingStartingString)
    {
        $pattern = '/^' . str_replace('/', '\/', $removingStartingString) . '/';
        return preg_replace($pattern, '', $originString, 1);
    }

    public static function getParametersFromRequestUri($requestUri, $removingStartingUri)
    {
        $slashedString = Helper::removeStartStringWithSlash($requestUri, $removingStartingUri);
        return Helper::explodeSlash($slashedString);
    }

    private static function createMysqlPdo($host, $user, $pass, $dbName) {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8', $host, $dbName);
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public static function createDb($host, $user, $pass, $dbName)
    {
        $pdo = Helper::createMysqlPdo($host, $user, $pass, $dbName);
        $db = new Db($pdo);
        return $db;
    }

}
