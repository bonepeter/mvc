<?php

namespace app\db;

use lib\framework\Db;

class UserDb extends DbTable
{
	public function __construct(Db $db)
    {
		parent::__construct($db);
		$this->tableName = 'User';
		$this->idColName = 'User_Id';
		$this->colsName = array('User_Id', 'User_Username', 'User_Password',
            'User_DisplayName', 'User_Level');
	}

	public function getUserByUsername($username) {
		return $this->db->select($this->tableName, '*', array('User_Username' => $username));
	}

}
