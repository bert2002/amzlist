#!/usr/bin/perl


# Configuration Package

package amazonConfig; 

%prod = (

    DEBUG => '1',
    MYSQL_USER => 'amzlist',
    MYSQL_HOST => 'localhost',
    MYSQL_DATABASE => 'amzlist',
    MYSQL_PASSWORD => 'PASSWORD',
    MYSQL_PORT => '3306',
    AWS_API_KEY => 'AWSAPI',
    AWS_API_SECRET_KEY => 'AWSAPIKEY',
    LOGFILE => '/amz/perl/log/amzlist.log',
    LOCKFILE => '/amz/perl/lock/amzlist.lock',
    GRAPHPATH => '/amz/apache/graph'

);

# end
1; 
