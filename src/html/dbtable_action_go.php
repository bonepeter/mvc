<?php

namespace html;

use app\controller\DbCrudController;
use lib\helper\HttpHelper;

require_once __DIR__ . '/../lib/framework/main.php';

require_once 'isLogin.inc.php';

$table = HttpHelper::getRequest('table', 'post');
$action = HttpHelper::getRequest('action', 'post');

try {
    $controller = new DbCrudController($table);
    switch ($action)
    {
        case 'search':
            $controller->display(); break;
        case 'add':
            $controller->add(); break;
        case 'edit':
            $controller->edit(); break;
        case 'delete':
            $controller->delete(); break;
    }
} catch (\Exception $e) {
    echo $e->getMessage();
    exit;
}

