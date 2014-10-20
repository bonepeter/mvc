<?php

namespace html;

require_once '../lib/framework/main.php';

use app\controller\ControllerFactory;

$controller = ControllerFactory::makeController('Auth');
$controller->loginForm();