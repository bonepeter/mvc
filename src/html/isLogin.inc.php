<?php

namespace html;

require_once '../lib/framework/main.php';

$auth = \app\model\DbModelFactory::makeDbModel('Auth');
if(!$auth->isLogin()) {
	$url = 'login_form.php';
	header('Location: ' . $url);
}