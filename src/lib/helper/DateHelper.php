<?php

namespace lib\helper;

class DateHelper
{
    public static function mysqlDate($addSecond = 0) {
        $date = time() + $addSecond;
        return date("Y-m-d H:i:s", $date);
    }

    public static function stringDateToI18nDate($date)
    {
        date_default_timezone_set('Asia/Hong_Kong');
        $timestamp = strtotime($date);
        //return date("Y-m-d H:i:s P", $timestamp);
        return date("r", $timestamp);
    }

} 