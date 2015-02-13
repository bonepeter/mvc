<?php

namespace lib\framework;

use \PDO;

class DbWhereColumns
{
    private $cols;
    private $colCount;

    function __construct()
    {
        $this->cols = array();
        $this->colCount = 0;
    }

    public function addCol($name, $value, $op = '=', $type = DbWhereColumnType::String)
    {
        $this->colCount ++;
        $col = array(
            'name' => $name,
            'value' => $value,
            'op' => $op,
            'type' => $type,
            'placeHolder' => sprintf(':%s_%d_%d', $name, rand(1000, 9999), $this->colCount),
        );
        array_push($this->cols, $col);
    }

    public function getSqlString()
    {
        $whereStr = '';
        foreach ($this->cols as $whereCol) {
            $str = sprintf('%s %s %s', $whereCol['name'], $whereCol['op'], $whereCol['placeHolder']);
            $whereStr .= $str . ' AND ';
        }
        return ($whereStr === '') ? '' : 'WHERE ' . substr($whereStr, 0, -5);
    }

    public function bindValueToStatement(\PDOStatement $stmt)
    {
        foreach ($this->cols as $whereCol) {
            $stmt->bindValue($whereCol['placeHolder'], $whereCol['value'], $whereCol['type']);
        }
    }
}

abstract class DbWhereColumnType
{
    const String = PDO::PARAM_STR;
    const Integer = PDO::PARAM_INT;
    const Boolean = PDO::PARAM_BOOL;
}