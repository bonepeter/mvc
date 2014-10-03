<?php

namespace html;

require_once '../lib/framework/main.php';

use app\model\DbModelFactory;
use lib\helper\HttpHelper;

$username = HttpHelper::getRequest('username', 'post');
$password = HttpHelper::getRequest('password', 'post');

$auth = DbModelFactory::makeDbModel('Auth');
$auth->login($username, $password);

$userLog = DbModelFactory::makeDbModel('Log');

if($auth->isLogin()) {
	$url = 'index.php';
	echo 'Login success';
	$userLog->log($auth->getUserId(), 'Login Success: ' . $username);
} else {
	$url = 'login_form.php';
	echo 'Login failed';
	$userLog->log(0, 'Login failed: ' . $username);
}

header('Refresh: 1;url=' . $url);
