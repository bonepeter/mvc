<?php

namespace app\controller;

/**
 * For unit test
 * @package app\controller
 */
class TestController extends DbController
{
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