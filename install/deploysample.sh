#!/bin/bash

# ========== Setting Start ==========

### Release Number
releaseNo=001


### Project Name
project=mvc


### Git Respository
#gitRespository=ssh://peter@gitHost/var/git/${project}
gitRespository=https://github.com/bonepeter/mvc.git


### Web Server Settings
webServerHost=hostnameOrIp
webServerPort=22
webServerUser=deployer
webServerPass=password
#webServerPrivateKey=/path/to/private/key/private.key


### Database Server Settings
dbServerHost=${webServerHost}
dbServerPort=${webServerPort}
dbServerUser=${webServerUser}
dbServerPass=${webServerPass}
#dbServerPrivateKey=${webServerPrivateKey}

dbDatabase=${project}
dbUser=${project}deployer
dbPass=${project}deployerpass


### Local Settings
gitDir=git


# ========== Setting End ==========

source deploylib.sh