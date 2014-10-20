<?php

namespace html;

require_once '../lib/framework/main.php';

use app\controller\ControllerFactory;
use lib\helper\HttpHelper;

$username = HttpHelper::getRequest('username', 'post');
$password = HttpHelper::getRequest('password', 'post');

$controller = ControllerFactory::makeController('Auth');
$controller->login($username, $password);