<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 7/9/14
 * Time: 上午11:48
 */

namespace lib\helper;

/**
 * Class FileHelper
 * @package lib\helper
 * @param String $path
 * @return String
 */
class FileHelper {
    public function convertToPhpDirSeparator($path)
    {
        return str_replace('\\', DIRECTORY_SEPARATOR, $path);
    }
} 