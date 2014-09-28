<?php
/**
 * Project: framework
 * User: peter
 * Date: 20140914
 */

class TestModelTest extends PHPUnit_Framework_TestCase
{
    public function test_CreateTestModelClass()
    {
        $db = $this->getMockBuilder('lib\framework\Db')
            ->disableOriginalConstructor()
            ->getMock();
        $model = new \app\model\TestDbModel($db);
    }

}
