<?php

namespace lib\helper;

require_once '../config/config.php';

class HttpHelper
{
    public static function redirect($url) {
        header('Location: ' . WEB_BASE_PATH . $url);
    }

    public static function getRequest($varName, $type = 'get') {
        if ($type == 'get') {
            return filter_input(INPUT_GET, $varName, FILTER_SANITIZE_STRING);
        }
        if ($type == 'post') {
            return filter_input(INPUT_POST, $varName, FILTER_SANITIZE_STRING);
        }
        return '';
    }
} 