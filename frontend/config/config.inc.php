<?php

#error_reporting(E_ALL);

// is maintenance
$IS_MAINTENANCE = "0";

// is domain
$IS_DOMAIN = "www.yourdomain.com";

// adodb cache
$ADODB_CACHE_DIR = '/amz/apache/cache';

// database settings
$MYSQL_USER = "amzlist";
$MYSQL_PASSWORD = "MYPASSWORD";
$MYSQL_DATABASE = "amzlist";
$MYSQL_HOST = "localhost";

// amazon settings
$AWS_API_KEY = "MYAPIKEY";
$AWS_API_SECRET_KEY = "MYAPISECRETKEY";
$AWS_ASSOCIATE_TAG = array (
'de' => 'MYAWSTAG',
'uk' => 'MYAWSTAG',
'us' => 'MYAWSTAG',
'fr' => 'MYAWSTAG',
'ca' => 'MYAWSTAG',
'jp' => 'MYAWSTAG',
'it' => 'MYAWSTAG',
'cn' => 'MYAWSTAG'
);

$AWS_COUNTRY_CODE = array (
'de' => 'de',
'us' => 'com',
'uk' => 'co.uk',
'ca' => 'ca',
'fr' => 'fr',
'jp' => 'co.jp',
'it' => 'it',
'cn' => 'cn'
);

// country and domain settings
// $VALID_COUNTRY_COUNT="5";
$VALID_COUNTRY_COUNT = "4";
$VALID_COUNTRY_MAX = "7";
$VALID_COUNTRY = array(
  'de' => array('de','uk','us','ca','fr','jp','it','cn'),
  'uk' => array('uk','de','us','ca','fr','jp','it','cn'),
  'us' => array('us','de','uk','ca','fr','jp','it','cn'),
  'fr' => array('fr','uk','us','ca','de','jp','it','cn'),
  'ca' => array('ca','uk','us','de','fr','jp','it','cn'),
  'jp' => array('jp','uk','us','ca','fr','de','it','cn'),
  'it' => array('it','uk','us','ca','fr','de','jp','cn'),
  'cn' => array('cn','uk','us','ca','fr','de','jp','it')
);

$VALID_CURRENCY_COUNT = "3";
$VALID_CURRENCY_MAX = "4";
$VALID_CURRENCY = array(
  'EUR' => array('EUR','USD','GBP','CAD','YEN'),
  'USD' => array('USD','EUR','GBP','CAD','YEN'),
  'GBP' => array('GBP','EUR','USD','CAD','YEN'),
  'CAD' => array('CAD','GBP','USD','EUR','YEN'),
  'YEN' => array('YEN','GBP','USD','EUR','CAD')
);

$VALID_DOMAINS = array(
  'de' => 'de',
  'uk' => 'uk',
  'com' => 'us',
  'ca' => 'ca',
  'fr' => 'fr',
  'jp' => 'jp',
  'it' => 'it',
  'cn' => 'cn'
);

$VALID_LANGUAGE = array('en','de');



