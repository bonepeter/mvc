<?php

namespace app\db;

use lib\framework\Db;
use lib\framework\DbWhereColumns;
use lib\framework\DbWhereColumnType;

abstract class DbTable {

    protected $db = null;
    protected $data = array();
    protected $tableName = '';
    protected $prefix = '';
    protected $idColName = '';
    protected $colsName = array();

    protected $pagingRowPerPage = 0;
    protected $pagingLimitFirstRow = 0;

    protected $crudReadable = false;
    protected $crudWritable = false;

    protected $sqlSortBy = '';

    public function __construct(Db $db) {
        $this->db = $db;
        $this->data = array();
    }

    public function resetPaging()
    {
        $this->pagingRowPerPage = 0;
        $this->pagingLimitFirstRow = 0;
    }

    public function setPagingRowPerPage($rowPerPage)
    {
        $this->pagingRowPerPage = $rowPerPage;
    }

    public function setPagingPageNo($pageNo, $rowPerPage = -1)
    {
        if ($rowPerPage != -1)
        {
            $this->setPagingRowPerPage($rowPerPage);
        }
        $this->pagingLimitFirstRow = 0;
        if ($this->pagingRowPerPage != 0)
        {
            $this->pagingLimitFirstRow = $this->pagingRowPerPage * ( $pageNo - 1 );
        }
    }

//    public function setSortString($sortingArray)
//    {
//        $sortStr = '';
//        $separator = '';
//        foreach ($sortingArray as $sortCol)
//        {
//            $desc = '';
//            if (StringHelper::endsWith($sortCol, '^'))
//            {
//                $col = substr($sortCol, 0, -1);
//                $desc = ' desc';
//            }
//            else
//            {
//                $col = $sortCol;
//            }
//            if (in_array($col, $this->colsName))
//            {
//                $sortStr .= $separator . $col . $desc;
//                $separator = ',';
//            }
//        }
//        $this->sqlSortBy = $sortStr;
//    }

    public function getCols()
    {
        return $this->colsName;
    }

    public function isCrudReadable()
    {
        return $this->crudReadable;
    }

    public function isCrudWritable()
    {
        return $this->crudWritable;
    }

    public function getTableName() {
        return $this->tableName;
    }

    public function getIdColName() {
        return $this->idColName;
    }

    public function getDataArray() {
        return $this->data;
    }

    protected function getDataItem($name) {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        } else {
            return '';
        }
    }

    public function getData($searchCols)
    {
        return $this->db->select('*', $this->tableName, $this->getDbWhereCols($searchCols),
            '', $this->pagingRowPerPage, $this->pagingLimitFirstRow);
    }

    public function getDataCount($searchCols)
    {
        $result = $this->db->select('count(*) as count', $this->tableName, $this->getDbWhereCols($searchCols));
        return $this->getDbCount($result);
    }

    public function getDataById($id) {
        $result = $this->db->select($this->tableName, '*', array($this->idColName => $id));
        $this->setDataWithMysqlResult($result);
        return $this->data;
    }

    protected function setDataWithMysqlResult($mysqlResult) {
        if ($mysqlResult) {
            $this->data = $mysqlResult[0];
        } else {
            $this->data = array();
        }
    }

    public function getAll() {
        return $this->db->select($this->tableName, '*');
    }

    public function add($data) {
        return $this->db->insert($this->tableName, $data);
    }

    public function update($data) {
        return $this->db->update($this->tableName, $data, $this->idColName);
    }

    public function deleteById($id) {
        return $this->db->delete($this->tableName, $id, $this->idColName);
    }

    public function runQuery($sql) {
        return $this->db->runQuery($sql);
    }

    public function runSelectQuery($sql) {
        return $this->db->runSelectQuery($sql);
    }

    protected function setWhereArray(&$arr, $colName, $value)
    {
        if (is_null($value))
        {
            return false;
        }
        if ($value === '')
        {
            return false;
        }
        if ($value == 0)
        {
            $arr[$colName] = $value;
        }
        if (! empty($value))
        {
            $arr[$colName] = $value;
        }
    }

    protected function setWhereSql(&$sql, $colName, $op, $value)
    {
        if (is_null($value))
        {
            return false;
        }
        if ($value == '')
        {
            return false;
        }
        if ($value == 0)
        {
            $where = $colName . $op . "'" . $value . "'";
        }
        if (! empty($value))
        {
            $where = $colName . $op . "'" . $value . "'";
        }
        if (empty($sql))
        {
            $sql = ' WHERE ' . $where;
        } else {
            $sql .= ' AND ' . $where;
        }
    }

    protected function getDbWhereCols($whereArr)
    {
        $whereCols = new DbWhereColumns();
        foreach ($whereArr as $item) {
            if (array_key_exists($item['name'], $this->colsName)) {
                $op = '=';
                if (is_array($item))
                {
                    $value = $item['value'];
                    if (isset($item['op']))
                    {
                        $op = $item['op'];
                    }
                    $type = $this->getDbWhereColType($item['name']);

                    $whereCols->addCol($this->prefix . $item['name'], $value, $op, $type);
                }
            }
        }
        return $whereCols;
    }

    protected function getDbCount($result)
    {
        return $result ? $result[0]['count'] : 0;
    }

    private function getDbWhereColType($key)
    {
        switch ($this->colsName[$key]['type']) {
            case 'int':
                return DbWhereColumnType::Integer;
            case 'string':
                return DbWhereColumnType::String;
            case 'boolean':
                return DbWhereColumnType::Boolean;
            case 'date':
                return DbWhereColumnType::String;
        }
        return DbWhereColumnType::String;
    }

}
