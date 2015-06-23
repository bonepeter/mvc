<?php

namespace lib\framework\db;

use lib\framework\adt\Adt;

class DbCondition
{
    /**
     * @var DbColumn
     */
    private $column;

    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @var Adt
     */
    private $adt;

    public function getAdt()
    {
        return $this->adt;
    }

    private $op;

    public function getOp()
    {
        return $this->op;
    }

    function __construct($column, $adt, $op = '=')
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