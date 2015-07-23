<?php

namespace lib\framework\db;


class MysqlDbColumn extends DbColumn
{
    public function getType()
    {
        if ( $this->type = 'string' )
        {
            return \PDO::PARAM_STR;
        }
        return null;
    }
} 