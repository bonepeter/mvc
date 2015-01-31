<?php

namespace app\domain;

class Paging {

	private $rowCount = 0;
    private $totalDisplayPage = 10;
    private $rowEachPage = 10;

	public function __construct($rowCount) {
        $this->rowCount = $rowCount;
	}

    public function setTotalDisplayPage($count)
    {
        $this->totalDisplayPage = $count;
        return $this;
    }

    public function setRowEachPage($count)
    {
        $this->rowEachPage = $count;
        return $this;
    }

    // firstPage | startPage | ... | endPage | lastPage
    public function getPagingData($currentPage)
    {
        $firstPage = 1;
        $lastPage = ceil($this->rowCount / $this->rowEachPage);
        $halfOfTotalDisplayPage = $this->totalDisplayPage / 2;

        // Normal
        $startPage = $currentPage - ceil($halfOfTotalDisplayPage)  + 1;
        $endPage = $currentPage + floor($halfOfTotalDisplayPage);

        // not enough display page
        if ($lastPage <= $this->totalDisplayPage)
        {
            $startPage = $firstPage;
            $endPage = $lastPage;
        }
        // at the begin
        else if ($currentPage <= ceil($halfOfTotalDisplayPage))
        {
            $startPage = 1;
            $endPage = $this->totalDisplayPage;
        }
        // at the end
        else if ($currentPage >= $lastPage - floor($halfOfTotalDisplayPage))
        {
            $startPage = $lastPage - $this->totalDisplayPage + 1;
            $endPage = $lastPage;
        }

        return array('firstPage' => $firstPage,
            'lastPage' => $lastPage,
            'startPage' => $startPage,
            'endPage' => $endPage,
            'currentPage' => $currentPage);
    }


}
