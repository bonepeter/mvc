<?php

namespace app\db;

use lib\framework\Db;

class UserDb extends DbTable
{
	public function __construct(Db $db)
    {
		parent::__construct($db);
		$this->tableName = 'user';
		$this->idColName = 'User_Id';
        $this->cols = array(
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
		return $this->db->select($this->tableName, '*', array('User_Username' => $username));
	}

}
