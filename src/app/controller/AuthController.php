<?php

namespace app\controller;

use app\db\LogDb;
use app\domain\Auth;
use lib\framework\Helper;
use lib\helper\HttpHelper;
use app\view\ViewFactory;
use app\controller\session\PhpSession;

class AuthController
{
    public function loginForm() {
        $view = ViewFactory::makeView('SmartyTemplate');
        echo $view->render('login_form');
    }

    public function login($username = '', $password = '')
    {
        $auth = new Auth();
        $row = $auth->isAuthenticate($username, $password);
        if ($row) {
            $this->setLogin($row);
            $this->log('Auth', 'Login: ' . $username);
        } else {
            $this->setLogout();
            $this->log('Auth', 'Login Fail: ' . $username);
        }
        $this->redirect($this->isLogin());
    }

    private function setLogin($rowUserData) {
        $session = new PhpSession();
        $session->setVar('userId', $rowUserData['User_Id']);
        $session->setVar('username', $rowUserData['User_Username']);
        $session->setVar('userDisplayName', $rowUserData['User_DisplayName']);
    }

    private function setLogout() {
        $session = new PhpSession();
		$session->setVar('userId', '');
		$session->setVar('username', '');
		$session->setVar('userDisplayName', '');
        session_destroy();
        setcookie(session_name(), '', time() - 300, '/', '', 0);
    }

    public function logout()
    {
        $session = new PhpSession();
        $username = $session->getVar('username');

        $this->setLogout();

        $this->log('Auth', 'Logout: ' . $username);
        $this->redirect($this->isLogin());
    }

    public function isLogin() {
        $session = new PhpSession();
        $sessionUserId = $session->getVar('userId');
        if (empty($sessionUserId)) {
            return false;
        }

        //temp before a more security way
        //
        // check also the ip
        //
        //$this->user->setUserByUsername($sessionUserId);

        return true;
        //TODO need a more security way
//		if ($sessionUserId == $this->user->getUserId()) {
//			return true;
//		} else {
//			return false;
//		}
    }

    private function redirect($isLogin) {
        if ($isLogin) {
            HttpHelper::redirect('index.php');
        } else {
            HttpHelper::redirect('login.php');
        }
    }

    private function log($type, $message)
    {
        //$log = DbModelFactory::makeDbModel('Log');
        $db = Helper::createDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $log = new LogDb($db);
        $log->log($type, $message);
    }

}