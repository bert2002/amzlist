<?php


// check if the search is a string or a asin
function checkInput($country, $input) {

include('../config/config.inc.php');

	if (preg_match("/^http/i", "$input")) {
   
		# find correct country out of url
        	preg_match('@^(?:http://)?([^/]+)@i', $input, $domain);
        	$host = $domain[1];
        	preg_match('/[^.]+$/', $host, $localized);
        	$country = $VALID_DOMAINS[''.$localized[0].''];

        	#preg_match("/B(?=.*\d)(?=.*[A-Z]).{9}/", "$input", $asin);
        	preg_match("/[A-Z0-9]{10}/", "$input", $asin); 
        	$asin = $asin[0];

    	} elseif (preg_match("/[A-Z0-9]{10}/", "$input", $asin)) {
        	$asin = $asin[0];

	} else {
	        $asin = "";
	}


	# return results 
	$results = array($country, $asin);
	return $results;

}

// checks if the given ASIN is availabe on the amazon store 
function checkAmazonASIN($amazonResponseGroup) {

	$amazonResult = $amazonResponseGroup['Items']['Request']['Errors']['Error']['Code'];
	$AWSerror = strpos($amazonResult, "AWS");

	if ($AWSerror !== false) {
		# could not find ASIN on amazon store
		$results = "";
		return false; 
	} else {
		# could find ASIN on amazon store
		$results = "";
		return true; 
	}


}

// query amazon for details of given ASIN number
function queryAmazonASIN($country, $asin, $amazonResponseGroup, $desiredcurrency) {

include('../config/config.inc.php');

	# found ASIN in Amazon
        $price = amazonSearch($amazonResponseGroup, "FormattedPrice", "0" );
        $title = amazonSearch($amazonResponseGroup, "GroupTitle", "0" );
	$detailpageurl = amazonSearch($amazonResponseGroup, "GroupDetailPageURL", "0" );
	$manufacturer = amazonSearch($amazonResponseGroup, "GroupManufacturer", "0" );
	$lowestnewprice = amazonSearch($amazonResponseGroup, "GroupLowestNewPrice", "0" );
	$lowestnewpricecurrencycode = amazonSearch($amazonResponseGroup, "GroupLowestNewPriceCurrencyCode", "0" );
        $smallimage = amazonSearch($amazonResponseGroup, "SmallImage", "0" );
        $binding = amazonSearch($amazonResponseGroup, "GroupBinding", "0" );
        $currencycode = amazonSearch($amazonResponseGroup, "CurrencyCode", "0" );
        $totalnew = amazonSearch($amazonResponseGroup, "GroupTotalNew", "0" );

        # check if product is available
        if ( $totalnew > "0" ) {
            #$totalnew = "Yes ($totalnew NEW)";
            $totalnew = "Yes";
        } else {
            $totalnew = "N/A"; 
        }                                                                                        

        # set lowest price
        if ($lowestnewprice) {
            if ($lowestnewprice != "\$0.00") {
                $price = $lowestnewprice;
                $currencycode = $lowestnewpricecurrencycode;
            }
        }

        # check binding
        $validbinding = $VALID_SEARCHINDEX[''.$country.''][''.$binding.''][0];

        if (empty($validbinding)) {
            unset($binding); 
        }

	# return results
	$results = array($country, $asin, $title, $price, $smallimage, $detailpageurl, $manufacturer, $binding, $currencycode, $desiredcurrency, $totalnew);
	return $results; 

}

