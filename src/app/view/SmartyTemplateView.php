<?php

namespace app\view;

class SmartyTemplateView
{
    public function render($contentFile = 'index')
    {
        $template = new SmartyTemplate();
        $template->addData('contentFile', $contentFile . '.html');
        return $template->render('main.html');
    }
} 