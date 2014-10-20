<?php

namespace app\controller;

use lib\helper\HttpHelper;
use app\view\ViewFactory;

class AuthController extends DbController
{
    public function loginForm() {
        $view = ViewFactory::makeView('SmartyTemplate');
        echo $view->render('login_form');
    }

    public function login($username = '', $password = '') {
        $model = $this->makeModel('Auth');
        $model->login($username, $password);
        $this->log('Auth', 'Login: ' . $username);
        $this->redirect($model->isLogin());
    }

    public function logout() {
        $model = $this->makeModel('Auth');
        $username = $model->getUsername();
        $model->logout();
        $this->log('Auth', 'Logout: ' . $username);
        $this->redirect($model->isLogin());
    }

    private function redirect($isLogin) {
        if ($isLogin) {
            HttpHelper::redirect('index.php');
        } else {
            HttpHelper::redirect('login.php');
        }
    }

}