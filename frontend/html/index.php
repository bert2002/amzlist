<?php
session_start(); 

include('../config/config.inc.php');
include('functions.php');
include('functions-db.php');
require_once 'lib/AmazonECS.class.php';

$input = $_POST['search'];
$country = $_POST['country'];
$desiredcurrency = $_POST['currency'];

# detect IE
if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) {
  header("Location: http://$IS_DOMAIN/unsupported/$language/",TRUE,301);
}

# captcha stuff
$link = $_POST['link'];
$childasin = $_POST['childasin'];
$childcountry = $_POST['childcountry'];
$language = $_POST['language'];

if( md5( $_POST[ 'code' ] ) == $_SESSION[ 'key' ] ) {

  if ($link == "Yes" && isset($childasin) && isset($childcountry) ) {
      $input = $_POST['input'];
      $country = $_POST['country'];
      $desiredcurrency = $_POST['currency'];
      $language = $_POST['language'];
      linkDatabaseASIN($db, $input, $country, $childasin, $childcountry);

      header("Location: http://$IS_DOMAIN/compare/$input/$country/$desiredcurrency/$language/",TRUE,301);      
  }
} else {

  if ($link == "Yes" && isset($childasin) && isset($childcountry) ) {
      $input = $_POST['input'];
      $country = $_POST['country'];
      $desiredcurrency = $_POST['currency'];
      $language = $_POST['language'];
      
      header("Location: http://$IS_DOMAIN/compare/$input/$country/$desiredcurrency/$language/",TRUE,301);
  }
}  

# get stuff
   
if (empty($input)) {
    $input  = $_GET['search'];
}

if (empty($country)) {
    $country = $_GET['country'];
}

if (empty($desiredcurrency)) {
    $desiredcurrency = $_GET['currency'];
}

if (empty($language)) {
  $language = $_GET['language'];
}

if (empty($desiredcurrency)) {
    $desiredcurrency = "EUR";
}

if (empty($country)) {
    $country = "us";
}

if ($language) {
        include('../lang/'.$language.'.php');
} else {
        include('../lang/en.php');
        $language = "en";
        header("Location: http://$IS_DOMAIN/$language/");
}

# check maintenance
if ($IS_MAINTENANCE) {
  header("Location: http://$IS_DOMAIN/maintenance/$language/");
}   


?>
<html>
<head>
<link rel="SHORTCUT ICON" href="favicon.ico">
<title><?php echo $LANG['page']['title'];?></title>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta name="robots" content="index, follow">
  <meta name="description" content="<?php echo $LANG['page']['howitworks']['text']; ?>">
  <meta name="keywords" content="amazon,price,compare,comparison,search,money,usd,eur,pound,list,compare products,import,export,national,international,shipping,graph,tracking,price history,alerts,notifications">
  <link rel="stylesheet" type="text/css" href="/css/boxdesign.css">

  <!-- google fonts -->
  <link href='http://fonts.googleapis.com/css?family=Ultra&v2' rel='stylesheet' type='text/css'>
<script type="text/javascript">
var ray={
        ajax:function(st)
        {
                this.show('load');
        },
                show:function(el)
        {
                this.getID(el).style.display='';
        },
                getID:function(el)
        {
                return document.getElementById(el);
        }
}

</script>
</head>

<div class="main">
<div class="maintop">
 <div class="center">
  <div style="font-family: 'Ultra', serif; font-size: 56px; float:left; padding: 15px; background: white; margin-top: 11px;"><a href="/<?php echo $language;?>/" style="text-decoration: none; display: block;">*AMZlist</a></div>
  <div style="float:right; margin-top: 70px;">
  	<a href="/en/"><button class="button" >EN</button></a>
  	<a href="/de/"><button class="button" >DE</button></a>
  </div>
 </div> <!-- center end -->  
</div> <!-- maintop end -->

  <div class="maincontent">

  <div class="center">    
    <!-- search -->
  <div class="frame">
    <form id="search" method="post" action="/" onsubmit="return ray.ajax()" class="form">
      <input name="search" placeholder="<?php echo $LANG['search']['text'];?>" value="<?php echo $input;?>" class="search"/>
      <select name="country" class="button">
        <?php
        for ($i = 0; $i <= $VALID_COUNTRY_COUNT; $i++) {
            $new = $VALID_COUNTRY[''.$country.''][$i];
            $upper = strtoupper($new);
            echo '<option value="'.$new.'">'.$upper.'</option>';
        }
        ?>
      </select>
      <select name="currency" class="button">
        <?php
        for ($i = 0; $i <= $VALID_CURRENCY_COUNT; $i++) {
            $new = $VALID_CURRENCY[''.$desiredcurrency.''][$i];
            echo '<option value="'.$new.'">'.$new.'</option>';
        }
        ?> 
       </select>
      <input type="hidden" name="language" value="<?php echo $language;?>"/>
      <button type="submit" class="button"><?php echo $LANG['search']['title'];?></button>
    </form> <!-- search form end -->
  </div> <!-- frame end -->
  </div> <!-- center end -->

