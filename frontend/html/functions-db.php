<?php


include('../adodb5/adodb.inc.php');
include('../config/config.inc.php');

$db = ADONewConnection('mysql'); # eg 'mysql' or 'postgres'         
$db->debug = false;
$db->Connect($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASSWORD, $MYSQL_DATABASE);
$db->cacheSsecs = 3600*2;

#############
# get data from DB 


function checkDatabaseASIN($db, $country, $asin, $desiredcurrency, $childcountry, $language) {

include('../lang/'.$language.'.php');

        # check if childcountry is set
        if($childcountry) {
                $rs = $db->Execute("select asin$country from $childcountry where asin = '".$asin."' ");
                if ($rs) {
                        $rs = $rs->GetArray();
                }
                
                #var_dump($rs);

                if (empty($rs[0][0])) {
                        unset($rs); 
                } else {
                        $rs = $db->Execute("select * from $country where asin = '".$rs[0][0]."' ");
                        if ($rs) {
                                $rs = $rs->GetArray();
                        }
                }
                
        } else {
                $rs = $db->Execute("select * from $country where asin = '".$asin."' ");
                if ($rs) {
                        $rs = $rs->GetArray();
                }
        } # check childcountry end
        


        # create new array to prepare it to present the customer
        if ($rs) {        
                
                #var_dump($rs);        
                $title = $rs[0]['title'];
                $price = $rs[0]['price'];
                $smallimage = $rs[0]['smallimage'];
                $detailpageurl = $rs[0]['detailpageurl'];
                $manufacturer = $rs[0]['manufacturer'];
                $binding = $rs[0]['binding'];
                $currencycode = $rs[0]['currencycode'];
                $totalnew = $rs[0]['totalnew'];
        
                # check if product is available
                if ( $totalnew > "0" ) {
                        $totalnew = $LANG['results']['yes'];
                } else {
                        $totalnew = $LANG['results']['na']; 
                }
        
                $results = array($country, $asin, $title, $price, $smallimage, $detailpageurl, $manufacturer, $binding, $currencycode, $desiredcurrency, $totalnew);
        
        } else {
                unset($results);
        } #check rs end 
        
        
        return $results; 
        
}


#############
# show latest search

function showDatabaseLatest($db, $country, $language, $desiredcurrency, $language) {

include('../lang/'.$language.'.php');

$rs = $db->CacheExecute("select * from $country order by lastupdate DESC limit 24 ");
$count = "1";

        if (!$rs) {
                print $db->ErrorMsg();
        } else { 

                $latesttitle = $LANG['latest']['title']; 

                echo "<div style=\"height: 5px;\"></div>";
		echo "<div class=\"latest\">";
      		echo "<div class=\"headline\">$latesttitle</div><br>";

        
                while (!$rs->EOF) {

                        #var_dump($rs);        
                        $asin = $rs->fields['asin'];
                        $title = $rs->fields['title'];
                        $price = $rs->fields['price'];
                        $smallimage = $rs->fields['smallimage'];

                        # check for empty picture
                        if (empty($smallimage)) {
                                $smallimage = "/img/nocover.png";
                        }
                                                                    
		        echo "<div style=\"float: left; width: 75px; height: 75px; text-align: center;\"><a href=\"/compare/$asin/$country/$desiredcurrency/$language/\" title=\"$title for $price\" onclick=\"return ray.ajax()\"><img src=\"$smallimage\" style=\"text-align: center; text-decoration: none;\"></a></div>";
    			echo "<div class=\"divider\"></div>";


			if ( $count == "8" or $count == "16" ) {
				echo "<br><br><div style=\"height: 60px; width: 700px;\"></div>";
			}


			++$count; 
                        $rs->MoveNext();
                }

		echo "</div>";
                
                $rs->Close(); # optional
        }

}

#############
# write results to DB

