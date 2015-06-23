<?php

namespace lib\framework\db\statement;

use \PDO;
use \PDOStatement;
use lib\framework\db\Db;

class MysqlDb implements Db
{
    private $dbh = null;

    function __construct(PDO $dbh) {
        $this->dbh = $dbh;
    }

    public function runSelect(SelectStatement $statement)
    {
        if ($statement->isNoTable())
        {
            $prepareSql = sprintf("SELECT %s", $this->getSelectAttributesClause($statement->getSelectAttributes()));
        }
        else
        {
            $prepareSql = sprintf("SELECT %s FROM %s;", $statement->getSelectAttributes(),
                    $this->getTableClause($statement->getTables()));
        }
        $stmt = $this->dbh->prepare($prepareSql);
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

    private function executeSql(PDOStatement $stmt)
    {
        return $stmt->execute() ? $stmt->fetchAll(PDO::FETCH_ASSOC) : false;
    }

} 