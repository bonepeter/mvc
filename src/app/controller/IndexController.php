<?php

namespace app\controller;

use lib\framework\Controller;

class IndexController extends Controller
{
    public function view() {
        $view = $this->getView('SmartyTemplate');
        echo $view->render('index');
    }
}