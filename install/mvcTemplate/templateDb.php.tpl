<?php

namespace app\db;

use lib\framework\Db;

class /* CapitalName */Db extends DbTable
{
    public function __construct(Db $db)
    {
        parent::__construct($db);
        $this->tableName = '/* Name */';
        $this->idColName = '/* CapitalName */_Id';
        $this->cols = array(
/* colArray */        );
        $this->crudReadable = true;
        $this->crudWritable = true;
    }
}
