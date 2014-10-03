<?php

namespace html;

use app\controller\ControllerFactory;

require_once '../lib/framework/main.php';

require_once 'isLogin.inc.php';

$index = ControllerFactory::makeController('index');

$index->view();
