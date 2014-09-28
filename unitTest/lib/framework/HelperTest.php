<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 7/9/14
 */

require_once __DIR__ . '/../../../src/lib/framework/autoLoader.php';

use lib\framework\Helper;

class helperTest extends PHPUnit_Framework_TestCase {

    /**
     * explodeSlash($string)
     */
    public function test_explodeSlash_EmptyInput_EmptyArray()
    {
        $result = Helper::explodeSlash('');
        $this->assertEquals(array(''), $result);
    }

    public function test_explodeSlash_OneSlash_ArrayWithEmptyString()
    {
        $result = Helper::explodeSlash('/');
        $this->assertEquals(array('', ''), $result);
    }

    public function test_explodeSlash_OneSlashAndOneItem_ArrayWithItem()
    {
        $result = Helper::explodeSlash('/a');
        $this->assertEquals(array('', 'a'), $result);
    }

    public function test_explodeSlash_NoSlashOnlyOneItem_ArrayWithItem()
    {
        $result = Helper::explodeSlash('a');
        $this->assertEquals(array('a'), $result);
    }

    public function test_explodeSlash_ManyItems_ArrayWithItems()
    {
        $result = Helper::explodeSlash('/a/b/c/');
        $this->assertEquals(array('', 'a', 'b', 'c', ''), $result);
    }

    public function test_explodeSlash_ManyItemsWithRepeatingSlash_ArrayWithItems()
    {
        $result = Helper::explodeSlash('///a////b/c/');
        $this->assertEquals(array('', '', '', 'a', '', '', '', 'b', 'c', ''), $result);
    }

    /*
     * removeStartStringWithSlash($originString, $removingStartingString)
     */
    public function testMethod_EmptyString_EmptyString()
    {
        $result = Helper::removeStartStringWithSlash('', '');
        $this->assertEquals('', $result);
    }

    public function testMethod_NormalString_StringRemoveWebBasePath()
    {
        $result = Helper::removeStartStringWithSlash('abc', 'a');
        $this->assertEquals('bc', $result);
    }

    public function testMethod_SlashAndNormalString_StringRemoveWebBasePath()
    {
        $result = Helper::removeStartStringWithSlash('/abc/d/e/f', '/abc/');
        $this->assertEquals('d/e/f', $result);
    }

    /**
     * getParametersFromRequestUri($requestUri, $removeStartingUri)
     */
    public function testGetParametersFromRequestUri_EmptyInput_EmptyArray()
    {
        $result = Helper::getParametersFromRequestUri('', '');
        $this->assertEquals(array(''), $result);
    }

    public function testGetParametersFromRequestUri_NormalUri_ArrayWithParameters()
    {
        $result = Helper::getParametersFromRequestUri('/abc/d/e/f', '/abc/');
        $this->assertEquals(array('d', 'e', 'f'), $result);
    }

    public function testGetParametersFromRequestUri_RemoveStringNotMatched_ArrayWithAllParameters()
    {
        $result = Helper::getParametersFromRequestUri('/abc/d/e/f', 'abc');
        $this->assertEquals(array('', 'abc', 'd', 'e', 'f'), $result);
    }
}
