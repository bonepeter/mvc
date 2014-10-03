<?php

namespace app\controller;

use app\model\DbModelFactory;
use lib\framework\Auth;
use lib\framework\session\PhpSession;
use lib\helper\Creator;
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

    public function getAll() {
        $model = $this->getDbModel();
        $result = $model->getAll();
        return $result;
    }

    public function getAllDisplayBySmartyTemplate() {
//        $model = $this->getDbModel();
//        $result = $model->getAll();

        $view = $this->getView('TestSmarty');
        echo $view->render();
    }

    public function testLog() {
        $model = $this->getDbModel('Log');
        $model->addLog('info', 'message2');
        echo 'run testLog';
    }

    public function testGetLog() {
        $model = $this->getDbModel('Log');
        var_dump($model->getAll());
    }

    public function testGetUserByUsername($user) {
        $model = $this->getDbModel('User');
        var_dump($model->getUserByUsername($user));
    }

    public function testAuth() {
        $user = $this->getDbModel('User');
        $session = new PhpSession();
        $auth = new Auth($user, $session);
        $auth->logout();
        var_dump($auth->isLogin());
        $auth->login('peter', 'peter');
        var_dump($auth->isLogin());
        echo 'auth';
    }

    public function testSimCard() {
        $model = $this->getDbModel('SimCard');
        $data = array('SimCard_Imei' => '1', 'SimCard_SN' => '1', 'SimCard_Number' => '1', 'SimCard_Status' => '1');
        $model->add($data);
        var_dump($model->getAll());
    }

    public function testStore() {
        $model = $this->getDbModel('Store');
        $data = array('Store_SimCardId' => '1', 'Store_Action' => '1', 'Store_Location' => '1',
            'Store_BoxNo' => '1', 'Store_Date' => date("Y-m-d H:i:s"), 'Store_Sender' => '1',
            'Store_Receiver' => '1', 'Store_Remark' => '1');
        $model->add($data);
        var_dump($model->getAll());
    }
}