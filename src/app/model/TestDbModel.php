<?php
/**
 * Project: framework
 * User: peter
 * Date: 20140914
 */

namespace app\model;

use lib\framework\Db;

class TestDbModel extends DbModel {

    public function __construct(Db $db) {
        parent::__construct($db);
        $this->tableName = 'Test';
        $this->idColName = 'Test_Id';
        $this->colsName = array('Test_Id', 'Test_Field1');
    }

}
