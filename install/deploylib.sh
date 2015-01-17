#!/bin/bash

# Set variables
releaseDir=/var/www/html/${project}Release/
htmlLink=/var/www/html/${project}

setSshVariables() {
    prefix=$1
    eval "host=\$${prefix}ServerHost"
    eval "port=\$${prefix}ServerPort"
    eval "user=\$${prefix}ServerUser"
    eval "pass=\$${prefix}ServerPass"
    eval "privateKey=\$${prefix}ServerPrivateKey"

    userAtHost=${user}@${host}

    # if server authenticated by public/private key
    sshCmd="ssh ${userAtHost}"
    scpCmd="scp"

    # if server authenticated by password
    if [ -n "$pass" ]
    then
        sshCmd="sshpass -p ${pass} ssh ${userAtHost} -p ${port}"
        scpCmd="sshpass -p ${pass} scp -P ${port}"
    fi

    # if server authenticated by public/private key (including the key file)
    if [ -n "$privateKey" ]
    then
        sshCmd="ssh -i ${privateKey} ${userAtHost}"
        scpCmd="scp -i ${privateKey}"
    fi

    eval "${prefix}ServerSsh=\"${userAtHost}\""
    eval "${prefix}ServerSshCmd=\"${sshCmd}\""
    eval "${prefix}ServerScpCmd=\"${scpCmd}\""

    unset prefix
    unset host
    unset port
    unset user
    unset pass
    unset privateKey
    unset userAtHost
    unset sshCmd
    unset scpCmd
}

setSshVariables web

setSshVariables db


# Script start...
echo "Deploy Release $releaseNo ..."

echo "Clear existing git directory..."
rm -rf ${gitDir}/

echo "Get respository..."
git clone ${gitRespository} ${gitDir}/

if [ ! -d ${gitDir}/src ]
then
    echo "Error: Cannot get git respository"
    exit;
fi

echo "Zip the upload code..."
cd ${gitDir}/src
tar -cf ../deploy.tar .
mv ../deploy.tar ../..
cd ../..

echo "Clear server release directory..."
${webServerSshCmd} "cd $releaseDir; rm -rf $releaseNo; mkdir $releaseNo"

echo "Upload zip files to server..."
${webServerScpCmd} deploy.tar ${webServerSsh}:${releaseDir}${releaseNo}

echo "Delete local deploy.tar ..."
rm deploy.tar

echo "Unzip files at the server..."
${webServerSshCmd} "cd $releaseDir$releaseNo; tar -xvf deploy.tar; rm deploy.tar"
echo "Update server config files..."
${webServerSshCmd} "cd $releaseDir; cp deploy/conf/config/config.php $releaseNo/config/config.php"

echo "Upload Database Patch..."
if [ -f "git/db/patch$releaseNo.sql" ]
then

	${dbServerScpCmd} git/db/*${releaseNo}.sql ${webServerSsh}:${releaseDir}deploy
	echo "Run Database Patch..."
	${dbServerSshCmd} "cd ${releaseDir}deploy; mysql -u $dbUser -p$dbPass $dbDatabase < patch$releaseNo.sql; rm patch$releaseNo.sql"
	echo "Delete Database Patch..."

else
	echo "No Database Path for this Release"
fi

echo "Change symbolic link to new Release..."
${webServerSshCmd} "ln -sfn ${releaseDir}$releaseNo $htmlLink"

echo "Clear Local Git Directory..."
rm -rf ${gitDir}

echo "Deployment Done"

echo
echo "How to rollback"
echo "- Login to the server"
echo "> mysql -u ${dbUser} -p ${dbDatabase} < ${releaseDir}rollback${releaseNo}.sql"
echo "> ln -sfn ${releaseDir}<last release no> ${htmlLink}"