// shows the amazon query result from queryAmazonASIN()
function showResults($result, $language) {

include('../lang/'.$language.'.php');

if (empty($result[3])) {
    $result[3] = "N/A";
} else {
    $price = $result[3];
    $currencycode = $result[8];
    $desiredcurrency = $result[9];
    $calculated = currency($currencycode,$desiredcurrency,$price);

}                                    

        # shortend the title
	$shorttitle=substr(''.$result[2].'', 0, 42);
	if ($shorttitle) {
	    if ($shorttitle != $title) {
                $shorttitle .="...";
            }                                                    	
        } else {
            $shorttitle = "N/A";
        }

	# check for empty picture
	if (empty($result[4])) {
	    $result[4] = "/img/nocover.png";
        }

        $texttitle = $LANG['results']['title'];
        $textinstock = $LANG['results']['instock']; 

echo"	<div style=\"height: 5px;\"></div>";
echo"	<div class=\"box\">";
if ($calculated) {
    echo"       <div class=\"boxbuy\"><div class=\"yellow\">$calculated</div>$result[3]<a href=\"$result[5]\" target=\"_blank\"><img src=\"/img/buy-$language.gif\" style=\"text-decoration: none; margin-top: 5px;\"></a></div>";
} else {
    echo"	<div class=\"boxbuy\"><div class=\"yellow\">$result[3]</div>--<br><a href=\"$result[5]\" target=\"_blank\"><img src=\"/img/buy-$language.gif\" style=\"text-decoration: none; margin-top: 5px;\"></a></div>";
}
echo"	<img src=\"/img/country-$result[0].png\" style=\"float: left; text-decoration: none;\"/>";
echo"	<div class=\"divider\"></div>";
echo"	<div style=\"float: left; width: 75px; height: 75px; text-align: center;\"><img src=\"$result[4]\" style=\"text-align: center;\"></div>";
echo"	<div class=\"divider\"></div>";
echo"	<div class=\"boxtop\"><b>$texttitle:</b> $shorttitle</div>";
echo"	<div class=\"dividervertical\"></div>";
echo"	<div class=\"boxbottom\"><b>ASIN:</b> $result[1] <b>$textinstock:</b> $result[10]</div>";
echo"	</div> <!-- box end -->";




}

// shows a error because the ASIN is not available on the amazon store
function showAmazonASINerror($country, $asin, $language) {

include('../lang/'.$language.'.php');

$textnoresults = $LANG['results']['noresults']; 

echo"	<div style=\"height: 5px;\"></div>";
echo"	<div class=\"box\">";
echo"	<img src=\"/img/country-$country.png\" style=\"float: left;\"/>";
echo"	<div class=\"divider\"></div>";
echo"	<div class=\"boxerror\">$textnoresults</div>";
echo"	</div> <!-- box end -->";


}

