<?php

namespace app\domain;

use app\db\UserDb;
use lib\framework\Helper;


class Auth {

	private $user;

	public function __construct() {
        $db = Helper::createDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $user = new UserDb($db);
		$this->user = $user;
	}

	public function isAuthenticate($username, $password) {
		$result = $this->user->getUserByUsername($username);
		if ($result) {
			$row = $result[0];
			if ($row['User_Password'] == sha1($password)) {
				return $row;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

//	private function setLogin($rowUserData) {
//		$this->session->setVar('userId', $rowUserData['User_Id']);
//		$this->session->setVar('username', $rowUserData['User_Username']);
//		$this->session->setVar('userDisplayName', $rowUserData['User_DisplayName']);
//	}

//	private function setLogout() {
////		$this->session->setVar('userId', '');
////		$this->session->setVar('username', '');
////		$this->session->setVar('userDisplayName', '');
//
//		//session_destroy();
//		//setcookie( session_name(), '', time() - 300, '/', '', 0 );
//	}

//	public function logout() {
//		$this->setLogout();
//	}
//
//	public function getUserDisplayName() {
//		return $this->session->getVar('userDisplayName');
//	}
//
//	public function getUsername() {
//		return $this->session->getVar('username');
//	}
//
//	public function getUserId() {
//		return $this->session->getVar('userId');
//	}

    // TODO:
//	public function registration($userInfo) {
//
//		if ($this->isLogin()) {
//			throw new RegistrationFailException('Already Login');
//		}
//		if (empty($userInfo->username)) {
//			throw new RegistrationFailException('Wrong Username');
//		}
//		if (empty($userInfo->password)) {
//			throw new RegistrationFailException('Wrong Password');
//		}
//		if (!filter_var($userInfo->email, FILTER_VALIDATE_EMAIL)) {
//			throw new RegistrationFailException('Wrong Email');
//		}
//		if (empty($userInfo->displayName)) {
//			throw new RegistrationFailException('Wrong Name');
//		}
//		if ($this->user->getUserDatabase()->isUsernameExist($userInfo->username)) {
//			throw new RegistrationFailException('Username already exists');
//		}
//		if ($this->user->getUserDatabase()->isEmailExist($userInfo->email)) {
//			throw new RegistrationFailException('Email already exists');
//		}
//
//		return $this->user->getUserDatabase()->addUser($userInfo);
//
//		// sendMail?
////		// If insert ok
////		if ($db->affected_rows == 1) {
////			$activateLink = sprintf('%s/activate.php?x = %s&y = %s' . "\n\n", $Hostname, $db->insert_id, $activationCode);
////			include_once( 'sendMail.lib.php' );
////			sendActivateMail($email, $activateLink);
////			exit;
////		}
//	}

//	public function isLogin() {
//		$sessionUserId = $this->session->getVar('userId');
//		if (empty($sessionUserId)) {
//			return false;
//		}
//
//		//temp before a more security way
//		//
//		// check also the ip
//		//
//		//$this->user->setUserByUsername($sessionUserId);
//
//		return true;
//		//TODO need a more security way
////		if ($sessionUserId == $this->user->getUserId()) {
////			return true;
////		} else {
////			return false;
////		}
//	}

    // TODO
//	public function activate($username, $key) {
//		if (!$this->user->getUserDatabase()->isActiveKeyMatched($username, $key)) {
//			throw new RegistrationFailException('Activate Key not match');
//		}
//		$this->user->getUserDatabase()->activate($username);
//	}

}

// TODO
// try to think of hacking use of this class and make test about it
// sendMail
// changePassword()
// forgetPassword()