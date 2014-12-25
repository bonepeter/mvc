<?php

namespace app\controller;

use app\view\SmartyTemplateView;

class IndexController
{
    public function view() {
        $view = new SmartyTemplateView();
        echo $view->render('index');
    }
}