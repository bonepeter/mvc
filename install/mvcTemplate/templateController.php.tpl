<?php

namespace app\controller;

use app\db\/* CapitalName */Db;
use lib\framework\Helper;

class /* CapitalName */Controller extends DbCrudController
{
    function __construct()
    {
        $this->tableName = '/* Name */';
        $this->displayTemplate = '/* Name */_form';
        $this->db = Helper::createDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $this->table = new /* CapitalName */Db($this->db);
        if (! $this->table->isCrudReadable())
        {
            throw new \Exception('Table not readable.');
        }
    }
}