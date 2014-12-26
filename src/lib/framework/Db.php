<?php

namespace lib\framework;

use \PDO;
use lib\framework\exception\DbCannotUpdateException;

class Db {

    private $dbh = null;

    /**
     * @param $dbh PDO object. Can be obtained by createSqliteMemoryPdo or createMysqlPdo
     */
    function __construct($dbh) {
        $this->dbh = $dbh;
    }

//    public static function createSqliteMemoryPdo() {
//        $pdo = new PDO('sqlite::memory:');
//        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//        return $pdo;
//    }
//
//    public static function createMysqlPdo($host, $user, $pass, $dbName) {
//        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8', $host, $dbName);
//        $pdo = new PDO($dsn, $user, $pass);
//        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//        return $pdo;
//    }

    public function dbh() {
        return $this->dbh;
    }

    /**
     * Get rows from a database table
     * TODO check SQL injection
     *
     * @param string $tableName Table name
     * @param string $select Select column name
     * @param array $where Where statement of the sql (without WHERE)
     * 	developer needs to escape the input string
     * @param string $orderby Order By statement of the sql (without ORDER BY)
     * 	developer needs to escape the input string
     * @param string $limit Limit statement of the sql (without LIMIT)
     * 	developer needs to escape the input string
     * @return array Result of the select statement
     */
    public function select($tableName, $select = '*', $where = array(), $orderby = '', $limit = '') {
        $whereStr = 'WHERE ';

        foreach ($where as $key => $value) {
            $whereStr .= sprintf("%s=:%s AND ", $key, $key);
        }
        if ($whereStr == 'WHERE ') {
            $whereStr = '';
        } else {
            $whereStr = substr($whereStr, 0, -5);
        }

        // todo: need to check for sql injection
        $orderbyStr = '';
        if ($orderby != '') {
            $orderbyStr = ' ORDER BY ' . $orderby;
        }

        $limitStr = '';
        if ($limit != '') {
            $limitStr = ' LIMIT ' . $limit;
        }

        $prepareSql = sprintf("SELECT %s FROM %s %s %s %s;", $select, $tableName, $whereStr, $orderbyStr, $limitStr);
        $stmt = $this->dbh->prepare($prepareSql);
        $isSqlRunOk = $stmt->execute($where);
        if ($isSqlRunOk) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    /**
     * Insert values to a database table
     *
     * @param string $tableName Table name
     * @param array $row Array as field name => value
     * @return boolean False if failure, insert id if success
     */
    public function insert($tableName, $row) {
        // Set sql string
        $setStr = '';
        $valueStr = '';
        foreach ($row as $key => $value) {
            $setStr .= $key . ',';
            $valueStr .= ':' . $key . ',';
        }
        $setStr = substr($setStr, 0, -1);
        $valueStr = substr($valueStr, 0, -1);
        $prepareSql = sprintf("INSERT INTO %s (%s) VALUES (%s)", $tableName, $setStr, $valueStr);

        // Run sql
        $stmt = $this->dbh->prepare($prepareSql);
        $execOk = $stmt->execute($row);

        // Return value
        if ($execOk) {
            if ($stmt->rowCount() == 1) {
                return $this->dbh->lastInsertId();
            }
        }
        return false;
    }

    /**
     * Update values to a database table
     *
     * @param string $tableName Table name
     * @param array $row Array as field name => value
     * @param string $idColName
     * @return boolean False if failure, true if success
     * @throws DbCannotUpdateException
     */
    public function update($tableName, $row, $idColName = 'id') {
        if (!is_array($row)) {
            throw new DbCannotUpdateException('Input row is not an array');
        }
        $setStr = 'SET ';
        $whereStr = '';
        foreach ($row as $key => $value) {
            if ($key == $idColName) {
                $whereStr = $key . '=' . ':' . $key;
            } else {
                $setStr .= $key . '=' . ':' . $key . ',';
            }
        }

        if (empty($whereStr) || $setStr == 'SET ') {
            throw new DbCannotUpdateException('Wrong arguments');
        }
        $setStr = substr($setStr, 0, -1);
        $prepareSql = sprintf("UPDATE %s %s WHERE %s", $tableName, $setStr, $whereStr);

        // Run sql
        $stmt = $this->dbh->prepare($prepareSql);
        $execOk = $stmt->execute($row);

        // Return value
        if ($execOk) {
            return true;
        }
        return false;
    }

    /**
     * Delete a row from a database table
     *
     * Note: The column name is default as 'id'
     * Note: If $idValue = '', delete the whole table
     *
     * @param string $tableName Table name
     * @param string $idValue Search value of the deleting row
     * @param string $idColName Search column name of the deleting row
     *
     * @return boolean true if successfully delete the row
     */
    public function delete($tableName, $idValue = '', $idColName = 'id') {
        if (empty($idValue)) {
            $prepareSql = sprintf("DELETE FROM %s;", $tableName);
            $stmt = $this->dbh->prepare($prepareSql);
            return $stmt->execute();
        } else {
            $prepareSql = sprintf("DELETE FROM %s WHERE %s=:%s;", $tableName, $idColName, $idColName);
            $stmt = $this->dbh->prepare($prepareSql);
            return $stmt->execute(array(':' . $idColName => $idValue));
        }
    }

    public function runQuery($sql, $data = array()) {
        //var_dump($sql);
        $stmt = $this->dbh->prepare($sql);
        return $stmt->execute($data);
    }

    public function runSelectQuery($sql, $data = array()) {
        $stmt = $this->dbh->prepare($sql);
        $isSqlRunOk = $stmt->execute($data);
        if ($isSqlRunOk) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

}
