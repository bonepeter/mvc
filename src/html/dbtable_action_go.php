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
    if ($action == 'add') {
        $controller->add();
    }
    if ($action == 'edit') {
        $controller->edit();
    }
    if ($action == 'delete') {
        $controller->delete();
    }
} catch (\Exception $e) {
    echo $e->getMessage();
    exit;
}

