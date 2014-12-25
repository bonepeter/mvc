<?php

namespace lib\framework;

use PDO;

class Helper
{
    private static function createMysqlPdo($host, $user, $pass, $dbName) {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8', $host, $dbName);
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    }

    public static function createDb($host, $user, $pass, $dbName)
    {
        $pdo = Helper::createMysqlPdo($host, $user, $pass, $dbName);
        $db = new Db($pdo);
        return $db;
    }

}
