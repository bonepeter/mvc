<?php

namespace html;

require_once '../lib/framework/main.php';

use app\controller\AuthController;

$controller = new AuthController();
$controller->loginForm();