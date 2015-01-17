<?php

// This file is for debug the fixture

include_once('Login.php');

$fixture = new Login();
$fixture->setUsername('user');
$fixture->setPassword('pass');
var_dump($fixture->result());
