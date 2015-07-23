<?php

namespace lib\framework\db;

use lib\framework\adt\Adt;

class DbCondition
{
    /**
     * @var DbColumn
     */
    private $column;

    public function getColumnName()
    {
        return $this->column->getName();
    }

    public function getColumnDbType()
    {
        return $this->column->getType();
    }

    public function getColumnPlaceHolder()
    {
        return $this->column->getPlaceHolder();
    }

    /**
     * @var Adt
     */
    private $adt;

    public function getValue()
    {
        return $this->adt->getValue();
    }

    private $op;

    public function getOp()
    {
        return $this->op;
    }

    function __construct(DbColumn $column, Adt $adt, $op = '=')
    {
        $this->column = $column;
        $this->adt = $adt;
        $this->op = $op;
    }

    function isConstant()
    {
        return $this->column->getType() == 'constant';
    }

}