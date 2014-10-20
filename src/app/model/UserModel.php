<?php

namespace app\model;

use lib\framework\Db;

class UserModel extends DbModel {

	public function __construct(Db $db) {
		parent::__construct($db);
		$this->tableName = 'User';
		$this->idColName = 'User_Id';
		$this->colsName = array('User_Username', 'User_Password', 'User_DisplayName');
	}

	public function getUserByUsername($username) {
		return $this->db->select($this->tableName, '*', array('User_Username' => $username));
	}

}