// shows the query result of amazonQuery()
function showAmazonSearch($country, $amazonResponse, $count, $desiredcurrency, $link, $childasin, $childcountry, $db, $language) {

include('../lang/'.$language.'.php');

        $result = "";

        $amazonResult = $amazonResponse['Items']['Request']['Errors']['Error']['Code'];
        $AWSerror = strpos($amazonResult, "AWS");
                
        if ( $AWSerror !== false ) {
            $texterror = $LANG['results']['noresults']; 
            $result .= "<br>$texterror";
        } else {
            #echo "okay";


	for ($i = 0; $i <= $count; $i++) {
		$title = amazonSearch($amazonResponse, "Title", $i );
		$asin = amazonSearch($amazonResponse, "ASIN", $i );
		
		$amazonResponseGroupItemAttributes = amazonQueryResponseGroup("Medium", $country, $asin);
		#var_dump($amazonResponseGroupItemAttributes);
		$price = amazonSearch($amazonResponseGroupItemAttributes, "FormattedPrice", $i );
		$detailpageurl = amazonSearch($amazonResponseGroupItemAttributes, "GroupDetailPageURL", $i );
		$smallimage = amazonSearch($amazonResponseGroupItemAttributes, "SmallImage", $i );
		$currencycode = amazonSearch($amazonResponseGroupItemAttributes, "CurrencyCode", $i );
		$totalnew = amazonSearch($amazonResponseGroupItemAttributes, "GroupTotalNew", $i );
		$manufacturer = amazonSearch($amazonResponseGroupItemAttributes, "GroupManufacturer", $i );
		$binding = amazonSearch($amazonResponseGroupItemAttributes, "GroupBinding", $i );
		$totalnew = amazonSearch($amazonResponseGroupItemAttributes, "GroupTotalNew", $i );		
		
		if ( $totalnew > "0" ) {
		    $totalnew = "Yes";
		} else {
		    $totalnew = "N/A"; 
		}


		# check binding
		$validbinding = $VALID_SEARCHINDEX[''.$country.''][''.$binding.''][0];
              
		if (empty($validbinding)) {
		    unset($binding); 
                }

                # check for valid price
		if ($price == "\$0.00") {
		    $lowestnewprice = amazonSearch($amazonResponseGroupItemAttributes, "GroupLowestNewPrice", $i );
		    $lowestnewpricecurrencycode = amazonSearch($amazonResponseGroupItemAttributes, "GroupLowestNewPriceCurrencyCode", $i );
		    $price = $lowestnewprice; 
		    $currencycode = $lowestnewpricecurrencycode; 
		}

		if (empty($price)) {
		    $lowestnewprice = amazonSearch($amazonResponseGroupItemAttributes, "GroupLowestNewPrice", $i );
		    $lowestnewpricecurrencycode = amazonSearch($amazonResponseGroupItemAttributes, "GroupLowestNewPriceCurrencyCode", $i );
		    if (empty($lowestnewprice)) {
		        $price = "N/A";
		    } else {
		        $price = $lowestnewprice; 
		        $currencycode = $lowestnewpricecurrencycode;
		    }
                }
 
                # check for empty picture
                if (empty($smallimage)) {
                    $smallimage = "/img/nocover.png";
                }

		# shortend title
                $shorttitle=substr(''.$title.'', 0, 42);
                
                if ($shorttitle) {
                    if ($shorttitle != $title) {
                        $shorttitle .="...";
                    }
                } else {
                    $shorttitle = "N/A";
                }
               
                # calculate currency
                $calculated = currency($currencycode,$desiredcurrency,$price);
		
		# reset country 
		if ( $i > 0 ) {
			$countryimage = "img/country-white.png";
		} else {
			$countryimage = "img/country-$country.png";
		}
		
		# only show valid results (no empty results)
		if($shorttitle !== "...") {

		        $texttitle = $LANG['results']['title'];
		        $textprice = $LANG['results']['price'];
			$textcompare = $LANG['results']['compare'];
			$textlinkit = $LANG['results']['linkit'];


        		if ( $link == "Yes" ) {
                          $htmlresult .= "<div class=\"boxbuy\"><a <a href=\"/link/$childasin/$childcountry/$desiredcurrency/$asin/$country/$language/\" title=\"$title for $price\"><div class=\"yellow\">$textlinkit</div></a><div class=\"calculated\">$calculated</div><a href=\"$detailpageurl\" target=\"_blank\" style=\"text-decoration: none;\"><img src=\"/img/buy-$language.gif\" style=\"text-decoration: none; margin-top: 5px;\"></a></div>
                          <img src=\"/$countryimage\" style=\"float: left; text-decoration: none;\"/>
                          <div class=\"divider\"></div>
                          <div style=\"float: left; width: 75px; height: 75px; text-align: center;\"><img src=\"$smallimage\" style=\"text-align: center; text-decoration: none;\"></div>
                          <div class=\"divider\"></div>
                          <div class=\"boxtop\"><b>$texttitle:</b> $shorttitle</div>
                          <div class=\"dividervertical\"></div>
                          <div class=\"boxbottom\"><b>ASIN:</b> $asin <b>$textprice:</b> $price</div>
                          <br><br><br><br><br><br>";
        		} else {
                          $htmlresult .= "<div class=\"boxbuy\"><a href=\"/compare/$asin/$country/$desiredcurrency/$language/\" title=\"$title for $price\"><div class=\"yellow\">$textcompare</div></a><div class=\"calculated\">$calculated</div><a href=\"$detailpageurl\" target=\"_blank\" style=\"text-decoration: none;\"><img src=\"/img/buy-$language.gif\" style=\"text-decoration: none; margin-top: 5px;\"></a></div>
                          <img src=\"/$countryimage\" style=\"float: left; text-decoration: none;\"/>
                          <div class=\"divider\"></div>
                          <div style=\"float: left; width: 75px; height: 75px; text-align: center;\"><img src=\"$smallimage\" style=\"text-align: center; text-decoration: none;\"></div>
                          <div class=\"divider\"></div>
                          <div class=\"boxtop\"><b>$texttitle:</b> $shorttitle</div>
                          <div class=\"dividervertical\"></div>
                          <div class=\"boxbottom\"><b>ASIN:</b> $asin <b>$textprice:</b> $price</div>
                          <br><br><br><br><br><br>";
                        }

                # write information to database
                $result = array($country, $asin, $title, $price, $smallimage, $detailpageurl, $manufacturer, $binding, $currencycode, $desiredcurrency, $totalnew);
                writeDatabaseResults($db, $result);

                } # end if title is set


        }

        } // if $AWSerror end



                if ($htmlresult) {
    	        	echo"	<div style=\"height: 7px;\"></div>";
        		echo"	<div class=\"boxchoose\">";
        		echo"$htmlresult";
        		echo" </div> <!-- boxchoose end -->";
                } else {
                        showAmazonASINerror($country, $asin, $language ); 
                }



}

