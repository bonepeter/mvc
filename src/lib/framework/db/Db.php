<?php

namespace lib\framework\db;

use lib\framework\db\statement\SelectStatement;

interface Db {
    public function runSelect(SelectStatement $statement);
    //public function runInsert(SelectStatement $statement);
}