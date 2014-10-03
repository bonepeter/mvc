<?php

// For development
error_reporting(E_ALL);
ini_set("display_errors", 1);

// For production
//error_reporting(0);
//ini_set("display_errors", 0);

//define('WEB_BASE_PATH', '/framework/src/');
//define('SMARTY_TEMPLATE_PATH', '/var/www/smarty/');

define('HTML_DIR', '/var/www/html/');
define('SMARTY_WEB_PATH', '/mvcTemplate/');
define('SMARTY_TEMPLATE_PATH', HTML_DIR . SMARTY_WEB_PATH);


define('DB_HOST', 'localhost');
define('DB_USER', 'frameworkuser');
define('DB_PASSWORD', 'frameworkpass');
define('DB_NAME', 'framework');