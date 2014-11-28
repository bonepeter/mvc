<?php

namespace app\db;

use lib\framework\Db;

abstract class DbTable {

    protected $db = null;
    protected $data = array();
    protected $tableName = '';
    protected $idColName = '';
    protected $colsName = array();

    public function __construct(Db $db) {
        $this->db = $db;
        $this->data = array();
    }

    public function getTableName() {
        return $this->tableName;
    }

    public function getColsName() {
        return $this->colsName;
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

}
