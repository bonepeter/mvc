<?php

namespace html;

require_once '../lib/framework/main.php';

use app\controller\ControllerFactory;

$auth = ControllerFactory::makeController('Auth');
$auth->logout();
