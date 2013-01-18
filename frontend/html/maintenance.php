<?php
session_start(); 

include('../config/config.inc.php');

# detect IE
if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) {
  header("Location: http://$IS_DOMAIN/unsupported/$language/",TRUE,301);
}
  

# get stuff
if (empty($language)) {
  $language = $_GET['language'];
}

if ($language) {
        include('../lang/'.$language.'.php');
} else {
        include('../lang/en.php');
        $language = "en";
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
  	<a href="/maintenance/en/"><button class="button" >EN</button></a>
  	<a href="/maintenance/de/"><button class="button" >DE</button></a>
  </div>
 </div> <!-- center end -->  
</div> <!-- maintop end -->

  <div class="maincontent">

  <div class="center">    
    <!-- search -->
  </div> <!-- center end -->

    <!-- center -->
    <div class="center">

    <?php
      $texterrortitle = $LANG['maintenance']['title'];
      $texterrortext = $LANG['maintenance']['text'];
    ?>

    <div style="height: 5px;"></div>
      <div class="headline"><?php echo $texterrortitle;?></div>
      <div class="text"><?php echo $texterrortext; ?></div>
    </div> <!-- center end -->
  </div> <!-- maincontent end -->

<div class="mainbottom">
 <div class="center">
      <a href="http://www.twitter.com/amzlist" target="_blank"><button class="button">Twitter</button></a>
  </div> <!-- center end -->
</div> <!-- mainbottom end -->



</div> <!-- main end -->

</body>
</html>
