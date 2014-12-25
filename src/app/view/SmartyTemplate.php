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
        $workingDir = SERVER_HTML_BASE_PATH . SERVER_SMARTY_WORKING_PATH;
        $this->templateBaseDir = SERVER_HTML_BASE_PATH . SERVER_SMARTY_TEMPLATES_PATH;
        $this->template = new Smarty();
        $this->template->setTemplateDir($this->templateBaseDir);
        $this->template->setCompileDir($workingDir . 'templates_c');
        $this->template->setCacheDir($workingDir . 'cache');
        $this->template->setConfigDir($workingDir . 'configs');
        $this->addData('baseDir', URL_SMARTY_TEMPLATES_PATH);
    }

    public function addData($name, $data) {
        $this->template->assign($name, $data);
    }

    public function render($file = 'index.html') {
        return $this->template->fetch($this->templateBaseDir . '/' . $file);
    }
}
