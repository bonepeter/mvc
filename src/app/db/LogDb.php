<?php

namespace app\db;

use lib\framework\Db;

class LogDb extends DbTable
{
    public function __construct(Db $db)
    {
        parent::__construct($db);
        $this->tableName = 'log';
        $this->idColName = 'Log_Id';
        $this->cols = array(
            array('name' => 'Log_Id'),
            array('name' => 'Log_Type'),
            array('name' => 'Log_Message'),
            array('name' => 'Log_CreateDate'),
        );
        $this->crudReadable = true;
        $this->crudWritable = false;
    }

    public function log($info, $message) {
        $data = array('Log_Type' => $info, 'Log_Message' => $message);
        return $this->add($data);
    }
}