<div id="load" style="display:none;"><br><br><br><b><?php echo $LANG['loading']['title'];?><b><br><img src="/img/loading.gif"/><br><?php echo $LANG['loading']['text'];?><br></div>
    <!-- center -->
    <div class="center">
    <div style="height: 5px;"></div>

    <?php

    if (isset($input)) {

	$check = checkInput($country, $input);
	$country = $check[0];
	$asin = $check[1]; 
	if ($asin) {
		# check database
		$result = checkDatabaseASIN($db, $country, $asin, $desiredcurrency, $childcountry, $language);
		#var_dump($result);
		if ($result) {
			showResults($result, $language);
		} else { # asin not in database
			# query amzon for asin
			$amazonResponseGroupItemAttributes = amazonQueryResponseGroup("Medium", $country, $asin);
			#var_dump($amazonResponseGroupItemAttributes);
			$check = checkAmazonASIN($amazonResponseGroupItemAttributes);
			if ($check) {
				# get more details, write to database and show result
				$result = queryAmazonASIN($country, $asin, $amazonResponseGroupItemAttributes, $desiredcurrency);
                                #var_dump($result); 
				writeDatabaseResults($db, $result);
				showResults($result, $language);
                        } else { # asin not known in amazon store
                                showAmazonASINerror($country, $asin, $language);
                        }
                        
                } # asin not in database end
                
		$asin = $result[1];
		$title = $result[2];
		$manufacturer = $result[6];
		$binding = $result[7];

		$childasin = $asin;
		$childcountry = $country;

		# now we need query for the other contry!!!
		for ($i = 1; $i <= $VALID_COUNTRY_COUNT; $i++) {
			$othercountry = $VALID_COUNTRY[''.$country.''][$i];
			queryOtherCountry($othercountry, $asin, $title, $manufacturer, $binding, $desiredcurrency, $childasin, $childcountry, $db, $language);
		}

			
	} else { # if check = search
		# check amazon
		$amazonResponse = amazonQuery($country, $input, "", "All");
		showAmazonSearch($country, $amazonResponse, "8", $desiredcurrency, "No", "", "", $db, $language);

	} # end if check = search


    } else { # else fof isset
    
        showDatabaseLatest($db, $country, $language, $desiredcurrency, $language); 

        $texthowitworkstitle = $LANG['page']['howitworks']['title']; 
        $texthowitworkstext = $LANG['page']['howitworks']['text']; 
        echo "<div style=\"height: 5px;\"></div>";
        echo "<div class=\"headline\">$texthowitworkstitle</div>";
        echo "<div class=\"text\">$texthowitworkstext</div>";
    }
    ?>
    </div> <!-- center end -->
  </div> <!-- maincontent end -->

<div class="mainbottom">
  <div class="center">
      <?php if (isset($input)) { 
        #$now = date('l jS \of F Y h:i:s A'); 
        $now = checkDatabaseLastUpdate($db, $language); 
        $textagreementdate = $LANG['page']['agreement']['date']; 
        $textagreementtext = $LANG['page']['agreement']['text'];         
        
        echo "<div class=\"text agreement\">$textagreementdate $now UTC</div>";
        echo "<div class=\"text agreement\">$textagreementtext</div>";
        }
      ?>
      <a href="/news/<?php echo $language;?>/"><button class="button"><?php echo $LANG['news']['title'];?></button></a>
      <a href="/aboutus/<?php echo $language;?>/"><button class="button"><?php echo $LANG['aboutus']['title'];?></button></a>
      <a href="/legal/<?php echo $language;?>/"><button class="button"><?php echo $LANG['legal']['title'];?></button></a>
      <a href="http://www.twitter.com/amzlist" target="_blank"><button class="button">Twitter</button></a>
  </div> <!-- center end -->
</div> <!-- mainbottom end -->



</div> <!-- main end -->

</body>
</html>
