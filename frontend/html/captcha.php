<html>
<head>

<?php
include('../config/config.inc.php');
include('functions.php');
include('functions-db.php');
require_once 'lib/AmazonECS.class.php';

# detect IE
if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) {
  header("Location: http://$IS_DOMAIN/unsupported/$language/",TRUE,301);
}
  

$input = $_GET['search'];
$country = $_GET['country'];
$language = $_GET['language'];
$desiredcurrency = $_GET['currency'];

$link = $_GET['link'];
$childasin = $_GET['childasin'];
$childcountry = $_GET['childcountry'];

if (empty($desiredcurrency)) {
    $desiredcurrency = "USD";
}

if (empty($country)) {
    $country = "us";
}

if ($language) {
        require_once('../lang/'.$language.'.php');
} else {
        require_once('../lang/en.php');
        $language = "en";
        header("Location: http://$IS_DOMAIN/aboutus/$language/",TRUE,301);
}

# check maintenance
if ($IS_MAINTENANCE) {
  header("Location: http://$IS_DOMAIN/maintenance/$language/",TRUE,301);
}   

?>

<link rel="SHORTCUT ICON" href="favicon.ico">
<title>amzlist - Your partner for price comparision around the globe</title>
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
      <input name="search" placeholder="Search by Name, ASIN or URL" value="<?php echo $input;?>" class="search"/>
      <select name="country" class="button">
        <?php
        for ($i = 0; $i <= $VALID_CURRENCY_COUNT; $i++) {
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
      <button type="submit" class="button">Search</button>
    </form> <!-- search form end -->
  </div> <!-- frame end -->
  </div> <!-- center end -->

  <?php
  if (empty($_GET['childcountry'])) {
    return(false);
  }

	$texttitle = $LANG['captcha']['title']; 
	$texthuman = $LANG['captcha']['text']; 
	$textsubmittitle = $LANG['captcha']['submit']['title']; 
	$textsubmittext = $LANG['captcha']['submit']['text']; 


  ?>


    <!-- center -->
    <div class="center">

    <div style="height: 5px;"></div>
    <div class="headline"><? echo $texttitle;?></div>
        <div class="text"><? echo $texthuman;?><br><br>
        <img src="/captchaimg.php" border="0" /> <br>
        <form id="captcha" method="post" action="/" class="form">
          <input name="code" placeholder="<? echo $textsubmittext;?>" class="captcha"/>
          <input type="hidden" name="link" value="Yes"/>
          <input type="hidden" name="childasin" value="<?php echo $childasin;?>"/>
          <input type="hidden" name="childcountry" value="<?php echo $childcountry;?>"/>
          <input type="hidden" name="input" value="<?php echo $input;?>"/>
          <input type="hidden" name="country" value="<?php echo $country;?>"/>
          <input type="hidden" name="currency" value="<?php echo $desiredcurrency;?>"/>
          <input type="hidden" name="language" value="<?php echo $language;?>"/>
          <button type="submit" class="button"><? echo $textsubmittitle;?></button><br><br><br><br><br>
        </form>
    </div>

    </div> <!-- center end -->
  </div> <!-- maincontent end -->

<div class="mainbottom">
  <div class="center">
      <a href="/news/<?php echo $language;?>/"><button class="button"><?php echo $LANG['news']['title'];?></button></a>
      <a href="/aboutus/<?php echo $language;?>/"><button class="button"><?php echo $LANG['aboutus']['title'];?></button></a>
      <a href="/legal/<?php echo $language;?>/"><button class="button"><?php echo $LANG['legal']['title'];?></button></a>
      <a href="http://www.twitter.com/amzlist" target="_blank"><button class="button">Twitter</button></a>
  </div> <!-- center end -->
</div> <!-- mainbottom end -->



</div> <!-- main end -->

</body>
</html>
