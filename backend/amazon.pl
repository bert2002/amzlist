#!/usr/bin/perl
use strict;
use utf8; 
use DateTime; 
use Data::Dumper;
use URI::Escape qw (uri_escape_utf8);
use Digest::SHA qw (hmac_sha256_base64);
use LWP;
use DBI; 
use XML::Simple;
use Getopt::Long;
use Fcntl ':flock';
use RRD::Simple;
use amazonConfig; 

my $DEBUG;
my $MYSQL_USER;
my $MYSQL_HOST;
my $MYSQL_DATABASE;
my $MYSQL_PASSWORD; 
my $MYSQL_PORT; 
my $AWS_API_KEY; 
my $AWS_API_SECRET_KEY; 
my $LOGFILE; 
my $LOCKFILE; 
my $GRAPHPATH; 

my $country; 
my $asin; 
my $limit; 
my $mode;
GetOptions (
    'country=s' => \$country,
    'mode=s' => \$mode,
    'limit=s' => \$limit);
        
###############
### Settings
my %AWS_ASSOCIATE_TAG = (
  'de' => 'AWSKEY',
  'uk' => 'AWSKEY',
  'us' => 'AWSKEY',
  'fr' => 'AWSKEY',
  'ca' => 'AWSKEY', 
  'it' => 'AWSKEY',
  'cn' => 'AWSKEY',
  'jp' => 'AWSKEY'
);

my %VALID_DOMAIN = (
  'de' => 'de',
  'uk' => 'co.uk',
  'us' => 'com',
  'ca' => 'ca',
  'fr' => 'fr',
  'jp' => 'co.jp',
  'it' => 'it',
  'cn' => 'cn'
);


# reading config
if ( $mode eq "prod" ) {
 $MYSQL_USER =          $amazonConfig::prod{MYSQL_USER};
 $MYSQL_HOST =          $amazonConfig::prod{MYSQL_HOST};
 $MYSQL_DATABASE =      $amazonConfig::prod{MYSQL_DATABASE};
 $MYSQL_PASSWORD =      $amazonConfig::prod{MYSQL_PASSWORD};
 $MYSQL_PORT =          $amazonConfig::prod{MYSQL_PORT};
 $AWS_API_KEY =         $amazonConfig::prod{AWS_API_KEY};
 $AWS_API_SECRET_KEY =  $amazonConfig::prod{AWS_API_SECRET_KEY};
 $LOGFILE =             $amazonConfig::prod{LOGFILE};
 $LOCKFILE =            $amazonConfig::prod{LOCKFILE};
 $DEBUG =               $amazonConfig::prod{DEBUG};
 $GRAPHPATH =           $amazonConfig::prod{GRAPHPATH};
} 


# logging 
open (LOGFILE, ">>$LOGFILE");
sub mylog {
  my($error, $status) = @_;
  my $date = localtime;  
  if ($DEBUG) {
   print 	 "$date  $error     $status\n";
  } else {
   print LOGFILE "$date  $error     $status\n";
  }
  
}

# lock file
mylog("OKAY","check lock file");
open (my $FH, '>>', $LOCKFILE) or die $!;

unless (flock($FH, LOCK_EX|LOCK_NB)) {
 mylog("OKAY","$0 is still running. exit.");
 exit(0);
}

# connecting to database
my $dsn = "DBI:mysql:database=$MYSQL_DATABASE;host=$MYSQL_HOST;port=$MYSQL_PORT";
my $dbh = DBI->connect($dsn, $MYSQL_USER, $MYSQL_PASSWORD) || die "Cannot connect: $DBI::errstr";
   
