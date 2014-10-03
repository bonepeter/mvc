<?php
/**
 * Project: framework
 * User: peter
 * Date: 20140914
 */

namespace app\view;

use Smarty;

require_once '/usr/local/lib/php/Smarty/Smarty.class.php';

require_once __DIR__ . '/../../config/config.php';

class SmartyTemplate
{
    private $template;
    private $templateBaseDir;

    public function __construct()
    {
        $baseDir = SMARTY_TEMPLATE_PATH;
        $this->templateBaseDir = $baseDir . 'templates/';
        $this->template = new Smarty();
        $this->template->setTemplateDir($this->templateBaseDir);
        $this->template->setCompileDir($baseDir . 'templates_c');
        $this->template->setCacheDir($baseDir . 'cache');
        $this->template->setConfigDir($baseDir . 'configs');
        $this->addData('baseDir', SMARTY_WEB_PATH . 'templates/');
    }

    public function addData($name, $data) {
        $this->template->assign($name, $data);
    }

    public function render($file = 'index.html') {
        return $this->template->fetch($this->templateBaseDir . '/' . $file);
    }
}
