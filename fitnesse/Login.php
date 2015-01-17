<?php

require_once __DIR__ . '/../src/lib/framework/main.php';

require_once 'config.php';

use app\domain\Auth;
use lib\framework\Helper;

class Login
{
    private $user;
    private $pass;

    public function setUsername($user)
    {
        $this->user = $user;
    }

    public function setPassword($pass)
    {
        $this->pass = $pass;
    }

    public function result()
    {
        $db = Helper::createDb(TEST_DB_HOST, TEST_DB_USER, TEST_DB_PASSWORD, TEST_DB_NAME);
        $auth = new Auth($db);
        $result = $auth->isAuthenticate($this->user, $this->pass);
        if ($result)
        {
            return (strtolower($result['User_Username']) == strtolower($this->user)) ? true : false;
        }
        else
        {
            return false;
        }
    }

}