<?php

namespace app\controller;

class IndexController extends DbController
{
    public function view() {
        echo 'It works!';
    }

    public function testMethod($arg1 = '', $arg2 = '', $arg3 = '') {
        $retTail = '';
        $retTail .= empty($arg1) ? '' : ' ' . $arg1;
        $retTail .= empty($arg2) ? '' : ' ' . $arg2;
        $retTail .= empty($arg3) ? '' : ' ' . $arg3;
        return 'TestController->testMethod' . $retTail;
    }

    public function testModel() {
        $model = $this->getDbModel();
        $result = $model->getAll();
        return $result;
    }

    public function testSmarty() {
        $model = $this->getDbModel();
        $result = $model->getAll();

        $view = $this->getView('TestSmarty');
        echo $view->render($result);
    }
}