// http://docs.amazonwebservices.com/AWSECommerceService/2011-08-01/DG/
// CN still missing -> http://docs.amazonwebservices.com/AWSECommerceService/latest/DG/index.html?APPNDX_SearchIndexValues.html
$VALID_SEARCHINDEX = array(
'ca' => array('All' => array('1'),'Books' => array('1'),'Classical' => array('1'),'DVD' => array('1'),'Electronics' => array('1'),'ForeignBooks' => array('1'),'Kitchen' => array('1'),'Music' => array('1'),'Software' => array('1'),'SoftwareVideoGames' => array('1'),'VHS' => array('1'),'Video' => array('1'),'VideoGames' => array('1')),
'de' => array('All' => array('1'),'Apparel' => array('1'),'Automotive' => array('1'),'Baby' => array('1'),'Beauty' => array('1'),'Books' => array('1'),'Classical' => array('1'),'DVD' => array('1'),'Electronics' => array('1'),'ForeignBooks' => array('1'),'Grocery' => array('1'),'HealthPersonalCare' => array('1'),'HomeGarden' => array('1'),'HomeImprovement' => array('1'),'Jewelry' => array('1'),'KindleStore' => array('1'),'Kitchen' => array('1'),'Magazines' => array('1'),'Jewelry' => array('1'),'MP3Downloads' => array('1'),'Music' => array('1'),'MusicalInstruments' => array('1'),'MusicTracks' => array('1'),'OfficeProducts' => array('1'),'OutdoorLiving' => array('1'),'Outlet' => array('1'),'PCHardware' => array('1'),'Photo' => array('1'),'Shoes' => array('1'),'Software' => array('1'),'SportingGoods' => array('1'),'SoftwareVideoGames' => array('1'),'Tools' => array('1'),'Toys' => array('1'),'VHS' => array('1'),'Video' => array('1'),'VideoGames' => array('1'),'Watches' => array('1')),
'fr' => array('All' => array('1'),'Apparel' => array('1'),'Baby' => array('1'),'Beauty' => array('1'),'Books' => array('1'),'Classical' => array('1'),'DVD' => array('1'),'Electronics' => array('1'),'ForeignBooks' => array('1'),'HealthPersonalCare' => array('1'),'Jewelry' => array('1'),'Kitchen' => array('1'),'Jewelry' => array('1'),'MP3Downloads' => array('1'),'Music' => array('1'),'MusicalInstruments' => array('1'),'MusicTracks' => array('1'),'OfficeProducts' => array('1'),'Outlet' => array('1'),'Shoes' => array('1'),'PCHardware' => array('1'),'Software' => array('1'),'SoftwareVideoGames' => array('1'),'SportingGoods' => array('1'),'VHS' => array('1'),'Video' => array('1'),'VideoGames' => array('1'),'Watches' => array('1')),
'it' => array('All' => array('1'),'Books' => array('1'),'DVD' => array('1'),'Electronics' => array('1'),'ForeignBooks' => array('1'),'Music' => array('1'),'Software' => array('1'),'VideoGames' => array('1')),
'jp' => array('All' => array('1'),'Apparel' => array('1'),'Automotive' => array('1'),'Baby' => array('1'),'Beauty' => array('1'),'Books' => array('1'),'Classical' => array('1'),'DVD' => array('1'),'Electronics' => array('1'),'ForeignBooks' => array('1'),'Grocery' => array('1'),'HealthPersonalCare' => array('1'),'Hobbies' => array('1'),'HomeImprovement' => array('1'),'Jewelry' => array('1'),'Kitchen' => array('1'),'MP3Downloads' => array('1'),'Music' => array('1'),'MusicalInstruments' => array('1'),'MusicTracks' => array('1'),'OfficeProducts' => array('1'),'Shoes' => array('1'),'Software' => array('1'),'SportingGoods' => array('1'),'Toys' => array('1'),'VHS' => array('1'),'Video' => array('1'),'VideoGames' => array('1'),'Watches' => array('1')),
'uk' => array('All' => array('1'),'Apparel' => array('1'),'Automotive' => array('1'),'Baby' => array('1'),'Beauty' => array('1'),'Books' => array('1'),'Classical' => array('1'),'DVD' => array('1'),'Electronics' => array('1'),'Grocery' => array('1'),'HealthPersonalCare' => array('1'),'HomeGarden' => array('1'),'HomeImprovement' => array('1'),'Jewelry' => array('1'),'KindleStore' => array('1'),'Kitchen' => array('1'),'Jewelry' => array('1'),'Outlet' => array('1'),'MP3Downloads' => array('1'),'Music' => array('1'),'MusicalInstruments' => array('1'),'MusicTracks' => array('1'),'OfficeProducts' => array('1'),'OutdoorLiving' => array('1'),'PCHardware' => array('1'),'Shoes' => array('1'),'Software' => array('1'),'SoftwareVideoGames' => array('1'),'Toys' => array('1'),'VHS' => array('1'),'Video' => array('1'),'VideoGames' => array('1'),'Watches' => array('1')),
'us' => array('All' => array('1'),'Apparel' => array('1'),'Appliances' => array('1'),'ArtsAndCrafts' => array('1'),'Automotive' => array('1'),'Baby' => array('1'),'Beauty' => array('1'),'Books' => array('1'),'Classical' => array('1'),'DigitalMusic' => array('1'),'DVD' => array('1'),'Electronics' => array('1'),'Grocery' => array('1'),'HealthPersonalCare' => array('1'),'HomeImprovement' => array('1'),'Industrial' => array('1'),'Jewelry' => array('1'),'KindleStore' => array('1'),'Kitchen' => array('1'),'Magazines' => array('1'),'Merchants' => array('1'),'Miscellaneous' => array('1'),'MobileApps' => array('1'),'MP3Downloads' => array('1'),'Music' => array('1'),'MusicalInstruments' => array('1'),'MusicTracks' => array('1'),'OfficeProducts' => array('1'),'OutdoorLiving' => array('1'),'PCHardware' => array('1'),'PetSupplies' => array('1'),'Photo' => array('1'),'Shoes' => array('1'),'Software' => array('1'),'SportingGoods' => array('1'),'Tools' => array('1'),'Toys' => array('1'),'UnboxVideo' => array('1'),'VHS' => array('1'),'Video' => array('1'),'VideoGames' => array('1'),'Watches' => array('1'),'Wireless' => array('1'),'WirelessAccessories' => array('1'))
);


?>
