<?php

namespace html;

require_once '../lib/framework/main.php';

use app\model\DbModelFactory;

$auth = DbModelFactory::makeDbModel('Auth');
if(!$auth->isLogin()) {
	$url = 'login.php';
	header('Location: ' . $url);
}