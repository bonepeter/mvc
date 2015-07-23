<?php

namespace lib\framework\db;


class DbColumn
{
    private $name;

    public function getName()
    {
        return $this->name;
    }

    protected $type;

    public function getType()
    {
        return $this->type;
    }

    private $placeHolder;

    public function getPlaceHolder()
    {
        return $this->placeHolder;
    }

    function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;
        $milliseconds = round(microtime(true) * 1000);
        $this->placeHolder = sprintf(':%s_%d_%d', $name, mt_rand(), $milliseconds);
    }
} 