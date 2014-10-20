#!/bin/bash

# ========== Setting Start ==========

# Release Number
releaseNo=001

# Git Respository
gitRespository=ssh://peter@gitHost/var/git/framework

# Server Settings
serverHost=hostnameOrIp
serverUser=deployer
serverPass=password

releaseDir=/var/www/html/frameworkRelease/
htmlLink=/var/www/html/framework
dbDatabase=framework
dbUser=frameworkdeployer
dbPass=frameworkpass

serverSsh=${serverUser}@${serverHost}

# if server authenticated by public/private key
#sshCmd="ssh $serverSsh"
#scpCmd="scp"

# if server authenticated by public/private key (including the key file)
#sshCmd="ssh -i /path/to/private/key/private.key $serverSsh"
#scpCmd="scp -i /path/to/private/key/private.key"

# if server authenticated by password
sshCmd="sshpass -p $serverPass ssh $serverSsh"
scpCmd="sshpass -p $serverPass scp"

# Local Settings
gitDir=git

# ========== Setting End ==========

# Script start...
echo "Deploy Release $releaseNo ..."

echo "Clear existing git directory..."
rm -rf $gitDir/

echo "Get respository..."
git clone $gitRespository $gitDir/

echo "Zip the upload code..."
cd $gitDir/src
tar -cf ../deploy.tar .
mv ../deploy.tar ../..
cd ../..

echo "Clear server release directory..."
$sshCmd "cd $releaseDir; rm -rf $releaseNo; mkdir $releaseNo"

echo "Upload zip files to server..."
$scpCmd deploy.tar $serverSsh:${releaseDir}$releaseNo

echo "Delete local deploy.tar ..."
rm deploy.tar

echo "Unzip files at the server..."
$sshCmd "cd $releaseDir$releaseNo; tar -xvf deploy.tar; rm deploy.tar"
echo "Update server config files..."
#$sshCmd "cd $releaseDir; cp deploy/conf/.htaccess $releaseNo; cp deploy/conf/config/config.php $releaseNo/config/config.php"
$sshCmd "cd $releaseDir; cp deploy/conf/config/config.php $releaseNo/config/config.php"

echo "Upload Database Patch..."
if [ -f "git/db/patch$releaseNo.sql" ]
then

	$scpCmd git/db/*${releaseNo}.sql $serverSsh:${releaseDir}deploy
	echo "Run Database Patch..."
	$sshCmd "cd ${releaseDir}deploy; mysql -u $dbUser -p$dbPass $dbDatabase < patch$releaseNo.sql; rm patch$releaseNo.sql"
	echo "Delete Database Patch..."

else
	echo "No Database Path for this Release"
fi

echo "Change symbolic link to new Release..."
$sshCmd "ln -sfn ${releaseDir}$releaseNo $htmlLink"

echo "Clear Local Git Directory..."
rm -rf $gitDir

echo "Deployment Done"

echo
echo "How to rollback"
echo "- Login to the server"
echo "> mysql -u $dbUser -p $dbDatabase < ${releaseDir}rollback$releaseNo.sql"
echo "> ln -sfn $releaseDir<last release no> $htmlLink"