<?php

namespace lib\framework\db\statement;


class SelectStatement
{
    private $tables = array();

    public function addTable($tableName)
    {
        array_push($this->tables, $tableName);
    }

    public function getTables()
    {
        return $this->tables;
    }

    public function isNoTable()
    {
        return empty($this->tables);
    }


    private $selectAttributes = array();

    public function addSelectAttribute($attribute)
    {
        array_push($this->selectAttributes, $attribute);
    }

    public function getSelectAttributes()
    {
        return $this->selectAttributes;
    }
} 