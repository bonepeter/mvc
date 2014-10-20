<?php

require_once '../src/lib/framework/main.php';

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
    echo 'Requirement: Apache, php 5, Smarty';
    echo '<p><a href="?action=smarty">Install Smarty</a></p>';
}

function smarty()
{
    echo '<h1>Install Smarty</h1>';
    $smartyTemplate = SMARTY_TEMPLATE_PATH;
    $output = <<< EOT
SSH to the server and run this command:<br />
==========<br />
cd /tmp<br />
mkdir smarty<br />
cd smarty<br />
wget http://www.smarty.net/files/Smarty-stable.tar.gz<br />
tar -zxvf Smarty-stable.tar.gz<br />
sudo mkdir -p /usr/local/lib/php/Smarty<br />
sudo cp -r Smarty-3.1.18/libs/* /usr/local/lib/php/Smarty<br />
sudo mkdir -p ${smartyTemplate}<br />
cd ${smartyTemplate}<br />
sudo mkdir templates<br />
sudo mkdir templates_c<br />
sudo mkdir cache<br />
sudo mkdir configs<br />
sudo chown peter:peter templates<br />
sudo chown www-data:www-data templates_c<br />
sudo chown www-data:www-data cache<br />
sudo chmod 775 templates_c<br />
sudo chmod 775 cache<br />
cd /tmp<br />
rm -rf smarty<br />

cd ${smartyTemplate}<br />
sudo vi .htaccess<br />
==========<br />
Add this lines:<br />
>>>>> frameworkTemplate/.htaccess <<<<<<br />
Options -Indexes<br />
RewriteEngine On<br />
RewriteCond %{REQUEST_FILENAME} -f<br />
RewriteRule !\.(js|gif|jpg|png|css)$ - [F]<br />
>>>>> frameworkTemplate/.htaccess <<<<<<br />

vi smarty/templates/index.tpl<br />
EOT;
    echo $output;
    echo '<p><a href="?action=deploy">Next - Deployment Setup</a></p>';
}

function deploy()
{
    echo '<h1>Deployment Setup</h1>';
    $htmlDir = HTML_DIR;
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
}

// ---------- Functions ----------



// ---------- Class ----------

Class createDatabaseAction
{
    public function printForm()
    {
        $output = <<< EOT
Create database:
<form action="">
<p>Database Host: <input type="text" name="dbHost" value="%s"></p>
<p>Database Name: <input type="text" name="dbName" value="%s"></p>
<p>Database Username: <input type="text" name="dbUser" value="%s"></p>
<p>Database Password: <input type="text" name="dbPass" value="%s"></p>
<p>Database Deploy Username: <input type="text" name="dbDeployUser" value="%s"></p>
<p>Database Deploy Password: <input type="text" name="dbDeployPass" value="%s"></p>
<input type="hidden" name="action" value="createDb_go">
<input type="Submit">
</form>
EOT;
        $deployUser = HttpHelper::getRequest('dbDeployUser');
        $deployUser = $deployUser == '' ? PROJECT_NAME . 'deployer' : $deployUser;
        $deployPass = HttpHelper::getRequest('dbDeployPass');
        $deployPass = $deployPass == '' ? PROJECT_NAME . 'deployerpass' : $deployPass;
        echo sprintf($output,
            DB_HOST,
            DB_NAME,
            DB_USER,
            DB_PASSWORD,
            $deployUser,
            $deployPass);
    }

    public function go()
    {
        $dbHost = HttpHelper::getRequest('dbHost');
        $dbName = HttpHelper::getRequest('dbName');
        $dbUser = HttpHelper::getRequest('dbUser');
        $dbPass = HttpHelper::getRequest('dbPass');
        $dbDeployUser = HttpHelper::getRequest('dbDeployUser');
        $dbDeployPass = HttpHelper::getRequest('dbDeployPass');

        $createDatabaseCmd = "CREATE DATABASE $dbName;";
        $createDeployUserCmd = "CREATE USER '$dbDeployUser'@'$dbHost' identified by '$dbDeployPass';";
        $createDeployAccessCmd = "GRANT create, alter, drop, insert on $dbName.* to '$dbDeployUser'@'$dbHost';";
        $createWebUserCmd = "CREATE USER '$dbUser'@'$dbHost' identified by '$dbPass';";
        $createWebUserAccessCmd = "GRANT select, insert, delete, update on $dbName.* to '$dbUser'@'$dbHost';";

        echo 'SSH to the server, run mysql command to create the database:<br />';
        echo <<< EOT
==========<br />
$createDatabaseCmd<br />
$createDeployUserCmd<br />
$createDeployAccessCmd<br />
$createWebUserCmd<br />
$createWebUserAccessCmd<br />
==========<br />
EOT;
        $this->printFailLinks();

        $this->printContinueLink();
    }

    private function printFailLinks() {
        $url = "?" . http_build_query($_REQUEST) . '&action=createDb_form';
        echo "<p></p><a href=\"$url\">Back</a></p>";
    }

    private function printContinueLink() {
        $url = "?" . http_build_query($_REQUEST) . '&action=config_form';
        echo sprintf('<p>DONE</p>', $url);
    }
}
