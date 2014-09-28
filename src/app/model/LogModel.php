<?php
/**
 * Project: framework
 * User: peter
 * Date: 20140914
 */

namespace app\model;

use lib\framework\Db;

class LogModel extends DbModel {

    public function __construct(Db $db) {
        parent::__construct($db);
        $this->tableName = 'Log';
        $this->idColName = 'Log_Id';
        $this->colsName = array('Log_Id', 'Log_Type', 'Log_Message');
    }

    public function addLog($info, $message) {
        $data = array('Log_Type' => $info, 'Log_Message' => $message);
        return $this->add($data);
    }

}
