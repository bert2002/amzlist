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
        header("Location: http://$IS_DOMAIN/news/$language/",TRUE,301);
}

# check maintenance
if ($IS_MAINTENANCE) {
  header("Location: http://$IS_DOMAIN/maintenance/$language/",TRUE,301);
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
  	<a href="/news/en/"><button class="button" >EN</button></a>
  	<a href="/news/de/"><button class="button" >DE</button></a>
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
      <button type="submit" class="button"><?php echo $LANG['search']['title'];?></button>
    </form> <!-- search form end -->
  </div> <!-- frame end -->
  </div> <!-- center end -->

<div id="load" style="display:none;"><br><br><br><b><?php echo $LANG['loading']['title'];?><b><br><img src="/img/loading.gif"/><br><?php echo $LANG['loading']['text'];?><br></div>
    <!-- center -->
    <div class="center">

    <div style="height: 5px;"></div>
      <div class="headline">2011.09.23*BETA</div>
      <div class="text">The first update includes minor bug fixing and the Amazon store from Canada and France. Enjoy!</div>
      <div class="headline">2011.08.31*BETA</div>
      <div class="text">We are online! The very first version of AMZLIST.COM appears online and we are proud of it. Now it is easy to search for products within several stores of Amazon. We bring the globalisation to the next level. Customers can compare products and safe real money.<br>The Amazon stores from Germany, USA and the United Kingdom are available now. Many more and fresh innovative features will follow in the next month.</div>
    </div> <!-- center end -->
  </div> <!-- maincontent end -->

<div class="mainbottom">
  <div class="center">
      <?php if (isset($input)) { 
        $now = date('l jS \of F Y h:i:s A'); 
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
