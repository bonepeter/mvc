<?php

namespace lib\framework\session;

class PhpSession implements Session {

	public function __construct() {
		session_start();
	}

	public function getVar($varName) {
		return isset($_SESSION[$varName]) ? $_SESSION[$varName] : null;
	}

	public function setVar($varName, $value) {
		$_SESSION[$varName] = $value;
	}

}
