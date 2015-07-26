<?php

namespace unitTest\lib\framework\db;

require_once __DIR__ . '/../../../../src/lib/framework/autoLoader.php';

use lib\framework\adt\TextAdt;
use lib\framework\db\DbCondition;
use lib\framework\db\MysqlDbColumn;
use \PDO;
use lib\framework\db\MysqlDb;
use lib\framework\db\statement\SelectStatement;

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'mvcuser');
define('DB_PASSWORD', 'mvcpass');
define('DB_NAME', 'mvc_unittest');

class MysqlDbTest extends \PHPUnit_Framework_TestCase
{
    private function createMysqlDb()
    {
        $pdo = $this->createMysqlPdo();
        return new MysqlDb($pdo);
    }

    private function createMysqlPdo()
    {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8', DB_HOST, DB_NAME);
        $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    }

    public function test_Select_OnlySelectAttribute()
    {
        $db = $this->createMysqlDb();
        $statement = new SelectStatement();
        $statement->addSelectAttribute('1 as col');

        $result = $db->runSelect($statement);

        $this->assertEquals('1', $result[0]['col']);
    }

    public function test_Select_EmptyTable()
    {
        $this->mysqlExec("TRUNCATE user;");

        $db = $this->createMysqlDb();
        $statement = new SelectStatement();
        $statement->addTable('user');

        $result = $db->runSelect($statement);

        $this->assertEquals(0, count($result));
    }

    public function test_Select_OnlyTable()
    {
        $this->mysqlExec("TRUNCATE user;");
        $this->mysqlExec("INSERT INTO user SET username='u1';");

        $db = $this->createMysqlDb();
        $statement = new SelectStatement();
        $statement->addTable('user');

        $result = $db->runSelect($statement);

        $this->assertEquals(1, count($result));
    }

    private function mysqlExec($sql)
    {
        $pdo = $this->createMysqlPdo();
        $pdo->exec($sql);
    }

    public function test_Select_Condition()
    {
        $this->mysqlExec("TRUNCATE user; INSERT INTO user SET username='u1'; INSERT INTO user SET username='u2';");

        $db = $this->createMysqlDb();
        $statement = new SelectStatement();
        $statement->addTable('user');
        $statement->addCondition(New DbCondition(new MysqlDbColumn('username', 'string'), New TextAdt('u1')));

        $result = $db->runSelect($statement);

        $this->assertEquals(1, count($result), 'Wrong number of records');
        $this->assertEquals('u1', $result[0]['username']);
    }

    public function test_Select_ConstantCondition()
    {
        $this->mysqlExec("TRUNCATE user; INSERT INTO user SET username='u1'; INSERT INTO user SET username='u2';");

        $db = $this->createMysqlDb();
        $statement = new SelectStatement();
        $statement->addTable('user');
        $statement->addCondition(New DbCondition(new MysqlDbColumn('username', 'constant'), New TextAdt('u2')));

        $result = $db->runSelect($statement);

        $this->assertEquals(1, count($result), 'Wrong number of records');
        $this->assertEquals('u2', $result[0]['username']);

    }


}
 