// query db, amazon for other countries
function queryOtherCountry($country, $asin, $title, $manufacturer, $binding, $desiredcurrency, $childasin, $childcountry, $db, $language) {

	if ($asin) {
		# check database
		$result = checkDatabaseASIN($db, $country, $asin, $desiredcurrency, $childcountry, $language);
		if ($result) {
		        #linkDatabaseASIN($db, $asin, $country, $childasin, $childcountry); # disabled due to created wring linking 
			showResults($result, $language);
		} else { # asin not in database
			# query amzon for asin
			$amazonResponseGroupItemAttributes = amazonQueryResponseGroup("Medium", $country, $asin);
			#var_dump($amazonResponseGroupItemAttributes);
			$check = checkAmazonASIN($amazonResponseGroupItemAttributes);
			if ($check) {
				# get more details, write to database and show result
				$result = queryAmazonASIN($country, $asin, $amazonResponseGroupItemAttributes, $desiredcurrency);
				writeDatabaseResults($db, $result);
				showResults($result, $language);

			} else { # asin not known in amazon store
			        $shorttitle = preg_replace('/\(.*/i', '', $title);
			        $shorttitle = preg_replace('/\,.*/i', '', $shorttitle);
				$amazonResponse = amazonQuery($country, $shorttitle, $manufacturer, $binding);
				showAmazonSearch($country, $amazonResponse, "4", $desiredcurrency, "Yes", $childasin, $childcountry, $db, $language );

			} # asin not known in amazon store end
			
		} # asin not in database end

	} 


} // end queryOtherCountry



#########################
# Query Amazon

function amazonQuery($country, $input, $manufacturer, $binding) {

include('../config/config.inc.php');

    $amazonEcs = new AmazonECS($AWS_API_KEY, $AWS_API_SECRET_KEY, $AWS_COUNTRY_CODE[''.$country.''], $AWS_ASSOCIATE_TAG[''.$country.''] );
    $amazonEcs->returnType(AmazonECS::RETURN_TYPE_ARRAY);

    if (empty($manufacturer)) {
        $amazonResponse = $amazonEcs->category('All')->search(''.$input.'');
    } else {
        if (isset($binding) && $country !== "de" ) { # we can remove the DE clausel when we have something to map the $binding to the correct name
            $amazonResponse = $amazonEcs->category(''.$binding.'')->optionalParameters(array('Manufacturer' => $manufacturer))->search(''.$input.'');
        } else {
            $amazonResponse = $amazonEcs->category('All')->search(''.$input.'');
        }
    }

    #var_dump($amazonResponse);
    return $amazonResponse; 
}



