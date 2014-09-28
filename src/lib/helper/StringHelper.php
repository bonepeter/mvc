<?php
/**
 * Project: framework
 * User: peter
 * Date: 20140908
 */

namespace lib\helper;


class StringHelper {
    public static function startsWith($haystack, $needle)
    {
        return $needle === "" || strpos($haystack, $needle) === 0;
    }

    public static function endsWith($haystack, $needle)
    {
        return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
    }
} 