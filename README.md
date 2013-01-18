
AMZLIST
=============

AMZLIST is a easy tool to compare different products on all Amazon Stores worldwide. 
I created the site a couple years ago, but never did any advertisment for it. So it never made it
and I cant create a nice user interface.


Features
-------

* search products
* select currency
* conversion of other currencys
* create links between products on different sites
* secure captcha for linking products
* multilingual
* graph for price history (only backend)

Demo
------

http://www.amzlist.itbert.de 

Installation
-------

To install your own AMZLIST you need a Linux server with Apache + Mysql and a couple of perl modules.
The product is diveded within a backend and fronted for a higher scalability. 

A script for the installation is available. It is tested on ubuntu, but should work on debian as well.
The best would be to run the script on a empty server or check the install script for what needs to be done.

$ git clone https://github.com/bert2002/amzlist.git
$ cd amzlist/setup/ubuntu-init/install-ubuntu.sh
$ ./install-ubuntu.sh

Configuration
-------

The simple configuration is made within the webinterfaces and backend configuration file:

/var/www/amzlist/config/config.php
/global/scripts/amzlist/amazonConfig.pm

Some comments were added. Mostly you need to change the domain and add the Amazon reference IDs if you want to save some money. 
The rest should be untouched until you know what you are doing :) 

Known Problems
-------

* Its slow
* Ugly code

Screenshots
-------

http://github.com/bert2002/amzlist/blob/master/screenshots/amzlist1.png
http://github.com/bert2002/amzlist/blob/master/screenshots/amzlist2.png
http://github.com/bert2002/amzlist/blob/master/screenshots/amzlist3.png

