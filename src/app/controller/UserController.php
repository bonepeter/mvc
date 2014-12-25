<?php

namespace app\controller;

use app\db\LogDb;
use app\db\UserDb;
use lib\framework\Helper;
use app\view\SmartyTemplateView;

class UserController
{
    private $user = null;

    function __construct()
    {
        $db = Helper::createDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $this->user = new UserDb($db);
    }

    public function display($id) {
        $view = new SmartyTemplateView();
        $users = $this->user->getAll();

        if (empty($id))
        {
            $editUser = array('User_Id' => '', 'User_Username' => '',
                'User_DisplayName' => '', 'User_Level' => '');
        }
        else {
            $editUser = $this->user->getDataById($id);
        }

        $data = array('users' => $users, 'edit' => $editUser);
        echo $view->renderWithData($data, 'user_list');
    }

    public function add($username, $password, $displayName, $level)
    {
        if (empty($username) || empty($password) || empty($displayName) || empty($level) )
        {
            echo '<p>Invalid Input</p>';
            echo '<p><a href="javascript:history.back()">Back</a></p>';
            return;
        }
        else
        {
            $data = array('User_Username' => $username, 'User_Password' => sha1($password),
                'User_DisplayName' => $displayName, 'User_Level' => $level,);
            $this->user->add($data);
        }
        echo '<p><a href="user_list.php">Back</a></p>';
    }

    public function edit($id, $username, $password, $displayName, $level)
    {
        if (empty($id) || empty($username) || empty($displayName) || empty($level) )
        {
            echo '<p>Invalid Input</p>';
            echo '<p><a href="javascript:history.back()">Back</a></p>';
            return;
        }

        if (empty($password)) {
            $data = array('User_Id' => $id,
                'User_Username' => $username,
                'User_DisplayName' => $displayName, 'User_Level' => $level,);
        } else {
            $data = array('User_Id' => $id,
                'User_Username' => $username, 'User_Password' => sha1($password),
                'User_DisplayName' => $displayName, 'User_Level' => $level,);
        }
        $this->user->update($data);

        echo '<p><a href="user_list.php">Back</a></p>';
    }

    private function log($type, $message)
    {
        $db = Helper::createDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $log = new LogDb($db);
        $log->log($type, $message);
    }

}