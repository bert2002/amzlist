#!/bin/bash
# script to install all necessary stuff for a AWS instance


# changelog
#
# 2011-08-29 init

# settings

ECHO="/bin/echo"
APTITUDE="/usr/bin/aptitude -q=0 -y"
MKDIR="/bin/mkdir"
ADDUSER="/usr/sbin/adduser"
CHOWN="/bin/chown"
CP="/bin/cp"
MYSQL="/usr/bin/mysql"
RM="/bin/rm"

MYHOME=`pwd`

# script

$ECHO "------------------------------------------"
$ECHO "update system"
$APTITUDE update && $APTITUDE upgrade

$ECHO "------------------------------------------"
$ECHO "install perl modules"
$APTITUDE install libdbd-mysql-perl libdatetime-perl libdatetime-locale-perl libdatetime-timezone-perl libxml-simple-perl librrds-perl

cd $MYHOME/perl/ && tar -xzf RRD-Simple-1.44.tar.gz && cd RRD-Simple-1.44 && perl Makefile.PL && make && make install


$ECHO "------------------------------------------"
$ECHO "install apache2 + php5"
$APTITUDE install apache2-doc apache2-mpm-prefork apache2-utils apache2.2-bin apache2.2-common libapache2-mod-php5 php5 php5-cli php5-common php5-dev php5-gd php5-mysql php5-curl
$CP $MYHOME/apache/security /etc/apache2/conf.d/
$CP $MYHOME/apache/status.conf /etc/apache2/mods-available/
$CP $MYHOME/apache/amzlist /etc/apache2/sites-available/
cd /etc/apache2/mods-enabled && ln -s ../mods-available/rewrite.load . 
cd /etc/apache2/sites-enabled/ && rm 000-default && ln -s ../sites-available/amzlist 
apache2ctl restart

$ECHO "------------------------------------------"
$ECHO "install mysql"
$APTITUDE install mysql-server-5.1 mysql-common mysql-client libmysqlclient16
$ECHO "create database. Please enter Password: "
$MYSQL -uroot -p < $MYHOME/sql/init.sql
$MYSQL -uroot -p < $MYHOME/sql/amzlist.sql

$ECHO "------------------------------------------"
$ECHO "install mysql backup"
$APTITUDE install automysqlbackup
$CP $MYHOME/mysql/automysqlbackup /etc/default/automysqlbackup

$ECHO "------------------------------------------"
$ECHO "update logrotate.d"
$CP $MYHOME/logrotate.d/amzlist-updatedb /etc/logrotate.d/

$ECHO "------------------------------------------"
$ECHO "create folder"
$MKDIR -p /amz/perl/log/ /amz/perl/lock/ /amz/perl/scripts/ /amz/apache/ /amz/backup/mysql/

$ECHO "------------------------------------------"
$ECHO "create user"
$ADDUSER amzuser
$CHOWN -R amzuser:amzuser /amz/

$ECHO "------------------------------------------"
$ECHO "copy frontend to /amz/apache/"
$CP -r $MYHOME/../../frontend/* /amz/apache/

$ECHO "------------------------------------------"
$ECHO "copy backend to /amz/perl/scripts/"
$CP -r $MYHOME/../../backend/* /amz/perl/scripts/

cd $MYHOME

$ECHO ""
$ECHO "------------------------------------------"
$ECHO "Installation finished"
$ECHO "1. create htpasswd user"
$ECHO "$ htpasswd /amz/apache/html/.htpasswd amzlist"
$ECHO ""
$ECHO "2. adjust your configuration files like database and AWS API Keys"
$ECHO "# aws: get AWS API key from https://aws.amazon.com/"
$ECHO "# mysql: UPDATE mysql.user SET Password=PASSWORD('XXXYYYZZZ') WHERE User='amzlist' AND Host='localhost';"
$ECHO "$ vi /amz/apache/config/config.inc.php"
$ECHO "$ vi /amz/perl/scripts/amazonConfig.pm"
$ECHO ""




