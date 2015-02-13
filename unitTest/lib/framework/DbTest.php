<?php

namespace unitTest\lib\framework;

require_once __DIR__ . '/../../../src/lib/framework/autoLoader.php';

use lib\framework\Db;
use lib\framework\DbWhereCol;
use lib\framework\DbWhereColumnType;
use lib\framework\DbWhereColumns;

class DbTest extends \PHPUnit_Framework_TestCase
{
    public function test_Select_NoTable()
    {
        $db = $this->createDb();
        $result = $db->select('1 as count');
        $this->assertEquals('1', $result[0]['count'], 'Wrong count');
    }

    public function test_Select_GoodTable()
    {
        $db = $this->createDb();
        $result = $db->select('*', 'table1');

        $this->assertEquals('t1', $result[0]['title'], 'Wrong title');
        $this->assertEquals('m1', $result[0]['message'], 'Wrong message');
        $this->assertEquals('t2', $result[1]['title'], 'Wrong title');
        $this->assertEquals('m2', $result[1]['message'], 'Wrong message');
    }

    private function createDb()
    {
        $dbh = new \PDO('sqlite::memory:');
        $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        $dbh->exec("CREATE TABLE table1 ( id INTEGER PRIMARY KEY, title TEXT, message TEXT)");
        $dbh->exec("INSERT INTO table1 ('title', 'message') VALUES ('t1', 'm1');");
        $dbh->exec("INSERT INTO table1 ('title', 'message') VALUES ('t2', 'm2');");
        return new Db($dbh);
    }

    public function test_Select_BadTable()
    {
        $db = $this->createDb();
        try
        {
            $db->select('*', 'table2');
        }
        catch (\PDOException $e)
        {
            $this->assertEquals('SQLSTATE[HY000]: General error: 1 no such table: table2', $e->getMessage(), 'Wrong sql error message');
            return;
        }
        $this->assertTrue(false, 'Exception not throw');
    }

    public function test_Select_NormalWhere()
    {
        $db = $this->createDb();

        $whereCols = new DbWhereColumns();
        $whereCols->addCol('id', 2, '>=', DbWhereColumnType::Integer);
        $result = $db->select('*', 'table1', $whereCols);

        $this->assertEquals('t2', $result[0]['title'], 'Wrong title');
    }

    public function test_Select_WhereSameColumnName()
    {
        $db = $this->createDb();

        $whereCols = new DbWhereColumns();
        $whereCols->addCol('id', 2, '>=', DbWhereColumnType::Integer);
        $whereCols->addCol('id', 3, '<=', DbWhereColumnType::Integer);
        $result = $db->select('*', 'table1', $whereCols);

        $this->assertEquals('t2', $result[0]['title'], 'Wrong title');
    }

    public function test_Select_WhereWithLike()
    {
        $db = $this->createDb();

        $whereCols = new DbWhereColumns();
        $whereCols->addCol('title', 't%', 'like', DbWhereColumnType::String);
        $result = $db->select('*', 'table1', $whereCols);

        $this->assertEquals('t1', $result[0]['title'], 'Wrong title');
        $this->assertEquals('m1', $result[0]['message'], 'Wrong message');
        $this->assertEquals('t2', $result[1]['title'], 'Wrong title');
        $this->assertEquals('m2', $result[1]['message'], 'Wrong message');
    }

    public function test_Select_NormalOrderBy()
    {
        $db = $this->createDb();

        $result = $db->select('*', 'table1', NULL, 'id desc');

        $this->assertEquals('t2', $result[0]['title'], 'Wrong title');
        $this->assertEquals('t1', $result[1]['title'], 'Wrong title');
    }

    public function test_Select_NormalLimit()
    {
        $db = $this->createDb();

        $result = $db->select('*', 'table1', NULL, '', 1);

        $this->assertEquals(1, count($result), 'Wrong count');
    }

    public function test_Select_NormalOffset()
    {
        $db = $this->createDb();

        $result = $db->select('*', 'table1', NULL, '', 1, 1);

        $this->assertEquals('t2', $result[0]['title'], 'Wrong title');
    }

}
 