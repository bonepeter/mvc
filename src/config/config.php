<?php

// For development
error_reporting(E_ALL);
ini_set("display_errors", 1);

// For production
//error_reporting(0);
//ini_set("display_errors", 0);

define('PROJECT_NAME', 'mvc');

define('SERVER_HTML_BASE_PATH', '/Applications/mampstack-5.4.34-0/apache2/htdocs/');
//define('SERVER_HTML_BASE_PATH', '/var/www/html/');

define('SERVER_SMARTY_LIB_PATH', '/Applications/mampstack-5.4.34-0/frameworks/smarty/libs/Smarty.class.php');
//define('SERVER_SMARTY_LIB_PATH', '/usr/local/lib/php/Smarty/Smarty.class.php');

define('SERVER_SMARTY_WORKING_PATH', '/mvcTemplates/');

define('SERVER_SMARTY_TEMPLATES_PATH', '/mvc/src/html/templates/');

define('URL_SMARTY_TEMPLATES_PATH', '/mvc/src/html/templates/');

define('DB_HOST', 'localhost');
define('DB_USER', 'mvcuser');
define('DB_PASSWORD', 'mvcpass');
define('DB_NAME', 'mvc');

date_default_timezone_set('Asia/Hong_Kong');
