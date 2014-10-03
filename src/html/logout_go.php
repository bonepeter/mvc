<?php

namespace html;

require_once '../lib/framework/main.php';

use app\model\DbModelFactory;

$auth = DbModelFactory::makeDbModel('Auth');
$userLog = DbModelFactory::makeDbModel('Log');

$userLog->log($auth->getUserId(), 'Logout');

$auth->logout();

$url = 'login_form.php';

header('Refresh: 1;url=' . $url);

echo 'Logout Success';