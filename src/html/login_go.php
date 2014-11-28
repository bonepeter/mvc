<?php

namespace html;

require_once __DIR__ . '/../lib/framework/main.php';

use app\controller\AuthController;
use lib\helper\HttpHelper;

$username = HttpHelper::getRequest('username', 'post');
$password = HttpHelper::getRequest('password', 'post');

$controller = new AuthController();
$controller->login($username, $password);