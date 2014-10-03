<?php

namespace lib\helper;

class HttpHelper
{
    public static function redirect($url) {
        header('Location: ' .  $url);
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