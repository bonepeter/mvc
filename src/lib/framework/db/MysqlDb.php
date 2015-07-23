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
            $prepareSql = sprintf("SELECT %s;", $this->getSelectAttributesClause($statement->getSelectAttributes()));
        }
        else
        {
            $prepareSql = sprintf("SELECT %s FROM %s %s;",
                    $this->getSelectAttributesClause($statement->getSelectAttributes()),
                    $this->getTableClause($statement->getTables()),
                    $this->getWhereClause($statement->getConditions()));
        }

        $stmt = $this->pdo->prepare($prepareSql);

        /* @var $condition DbCondition */
        foreach($statement->getConditions() as $condition)
        {
            $stmt->bindValue($condition->getColumnPlaceHolder(), $condition->getValue(), $condition->getColumnDbType());
        }

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

        /* @var $condition DbCondition */
        foreach($conditions as $condition)
        {
            if ($condition->isConstant())
            {
                $this->setConstantWhereClausePart($whereClause, $separator, $condition);
            }
            else
            {
                $this->setWhereClausePart($whereClause, $separator, $condition);
            }
            $separator = ' AND ';
        }

        if ( ! empty($whereClause) )
        {
            $whereClause = ' WHERE ' . $whereClause;
        }
        return $whereClause;
    }

    private function executeSql(PDOStatement $stmt)
    {
        return $stmt->execute() ? $stmt->fetchAll(PDO::FETCH_ASSOC) : false;
    }

    private function setConstantWhereClausePart(&$whereClause, $separator, DbCondition $condition)
    {
        $whereClause .= $separator . sprintf("%s %s %s",
                $condition->getColumnName(),
                $condition->getOp(),
                $condition->getValue());
    }

    private function setWhereClausePart(&$whereClause, $separator, DbCondition $condition)
    {
        $whereClause .= $separator . sprintf("%s %s %s",
                $condition->getColumnName(),
                $condition->getOp(),
                $condition->getColumnPlaceHolder());
    }

}
