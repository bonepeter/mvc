<?php

namespace lib\framework\db;

use lib\framework\db\statement\SelectStatement;
use \PDO;
use \PDOStatement;

class MysqlDb implements Db
{
    private $pdo = null;

    function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function runSelect(SelectStatement $statement)
    {
        if ($statement->isNoTable())
        {
            $prepareSql = sprintf("SELECT %s", $this->getSelectAttributesClause($statement->getSelectAttributes()));
        }
        else
        {
            $prepareSql = sprintf("SELECT %s FROM %s;",
                    $this->getSelectAttributesClause($statement->getSelectAttributes()),
                    $this->getTableClause($statement->getTables()));
        }
        $stmt = $this->pdo->prepare($prepareSql);
        return $this->executeSql($stmt);
    }

    private function getSelectAttributesClause($attributes)
    {
        $selectStr = implode(',', $attributes);
        if ($selectStr == '')
        {
            return '*';
        }
        else
        {
            return $selectStr;
        }
    }

    private function getTableClause($tables)
    {
        return implode(',', $tables);
    }

    private function getWhereClause($conditions)
    {
        $whereClause = '';
        $separator = '';
        $i = 0;

        /* @var $condition DbCondition */
        foreach($conditions as $condition)
        {
            if ($condition->isConstant())
            {
                //$whereClause .= $separator . //ZZzzzz
            }


        }

    }

    private function executeSql(PDOStatement $stmt)
    {
        return $stmt->execute() ? $stmt->fetchAll(PDO::FETCH_ASSOC) : false;
    }

} 