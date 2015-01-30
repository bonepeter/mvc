<?php

namespace app\controller\session;

class PhpSession implements Session {

	public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
	}

	public function getVar($varName) {
		return isset($_SESSION[$varName]) ? $_SESSION[$varName] : null;
	}

	public function setVar($varName, $value) {
		$_SESSION[$varName] = $value;
	}

}
