<?php

namespace app\controller;

use app\model\DbModelFactory;
use lib\helper\HttpHelper;

class AuthController extends DbController
{
    public function loginForm() {
        $view = $this->getView('SmartyTemplate');
        echo $view->render('login');
    }

    public function login($username = '', $password = '') {
        $auth = DbModelFactory::makeDbModel('Auth');
        $auth->login($username, $password);
        $this->log('Auth', 'Login: ' . $username);
        $this->redirect($auth->isLogin());
    }

    public function logout() {
        $auth = DbModelFactory::makeDbModel('Auth');
        $username = $auth->getUsername();
        $auth->logout();
        $this->log('Auth', 'Logout: ' . $username);
        $this->redirect($auth->isLogin());
    }

    private function redirect($isLogin) {
        if ($isLogin) {
            HttpHelper::redirect('index.php');
        } else {
            HttpHelper::redirect('login_form.php');
        }
    }

}