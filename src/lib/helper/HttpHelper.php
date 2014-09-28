<?php

namespace lib\helper;

require_once '../config/config.php';

class HttpHelper
{
    public static function redirect($url) {
        header('Location: ' . WEB_BASE_PATH . $url);
    }
} 