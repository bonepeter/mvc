<?php

namespace html;

require_once '../config/config.php';

define('BASE_PATH', __DIR__ . '/../');
define('APP_PATH', BASE_PATH . 'app/');
define('LIB_PATH', BASE_PATH . 'lib/');
define('FRAMEWORK_PATH', LIB_PATH . 'framework/');

define('REQUEST_URI', $_SERVER['REQUEST_URI']);

// peter: i don't know why i need to include here but not in the class file
require_once '/usr/local/lib/php/Smarty/Smarty.class.php';

require_once FRAMEWORK_PATH . 'core.php';