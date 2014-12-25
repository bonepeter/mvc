<?php

namespace app\view;

use Smarty;

require_once __DIR__ . '/../../config/config.php';

require_once SERVER_SMARTY_LIB_PATH;

class SmartyTemplate
{
    private $template;
    private $templateBaseDir;

    public function __construct()
    {
        $this->templateBaseDir = SERVER_HTML_BASE_PATH . SERVER_SMARTY_TEMPLATES_PATH;
        $this->template = new Smarty();
        $this->template->setTemplateDir($this->templateBaseDir);
        $this->template->setCompileDir(SERVER_SMARTY_WORKING_PATH . 'templates_c');
        $this->template->setCacheDir(SERVER_SMARTY_WORKING_PATH . 'cache');
        $this->template->setConfigDir(SERVER_SMARTY_WORKING_PATH . 'configs');
        $this->addData('baseDir', URL_SMARTY_TEMPLATES_PATH);
    }

    public function addData($name, $data) {
        $this->template->assign($name, $data);
    }

    public function render($file = 'index.html') {
        return $this->template->fetch($this->templateBaseDir . '/' . $file);
    }
}
