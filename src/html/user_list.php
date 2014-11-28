<?php

namespace html;

use app\controller\UserController;
use lib\helper\HttpHelper;

require_once __DIR__ . '/../lib/framework/main.php';

require_once 'isLogin.inc.php';

$id = HttpHelper::getRequest('id', 'get');

$user = new UserController();
$user->display($id);
