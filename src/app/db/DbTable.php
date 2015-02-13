<?php

namespace app\db;

use lib\framework\Db;
use lib\framework\DbWhereColumns;
use lib\framework\DbWhereColumnType;

abstract class DbTable {

    protected $db = null;
    protected $data = array();
    protected $tableName = '';
    protected $idColName = '';
    protected $cols = array();
    protected $crudReadable = false;
    protected $crudWritable = false;

    public function __construct(Db $db) {
        $this->db = $db;
        $this->data = array();
    }

    public function getTableName() {
        return $this->tableName;
    }

    public function getIdColName() {
        return $this->idColName;
    }

    public function getCols()
    {
        return $this->cols;
    }

    public function isCrudReadable()
    {
        return $this->crudReadable;
    }

    public function isCrudWritable()
    {
        return $this->crudWritable;
    }

    public function getDataById($id) {
        $whereCols = new DbWhereColumns();
        $whereCols->addCol($this->idColName, $id);
        $result = $this->db->select('*', $this->tableName, $whereCols);
        $this->setDataWithMysqlResult($result);
        return $this->data;
    }

    private function setDataWithMysqlResult($mysqlResult) {
        if ($mysqlResult) {
            $this->data = $mysqlResult[0];
        } else {
            $this->data = array();
        }
    }

//    public function getAll() {
//        return $this->db->select('*', $this->tableName);
//    }

    public function getData($whereArr = array(), $orderbyStr = '', $rowEachPage = 0, $pageNo = 0)
    {
        $whereColArr = $this->getWhereColArray($whereArr);
        if ($whereColArr === false)
        {
            return false;
        }

        $limitFirstRow = 0;
        if ($rowEachPage != 0)
        {
            $limitFirstRow = $rowEachPage * ( $pageNo - 1 );
        }

        return $this->db->select('*', $this->tableName, $whereColArr, $orderbyStr, $rowEachPage, $limitFirstRow);
    }

    private function getWhereColArray($whereArr)
    {
        $whereColArr = new DbWhereColumns();
        foreach ($whereArr as $key => $value)
        {
            if (! in_array(array('name' => $key), $this->cols))
            {
                // TODO: throw exception
                return false;
            }
            $whereColArr->addCol($key, $value);
            //$whereColArr = array(new DbWhereCol($key, $value));
        }
        return $whereColArr;
    }

    public function getRowCount($whereArr = array())
    {
        $whereColArr = $this->getWhereColArray($whereArr);
        if ($whereColArr === false)
        {
            return false;
        }

        $result = $this->db->select('count(*) as count', $this->tableName, $whereColArr);
        if ($result)
        {
            return $result[0]['count'];
        }
        else
        {
            return 0;
        }
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

}
