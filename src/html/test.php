<?php

require_once '../lib/framework/AutoLoader.php';

require_once '../config/config.php';

$ttt = new \app\controller\TestController();

echo $ttt->testMethod('ss');

$ttt->testSmarty();
