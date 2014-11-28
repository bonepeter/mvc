<?php

namespace html;

use app\controller\IndexController;

require_once __DIR__ . '/../lib/framework/main.php';

require_once 'isLogin.inc.php';

$index = new IndexController();
$index->view();
