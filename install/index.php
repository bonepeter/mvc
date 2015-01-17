<?php

require_once  __DIR__ . '/../src/lib/framework/main.php';

use lib\helper\HttpHelper;

$action = HttpHelper::getRequest('action');

switch($action)
{
    case 'requirement':
        showRequirement();
        break;
    case 'smarty':
        smarty();
        break;
    case 'deploy':
        deploy();
        break;
    case 'createDb_form':
        $createDatabaseAction = new createDatabaseAction();
        $createDatabaseAction->printForm();
        break;
    case 'createDb_go':
        $createDatabaseAction = new createDatabaseAction();
        $createDatabaseAction->go();
        break;
    default:
        index();
        break;
}

function index()
{
    echo 'This guide will help you to install the system to a new server.';
    echo '<p><a href="?action=requirement">Requirement</a></p>';
}

function showRequirement()
{
    echo '<p>Requirement: Apache, php 5, Smarty</p>';
    echo '- Setup file src/config/config.php<br />';
    $smartyTemplate = SERVER_SMARTY_WORKING_PATH;
    $output = <<< EOT
<form action="?action=smarty" method="post">
<p>Apache run user: <input type="text" name="apacheUser" value="www-data"></p>
<p>Smarty Working Path: <input type="text" name="smartyWorkingPath" value="${smartyTemplate}" size="80"></p>
<input type="Submit" value="Next">
</form>
EOT;
    echo $output;
}

