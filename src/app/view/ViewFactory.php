<?php
/**
 * Project: framework
 * User: peter
 * Date: 20140914
 */

namespace app\view;

use RuntimeException;

/**
 * New View needed to create from this factory
 * @package app\view
 */
class ViewFactory
{
    public static function makeView($name) {
        switch (strtolower($name)) {
            case 'smartytemplate':
                return new SmartyTemplateView();
            default:
                throw new RuntimeException('No Object found');
        }
    }
}