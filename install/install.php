<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

$action = $_REQUEST['action'];

switch($action) {
    case 'requirement':
        showRequirement();
        break;
    case 'createDb_form':
        $createDatabaseAction = new createDatabaseAction();
        $createDatabaseAction->printForm();
        break;
    case 'createDb_go':
        $createDatabaseAction = new createDatabaseAction();
        $createDatabaseAction->go();
        break;
    case 'config_form':
        $configAction = new configAction();
        $configAction->printForm();
        break;
    case 'config_go':
        $configAction = new configAction();
        $configAction->go();
        break;
    default:
        index();
        break;
}

function index() {
    echo 'index';
    echo '<p></p><a href="?action=requirement">Requirement</a></p>';
}

function showRequirement() {
    echo 'Requirement: Apache, php 5, Smarty';
    echo '<p></p><a href="?action=createDb_form">Create database</a></p>';
}

// Create config/config.php

// modify .htaccess

// Setup for deploy



// ---------- Functions ----------



// ---------- Class ----------

Class configAction
{
    public function printForm()
    {
        $output = <<< EOT
Create database:
<form action="">
<p>Production?: <input type="text" name="production" value="%s"></p>
<p>Template Path: <input type="text" name="template" value="%s"></p>
<p>Database Host: <input type="text" name="dbHost" value="%s"></p>
<p>Database Name: <input type="text" name="dbName" value="%s"></p>
<p>Database Username: <input type="text" name="dbUser" value="%s"></p>
<p>Database Password: <input type="text" name="dbPass" value="%s"></p>
<p>Database Deploy Username: <input type="text" name="dbDeployUser" value="%s"></p>
<p>Database Deploy Password: <input type="text" name="dbDeployPass" value="%s"></p>
<input type="hidden" name="action" value="config_go">
<input type="Submit">
</form>
EOT;
        echo sprintf($output, $_REQUEST['production'], $_REQUEST['template'],
            $_REQUEST['dbHost'], $_REQUEST['dbName'], $_REQUEST['dbUser'], $_REQUEST['dbPass'],
            $_REQUEST['dbDeployUser'], $_REQUEST['dbDeployPass']);
    }

    public function go()
    {
        echo '<p>config go</p>';
        $webBasePath = dirname($_SERVER["REQUEST_URI"]);

        $output = <<< EOT

<?php
// For development
error_reporting(E_ALL);
ini_set("display_errors", 1);

// For production
//error_reporting(0);
//ini_set("display_errors", 0);

define('WEB_BASE_PATH', '$webBasePath');
define('SMARTY_TEMPLATE_PATH', '/var/www/smarty/');

define('DB_HOST', 'localhost');
define('DB_USER', 'frameworkuser');
define('DB_PASSWORD', 'frameworkpass');
define('DB_NAME', 'framework');
EOT;

        echo '<textarea cols="80" rows="20">' . $output . '</textarea>';

    }
}

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
<p>Database Root Username: <input type="text" name="dbRootUser" value="%s"></p>
<p>Database Root Password: <input type="text" name="dbRootPass" value="%s"></p>
<input type="hidden" name="action" value="createDb_go">
<input type="Submit">
</form>
EOT;
        echo sprintf($output, $_REQUEST['dbHost'], $_REQUEST['dbName'], $_REQUEST['dbUser'], $_REQUEST['dbPass'],
            $_REQUEST['dbDeployUser'], $_REQUEST['dbDeployPass'], $_REQUEST['dbRootUser'], $_REQUEST['dbRootPass']);
    }

    public function go()
    {
        $dbHost = $_REQUEST['dbHost'];
        $dbName = $_REQUEST['dbName'];
        $dbUser = $_REQUEST['dbUser'];
        $dbPass = $_REQUEST['dbPass'];
        $dbDeployUser = $_REQUEST['dbDeployUser'];
        $dbDeployPass = $_REQUEST['dbDeployPass'];
        $dbRootUser = $_REQUEST['dbRootUser'];
        $dbRootPass = $_REQUEST['dbRootPass'];

        $createDatabaseCmd = "CREATE DATABASE $dbName;";
        $createDeployUserCmd = "CREATE USER '$dbDeployUser'@'$dbHost' identified by '$dbDeployPass';";
        $createDeployAccessCmd = "GRANT create, alter, drop, insert on $dbName.* to '$dbDeployUser'@'$dbHost';";
        $createWebUserCmd = "CREATE USER '$dbUser'@'$dbHost' identified by '$dbPass';";
        $createWebUserAccessCmd = "GRANT select, insert, delete, update on $dbName.* to '$dbUser'@'$dbHost';";

        $db = new DB($dbHost, $dbRootUser, $dbRootPass, '');

        if ($db->isConnected()) {
            $isSqlRunOk = $db->executeSql($createDatabaseCmd);
            if ($isSqlRunOk)
            {
                $isSqlRunOk = $db->executeSql($createDeployUserCmd);
            }
            if ($isSqlRunOk)
            {
                $isSqlRunOk = $db->executeSql($createDeployAccessCmd);
            }
            if ($isSqlRunOk)
            {
                $isSqlRunOk = $db->executeSql($createWebUserCmd);
            }
            if ($isSqlRunOk)
            {
                $isSqlRunOk = $db->executeSql($createWebUserAccessCmd);
            }
            if ($isSqlRunOk)
            {
                echo 'Database and users created.';
            }
            else
            {
                echo 'Cannot create database and/or users';
                $this->printFailLinks();
            }
        } else {
            echo 'Cannot connect to database by root, please try again or execute the following mysql commands yourself:';
            echo <<< EOT
$createDatabaseCmd<br />
$createDeployUserCmd<br />
$createDeployAccessCmd<br />
$createWebUserCmd<br />
$createWebUserAccessCmd<br />
EOT;
            $this->printFailLinks();
        }

        $this->printContinueLink();
    }

    private function printFailLinks() {
        echo '<a href="next_step">I have executed the mysql commands</a><br />';
        $url = "?" . http_build_query($_REQUEST) . '&action=createDb_form';
        echo "<a href=\"$url\">Back</a><br />";
    }

    private function printContinueLink() {
        $url = "?" . http_build_query($_REQUEST) . '&action=config_form';
        echo sprintf('<p></p><a href="%s">Next - config.php</a></p>', $url);
    }
}

Class DB
{
    private $pdo;

    public function __construct($host, $user, $pass, $dbName)
    {
        $this->pdo = $this->createMysqlPdo($host, $user, $pass, $dbName);
    }

    public function isConnected()
    {
        return ! is_null($this->pdo);
    }

    public function executeSql($sql)
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    private function createMysqlPdo($host, $user, $pass, $dbName)
    {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8', $host, $dbName);
        try {
            $pdo = new \PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $pdo = null;
        }
        return $pdo;
    }
}