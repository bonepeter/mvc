<?php

namespace app\db;

use lib\framework\Db;

class LogDb extends DbTable
{
    public function __construct(Db $db) {
        parent::__construct($db);
        $this->tableName = 'Log';
        $this->idColName = 'Log_Id';
        $this->colsName = array('Log_Id', 'Log_Type', 'Log_Message', 'Log_CreateDate');
    }

    public function log($info, $message) {
        $data = array('Log_Type' => $info, 'Log_Message' => $message);
        return $this->add($data);
    }
}
