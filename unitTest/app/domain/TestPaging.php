<?php

namespace unitTest\app\domain;

require_once __DIR__ . '/../../../src/lib/framework/autoLoader.php';

use app\domain\Paging;

class TestPaging extends \PHPUnit_Framework_TestCase
{
    public function test_NormalCase()
    {
        $paging = new Paging(1000);
        $paging->setRowEachPage(10);
        $paging->setTotalDisplayPage(10);

        $resultPageInfo = $paging->getPagingData(20);

        $expectedPageInfo['firstPage'] = 1;
        $expectedPageInfo['lastPage'] = 100;
        $expectedPageInfo['startPage'] = 16;
        $expectedPageInfo['endPage'] = 25;
        $expectedPageInfo['currentPage'] = 20;

        $this->assertPageInfo($expectedPageInfo, $resultPageInfo);
    }

    private function assertPageInfo($expected, $actual)
    {
        $this->assertEquals($expected['firstPage'], $actual['firstPage'], 'firstPage incorrect');
        $this->assertEquals($expected['lastPage'], $actual['lastPage'], 'lastPage incorrect');
        $this->assertEquals($expected['startPage'], $actual['startPage'], 'startPage incorrect');
        $this->assertEquals($expected['endPage'], $actual['endPage'], 'endPage incorrect');
        $this->assertEquals($expected['currentPage'], $actual['currentPage'], 'currentPage incorrect');
    }

    public function test_DisplayTotalPageIsOddNumber()
    {
        $paging = new Paging(1000);
        $paging->setRowEachPage(10);
        $paging->setTotalDisplayPage(9);

        $resultPageInfo = $paging->getPagingData(20);

        $expectedPageInfo['firstPage'] = 1;
        $expectedPageInfo['lastPage'] = 100;
        $expectedPageInfo['startPage'] = 16;
        $expectedPageInfo['endPage'] = 24;
        $expectedPageInfo['currentPage'] = 20;

        $this->assertPageInfo($expectedPageInfo, $resultPageInfo);
    }

    public function test_RowEachPageIsOddNumber()
    {
        $paging = new Paging(10);
        $paging->setRowEachPage(3);
        $paging->setTotalDisplayPage(5);

        $resultPageInfo = $paging->getPagingData(1);

        $expectedPageInfo['firstPage'] = 1;
        $expectedPageInfo['lastPage'] = 4;
        $expectedPageInfo['startPage'] = 1;
        $expectedPageInfo['endPage'] = 4;
        $expectedPageInfo['currentPage'] = 1;

        $this->assertPageInfo($expectedPageInfo, $resultPageInfo);
    }

    public function test_BasicCase3()
    {
        $paging = new Paging(21);
        $paging->setRowEachPage(2);
        $paging->setTotalDisplayPage(5);

        $resultPageInfo = $paging->getPagingData(11);

        $expectedPageInfo['firstPage'] = 1;
        $expectedPageInfo['lastPage'] = 11;
        $expectedPageInfo['startPage'] = 7;
        $expectedPageInfo['endPage'] = 11;
        $expectedPageInfo['currentPage'] = 11;

        $this->assertPageInfo($expectedPageInfo, $resultPageInfo);
    }

    public function test_BugCase1()
    {
        $paging = new Paging(21);
        $paging->setRowEachPage(2);
        $paging->setTotalDisplayPage(5);

        $resultPageInfo = $paging->getPagingData(1);

        $expectedPageInfo['firstPage'] = 1;
        $expectedPageInfo['lastPage'] = 11;
        $expectedPageInfo['startPage'] = 1;
        $expectedPageInfo['endPage'] = 5;
        $expectedPageInfo['currentPage'] = 1;

        $this->assertPageInfo($expectedPageInfo, $resultPageInfo);
    }
}
