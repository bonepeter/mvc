<?php

require_once '../lib/framework/AutoLoader.php';

require_once '../config/config.php';

// should use factory to new controller
$ttt = new \app\controller\TestController();

echo $ttt->testMethod('ss');

echo $ttt->testSmarty();
