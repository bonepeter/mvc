<?php

namespace html;

use app\controller\/* CapitalName */Controller;
use lib\helper\HttpHelper;

require_once __DIR__ . '/../lib/framework/main.php';

require_once 'isLogin.inc.php';

$id = HttpHelper::getRequest('id', 'get');
$pageNo = HttpHelper::getRequest('page', 'get');
$sortBy = HttpHelper::getRequest('sort', 'get');

if (is_null($pageNo))
{
    $pageNo = 1;
}

try {
    $controller = new /* CapitalName */Controller();
    $controller->display($id, $pageNo, $sortBy);
} catch (\Exception $e) {
    echo $e->getMessage();
    exit;
}
