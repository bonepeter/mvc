<?php

namespace html;

require_once '../lib/framework/main.php';

use app\controller\AuthController;

$auth = new AuthController();
if(!$auth->isLogin()) {
	$url = 'login.php';
	header('Location: ' . $url);
}