<?php

namespace lib\framework;

use lib\helper\HttpHelper;

require_once 'AutoLoader.php';

date_default_timezone_set('Asia/Hong_Kong');

try {
    $router = new Router();
    $router->setWebBasePath(WEB_BASE_PATH);
    $router->dispatch(REQUEST_URI);
} catch (\RuntimeException $e) {
    HttpHelper::redirect('index/view');
}

