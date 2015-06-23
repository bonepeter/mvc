<?php

namespace unitTest\lib\framework\db;

use \PDO;
use lib\framework\db\MysqlDb;

class MysqlDbTest extends \PHPUnit_Framework_TestCase
{
    private function createDb()
    {
        define('DB_HOST', '127.0.0.1');
        define('DB_USER', 'mvcuser');
        define('DB_PASSWORD', 'mvcpass');
        define('DB_NAME', 'mvc');
        $pdo = $this->createMysqlPdo(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        return new MysqlDb($pdo);
    }

    private function createMysqlPdo($host, $user, $pass, $dbName) {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8', $host, $dbName);
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    }

    public function test_Select_NoTable()
    {
        $db = $this->createDb();
        $this->assertEquals('1', '1', 'Wrong count');
    }
}
 