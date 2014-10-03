<?php
/**
 * Project: framework
 * User: peter
 * Date: 20140914
 */

namespace app\view;

use Smarty;

require_once __DIR__ . '/../../config/config.php';
require_once '/usr/local/lib/php/Smarty/Smarty.class.php';

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
    }

    public function addData($name, $data) {
        $this->template->assign($name, $data);
    }

    public function render() {
        return $this->template->fetch($this->templateBaseDir . '/index.tpl');
    }
}
