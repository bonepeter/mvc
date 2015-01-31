<?php

namespace app\controller;

use app\db\LogDb;
use app\db\UserDb;
use app\domain\Paging;
use lib\framework\Helper;
use app\view\SmartyTemplateView;
use lib\helper\HttpHelper;

class DbCrudController
{
    protected $db = null;
    protected $table = null;
    protected $tableName = '';
    protected $displayTemplate = 'dbtable';

    function __construct($table)
    {
        $this->db = Helper::createDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        switch (strtolower($table))
        {
            case 'user':
                $this->table = new UserDb($this->db);
                break;
            case 'log':
                $this->table = new LogDb($this->db);
                break;
            default:
                throw new \Exception('Table not accessible.');
        }
        if (! $this->table->isCrudReadable())
        {
            throw new \Exception('Table not readable.');
        }

        $this->tableName = $this->table->getTableName();

    }


    public function display($id = '', $pageNo = 1, $sortBy = '')
    {
        $where = $this->httpParameterToArray();
        $whereStr = $this->searchArrayToString($where);

        $rowEachPage = 10;

        $totalRowCount = $this->table->getRowCount();
        $paging = new Paging($totalRowCount);
        $paging->setTotalDisplayPage(10);
        $paging->setRowEachPage($rowEachPage);
        $pagingInfo = $paging->getPagingData($pageNo);

        $rows = $this->table->getData($where, $sortBy, $rowEachPage, $pageNo);

        if (empty($id))
        {
            $editUser = array();
            foreach ($this->table->getCols() as $col)
            {
                $editUser[ $col['name'] ] = '';
            }
        }
        else {
            $editUser = $this->table->getDataById($id);
        }

        $data = array('tableName' => $this->tableName,
            'cols' => $this->table->getCols(), 'rows' => $rows,
            'searchStr' => $whereStr,
            'pagingFirst' => $pagingInfo['firstPage'],
            'pagingLast' => $pagingInfo['lastPage'],
            'pagingStart' => $pagingInfo['startPage'],
            'pagingEnd' => $pagingInfo['endPage'],
            'sort' => $sortBy,
            'edit' => $editUser);

        $view = new SmartyTemplateView();
        echo $view->renderWithData($data, $this->displayTemplate);
    }

    private function httpParameterToArray()
    {
        $where = array();
        foreach ($this->table->getCols() as $col)
        {
            $parameterValue = HttpHelper::getRequest($col['name'], 'get');
            if ($parameterValue != '')
            {
                $where[ $col['name'] ] = $parameterValue;
            }
            $parameterValue = HttpHelper::getRequest($col['name'], 'post');
            if ($parameterValue != '')
            {
                $where[ $col['name'] ] = $parameterValue;
            }
        }
        return $where;
    }


    private function searchArrayToString($whereArr)
    {
        $whereStr = '';
        foreach ($whereArr as $name => $value) {
            $whereStr .= sprintf('&%s=%s', $name, $value);
        }
        return $whereStr;
    }


    public function add()
    {
        if (! $this->table->isCrudWritable())
        {
            throw new \Exception('Table not writable.');
        }
        $data = array();
        $logCols = '';
        foreach ($this->table->getCols() as $col)
        {
            if (strtolower($col['name']) != strtolower($this->table->getIdColName()))
            {
                $data[ $col['name'] ] = HttpHelper::getRequest($col['name'], 'post');
                $logCols .= $col['name'] . ': ' . $data[ $col['name'] ] . ', ';
            }
        }

        $result = $this->table->add($data);
        $logMsg = sprintf('Add: %s: %s, %s', $this->table->getIdColName(), $result, $logCols);
        $this->log($this->tableName, $logMsg);

        echo '<p>Record Added</p>';
        echo sprintf('<p><a href="%s.php?table=%s">Back</a></p>', $this->displayTemplate, $this->tableName);
    }


    public function edit()
    {
        if (! $this->table->isCrudWritable())
        {
            throw new \Exception('Table not writable.');
        }
        $data = array();
        $logMsg = 'Edit: ';
        foreach ($this->table->getCols() as $col)
        {
            $data[ $col['name'] ] = HttpHelper::getRequest($col['name'], 'post');
            $logMsg .= $col['name'] . ': ' . $data[ $col['name'] ] . ', ';
        }

        $this->table->update($data);
        $this->log($this->tableName, $logMsg);

        echo '<p>Record Modified</p>';
        echo sprintf('<p><a href="%s.php?table=%s">Back</a></p>', $this->displayTemplate, $this->tableName);
    }


    public function delete()
    {
        if (! $this->table->isCrudWritable())
        {
            throw new \Exception('Table not writable.');
        }

        $id = HttpHelper::getRequest($this->table->getIdColName(), 'post');
        if (empty($id))
        {
            throw new \Exception('No Id');
        }

        $this->table->deleteById($id);
        $logMsg = sprintf('Delete: %s: %s', $this->table->getIdColName(), $id);
        $this->log($this->tableName, $logMsg);

        echo '<p>Record Deleted</p>';
        echo sprintf('<p><a href="%s.php?table=%s">Back</a></p>', $this->displayTemplate, $this->tableName);
    }


    private function log($type, $message)
    {
        $log = new LogDb($this->db);
        $log->log($type, $message);
    }

}