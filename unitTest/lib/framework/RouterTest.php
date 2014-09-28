<?php
/**
 * Project: framework
 * User: peter
 * Date: 20140908
 */

require_once __DIR__ . '/../../../src/lib/framework/autoLoader.php';

use lib\framework\Router;

class RouterTest extends PHPUnit_Framework_TestCase {

    const TEST_METHOD_RETURN = 'TestController->testMethod';

    private function createRouter($webBasePath) {
        $router = new Router();
        $router->setWebBasePath($webBasePath);
        return $router;
    }

    private function assertControllerReturnValue($ret)
    {
        $this->assertEquals(self::TEST_METHOD_RETURN, $ret);
    }

    public function test_dispatch_NormalInput_FunctionReturnsRightValue()
    {
        $router = $this->createRouter('/framework/');
        $ret = $router->dispatch('/framework/test/testMethod');
        $this->assertControllerReturnValue($ret);
    }

    public function test_dispatch_WebBasePathMoreThanOneSegment_FunctionReturnsRightValue()
    {
        $router = $this->createRouter('/frame/work/');
        $ret = $router->dispatch('/frame/work/test/testMethod');
        $this->assertControllerReturnValue($ret);
    }

    public function test_dispatch_WrongWebBasePath_ThrowsException()
    {
        $router = $this->createRouter('/webBasePath/');
        try {
            $router->dispatch('/wrongWebBasePath/test/testMethod');
        } catch (RuntimeException $e) {
            $this->assertEquals('No Object found', $e->getMessage());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    public function test_dispatch_WrongControllerClass_ThrowsException()
    {
        $router = $this->createRouter('/webBasePath/');
        try {
            $router->dispatch('/webBasePath/wrongApp/testMethod');
        } catch (RuntimeException $e) {
            $this->assertEquals('No Object found', $e->getMessage());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    public function test_dispatch_WrongAction_ThrowsException()
    {
        $router = $this->createRouter('/webBasePath/');
        try {
            $router->dispatch('/webBasePath/test/wrongFoo');
        } catch (RuntimeException $e) {
            $this->assertEquals('No Action found', $e->getMessage());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    public function test_dispatch_Parameters()
    {
        $router = $this->createRouter('/framework/');
        $ret = $router->dispatch('/framework/test/testMethod/1/2/3');
        $this->assertEquals(self::TEST_METHOD_RETURN . ' 1 2 3', $ret);
    }
}
 