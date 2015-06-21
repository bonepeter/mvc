<?php

namespace app\db;

use lib\framework\Db;
use lib\framework\DbWhereColumns;

class UserDb extends DbTable
{
	public function __construct(Db $db)
    {
		parent::__construct($db);
		$this->tableName = 'user';
		$this->idColName = 'User_Id';
        $this->colsName = array(
            array('name' => 'User_Id'),
            array('name' => 'User_Username'),
            array('name' => 'User_Password'),
            array('name' => 'User_DisplayName'),
            array('name' => 'User_Level'),
        );
        $this->crudReadable = true;
        $this->crudWritable = true;
	}

	public function getUserByUsername($username) {
        $whereCols = new DbWhereColumns();
        $whereCols->addCol('User_Username', $username);
        return $this->db->select('*', $this->tableName, $whereCols);
	}

}
