<?php

namespace html;

use app\controller\DbCrudController;
use lib\helper\HttpHelper;

require_once __DIR__ . '/../lib/framework/main.php';

require_once 'isLogin.inc.php';

$table = HttpHelper::getRequest('table', 'get');
$id = HttpHelper::getRequest('id', 'get');
$pageNo = HttpHelper::getRequest('page', 'get');
$sortBy = HttpHelper::getRequest('sort', 'get');

try {
    $controller = new DbCrudController($table);
    $controller->display($id, $pageNo, $sortBy);
} catch (\Exception $e) {
    echo $e->getMessage();
    exit;
}
