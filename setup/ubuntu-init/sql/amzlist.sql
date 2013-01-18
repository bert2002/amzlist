-- MySQL dump 10.13  Distrib 5.1.37, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: amzlist
-- ------------------------------------------------------
-- Server version	5.1.37-1ubuntu5.5

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `backend`
--

CREATE DATABASE amzlist;
USE amzlist;
DROP TABLE IF EXISTS `backend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backend` (
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `count` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `backend` (`lastupdate`, `name`, `count`) VALUES ('2011-08-31 14:03:14', 'updatedb', 1211);


--
-- Table structure for table `de`
--

DROP TABLE IF EXISTS `de`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `de` (
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `asin` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `asinuk` text COLLATE utf8_unicode_ci,
  `asinus` text COLLATE utf8_unicode_ci,
  `asinca` text COLLATE utf8_unicode_ci,
  `asinfr` text COLLATE utf8_unicode_ci,
  `asinjp` text COLLATE utf8_unicode_ci,
  `asinit` text COLLATE utf8_unicode_ci,
  `asincn` text COLLATE utf8_unicode_ci,
  `title` text COLLATE utf8_unicode_ci,
  `price` text COLLATE utf8_unicode_ci,
  `currencycode` text COLLATE utf8_unicode_ci,
  `detailpageurl` text COLLATE utf8_unicode_ci,
  `smallimage` text COLLATE utf8_unicode_ci,
  `manufacturer` text COLLATE utf8_unicode_ci,
  `binding` text COLLATE utf8_unicode_ci,
  `packetheight` int(5) DEFAULT NULL,
  `packetunit` text COLLATE utf8_unicode_ci,
  `packetlength` int(5) DEFAULT NULL,
  `packetwidth` int(5) DEFAULT NULL,
  `packetweight` int(5) DEFAULT NULL,
  `packetweightunit` text COLLATE utf8_unicode_ci,
  `upc` int(15) DEFAULT NULL,
  `ean` int(15) DEFAULT NULL,
  `totalnew` text COLLATE utf8_unicode_ci,
  KEY `asin` (`asin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uk`
--

DROP TABLE IF EXISTS `uk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uk` (
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `asin` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `asinde` text COLLATE utf8_unicode_ci,
  `asinus` text COLLATE utf8_unicode_ci,
  `asinca` text COLLATE utf8_unicode_ci,
  `asinfr` text COLLATE utf8_unicode_ci,
  `asinjp` text COLLATE utf8_unicode_ci,
  `asinit` text COLLATE utf8_unicode_ci,
  `asincn` text COLLATE utf8_unicode_ci,
  `title` text COLLATE utf8_unicode_ci,
  `price` text COLLATE utf8_unicode_ci,
  `currencycode` text COLLATE utf8_unicode_ci,
  `detailpageurl` text COLLATE utf8_unicode_ci,
  `smallimage` text COLLATE utf8_unicode_ci,
  `manufacturer` text COLLATE utf8_unicode_ci,
  `binding` text COLLATE utf8_unicode_ci,
  `packetheight` int(5) DEFAULT NULL,
  `packetunit` text COLLATE utf8_unicode_ci,
  `packetlength` int(5) DEFAULT NULL,
  `packetwidth` int(5) DEFAULT NULL,
  `packetweight` int(5) DEFAULT NULL,
  `packetweightunit` text COLLATE utf8_unicode_ci,
  `upc` int(15) DEFAULT NULL,
  `ean` int(15) DEFAULT NULL,
  `totalnew` text COLLATE utf8_unicode_ci,
  KEY `asin` (`asin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `us`
--

DROP TABLE IF EXISTS `us`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `us` (
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `asin` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `asinuk` text COLLATE utf8_unicode_ci,
  `asinde` text COLLATE utf8_unicode_ci,
  `asinca` text COLLATE utf8_unicode_ci,
  `asinfr` text COLLATE utf8_unicode_ci,
  `asinjp` text COLLATE utf8_unicode_ci,
  `asinit` text COLLATE utf8_unicode_ci,
  `asincn` text COLLATE utf8_unicode_ci,
  `title` text COLLATE utf8_unicode_ci,
  `price` text COLLATE utf8_unicode_ci,
  `currencycode` text COLLATE utf8_unicode_ci,
  `detailpageurl` text COLLATE utf8_unicode_ci,
  `smallimage` text COLLATE utf8_unicode_ci,
  `manufacturer` text COLLATE utf8_unicode_ci,
  `binding` text COLLATE utf8_unicode_ci,
  `packetheight` int(5) DEFAULT NULL,
  `packetunit` text COLLATE utf8_unicode_ci,
  `packetlength` int(5) DEFAULT NULL,
  `packetwidth` int(5) DEFAULT NULL,
  `packetweight` int(5) DEFAULT NULL,
  `packetweightunit` text COLLATE utf8_unicode_ci,
  `upc` int(15) DEFAULT NULL,
  `ean` int(15) DEFAULT NULL,
  `totalnew` text COLLATE utf8_unicode_ci,
  KEY `asin` (`asin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `ca` (
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `asin` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `asinuk` text COLLATE utf8_unicode_ci,
  `asinus` text COLLATE utf8_unicode_ci,
  `asinde` text COLLATE utf8_unicode_ci,
  `asinfr` text COLLATE utf8_unicode_ci,
  `asinjp` text COLLATE utf8_unicode_ci,
  `asinit` text COLLATE utf8_unicode_ci,
  `asincn` text COLLATE utf8_unicode_ci,
  `title` text COLLATE utf8_unicode_ci,
  `price` text COLLATE utf8_unicode_ci,
  `currencycode` text COLLATE utf8_unicode_ci,
  `detailpageurl` text COLLATE utf8_unicode_ci,
  `smallimage` text COLLATE utf8_unicode_ci,
  `manufacturer` text COLLATE utf8_unicode_ci,
  `binding` text COLLATE utf8_unicode_ci,
  `packetheight` int(5) DEFAULT NULL,
  `packetunit` text COLLATE utf8_unicode_ci,
  `packetlength` int(5) DEFAULT NULL,
  `packetwidth` int(5) DEFAULT NULL,
  `packetweight` int(5) DEFAULT NULL,
  `packetweightunit` text COLLATE utf8_unicode_ci,
  `upc` int(15) DEFAULT NULL,
  `ean` int(15) DEFAULT NULL,
  `totalnew` text COLLATE utf8_unicode_ci,
  KEY `asin` (`asin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `fr` (
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `asin` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `asinuk` text COLLATE utf8_unicode_ci,
  `asinus` text COLLATE utf8_unicode_ci,
  `asinca` text COLLATE utf8_unicode_ci,
  `asinde` text COLLATE utf8_unicode_ci,
  `asinjp` text COLLATE utf8_unicode_ci,
  `asinit` text COLLATE utf8_unicode_ci,
  `asincn` text COLLATE utf8_unicode_ci,
  `title` text COLLATE utf8_unicode_ci,
  `price` text COLLATE utf8_unicode_ci,
  `currencycode` text COLLATE utf8_unicode_ci,
  `detailpageurl` text COLLATE utf8_unicode_ci,
  `smallimage` text COLLATE utf8_unicode_ci,
  `manufacturer` text COLLATE utf8_unicode_ci,
  `binding` text COLLATE utf8_unicode_ci,
  `packetheight` int(5) DEFAULT NULL,
  `packetunit` text COLLATE utf8_unicode_ci,
  `packetlength` int(5) DEFAULT NULL,
  `packetwidth` int(5) DEFAULT NULL,
  `packetweight` int(5) DEFAULT NULL,
  `packetweightunit` text COLLATE utf8_unicode_ci,
  `upc` int(15) DEFAULT NULL,
  `ean` int(15) DEFAULT NULL,
  `totalnew` text COLLATE utf8_unicode_ci,
  KEY `asin` (`asin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `jp` (
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `asin` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `asinuk` text COLLATE utf8_unicode_ci,
  `asinus` text COLLATE utf8_unicode_ci,
  `asinca` text COLLATE utf8_unicode_ci,
  `asinfr` text COLLATE utf8_unicode_ci,
  `asinde` text COLLATE utf8_unicode_ci,
  `asinit` text COLLATE utf8_unicode_ci,
  `asincn` text COLLATE utf8_unicode_ci,
  `title` text COLLATE utf8_unicode_ci,
  `price` text COLLATE utf8_unicode_ci,
  `currencycode` text COLLATE utf8_unicode_ci,
  `detailpageurl` text COLLATE utf8_unicode_ci,
  `smallimage` text COLLATE utf8_unicode_ci,
  `manufacturer` text COLLATE utf8_unicode_ci,
  `binding` text COLLATE utf8_unicode_ci,
  `packetheight` int(5) DEFAULT NULL,
  `packetunit` text COLLATE utf8_unicode_ci,
  `packetlength` int(5) DEFAULT NULL,
  `packetwidth` int(5) DEFAULT NULL,
  `packetweight` int(5) DEFAULT NULL,
  `packetweightunit` text COLLATE utf8_unicode_ci,
  `upc` int(15) DEFAULT NULL,
  `ean` int(15) DEFAULT NULL,
  `totalnew` text COLLATE utf8_unicode_ci,
  KEY `asin` (`asin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `it` (
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `asin` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `asinuk` text COLLATE utf8_unicode_ci,
  `asinus` text COLLATE utf8_unicode_ci,
  `asinca` text COLLATE utf8_unicode_ci,
  `asinfr` text COLLATE utf8_unicode_ci,
  `asinjp` text COLLATE utf8_unicode_ci,
  `asinde` text COLLATE utf8_unicode_ci,
  `asincn` text COLLATE utf8_unicode_ci,
  `title` text COLLATE utf8_unicode_ci,
  `price` text COLLATE utf8_unicode_ci,
  `currencycode` text COLLATE utf8_unicode_ci,
  `detailpageurl` text COLLATE utf8_unicode_ci,
  `smallimage` text COLLATE utf8_unicode_ci,
  `manufacturer` text COLLATE utf8_unicode_ci,
  `binding` text COLLATE utf8_unicode_ci,
  `packetheight` int(5) DEFAULT NULL,
  `packetunit` text COLLATE utf8_unicode_ci,
  `packetlength` int(5) DEFAULT NULL,
  `packetwidth` int(5) DEFAULT NULL,
  `packetweight` int(5) DEFAULT NULL,
  `packetweightunit` text COLLATE utf8_unicode_ci,
  `upc` int(15) DEFAULT NULL,
  `ean` int(15) DEFAULT NULL,
  `totalnew` text COLLATE utf8_unicode_ci,
  KEY `asin` (`asin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `cn` (
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `asin` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `asinuk` text COLLATE utf8_unicode_ci,
  `asinus` text COLLATE utf8_unicode_ci,
  `asinca` text COLLATE utf8_unicode_ci,
  `asinfr` text COLLATE utf8_unicode_ci,
  `asinjp` text COLLATE utf8_unicode_ci,
  `asinit` text COLLATE utf8_unicode_ci,
  `asinde` text COLLATE utf8_unicode_ci,
  `title` text COLLATE utf8_unicode_ci,
  `price` text COLLATE utf8_unicode_ci,
  `currencycode` text COLLATE utf8_unicode_ci,
  `detailpageurl` text COLLATE utf8_unicode_ci,
  `smallimage` text COLLATE utf8_unicode_ci,
  `manufacturer` text COLLATE utf8_unicode_ci,
  `binding` text COLLATE utf8_unicode_ci,
  `packetheight` int(5) DEFAULT NULL,
  `packetunit` text COLLATE utf8_unicode_ci,
  `packetlength` int(5) DEFAULT NULL,
  `packetwidth` int(5) DEFAULT NULL,
  `packetweight` int(5) DEFAULT NULL,
  `packetweightunit` text COLLATE utf8_unicode_ci,
  `upc` int(15) DEFAULT NULL,
  `ean` int(15) DEFAULT NULL,
  `totalnew` text COLLATE utf8_unicode_ci,
  KEY `asin` (`asin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-08-29 15:01:54
