<?php

namespace lib\framework\db;


class DbColumn
{
    private $name;

    public function getName()
    {
        return $this->name;
    }

    private $type;

    public function getType()
    {
        return $this->type;
    }

    function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;
    }
} 