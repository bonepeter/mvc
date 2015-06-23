<?php

namespace lib\framework;

use \PDO;
use lib\framework\exception\DbCannotUpdateException;

class MysqlDb {

    private $dbh = null;

    function __construct(PDO $dbh) {
        $this->dbh = $dbh;
    }

    public function select($select = '*', $tableName = '', DbWhereColumns $whereColumns = NULL, $orderby = '', $limit = 0, $offset = 0) {
        if ($tableName === '')
        {
            $prepareSql = sprintf("SELECT %s", $select);
        }
        else
        {
            if (is_null($whereColumns))
            {
                $whereStr = '';
            }
            else
            {
                $whereStr = $whereColumns->getSqlString();
            }
            $orderbyStr = $this->getSelectOrderByString($orderby);
            $limitStr = $this->getSelectLimitString($limit, $offset);
            $prepareSql = sprintf("SELECT %s FROM %s %s %s %s;", $select, $tableName, $whereStr, $orderbyStr, $limitStr);
        }
        $stmt = $this->dbh->prepare($prepareSql);
        if (! is_null($whereColumns))
        {
            $whereColumns->bindValueToStatement($stmt);

        }
        return $this->executeSql($stmt);
    }

    private function getSelectOrderByString($orderBy)
    {
        return ($orderBy == '') ? '' : ' ORDER BY ' . $orderBy;
    }

    private function getSelectLimitString($limit, $offset)
    {
        $limitStr = '';
        if (is_numeric($limit) && is_numeric($offset)) {
            if ($limit != 0) {
                $limitStr = ' LIMIT ' . $limit;
                if ($offset != 0) {
                    $limitStr .= ' OFFSET ' . $offset;
                }
            }
        }
        return $limitStr;
    }

    private function executeSql(\PDOStatement $stmt)
    {
        return $stmt->execute() ? $stmt->fetchAll(PDO::FETCH_ASSOC) : false;
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
