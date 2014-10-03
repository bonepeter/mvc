<?php

namespace app\controller;

class IndexController extends DbController
{
    public function view() {
        $view = $this->getView('SmartyTemplate');
        echo $view->render('index');
    }
}