#########################  
# Query Amazon ResponseGroup


function amazonQueryResponseGroup($responsegroup, $country, $asin) {

include('../config/config.inc.php');

    $amazonEcs = new AmazonECS($AWS_API_KEY, $AWS_API_SECRET_KEY, $AWS_COUNTRY_CODE[''.$country.''], $AWS_ASSOCIATE_TAG[''.$country.''] );
    $amazonEcs->returnType(AmazonECS::RETURN_TYPE_ARRAY);
    $amazonResponseGroup = $amazonEcs->responseGroup(''.$responsegroup.'')->lookup(''.$asin.'');

    #var_dump($amazonResponseGroup);
    return $amazonResponseGroup;
}

#########################  
# Query Amazon Search Results for Data


function amazonSearch( $amazonResponse, $option, $count ) {


switch ($option):

    case "ASIN":
    # return ASIN
    $amazonResult = $amazonResponse['Items']['Item'][''.$count.'']['ASIN'];
    break;

    case "DetailPageURL":
    # return url to detailed page
    $amazonResult = $amazonResponse['Items']['Item'][''.$count.'']['DetailPageURL'];
    break;

    case "AddToWishlist":
    # return url to add to wish list link
    $amazonResult = $amazonResponse['Items']['Item'][''.$count.'']['ItemLinks']['ItemLink']['0']['URL'];
    break;
    
    case "TellAFriend":
    # return url to tell a friend url
    $amazonResult = $amazonResponse['Items']['Item'][''.$count.'']['ItemLinks']['ItemLink']['1']['URL'];  
    break;
    
    case "AllCustomerReviews":
    # return url to all customer reviews
    $amazonResult = $amazonResponse['Items']['Item'][''.$count.'']['ItemLinks']['ItemLink']['2']['URL'];
    break;
    
    case "AllOffers":
    # return url to all offers
    $amazonResult = $amazonResponse['Items']['Item'][''.$count.'']['ItemLinks']['ItemLink']['3']['URL'];
    break;
    
    case "Title":
    # return title
    $amazonResult = $amazonResponse['Items']['Item'][''.$count.'']['ItemAttributes']['Title'];
    break;

    case "FormattedPrice":
    # return list price
    #$amazonResponse = $amazonEcs->amazonResponseGroup('ItemAttributes')->country(''.$country.'')->lookup(''.$asin.'');
    $amazonResult = $amazonResponse['Items']['Item']['ItemAttributes']['ListPrice']['FormattedPrice'];
    break;

    case "CurrencyCode":
    # return Currency Code
    #$amazonResponse = $amazonEcs->amazonResponseGroup('ItemAttributes')->country(''.$country.'')->lookup(''.$asin.'');
    $amazonResult = $amazonResponse['Items']['Item']['ItemAttributes']['ListPrice']['CurrencyCode'];
    break;

    case "GroupBinding":  
    # return Group Binding
    #$amazonResponse = $amazonEcs->amazonResponseGroup('ItemAttributes')->country(''.$country.'')->lookup(''.$asin.'');
    $amazonResult = $amazonResponse['Items']['Item']['ItemAttributes']['Binding'];
    break;
                    
    case "GroupTitle":
    # return Group Title
    #$amazonResponse = $amazonEcs->amazonResponseGroup('ItemAttributes')->country(''.$country.'')->lookup(''.$asin.'');
    $amazonResult = $amazonResponse['Items']['Item']['ItemAttributes']['Title'];
    break;

    case "GroupDetailPageURL":
    # return Group DetailPageURL
    #$amazonResponse = $amazonEcs->amazonResponseGroup('ItemAttributes')->country(''.$country.'')->lookup(''.$asin.'');
    $amazonResult = $amazonResponse['Items']['Item']['DetailPageURL'];
    break;

    case "GroupManufacturer";
    # return Group Manufacturer
    #$amazonResponse = $amazonEcs->amazonResponseGroup('ItemAttributes')->country(''.$country.'')->lookup(''.$asin.'');
    $amazonResult = $amazonResponse['Items']['Item']['ItemAttributes']['Manufacturer'];
    break;

    case "GroupLowestNewPrice"; 
    # return Group LowestNewPrice
    #$amazonResponse = $amazonEcs->amazonResponseGroup('ItemAttributes')->country(''.$country.'')->lookup(''.$asin.'');
    $amazonResult = $amazonResponse['Items']['Item']['OfferSummary']['LowestNewPrice']['FormattedPrice'];
    break;                    

    case "GroupLowestNewPriceCurrencyCode";
    # return Group LowestNewPrice Currency Code
    #$amazonResponse = $amazonEcs->amazonResponseGroup('ItemAttributes')->country(''.$country.'')->lookup(''.$asin.'');
    $amazonResult = $amazonResponse['Items']['Item']['OfferSummary']['LowestNewPrice']['CurrencyCode'];
    break;

    case "GroupTotalNew";
    # return Group TotalNew
    #$amazonResponse = $amazonEcs->amazonResponseGroup('ItemAttributes')->country(''.$country.'')->lookup(''.$asin.'');
    $amazonResult = $amazonResponse['Items']['Item']['OfferSummary']['TotalNew'];
    break;
    
    case "SmallImage":
    # return smallimage url
    #$amazonResponse = $amazonEcs->amazonResponseGroup('Images')->country(''.$country.'')->lookup(''.$asin.'');
    $amazonResult = $amazonResponse['Items']['Item']['SmallImage']['URL'];  
    break;
    
    case "MediumImage":
    # return mediumimage url
    #$amazonResponse = $amazonEcs->amazonResponseGroup('Images')->country(''.$country.'')->lookup(''.$asin.'');
    $amazonResult = $amazonResponse['Items']['Item']['MediumImage']['URL'];
    break;
    
    case "LargeImage":
    # return mediumimage url
    #$amazonResponse = $amazonEcs->amazonResponseGroup('Images')->country(''.$country.'')->lookup(''.$asin.'');
    $amazonResult = $amazonResponse['Items']['Item']['LargeImage']['URL'];
    break;
    
    case "ErrorsCode":
    # return error code
    $amazonResult = $amazonResponse['Items']['Request']['Errors']['Error']['Code'];
    break;
    
    default:
    $amazonResult = "OPTION ERROR";
    endswitch;


    return $amazonResult; 

}

