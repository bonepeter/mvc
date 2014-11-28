<?php

namespace html;

use app\controller\UserController;
use lib\helper\HttpHelper;

require_once __DIR__ . '/../lib/framework/main.php';

require_once 'isLogin.inc.php';

$username = HttpHelper::getRequest('username', 'post');
$password = HttpHelper::getRequest('password', 'post');
$displayName = HttpHelper::getRequest('displayName', 'post');
$level = HttpHelper::getRequest('level', 'post');

$user = new UserController();
$user->add($username, $password, $displayName, $level);