function writeDatabaseResults($db, $result) {

        #var_dump($result);
        $record = array();
        $record["country"] = $result[0];
        $record["asin"] = $result[1];
        $record["title"] = $result[2];
        $record["price"] = $result[3];
        $record["smallimage"] = $result[4];
        $record["detailpageurl"] = $result[5];
        $record["manufacturer"] = $result[6];
        $record["binding"] = $result[7];
        $record["currencycode"] = $result[8];
        $record["desiredcurrency"] = $result[9];
        $record["totalnew"] = $result[10];

        $country = $result[0];
        $asin = $result[1];
                
        # check if record exist already
        $rs = $db->Execute("select asin from $country where asin = '".$record["asin"]."' ");
        if ($rs) {
                $rs = $rs->GetArray();
                if (empty($rs[0][0])) {
        
                        # insert into database
                        $sql = "SELECT * FROM $country WHERE asin = '".$asin."' ";
                        $rs = $db->Execute($sql);
                        $insertSQL = $db->GetInsertSQL($rs, $record);
                        $db->Execute($insertSQL);
                        #var_dump($record);
                }
        }



}

#########################
# Link ASIN to another ASIN in DB

function linkDatabaseASIN($db, $asin, $country, $childasin, $childcountry) {

include('../config/config.inc.php');

        #echo "Link $asin ($country) to $childasin ($childcountry)<br>";

        # update $childcountry
        $record = array();
        $record['asin'.$country.''] = $asin ; 
        
        $rs = $db->Execute("select * from $childcountry where asin = '".$childasin."' ");
        $updateSQL = $db->GetUpdateSQL($rs, $record);
        $db->Execute($updateSQL);
                

        # update $country
        $record = array();
        $record['asin'.$childcountry.''] = $childasin ;
        
        $rs = $db->Execute("select * from $country where asin = '".$asin."' ");
        $updateSQL = $db->GetUpdateSQL($rs, $record);
        $db->Execute($updateSQL);


        # update other country        
        for ($i = 1; $i <= $VALID_COUNTRY_COUNT; $i++) {
                $othercountry = $VALID_COUNTRY[''.$country.''][$i];
                
                if ( $othercountry !== $childcountry ) {

                        # get $othercountry $asin from $country
                        $rs = $db->Execute("select asin$othercountry from $country where asin = '".$asin."' ");
                        if ($rs) {
                                $rs = $rs->GetArray();
                                if (empty($rs[0][0])) {
                                        $rs = $db->Execute("select * from $othercountry where asin = '".$asin."' ");
                                } else {
                                        $rs = $db->Execute("select * from $othercountry where asin = '".$rs[0][0]."' ");
                                }
                        }

                        $record = array();
                        $record['asin'.$childcountry.''] = $childasin ;
                        
                        $updateSQL = $db->GetUpdateSQL($rs, $record);
                        $db->Execute($updateSQL);


                        # get $othercountry $asin where asin$childcountry is
                        $rs = $db->Execute("select asin from $othercountry where asin$childcountry = '".$childasin."' ");
                        if ($rs) {
                                $rs = $rs->GetArray();
                                $otherasin = $rs[0][0];
                                if ($otherasin) {
                                        $rs = $db->Execute("select * from $childcountry where asin = '".$childasin."' ");
                        
                                        $record = array();
                                        $record['asin'.$othercountry.''] = $otherasin ;
                        
                                        $updateSQL = $db->GetUpdateSQL($rs, $record);
                                        $db->Execute($updateSQL);
                                }
                        
                        }
                        
                        

                }

        }
        
        
        
    
}


#########################
# Show last Motified by backend

function checkDatabaseLastUpdate ($db, $language) {

include('../lang/'.$language.'.php');

        $rs = $db->Execute("select lastupdate from backend where name = 'updatedb' ");
        $rs = $rs->GetArray();

        if ($rs) {
                $result = $rs[0][0];
        } else {
                $result = "ERROR"; 
        }
        
        return $result; 
}





?>