#########################  
# Currency Calculator
# http://www.dynamicguru.com/php/currency-conversion-using-php-and-google-calculator-api/


function currency($from_Currency,$to_Currency,$amount) {

    $check = strpos($amount, ".");
    if ( $check == true ) {
        $s = explode(".",$amount);
    } else {
        $s = explode(",",$amount);
    }


    if ( $from_Currency == $to_Currency ) {
        return ("");
    } else {
    
    $amount = preg_replace("/[^0-9]/", "", $s[0]);
                                
    $amount = urlencode($amount);
    $from_Currency = urlencode($from_Currency);
    $to_Currency = urlencode($to_Currency);
    $url = "http://www.google.com/ig/calculator?hl=en&q=$amount$from_Currency=?$to_Currency";
    $ch = curl_init();
    $timeout = 0;
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $rawdata = curl_exec($ch);
    curl_close($ch);
    $data = explode('"', $rawdata);
    $data = explode(' ', $data['3']);
    $var = $data['0'];
    #return round($var,2);
    $calculated = round($var,2);
    
    # change Currency code
    if ($to_Currency == "USD" ) {
        $to_Currency = "$";
    } elseif ($to_Currency == "GBP" ) { 
        $to_Currency = "&pound;";
    } elseif ($to_Currency == "EUR" ) {
        $to_Currency = "EUR ";
    }
    $otherprice = "$to_Currency$calculated";
    
    return $otherprice; 

    }
    
}




?>
