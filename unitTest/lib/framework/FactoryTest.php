<?php
/**
 * Project: framework
 * User: peter
 * Date: 20140912
 */

require_once __DIR__ . '/../../../src/lib/framework/autoLoader.php';

use app\controller\ControllerFactory;
use app\model\DbModelFactory;
use app\view\ViewFactory;

class FactoryTest extends PHPUnit_Framework_TestCase
{
    public function test_RunMethodInTestController()
    {
        $testController = ControllerFactory::makeController('test');
        $result = $testController->testMethod('1');
        $this->assertEquals('TestController->testMethod 1', $result);
    }

    public function test_WrongControllerName()
    {
        try {
            ControllerFactory::makeController('wrongName');
        } catch (RuntimeException $e) {
            $this->assertEquals('No Object found', $e->getMessage());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    public function test_CreateView()
    {
        ViewFactory::makeView('test');
    }

}
