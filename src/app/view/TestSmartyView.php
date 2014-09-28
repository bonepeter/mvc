<?php
/**
 * Project: framework
 * User: peter
 * Date: 20140914
 */

namespace app\view;


class TestSmartyView
{
    public function render($data)
    {
        $template = new SmartyTemplate();
        $template->addData('name', 'Ned');
        $template->addData('data', $data);
        return $template->render();
    }

} 