function smarty()
{
    $apacheUser = HttpHelper::getRequest('apacheUser', 'post');
    $smartyTemplate = HttpHelper::getRequest('smartyWorkingPath', 'post');

    echo '<h1>Install Smarty</h1>';

    $output = <<< EOT
SSH to the server and run this command:<br />

<p>========== Install and Setup ==========</p>
cd /tmp<br />
mkdir smarty<br />
cd smarty<br />
wget http://www.smarty.net/files/Smarty-stable.tar.gz<br />
tar -zxvf Smarty-stable.tar.gz<br />
sudo mkdir -p /usr/local/lib/php/Smarty<br />
sudo cp -r Smarty-3.1.18/libs/* /usr/local/lib/php/Smarty<br />

cd /tmp<br />
rm -rf smarty<br />

<p>========== Setup Template ==========</p>
sudo mkdir -p ${smartyTemplate}<br />
cd ${smartyTemplate}<br />
sudo mkdir templates_c<br />
sudo mkdir cache<br />
sudo mkdir configs<br />
sudo chown {$apacheUser}:{$apacheUser} templates_c<br />
sudo chown {$apacheUser}:{$apacheUser} cache<br />
sudo chmod 775 templates_c<br />
sudo chmod 775 cache<br />

<p>========== Reference ==========</p>
- <a href="http://www.smarty.net/download" target="_blank">Download Smarty</a><br />
- <a href="http://www.smarty.net/quick_install" target="_blank">Quick Install Reference</a><br />
<p>==========</p>
EOT;
    echo $output;
    echo '<p><a href="?action=deploy">Next - Deployment Setup</a></p>';

    printBackLinks();
}

function deploy()
{
    echo '<h1>Deployment Setup</h1>';
    $htmlDir = SERVER_HTML_BASE_PATH;
    $projName = PROJECT_NAME;
    $output = <<< EOT
SSH to the server and run this command:<br />
<code>==========<br />
#sudo useradd deployer<br />
#sudo passwd deployer<br />
sudo adduser deployer<br />
cd $htmlDir<br />
sudo mkdir ${projName}Release<br />
sudo chown deployer:deployer ${projName}Release<br />
sudo chgrp deployer .<br />
sudo chmod g+w .<br />
==========</code><br />

<p>-> Login as deployer:</p>
<code>==========<br />
cd ${htmlDir}${projName}Release<br />
mkdir deploy<br />
cd deploy<br />
==========</code><br />
<p>vi .htaccess and add this line: Deny From All</p>
<code>==========<br />
mkdir conf<br />
cd conf<br />
mkdir config<br />
cd config<br />
==========</code><br />
<p>vi config.php, copy content from local/framework/config/config.php and edit for the server</p>
EOT;
    echo $output;
    echo '<p><a href="?action=createDb_form">Next - Create database</a></p>';

    printBackLinks();
}

// ---------- Functions ----------

function printBackLinks() {
    echo '<p><a href="javascript:history.back()">Back</a></p>';
}

// ---------- Class ----------

Class createDatabaseAction
{
    public function printForm()
    {
        $output = <<< EOT
Create database:
<form action="?action=createDb_go" method="post">
<p>Database Host: <input type="text" name="dbHost" value="%s"></p>
<p>Database Name: <input type="text" name="dbName" value="%s"></p>
<p>Database Username: <input type="text" name="dbUser" value="%s"></p>
<p>Database Password: <input type="text" name="dbPass" value="%s"></p>
<p>Database Deploy Username: <input type="text" name="dbDeployUser" value="%s"></p>
<p>Database Deploy Password: <input type="text" name="dbDeployPass" value="%s"></p>
<input type="Submit">
</form>
EOT;
        $deployUser = HttpHelper::getRequest('dbDeployUser', 'post');
        $deployUser = $deployUser == '' ? PROJECT_NAME . 'deploy' : $deployUser;
        $deployPass = HttpHelper::getRequest('dbDeployPass', 'post');
        $deployPass = $deployPass == '' ? PROJECT_NAME . 'deploypass' : $deployPass;
        echo sprintf($output,
            DB_HOST,
            DB_NAME,
            DB_USER,
            DB_PASSWORD,
            $deployUser,
            $deployPass);

        printBackLinks();
    }

    public function go()
    {
        $dbHost = HttpHelper::getRequest('dbHost', 'post');
        $dbName = HttpHelper::getRequest('dbName', 'post');
        $dbUser = HttpHelper::getRequest('dbUser', 'post');
        $dbPass = HttpHelper::getRequest('dbPass', 'post');
        $dbDeployUser = HttpHelper::getRequest('dbDeployUser', 'post');
        $dbDeployPass = HttpHelper::getRequest('dbDeployPass', 'post');

        $createDatabaseCmd = "CREATE DATABASE $dbName;";
        $createDeployUserCmd = "CREATE USER '$dbDeployUser'@'$dbHost' identified by '$dbDeployPass';";
        $createDeployAccessCmd = "GRANT create, alter, drop, insert on $dbName.* to '$dbDeployUser'@'$dbHost';";
        $createWebUserCmd = "CREATE USER '$dbUser'@'$dbHost' identified by '$dbPass';";
        $createWebUserAccessCmd = "GRANT select, insert, delete, update on $dbName.* to '$dbUser'@'$dbHost';";

        $createOptionalWebUserCmd = "CREATE USER '$dbUser'@'$dbHost' identified by '$dbPass';";
        $createOptionalWebUserAccessCmd = "GRANT ALL on $dbName.* to '$dbUser'@'$dbHost';";


        echo 'SSH to the server, run mysql command to create the database:<br />';
        echo <<< EOT
<p>==========</p>
$createDatabaseCmd<br />
<p>==========</p>
$createDeployUserCmd<br />
$createDeployAccessCmd<br />
$createWebUserCmd<br />
$createWebUserAccessCmd<br />
Or<br />
$createOptionalWebUserCmd<br />
$createOptionalWebUserAccessCmd<br />
<p>==========</p>
EOT;

        printBackLinks();

        $this->printContinueLink();
    }

    private function printContinueLink()
    {
        if ($_SERVER["SERVER_PORT"] != "80") {
            $host = $_SERVER["SERVER_NAME"] . ':' . $_SERVER["SERVER_PORT"];
        } else {
            $host = $_SERVER["SERVER_NAME"];
        }
        echo sprintf('<p>DONE</p>');
        echo sprintf('<p><a href="http://%s/%s">Try the new website</a></p>', $host, PROJECT_NAME);
    }
}
