<?php

namespace html;

use app\controller\UserController;
use lib\helper\HttpHelper;

require_once __DIR__ . '/../lib/framework/main.php';

require_once 'isLogin.inc.php';

$id = HttpHelper::getRequest('id', 'post');
$username = HttpHelper::getRequest('username', 'post');
$password = HttpHelper::getRequest('password', 'post');
$displayName = HttpHelper::getRequest('displayName', 'post');
$level = HttpHelper::getRequest('level', 'post');

$user = new UserController();
$user->edit($id, $username, $password, $displayName, $level);
