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

    public static function getParameters($method = 'get')
    {
        if ($method == 'get')
        {
            $parameters = array();
            parse_str($_SERVER['QUERY_STRING'], $parameters);
            return $parameters;
        }
        else
        {
            return $_POST;
        }
    }

    public static function printlnErrorMessage($msg)
    {
        echo sprintf('<p style="color: red; font-weight: bold;">%s</p>', $msg);
    }
} 