# check country parameter
my @country_input = split(/,/, $country);
while(@country_input) {

$country = my $item = pop @country_input;

 ###############
 ### Script 
 mylog("OKAY","start country: $country");
 # LWP
 my $ua = LWP::UserAgent->new;
 $ua->agent("AMZLIST.COM/BACKEND 0.1");

 mylog("OKAY", "query database");
 my $query = "select asin from $country order by lastupdate limit $limit";
 my $query_handle = $dbh->prepare($query);
 $query_handle->execute();
 $query_handle->bind_columns(undef, \$asin );
 while($query_handle->fetch()) {
  mylog("OKAY", "update asin: $asin ($country)");
  
  ######################### AMAZON     
  # create timestamp and signature
  my $dt = DateTime->from_epoch(epoch => time, time_zone => 'GMT');
  my $timestamp = $dt->strftime('%FT%T.%3NZ');
  $timestamp = uri_escape_utf8($timestamp);

  # url
  my $domain = $VALID_DOMAIN{$country}; 
  my $method = "GET";
  my $host = "ecs.amazonaws.$domain";
  my $uri = "/onca/xml";
  # params
  my $AWSAccessKeyId = $AWS_API_KEY; 
  my $AssociateTag = $AWS_ASSOCIATE_TAG{$country}; 
  my $ItemId = $asin; 
  my $Operation = "ItemLookup"; 
  my $ResponseGroup = "Medium";
  my $Service = "AWSECommerceService"; 
  my $Timestamp = $timestamp ;
  my $Version = "2011-08-01"; 
 
  # create urland signature
  my $string_to_sign = "$method\n$host\n$uri\nAWSAccessKeyId=$AWSAccessKeyId&AssociateTag=$AssociateTag&ItemId=$ItemId&Operation=$Operation&ResponseGroup=$ResponseGroup&Service=$Service&Timestamp=$Timestamp&Version=$Version";
  my $signature = hmac_sha256_base64( $string_to_sign, $AWS_API_SECRET_KEY);
  $signature = uri_escape_utf8($signature);
  $signature .= "%3D"; 
  my $url = "http://$host$uri?AWSAccessKeyId=$AWSAccessKeyId&AssociateTag=$AssociateTag&ItemId=$ItemId&Operation=$Operation&ResponseGroup=$ResponseGroup&Service=$Service&Timestamp=$Timestamp&Version=$Version&Signature=$signature\n";

  # we need to sleep one seconds // Amazon 
  sleep 1; 
 
  # get content
  my $req = HTTP::Request->new(GET => $url);
  my $result = $ua->request($req);

  # Check the outcome of the response
  if ($result->is_success) {
     my $xml = XML::Simple->new; 
     $xml = XMLin($result->content);
    
     # parse content
     my $title = $xml->{Items}->{Item}->{ItemAttributes}->{Title};
     my $listprice = $xml->{Items}->{Item}->{ItemAttributes}->{ListPrice}->{FormattedPrice};
     my $listpricecurrencycode = $xml->{Items}->{Item}->{ItemAttributes}->{ListPrice}->{CurrencyCode};
     my $lowestnewprice = $xml->{Items}->{Item}->{OfferSummary}->{LowestNewPrice}->{FormattedPrice};
     my $lowestnewpricecurrencycode = $xml->{Items}->{Item}->{OfferSummary}->{LowestNewPrice}->{CurrencyCode};
     my $detailpageurl = $xml->{Items}->{Item}->{DetailPageURL};
     my $smallimage = $xml->{Items}->{Item}->{SmallImage}->{URL};
     my $manufacturer = $xml->{Items}->{Item}->{ItemAttributes}->{Manufacturer};
     my $binding = $xml->{Items}->{Item}->{ItemAttributes}->{Binding};
     my $packetheight = $xml->{Items}->{Item}->{ItemAttributes}->{PackageDimensions}->{Height}->{content};
     my $packetunit = $xml->{Items}->{Item}->{ItemAttributes}->{PackageDimensions}->{Height}->{Units};
     my $packetlength = $xml->{Items}->{Item}->{ItemAttributes}->{PackageDimensions}->{Length}->{content};
     my $packetwidth = $xml->{Items}->{Item}->{ItemAttributes}->{PackageDimensions}->{Width}->{content};
     my $packetweight = $xml->{Items}->{Item}->{ItemAttributes}->{PackageDimensions}->{Weight}->{content};
     my $packetweightunit = $xml->{Items}->{Item}->{ItemAttributes}->{PackageDimensions}->{Weight}->{Units};
     my $upc = $xml->{Items}->{Item}->{ItemAttributes}->{UPC};
     my $ean = $xml->{Items}->{Item}->{ItemAttributes}->{EAN};
     my $totalnew = $xml->{Items}->{Item}->{OfferSummary}->{TotalNew};

     # calculate best price (copy from php frontend)
     my $price = $listprice;
     my $currencycode = $listpricecurrencycode;
     my $zeroprice = '$0.00';
     
     if ($price == $zeroprice) {
     
      if ($lowestnewprice) {
       if ($lowestnewprice ne $zeroprice) {
        $price = $lowestnewprice;
        $currencycode = $lowestnewpricecurrencycode;
        } else {
         $price = "N/A"; 
        }
       } else {
        $price = "N/A";
       }
      }

     # create date
     $dt = DateTime->now;
     #2011-09-20 15:08:23
     my $ymd = $dt->ymd;
     my $hms = $dt->hms;
     my $nowdate = "$ymd $hms";
    
     # update database
     my $dbUpdate = $dbh->prepare("UPDATE $country SET lastupdate=?,title=?,price=?,currencycode=?,detailpageurl=?,smallimage=?,manufacturer=?,binding=?,packetheight=?,packetunit=?,packetlength=?,packetwidth=?,packetweight=?,packetweightunit=?,upc=?,ean=?,totalnew=? WHERE asin=?");
     $dbUpdate->execute($nowdate,$title,$price,$currencycode,$detailpageurl,$smallimage,$manufacturer,$binding,$packetheight,$packetunit,$packetlength,$packetwidth,$packetweight,$packetweightunit,$upc,$ean,$totalnew,$asin) or die "Cannot execute: " . $dbUpdate->errstr();

     # rrd that shit
     if ( $price eq "N/A") {
      $price = "0";
     }
     my $rrdprice = $price; 
     $rrdprice =~ s/(EUR|USD|GBP|\$|CDN\$|YEN|CAD|\￥)//g;
     $rrdprice =~ s/,/./g;
     my $rrdcurrency = $currencycode;
     my $rrdfile = "$GRAPHPATH/$country/$asin.rrd";
     
     unless ( -e $rrdfile ) {
      # create the rrd file 
      mylog("INFO","create new rrd file: $rrdfile");
      my $rrd = RRD::Simple->new( file => "$rrdfile" );
      $rrd->create( price => "GAUGE" );
     # insert data first time
      $rrd->update( price => "$rrdprice" );
     } else {
      # update rrdfile
      my $rrd = new RRD::Simple;
      my $rrd = RRD::Simple->update( $rrdfile, price => "$rrdprice" );
     } # unless end
     mylog("INFO","update rrd price: $rrdprice $rrdcurrency");

     # graph that shit
     mylog("INFO","create graph from rrd");
     my $rrd = new RRD::Simple;
     my %rtn = $rrd->graph( $rrdfile,
      destination => "$GRAPHPATH/$country/",
      timestamp => "rrd", # graph, rrd, both or none
      line_thickness => 2,
      extended_legend => 1,
      vertical_label => "$rrdcurrency",
      title => "Price history",
      periods => [ qw(month) ]
     );

  } else {
    my $error = $result->status_line; 
    mylog("ERROR","$error \n");
  }
######################### AMAZON END 

 } # end $query_handle->fetch()

 # update backend table in dataabse
 my $dbBackend = $dbh->prepare("UPDATE backend SET count = count +1 where name='updatedb'");
 $dbBackend->execute();

} # while $country end 

  

# EOF Stuff
$dbh->disconnect();
close($FH) or warn "Could not close file $!";
mylog("OKAY","finished.");
close (LOGFILE);
