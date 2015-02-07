<?php

namespace unitTest\lib\framework;

require_once __DIR__ . '/../../../src/lib/framework/autoLoader.php';

use lib\framework\Db;
use lib\framework\DbWhereCol;
use lib\framework\DbWhereColType;

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

        $whereArr = array(new DbWhereCol('id', 2, '>=', DbWhereColType::Integer));
        $result = $db->select('*', 'table1', $whereArr);

        $this->assertEquals('t2', $result[0]['title'], 'Wrong title');
    }

    public function test_Select_WhereWithLike()
    {
        $db = $this->createDb();

        $whereArr = array(new DbWhereCol('title', 't%', 'like', DbWhereColType::String));
        $result = $db->select('*', 'table1', $whereArr);

        $this->assertEquals('t1', $result[0]['title'], 'Wrong title');
        $this->assertEquals('m1', $result[0]['message'], 'Wrong message');
        $this->assertEquals('t2', $result[1]['title'], 'Wrong title');
        $this->assertEquals('m2', $result[1]['message'], 'Wrong message');
    }

    public function test_Select_NormalOrderBy()
    {
        $db = $this->createDb();

        $result = $db->select('*', 'table1', array(), 'id desc');

        $this->assertEquals('t2', $result[0]['title'], 'Wrong title');
        $this->assertEquals('t1', $result[1]['title'], 'Wrong title');
    }

    public function test_Select_NormalLimit()
    {
        $db = $this->createDb();

        $result = $db->select('*', 'table1', array(), '', 1);

        $this->assertEquals(1, count($result), 'Wrong count');
    }

    public function test_Select_NormalOffset()
    {
        $db = $this->createDb();

        $result = $db->select('*', 'table1', array(), '', 1, 1);

        $this->assertEquals('t2', $result[0]['title'], 'Wrong title');
    }


    /*
     * Test DbWhereCol Class
     */

    public function test_DbWhereCol_getSqlString()
    {
        $col = new DbWhereCol('name1', 'value1', 'op1');
        $result = $col->getSqlString();
        $this->assertEquals('name1 op1 :name1', $result, 'wrong getSqlString');

    }

}
 