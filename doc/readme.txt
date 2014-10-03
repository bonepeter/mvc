Readme
======

=============================
Development Environment Setup
=============================
Code check: (Run in server)
> cd /var/www/html/framework
> phploc src
> phpcpd src
> phpmd src text cleancode,codesize,controversial,design,naming,unusedcode
> phpcs src

> rm -r phpdoc
> phpdoc -t phpdoc -d . --template="responsive-twig"


====================================
Production/Testing Environment Setup
====================================

LAMP Install
============


This File
=========
Replace _framework_ with your project name


Create Mysql Database and User
==============================
- Modify createDatabase.sql
- Execute createDatabase.sql


Project config
==============
- Modify config/config.php
- Modify src/.htaccess (TEMPLATE_URL)


Install Smarty for Template
===========================
- Download Smarty: http://www.smarty.net/download
- Quick Install Reference: http://www.smarty.net/quick_install
- Install the template files in /var/www/html/frameworkTemplate/smarty

cd /tmp
mkdir smarty
cd smarty
wget http://www.smarty.net/files/Smarty-stable.tar.gz
tar -zxvf Smarty-stable.tar.gz
sudo mkdir -p /usr/local/lib/php/Smarty
sudo cp -r Smarty-3.1.18/libs/* /usr/local/lib/php/Smarty
cd /var/www/html/
sudo mkdir _framework_Template
cd _framework_Template
sudo mkdir smarty
sudo mkdir smarty/templates
sudo mkdir smarty/templates_c
sudo mkdir smarty/cache
sudo mkdir smarty/configs
sudo chown peter:peter smarty/templates
sudo chown www-data:www-data smarty/templates_c
sudo chown www-data:www-data smarty/cache
sudo chmod 775 smarty/templates_c
sudo chmod 775 smarty/cache
cd /tmp
rm -rf smarty

cd /var/www/html/_framework_Template/
sudo vi .htaccess

Add this lines:
>>>>> frameworkTemplate/.htaccess <<<<<
Options -Indexes
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} -f
RewriteRule !\.(js|gif|jpg|png|css)$ - [F]
>>>>> frameworkTemplate/.htaccess <<<<<

vi smarty/templates/index.tpl


Setup deploy environment
========================
sudo useradd deployer
sudo passwd deployer

cd /var/www/html
sudo mkdir _framework_Release
sudo chown deployer:deployer _framework_Release

-> Login as deployer <-
cd /var/www/html/_framework_Release
mkdir deploy
cd deploy
vi .htaccess and add this line: Deny From All
mkdir conf
cd conf
vi .htaccess and copy content from local/framework/.htaccess
-> change TEMPLATE_URL
mkdir config
cd config
vi config.php, copy content from local/framework/config/config.php and edit for the server

Note: template will not deploy

Open /etc/apache2/apache2.conf, change the AllowOverride: (ubuntu server)
<Directory /var/www/>
        Options Indexes FollowSymLinks
        #AllowOverride None
        AllowOverride All
        Require all granted
</Directory>

> sudo a2enmod rewrite
> sudo service apache2 restart

OR

Open /etc/httpd/conf/httpd.conf, change the AllowOverride: (AWS server)
<Directory "/var/www/html">
    Options FollowSymLinks
    #AllowOverride None
    AllowOverride All
</Directory>

> sudo service httpd restart


================
FitNesse Testing
================

-> Download least release from http://fitnesse.org/FitNesseDownload to /tmp


cd /tmp
sudo mkdir /var/fitnesse
sudo mv fitnesse-standalone.jar /var/fitnesse
cd /var/fitnesse/
sudo wget https://cloud.github.com/downloads/ggramlich/phpslim/phpslim.phar
java -jar fitnesse-standalone.jar -p 8086 &

-> Browser: http://ip:8086
-> Add -> Test -> TestController

>>>>> Test <<<<<
!define TEST_RUNNER (/var/fitnesse/phpslim.phar)
!define COMMAND_PATTERN (php %m /var/www/html/framework/fitnesse)
!define TEST_SYSTEM {slim}

!|my fixture              |
|my value|value successor?|
|5       |6               |
|-4      |-3              |
|2       |4               |
>>>>> Test <<<<<
