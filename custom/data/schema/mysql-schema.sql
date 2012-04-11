-- MySQL dump 10.13  Distrib 5.1.51, for apple-darwin10.3.0 (i386)
--
-- Host: localhost    Database: sbc
-- ------------------------------------------------------
-- Server version    5.1.51 

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
-- Table structure for table `Adminacl`
--

DROP TABLE IF EXISTS `Adminacl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Adminacl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) DEFAULT NULL,
  `users` char(255) DEFAULT NULL,
  `groups` char(255) DEFAULT NULL,
  `acl` text,
  `type` char(255) DEFAULT '',
  `objtype` char(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Adminacl`
--

LOCK TABLES `Adminacl` WRITE;
/*!40000 ALTER TABLE `Adminacl` DISABLE KEYS */;
INSERT INTO `Adminacl` VALUES (1,'adminacl','','','{index:{groups:[1]},list:{groups:[1]},edit:{groups:[1]},save:{groups:[1]},apply:{groups:[1]},cancel:{groups:[1]}}','adminacl','adminacl'),(2,'checkouts','','','{index:{groups:[1]},checkin:{groups:[1,2]},checkin_all:{groups:[1,2]}}','adminacl','adminacl'),(3,'collections','','','{index:{groups:[1,2]}}','adminacl','adminacl'),(4,'configuration','','','{index:{groups:[1,2]},edit:{groups:[1,2]},save:{groups:[1,2]},cancel:{groups:[1,2]},delete:{groups:[1,2]},clear_cache:{groups:[1,2]}}','adminacl','adminacl'),(5,'console','','','{index:{groups:[1,2]},clear_cache:{groups:[1,2]}}','adminacl','adminacl'),(6,'contacts','','','{index:{groups:[1,2]},add:{groups:[1,2]},edit:{groups:[1,2]},save:{groups:[1,2]},cancel:{groups:[1,2]},delete:{groups:[1,2]}}','adminacl','adminacl'),(7,'extensions','','','{index:{groups:[1]},cancel:{groups:[1]},list_managers:{groups:[1]},list_fragments:{groups:[1]},list_skins:{groups:[1]},list_plugins:{groups:[1]},install:{groups:[1]},delete_manager:{groups:[1]},delete_fragment:{groups:[1]},delete_skin:{groups:[1]},delete_plugin:{groups:[1]},edit_manager:{groups:[1]},edit_fragment:{groups:[1]},edit_skin:{groups:[1]},edit_plugin:{groups:[1]},save_managers_config:{groups:[1]},save_fragments_config:{groups:[1]},save_skins_config:{groups:[1]},save_plugins_config:{groups:[1]}}','adminacl','adminacl'),(8,'help','','','{index:{groups:[1,2,3]},edit:{groups:[1,2]},save:{groups:[1,2]},cancel:{groups:[1,2,3]}}','adminacl','adminacl'),(9,'links','','','{index:{groups:[1,2,3]},list:{groups:[1,2,3]},add:{groups:[1,2]},edit:{groups:[1,2]},delete:{groups:[1,2]},save:{groups:[1,2]},cancel:{groups:[1,2,3]},editgroup:{groups:[1,2]},addgroup:{groups:[1,2]},listgroups:{groups:[1,2,3]},deletegroup:{groups:[1,2]},cancelgroups:{groups:[1,2,3]},save_group:{groups:[1,2]}}','adminacl','adminacl'),(10,'login','','','{index:{groups:[1,2,3]},lost_password:{groups:[1,2,3]},retrieve:{groups:[1,2,3]},login:{groups:[1,2,3]},logout:{groups:[1,2,3]},cancel:{groups:[1,2,3]}}','adminacl','adminacl'),(11,'media','','','{index:{groups:[1,2]},list:{groups:[1,2]},add:{groups:[1,2]},edit:{groups:[1,2]},cancel:{groups:[1,2]},upload:{groups:[1,2]},delete:{groups:[1,2]},save:{groups:[1,2]},rename:{groups:[1,2]},move:{groups:[1,2]},copy:{groups:[1,2]}}','adminacl','adminacl'),(12,'menus','','','{index:{groups:[1,2]},list:{groups:[1,2]},add:{groups:[1,2]},edit:{groups:[1,2]},save:{groups:[1,2]},cancel:{groups:[1,2]},delete:{groups:[1,2]}}','adminacl','adminacl'),(13,'meta','','','{index:{groups:[1,2]},add:{groups:[1,2]},edit:{groups:[1,2]},save:{groups:[1,2]},cancel:{groups:[1,2]},delete:{groups:[1,2]},editgroup:{groups:[1,2]},addgroup:{groups:[1,2]},listgroups:{groups:[1,2]},savegroup:{groups:[1,2]},deletegroup:{groups:[1,2]},cancelgroup:{groups:[1,2]}}','adminacl','adminacl'),(14,'page','','','{view:{users:[1],groups:[1,2,3]},list:{users:[1],groups:[1,2]},edit:{users:[1],groups:[1,2]},add:{groups:[1,2]},delete:{groups:[1,2]},save:{groups:[1,2]},apply:{groups:[1,2]},publish:{groups:[1,2]},copy:{groups:[1,2]},cancel:{groups:[1,2]},reorder:{groups:[1,2]},sitemap:{groups:[1,2,3]}}','adminacl','adminacl'),(15,'plugin','','','{index:{groups:[1,2]},list:{groups:[1,2]},add:{groups:[1,2]},edit:{groups:[1,2]},save:{groups:[1,2]},apply:{groups:[1,2]},cancel:{groups:[1,2]},publish:{groups:[1,2]},reorder:{groups:[1,2]},delete:{groups:[1,2]}}','adminacl','adminacl'),(16,'settings','','','{index:{groups:[1,2]}}','adminacl','adminacl'),(17,'skin','','','{index:{groups:[1,2]},list:{groups:[1,2]},delete:{groups:[1]},install:{groups:[1]},cancel:{groups:[1,2]},publish:{groups:[1,2]}}','adminacl','adminacl'),(18,'snippets','','','{index:{groups:[1,2]},list:{groups:[1,2]},edit:{groups:[1,2]},add:{groups:[1,2]},changetype:{groups:[1,2]},next:{groups:[1,2]},save:{groups:[1,2]},apply:{groups:[1,2]},delete:{groups:[1,2]},cancel:{groups:[1,2]}}','adminacl','adminacl'),(19,'users','','','{index:{groups:[1,2]},list:{groups:[1,2]},add:{groups:[1]},edit:{groups:[1]},save:{groups:[1]},cancel:{groups:[1,2]},delete:{groups:[1]},listgroups:{groups:[1,2]},editgroup:{groups:[1]},addgroup:{groups:[1]},save_group:{groups:[1]},deletegroup:{groups:[1]},cancelgroup:{groups:[1,2]}}','adminacl','adminacl');
/*!40000 ALTER TABLE `Adminacl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Cache`
--

DROP TABLE IF EXISTS `Cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Cache` (
  `id` char(255) NOT NULL,
  `content` longtext CHARACTER SET utf8,
  `time` char(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Cache`
--

LOCK TABLES `Cache` WRITE;
/*!40000 ALTER TABLE `Cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `Cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Checkout`
--

DROP TABLE IF EXISTS `Checkout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Checkout` (
  `id` char(255) NOT NULL,
  `checked_out` int(11) DEFAULT NULL,
  `checked_out_by` char(255) DEFAULT NULL,
  `checked_out_time` char(255) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_type` char(255) DEFAULT NULL,
  `name` char(255) DEFAULT NULL,
  `type` char(255) DEFAULT '',
  `objtype` char(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Checkout`
--

LOCK TABLES `Checkout` WRITE;
/*!40000 ALTER TABLE `Checkout` DISABLE KEYS */;
/*!40000 ALTER TABLE `Checkout` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Configuration`
--

DROP TABLE IF EXISTS `Configuration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) DEFAULT NULL,
  `value` char(255) DEFAULT NULL,
  `type` char(255) DEFAULT '',
  `objtype` char(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Configuration`
--

LOCK TABLES `Configuration` WRITE;
/*!40000 ALTER TABLE `Configuration` DISABLE KEYS */;
INSERT INTO `Configuration` VALUES (1,'site_name','SkyBlueCanvas','configuration','configuration'),(2,'site_slogan','Type. Click. Publish.','configuration','configuration'),(3,'site_url','http://127.0.0.1/sbc-sqlite/webroot/','configuration','configuration'),(4,'site_editor','wymeditor','configuration','configuration'),(5,'site_lang','en','configuration','configuration'),(6,'sef_urls','1','configuration','configuration'),(7,'use_cache','0','configuration','configuration'),(8,'contact_name','Scott Lewis','configuration','configuration'),(9,'contact_title','Engineer','configuration','configuration'),(10,'contact_address','','configuration','configuration'),(11,'contact_address_2','','configuration','configuration'),(12,'contact_city','Richmond','configuration','configuration'),(13,'contact_state','virginia','configuration','configuration'),(14,'contact_zip','23220','configuration','configuration'),(15,'contact_email','scott@skybluecanvas.com','configuration','configuration'),(16,'contact_phone','','configuration','configuration'),(17,'contact_fax','','configuration','configuration'),(18,'ui_theme','smoothness','configuration','configuration'),(19,'objtype','configuration','configuration','configuration'),(20,'name','','configuration','configuration');
/*!40000 ALTER TABLE `Configuration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Contact`
--

DROP TABLE IF EXISTS `Contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) DEFAULT NULL,
  `title` char(255) DEFAULT NULL,
  `email` char(255) DEFAULT NULL,
  `phone` char(255) DEFAULT NULL,
  `fax` char(255) DEFAULT NULL,
  `address` char(255) DEFAULT NULL,
  `city` char(255) DEFAULT NULL,
  `state` char(255) DEFAULT NULL,
  `zip` char(255) DEFAULT NULL,
  `type` char(255) DEFAULT 'contacts',
  `objtype` char(255) DEFAULT 'contacts',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Contact`
--

LOCK TABLES `Contact` WRITE;
/*!40000 ALTER TABLE `Contact` DISABLE KEYS */;
INSERT INTO `Contact` VALUES (1,'Scott E Lewis','Developer','scott@skybluecanvas.com','555-555-1234','555-555-4567','123 Main Street','Richmond','alabama','23220','contacts','contacts'),(2,'George Flanagin','CEO','george@digitalgaslight.com','555-555-0987','555-555-4321','1901 Woodbine Road','Richmond','alabama','23225','contacts','contacts');
/*!40000 ALTER TABLE `Contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Extension`
--

DROP TABLE IF EXISTS `Extension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Extension` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) DEFAULT NULL,
  `content` text,
  `context` char(255) DEFAULT NULL,
  `ordinal` int(11) DEFAULT NULL,
  `type` char(255) DEFAULT '',
  `objtype` char(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Extension`
--

LOCK TABLES `Extension` WRITE;
/*!40000 ALTER TABLE `Extension` DISABLE KEYS */;
INSERT INTO `Extension` VALUES (1,'plugin.embed-video.php','embeddedvideo_prefix=ev_plugin\n\nembeddedvideo_small=false\n\nembeddedvideo_shownolink=true\n\nembeddedvideo_width=450\n\n','plugins',0,'extensions','extensions'),(2,'plugin.embed-video.php','embeddedvideo_prefix=ev_plugin\n\nembeddedvideo_small=false\n\nembeddedvideo_shownolink=true\n\nembeddedvideo_width=350\n\n','plugins',0,'extensions','extensions'),(3,'plugin.embed-video.php','embeddedvideo_prefix=ev_plugin\n\nembeddedvideo_small=false\n\nembeddedvideo_shownolink=true\n\nembeddedvideo_width=350\n\n','plugins',0,'extensions','extensions'),(4,'plugin.embed-video.php','embeddedvideo_prefix=ev_plugin\n\nembeddedvideo_small=false\n\nembeddedvideo_shownolink=true\n\nembeddedvideo_width=350\n\n','plugins',0,'extensions','extensions'),(5,'plugin.embed-video.php','embeddedvideo_prefix=ev_plugin\n\nembeddedvideo_small=false\n\nembeddedvideo_shownolink=true\n\nembeddedvideo_width=350','plugins',0,'extensions','extensions');
/*!40000 ALTER TABLE `Extension` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Link`
--

DROP TABLE IF EXISTS `Link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) DEFAULT NULL,
  `url` char(255) DEFAULT NULL,
  `relationship` char(255) DEFAULT NULL,
  `groups` char(255) DEFAULT NULL,
  `type` char(255) DEFAULT '',
  `objtype` char(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Link`
--

LOCK TABLES `Link` WRITE;
/*!40000 ALTER TABLE `Link` DISABLE KEYS */;
INSERT INTO `Link` VALUES (1,'SkyBlueCanvas','http://www.skybluecanvas.com','bookmark','1','links','links');
/*!40000 ALTER TABLE `Link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Linksgroup`
--

DROP TABLE IF EXISTS `Linksgroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Linksgroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) DEFAULT NULL,
  `type` char(255) DEFAULT '',
  `objtype` char(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Linksgroup`
--

LOCK TABLES `Linksgroup` WRITE;
/*!40000 ALTER TABLE `Linksgroup` DISABLE KEYS */;
INSERT INTO `Linksgroup` VALUES (1,'Resources','linksgroups','linksgroups');
/*!40000 ALTER TABLE `Linksgroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Menu`
--

DROP TABLE IF EXISTS `Menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(255) DEFAULT NULL,
  `type` char(255) DEFAULT '',
  `objtype` char(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Menu`
--

LOCK TABLES `Menu` WRITE;
/*!40000 ALTER TABLE `Menu` DISABLE KEYS */;
INSERT INTO `Menu` VALUES (1,'Main Menu','menus','menus'),(2,'Top Menu','menus','menus');
/*!40000 ALTER TABLE `Menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Meta`
--

DROP TABLE IF EXISTS `Meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) DEFAULT NULL,
  `content` char(255) DEFAULT NULL,
  `groups` char(255) DEFAULT NULL,
  `type` char(255) DEFAULT '',
  `objtype` char(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Meta`
--

LOCK TABLES `Meta` WRITE;
/*!40000 ALTER TABLE `Meta` DISABLE KEYS */;
INSERT INTO `Meta` VALUES (1,'description','This is the meta description.','1','meta','meta');
/*!40000 ALTER TABLE `Meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Metagroup`
--

DROP TABLE IF EXISTS `Metagroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Metagroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) DEFAULT NULL,
  `type` char(255) DEFAULT '',
  `objtype` char(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Metagroup`
--

LOCK TABLES `Metagroup` WRITE;
/*!40000 ALTER TABLE `Metagroup` DISABLE KEYS */;
INSERT INTO `Metagroup` VALUES (1,'global','metagroups','metagroups');
/*!40000 ALTER TABLE `Metagroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Page`
--

DROP TABLE IF EXISTS `Page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `title` char(255) DEFAULT NULL,
  `menu` int(11) DEFAULT NULL,
  `pagetype` char(255) DEFAULT NULL,
  `is_error_page` int(11) DEFAULT NULL,
  `usesitename` int(11) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `metagroup` char(255) DEFAULT NULL,
  `keywords` text,
  `isdefault` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `syndicate` int(11) DEFAULT NULL,
  `modified` char(255) DEFAULT NULL,
  `meta_description` text,
  `acltype` char(255) DEFAULT NULL,
  `aclusers` char(255) DEFAULT NULL,
  `aclgroups` char(255) DEFAULT NULL,
  `permalink` char(255) DEFAULT NULL,
  `author` char(255) DEFAULT NULL,
  `story_content` text,
  `ordinal` int(11) DEFAULT NULL,
  `type` char(255) DEFAULT '',
  `objtype` char(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Page`
--

LOCK TABLES `Page` WRITE;
/*!40000 ALTER TABLE `Page` DISABLE KEYS */;
INSERT INTO `Page` VALUES (1,'Home','SkyBlueCanvas - A Lightweight, Open Source CMS written in PHP',1,'home',0,0,0,'','skyblue, canvas, lightweight, cms, content management system, small business, web site manager, xhtml, strict, css, valid, compliant, standards, tableless layout, open source, free',1,1,1,'2010-10-16T02:22:50+00:00','A Lightweight Open Source CMS for small web sites that is easy-to-use, extensible and skinnable.','no_acls','','','skybluecanvas-home-page','scott','PHA+U2t5Qmx1ZUNhbnZhcyBMaWdodHdlaWdodCA8YWNyb255bSB0aXRsZT0iIFxcPSI+Q01TPC9hY3JvbnltPiBpcyA8c3Ryb25nPmFuIG9wZW4gc291cmNlLCBmcmVlIGNvbnRlbnQgbWFuYWdlbWVudCBzeXN0ZW0gd3JpdHRlbiBpbiBwaHA8L3N0cm9uZz4gYW5kIGJ1aWx0IHNwZWNpZmljYWxseSBmb3Igc21hbGwgd2ViIHNpdGVzLiBUaGUgZW50aXJlIHNpdGUgeW91IGFyZSB2aWV3aW5nIGlzIGEgZGVtb25zdHJhdGlvbiBvZiB0aGUgU2t5Qmx1ZUNhbnZhcyBsaWdodHdlaWdodCBDTVMuIFNreUJsdWVDYW52YXMgaXMgY3VzdG9tLWJ1aWx0IGZvciB0aG9zZSBpbnN0YW5jZXMgd2hlbiBtb3JlIHJvYnVzdCBzeXN0ZW1zIGxpa2UgPHN0cm9uZyBjbGFzcz0iIFxcPSI+Sm9vbWxhLCBXb3JkUHJlc3MgYW5kIERydXBhbDwvc3Ryb25nPiBhcmUgdG9vIG11Y2ggaG9yc2Vwb3dlci48L3A+DQogIA0KPHA+TGlnaHR3ZWlnaHQgYW5kIHNpbXBsZSBkb2VzIG5vdCBtZWFuIHNpbXBsaXN0aWMsIGhvd2V2ZXIuIFNreUJsdWVDYW52YXMgaW5jbHVkZXMgYSBsb3Qgb2YgdGhlIHNhbWUgYmFzaWMgYWJpbGl0aWVzIGFzIG1vcmUgcm9idXN0IHN5c3RlbXMgYnV0IGluIGEgc2ltcGxlciBmb3JtLiBUaGUgc29mdHdhcmUgaXMgbm90IG1lYW50IHRvIGJlIGFsbCB0aGluZ3MgdG8gYWxsIHVzZXJzIGJ1dCBpdCBkb2VzIG9mZmVyIGZlYXR1cmVzIHlvdSBleHBlY3QgbGlrZSBhIGZhbWlsaWFyIDxhIGhyZWY9IiU1QyUyMiU1QyUyMmh0dHA6Ly9ibG9nLnNreWJsdWVjYW52YXMuY29tL3NreWJsdWVjYW52YXMtbGlnaHR3ZWlnaHQtY21zL3NreWJsdWVjYW52YXMtZXhhbXBsZS8lNUMlMjIlNUMlMjIiIHRpdGxlPSIgXFw9Ij5QbHVnaW4gQVBJPC9hPiwgPGEgaHJlZj0iJTVDJTIyJTVDJTIyaHR0cDovL2Jsb2cuc2t5Ymx1ZWNhbnZhcy5jb20vc2t5Ymx1ZWNhbnZhcy1saWdodHdlaWdodC1jbXMvZXhhbXBsZS1za3libHVlY2FudmFzLWV4dGVuc2lvbi8lNUMlMjIlNUMlMjIiIHRpdGxlPSIgXFw9Ij5FeHRlbnNpYmlsaXR5PC9hPiBhbmQgPGEgaHJlZj0iJTVDJTIyJTVDJTIyaHR0cDovL2Jsb2cuc2t5Ymx1ZWNhbnZhcy5jb20vc2t5Ymx1ZWNhbnZhcy1saWdodHdlaWdodC1jbXMvc2t5Ymx1ZWNhbnZhcy12MTEtcmMxLXNraW5zLWV4cGxhaW5lZC8lNUMlMjIlNUMlMjIiIHRpdGxlPSIgXFw9Ij5za2lubmFiaWxpdHk8L2E+LiA8L3A+',0,'page','page'),(2,'Features','SkyBlueCanvas Features',1,'default',0,0,0,'','',0,1,1,'2010-07-17T19:25:44+00:00','','no_acls','1','1','skybluecanvas-features','admin','PGgzPlNpbXBsZTwvaDM+PHA+DQpTa3lCbHVlQ2FudmFzIGFsbG93cyB5b3UsIHRoZSBzaXRlIG93bmVyLCB0byBrZWVwIHlvdXIgc2l0ZSBjb250ZW50IGZyZXNoIHVzaW5nIHRoZSBza2lsbHMgeW91IGFscmVhZHkgaGF2ZS4gSWYgeW91IGNhbiB1c2UgYSB3b3JkIHByb2Nlc3NvciwgeW91IGNhbiB3cml0ZSB5b3VyIHNpdGUncyB0ZXh0LiBZb3UgY2FuIHVwbG9hZCB5b3VyIHBob3RvcyBhbmQgb3RoZXIgYXJ0IHdvcmsgZGlyZWN0bHkgZnJvbSB5b3VyIGJyb3dzZXIgd2l0aCBhIGZldyBjbGlja3Mgb2YgeW91ciBtb3VzZSAoNCBjbGlja3MgdG8gYmUgZXhhY3QpLg0KPC9wPjxwPg0KU2t5Qmx1ZUNhbnZhcyB1c2VzIGVhc3ktdG8tdW5kZXJzdGFuZCBtZW51cywgYnV0dG9ucywgYW5kIGZvcm1zIHRvIGdpdmUgeW91IHRoZSBwb3dlciB0byBjaGFuZ2UgdGhlIHRleHQgYW5kIGFydHdvcmsgb24geW91ciBzaXRlLiBJbiBtb3N0IGNhc2VzLCBpdCBpcyBqdXN0IHBvaW50LCBjbGljaywgdHlwZSBhbmQgdGhlIGNvbnRlbnQgaXMgY2hhbmdlZC4NCjwvcD48aDM+RmVhdHVyZSBTdW1tYXJ5OjwvaDM+PHVsPg0KICAgIDxsaT5FYXN5LXRvLXVzZTwvbGk+DQogICAgPGxpPkVhc3ktdG8taW5zdGFsbDwvbGk+DQogICAgPGxpPlhNTCBEYXRhIFN0b3JhZ2UgKG5vIGRhdGFiYXNlIHJlcXVpcmVkKTwvbGk+IA0KICAgIDxsaT5FeHRlbnNpYmxlPC9saT4NCiAgICA8bGk+RmxleGlibGUgJmFtcDsgUG93ZXJmdWwgUGx1Z2luIEFQSTwvbGk+DQogICAgPGxpPlNraW5uYWJsZTwvbGk+DQogICAgPGxpPlNtYWxsIGluIHNpemUgKDMuNU1CKTwvbGk+DQogICAgPGxpPlZhbGlkIFhIVE1MIDEuMCBTdHJpY3Qgb3V0cHV0PC9saT4NCiAgICA8bGk+U2VhcmNoIEVuZ2luZSBGcmllbmRseSBVUkxzIChyZXF1aXJlcyBtb2RfcmV3cml0ZSk8L2xpPg0KICAgIDxsaT5DdXN0b21pemFibGUgTWV0YSBUYWdzIChnbG9iYWxseSBvciBieSBwYWdlKTwvbGk+PGxpPkJ1aWx0LWluIFJTUyBmZWVkIGNyZWF0aW9uIG9mIHBhZ2VzLCBhcnRpY2xlcyBhbmQgbmV3cyBpdGVtczwvbGk+PC91bD4NCiAgICANCjxoMz5TeXN0ZW0gUmVxdWlyZW1lbnRzPC9oMz48b2w+DQogIDxsaT5QSFAgdjQtNS54IHJ1bm5pbmcgb24gTGludXgsIFVuaXgsIEZyZWVCU0QsIE9wZW5CU0Qgb3IgTWFjT1MgWDxiciAvPg0KICAgICAgU29ycnksIFdpbmRvd3Mgc2VydmVycyBhcmUgbm90IHN1cHBvcnRlZCBhdCB0aGlzIHRpbWU8L2xpPg0KICA8bGk+QSBGVFAgQ2xpZW50ICg8YSBocmVmPSJodHRwOi8vZmlsZXppbGxhLXByb2plY3Qub3JnL2Rvd25sb2FkLnBocCI+R2V0IEZpbGVaaWxsYSBmcm9tIE1vemlsbGEgZm9yIGZyZWU8L2E+KTwvbGk+DQogIDxsaT5tb2RfcmV3cml0ZSBlbmFibGVkIChvcHRpb25hbCBidXQgcmVjb21tZW5kZWQpPC9saT4NCiAgPGxpPkphdmFTY3JpcHQgZW5hYmxlZCBpbiB5b3VyIHdlYiBicm93c2VyPC9saT48L29sPg==',0,'page','page'),(3,'About','About the SkyBlueCanvas Lightweight, Open Source CMS written in PHP',1,'default',0,0,0,'','lightweight content management, cms, plugin api, manager api, skinnable, extensible, skin',0,0,1,'2010-07-17T19:25:55+00:00','','no_acls','','','about-skybluecanvas-lightweight-cms','admin','PGgyPldoeSBBbm90aGVyIENvbnRlbnQgTWFuYWdlbWVudCBTeXN0ZW0/PC9oMj48cD4NClRoZXJlIGlzIG5vIHNob3J0YWdlIG9mIGF2YWlsYWJsZSBzb2Z0d2FyZSBwYWNrYWdlcyB0byA8c3Ryb25nIGNsYXNzPSJub3JtYWwiPm1hbmFnZSB3ZWIgc2l0ZXM8L3N0cm9uZz4uIFRoZXJlIGFyZSBudW1lcm91cywgd2VsbC1lc3RhYmxpc2hlZCA8c3Ryb25nIGNsYXNzPSJub3JtYWwiPmNvbnRlbnQgbWFuYWdlbWVudCBzeXN0ZW1zPC9zdHJvbmc+IGF2YWlsYWJsZSBjb21tZXJjaWFsbHkgYW5kIGFzIDxzdHJvbmcgY2xhc3M9Im5vcm1hbCI+b3BlbiBzb3VyY2Ugc29mdHdhcmU8L3N0cm9uZz4uIE1hbnkgb2YgdGhlIGF2YWlsYWJsZSBzeXN0ZW1zIG9mZmVyIGV4Y2VsbGVudCBzdXBwb3J0LCBtYXR1cmUgZGV2ZWxvcG1lbnQgcGxhdGZvcm1zLCBsYXJnZSBkZXZlbG9wZXIgYW5kIHVzZXIgY29tbXVuaXRpZXMgYW5kIGluIHNvbWUgY2FzZXMgdGhvdXNhbmRzIG9mIGF2YWlsYWJsZSB0aGlyZC1wYXJ0eSBhZGQtb25zIHRvIG1lZXQgYWxtb3N0IGV2ZXJ5IGN1c3RvbWl6YXRpb24gbmVlZC4NCjwvcD48cD4NCldoYXQgdGhlcmUgaXMgYSBzaG9ydGFnZSBvZiBpcyB3ZWxsLXdyaXR0ZW4sIDxzdHJvbmcgY2xhc3M9Im5vcm1hbCI+ZWFzeS10by11c2UgbGlnaHR3ZWlnaHQgY29udGVudCBtYW5hZ2VtZW50IHN5c3RlbXMgZm9yIHNtYWxsIHdlYiBzaXRlczwvc3Ryb25nPi4gVGhlIGxpbWl0ZWQgb3B0aW9ucyBhdmFpbGFibGUgdG8gPHN0cm9uZyBjbGFzcz0ibm9ybWFsIj5zbWFsbCB3ZWIgc2l0ZSBvd25lcnM8L3N0cm9uZz4gb2Z0ZW4gZm9yY2UgdGhlIHdlYiBzaXRlIG93bmVyIHRvIGNob29zZSBhIHN5c3RlbSB0aGF0IGhhcyBmYXIgbW9yZSBmZWF0dXJlcyBhbmQgZmFyIG1vcmUgY29tcGxleGl0eSB0aGFuIGFyZSBuZWVkZWQuIA0KPC9wPjxoMj5XaGF0IElzIEEgU21hbGwgV2ViIFNpdGU/PC9oMj48cD5XZSBkZWZpbmUgYSA8c3Ryb25nIGNsYXNzPSJub3JtYWwiPnNtYWxsIHdlYiBzaXRlPC9zdHJvbmc+IGFzIG9uZSB3aGljaCBoYXMgZmV3ZXIgdGhhbiAzIHNpdGUgbWFuYWdlcnMgLSB2ZXJ5IG9mdGVuIGp1c3QgMSAtIGFuZCBvbmUgd2hpY2ggaXMgY29tcHJpc2VkIG9mIGZld2VyIHRoYW4gMTAwIHBhZ2VzLCBhbmQgcGVyaGFwcyB0d28gb3IgdGhyZWUgcGFnZSBsYXlvdXRzIG9yIDxzdHJvbmcgY2xhc3M9Im5vcm1hbCI+dGVtcGxhdGVzPC9zdHJvbmc+LiBUaGlzIHR5cGUgb2Ygd2ViIHNpdGUgaXMgY29tbW9ubHkgcmVmZXJyZWQgdG8gYXMgYSA8c3Ryb25nIGNsYXNzPSJub3JtYWwiPmJyb2NodXJlIHN0eWxlIHdlYiBzaXRlPC9zdHJvbmc+IGFuZCBhY2NvdW50cyBmb3IgdGhlIG1ham9yaXR5IG9mIHNpdGVzIG9uIHRoZSB3b3JsZCB3aWRlIHdlYi48L3A+PHA+DQpTa3lCbHVlQ2FudmFzIExpZ2h0d2VpZ2h0IENNUyBpcyBidWlsdCB0byBwcm92aWRlIHRoZSA8c3Ryb25nIGNsYXNzPSJub3JtYWwiPnNtYWxsIHdlYiBzaXRlIG93bmVyPC9zdHJvbmc+IHdpdGggYW4gPHN0cm9uZyBjbGFzcz0ibm9ybWFsIj5lYXN5LXRvLXVzZSB3ZWIgc2l0ZSBtYW5hZ2VyPC9zdHJvbmc+LiBUaGVyZSBpcyBvbmx5IGFzIG11Y2ggY29tcGxleGl0eSBhcyBpcyBhYnNvbHV0ZWx5IG5lY2Vzc2FyeS4gU2t5Qmx1ZUNhbnZhcyBpcyBub3QgbWVhbnQgdG8gbWVldCBldmVyeSB3ZWIgc2l0ZSBuZWVkIG9yIHRvIGNvbXBldGUgd2l0aCBsYXJnZXIgc3lzdGVtcy4gVGhlIHNvZnR3YXJlIGlzIGludGVuZGVkIGZvciA8c3Ryb25nIGNsYXNzPSJub3JtYWwiPnNtYWxsIHdlYiBzaXRlczwvc3Ryb25nPiB3aXRoIHJlbGF0aXZlbHkgc2ltcGxlIHJlcXVpcmVtZW50cy4NCjwvcD48aDI+V2hhdCBJcyBJbmNsdWRlZDwvaDI+PHA+DQpTa3lCbHVlQ2FudmFzIGlzIHNpbXBsZSBidXQgZGVmaW5pdGVseSBub3Qgc2ltcGxpc3RpYy4gVGhlIHNvZnR3YXJlIGluY2x1ZGVzIG1hbnkgZmVhdHVyZXMgdG8gYWxsb3cgaXQgdG8gYmUgY3VzdG9taXplZCBhbmQgYWRhcHRlZCB0byBhIHZhcmlldHkgb2YgbmVlZHMuIFRoZXNlIGZlYXR1cmVzIGluY2x1ZGU6DQo8L3A+PHVsPg0KICAgIDxsaT5FeHRlbnNpYmlsZSBDb3JlIHRocm91Z2ggQ29udGVudCBNYW5hZ2VycyBhbmQgTW9kdWxlczwvbGk+DQogICAgPGxpPlBsdWdpbiBBUEkgdG8gYWxsb3cgY29kZSBleGVjdXRpb24gdHJpZ2dlcmVkIG9uIGN1c3RvbSBldmVudHM8L2xpPg0KICAgIDxsaT5Ta2lubmFiaWxpdHkgdGhyb3VnaCBIVE1ML0NTUyB0ZW1wbGF0ZXM8L2xpPjwvdWw+PGgyPldoYXQgSXMgTGVmdCBPdXQ8L2gyPjxwPg0KQmVjYXVzZSBTa3lCbHVlQ2FudmFzIExpZ2h0d2VpZ2h0IENNUyBpcyBidWlsdCBmb3IgPHN0cm9uZyBjbGFzcz0ibm9ybWFsIj5zbWFsbCB3ZWIgc2l0ZXM8L3N0cm9uZz4sIG1hbnkgZmVhdHVyZXMgbmVlZGVkIHRvIHN1cHBvcnQgbGFyZ2VyIHNpdGVzIGhhdmUgYmVlbiBsZWZ0IG91dC4gQW1vbmcgdGhlc2UgYXJlOg0KPC9wPjx1bD4NCiAgICA8bGk+PHN0cm9uZyBjbGFzcz0ibm9ybWFsIj5ObyBkYXRhYmFzZTwvc3Ryb25nPiBpcyByZXF1aXJlZDwvbGk+DQogICAgPGxpPlplcm8gY29uZmlndXJhdGlvbiAtIGp1c3QgdXBsb2FkIGl0IHRvIHlvdXIgd2ViIHNlcnZlciBhbmQgc3RhcnQgdXNpbmcgaXQ8L2xpPg0KICAgIDxsaT5ObyBjb21wbGljYXRlZCBVc2VyIE1hbmFnZW1lbnQgKDxhY3JvbnltIHRpdGxlPSJBY2Nlc3MgQ29udHJvbCBMaXN0Ij5BQ0w8L2Fjcm9ueW0+KTwvbGk+DQogICAgPGxpPk5vIGNvbXBsZXggY29udGVudCBoaWVyYXJjaHkgKFNlY3Rpb25zLCBDYXRlZ29yaWVzLCBldGMuKTwvbGk+PC91bD4=',0,'page','page'),(4,'Contact','Contact SkyBlueCanvas, the Lightweight Open Source CMS written in PHP',1,'contact',0,0,0,'','',0,1,0,'2010-05-01T12:03:59+00:00','','no_acls','','','contact','','PHA+RmVlbCBmcmVlIHRvIGRyb3AgdXMgYSBsaW5lIHRvIGFzayBhIHF1ZXN0aW9uLCBpbnF1aXJlIGFib3V0IHN1cHBvcnQgb3IganVzdCB0byBzYXkgIkhlbGxvIi48L3A+',0,'page','page'),(5,'Blog','SkyBlueCanvas Lightweight CMS Blog',1,'default',0,0,0,'','',0,1,0,'2010-02-10T21:11:59+00:00','','no_acls','','','skybluecanvas-lightweight-cms-blog','','W1twYWdlLnJlZGlyZWN0KGh0dHA6Ly9ibG9nLnNreWJsdWVjYW52YXMuY29tLyldXQ==',0,'page','page'),(6,'Admin','Admin Console',0,'default',0,0,0,'','',0,1,0,'2010-10-10T14:52:44+00:00','','no_acls','','','admin','admin','W1twYWdlLnJlZGlyZWN0KGh0dHA6Ly9ibG9nLnNreWJsdWVjYW52YXMuY29tLyldXQ==',0,'page','page'),(7,'Site Map','Site Map of SkyBlueCanvas, the Lightweight Open Source CMS written in PHP',0,'default',0,0,0,'2','',0,1,0,'2008-07-07T04:20:21+00:00','','','','','','','PGgyPlNreUJsdWVDYW52YXMuY29tIFNpdGUgTWFwPC9oMj4KW1tzaXRlLm1hcF1d',0,'page','page'),(8,'Forum','Forum for SkyBlueCanvas, the Lightweight Open Source CMS written in PHP',1,'default',0,0,0,'','',0,0,0,'2010-08-19T21:48:49+00:00','','no_acls','','','forum','admin','W1twYWdlLnJlZGlyZWN0KGh0dHA6Ly9mb3J1bS5za3libHVlY2FudmFzLmNvbSldXQ==',0,'page','page'),(9,'RSS','RSS Feed for SkyBlueCanvas, the Lightweight Open Source CMS written in PHP',1,'default',0,0,0,'','',0,0,0,'2008-11-23T04:48:13+00:00','','','','','','','PHA+W1twYWdlLnJlZGlyZWN0KHJzcy8pXV08L3A+',0,'page','page'),(10,'404 Page Not Found','404 Page Not Found',0,'default',1,0,0,'','',0,1,0,'2010-02-14T17:47:50+00:00','','no_acls','','','404-page-not-found','','PGgyPk9vcHMuIFdlIGNvdWxkIG5vdCBmaW5kIHRoZSBwYWdlIHlvdSByZXF1ZXN0ZWQuPC9oMj48cD5UaGUgcGFnZSB5b3UgYXJlIGxvb2tpbmcgZm9yIGNvdWxkIG5vdCBiZSBmb3VuZC4gVGhpcyBtYXkgaGF2ZSBoYXBwZW5lZCBmb3Igc2V2ZXJhbCByZWFzb25zOgo8L3A+PHVsPgogICAgPGxpPkEgbWlzdHlwZWQgVVJMLCBvciBhIGNvcHktYW5kLXBhc3RlIG1pc3Rha2U8L2xpPgogICAgPGxpPkJyb2tlbiBvciB0cnVuY2F0ZWQgbGlua3Mgb24gd2ViIHBhZ2VzIG9yIGluIGFuIGVtYWlsIG1lc3NhZ2U8L2xpPgogICAgPGxpPk1vdmVkIG9yIGRlbGV0ZWQgY29udGVudDwvbGk+PC91bD48cD4KRm9yIHlvdXIgY29udmVuaWVuY2UsIHdlIGhhdmUgaW5jbHVkZWQgYSBzaXRlbWFwIGJlbG93IHRvIGhlbHAgeW91IGZpbmQgd2hhdCB5b3UgbmVlZC4KPC9wPgoKW1tzaXRlLm1hcF1d',0,'page','page'),(11,'Preferred Hosting','Preferred Hosting for SkyBlueCanvas, the Lightweight CMS written in PHP',1,'default',0,0,2,'','skybluecanvas, lightweight, cms, php, hosting',0,1,1,'2010-10-10T16:29:13+00:00','This page explains why HostICan is the preferred hosting provider for the SkyBlueCanvas Lightweight CMS.','no_acls','','','preferred-hosting','admin','e3NuaXBwZXQoaG9zdGljYW5fYmFubmVyKX0NCg0KPGgyPlNreUJsdWVDYW52YXMgUHJlZmVycmVkIEhvc3RpbmcgUHJvdmlkZXI8L2gyPjxwPklmIHlvdSBoYXZlIGJvdWdodCBzZXJ2aWNlcyBmcm9tIGEgZmV3IHNoYXJlZCBob3N0aW5nIHByb3ZpZGVycywgeW91IGhhdmUgcHJvYmFibHkgbm90aWNlZCB0aGF0IGFsbCBwcm92aWRlcnMgYXJlIG5vdCBjcmVhdGVkIGVxdWFsLiBOb3QgZXZlcnkgd2ViIHNpdGUgb3duZXIgY2FuIGJlIGFuIGV4cGVydCBvbiBwcm9wZXIgc2VydmVyIGFkbWluaXN0cmF0aW9uIGFuZCBpdCBpcyBkaWZmaWN1bHQgdG8gZmluZCBnb29kIGluZm9ybWF0aW9uIGFib3V0IHRoZSBjb3JyZWN0IGNvbmZpZ3VyYXRpb24gdGhhdCBhbGxvd3MgdGhlIHdlYiBzaXRlIG93bmVyIHRvIGluc3RhbGwgdGhlIHNvZnR3YXJlIHRoZXkgbmVlZCB3aGlsZSBtYWludGFpbmluZyB0aGUgc2VjdXJpdHkgYW5kIGludGVncml0eSBvZiB0aGVpciB3ZWIgc2l0ZS48L3A+PHA+U2t5Qmx1ZUNhbnZhcy5jb20gaXMgaG9zdGVkIGJ5IEhvc3RJQ2FuLmNvbSBiZWNhdXNlIHdlIGJlbGlldmUgdGhhdCB0aGV5IGdldCBzZXJ2ZXIgY29uZmlndXJhdGlvbiBhbmQgc2VjdXJpdHkgcmlnaHQgYW5kIGF0IGEgbW9yZSB0aGFuIHJlYXNvbmFibGUgcHJpY2Ugb2YgJDYuOTUgcGVyIG1vbnRoLiBIb3N0SUNhbiBhbHNvIHByb3ZpZGVzIHVzIGFuZCBtYW55IG9mIG91ciBjbGllbnRzIHRvcC1ub3RjaCBjdXN0b21lciBzZXJ2aWNlLiBUaGVpciBzdGFmZiBpcyBmcmllbmRseSBhbmQgcmVzcG9uc2l2ZSwgb2Z0ZW4gZ29pbmcgdGhlIGV4dHJhIG1pbGUgdG8gbWFrZSBzdXJlIG91ciBuZWVkcyBhcmUgbWV0LjwvcD48cD5Ta3lCbHVlQ2FudmFzIGhhcyBjaG9zZW4gSG9zdElDYW4uY29tIGFzIG91ciBwcmVmZXJyZWQgaG9zdGluZyBwcm92aWRlciBmb3Igb3VyIGhvc3RpbmcsIHRoYXQgb2Ygb3VyIGNsaWVudHMgYW5kIGZvciB0aGUgU2t5Qmx1ZUNhbnZhcyBMaWdodHdlaWdodCBDTVMuIElmIHlvdSB3b3VsZCBsaWtlIHRvIGxlYXJuIG1vcmUgYWJvdXQgSG9zdElDYW4ncyBzZXJ2aWNlcywgZmVlbCBmcmVlIHRvIGNvbnRhY3QgdXMgdGhyb3VnaCBvdXIgPGEgaHJlZj0iY29udGFjdC5odG1sIj5Db250YWN0IFBhZ2U8L2E+IG9yIHZpc2l0IHRoZWlyIHNpdGUgdG8gc2lnbiB1cCB0b2RheSBhdCA8YSBocmVmPSJodHRwOi8vdHJhY2tpbmcuaG9zdGljYW4uY29tLz9yZWY9c2t5Ymx1ZWNhbnZhcyZhbXA7c2l6ZT00Njh4NjAmYW1wO2NhbXBhaWduX2lkPTEyODgiIG9ubW91c2VvdmVyPSJ3aW5kb3cuc3RhdHVzID0gJ2h0dHA6Ly93d3cuaG9zdGljYW4uY29tLyc7IHJldHVybiB0cnVlOyIgb25tb3VzZW91dD0id2luZG93LnN0YXR1cyA9ICcnOyByZXR1cm4gdHJ1ZTsiPkhvc3RJQ2FuLmNvbTwvYT4uPC9wPjxoMj5TZXJ2aWNlIE92ZXJ2aWV3PC9oMj48dWw+PGxpPkZSRUUgRG9tYWluIE5hbWU8L2xpPjxsaT4yLDAwMCBHQiBEaXNrIFNwYWNlPC9saT48bGk+MjAsMDAwIEdCIEJhbmR3aWR0aDwvbGk+PGxpPjIwMDArIEZSRUUgVGVtcGxhdGVzPC9saT48L3VsPjxoMj5UaGV5IGFsc28gZ3VhcmFudGVlPC9oMj48bGk+OTkuOSUgVXB0aW1lPC9saT48bGk+MjQvNy8zNjUgUGhvbmUgJmFtcDsgRW1haWwgU3VwcG9ydDwvbGk+PGxpPllvdXIgc2F0aXNmYWN0aW9uIG9yIHlvdXIgbW9uZXkgYmFjay48L2xpPjxoMj5BcmUgWW91IGEgRnJlZWxhbmNlIFdlYiBEZXNpZ25lciBvciBEZXZlbG9wZXI/PC9oMj48cD4NCklmIHlvdSBhcmUgYSBmcmVlbGFuY2Ugd2ViIGRlc2lnbmVyLCB3ZWIgZGV2ZWxvcGVyIG9yIHdlYiBzaXRlIG93bmVyLCBsZWFybiBob3cgeW91IGNhbiBwcm92aWRlIGEgdXNlZnVsIHNlcnZpY2UgdG8geW91ciBjbGllbnRzIGFuZCBieSByZWFkaW5nIG91ciBhcnRpY2xlIG9uIHRoZSA8YSBocmVmPSJob3N0aWNhbi1hZmZpbGlhdGUtcHJvZ3JhbS5odG1sIj5Ib3N0SUNhbiBBZmZpbGlhdGUgUHJvZ3JhbTwvYT4uIA0KPC9wPg==',0,'page','page'),(12,'Search','SkyBlueCanvas Search Page',0,'search',0,0,0,'','',0,1,0,'2009-03-07T22:59:26+00:00','','','','','','','',0,'page','page'),(13,'Apply Test','Apply Test Page',0,'default',0,0,0,'','',0,0,0,'2010-06-27T12:20:48+00:00','','no_acls','','','apply-test','admin','',0,'page','page');
/*!40000 ALTER TABLE `Page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Plugin`
--

DROP TABLE IF EXISTS `Plugin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Plugin` (
  `id` char(255) NOT NULL,
  `published` int(11) DEFAULT NULL,
  `name` char(255) DEFAULT NULL,
  `ordinal` int(11) DEFAULT NULL,
  `type` char(255) DEFAULT '',
  `objtype` char(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Plugin`
--

LOCK TABLES `Plugin` WRITE;
/*!40000 ALTER TABLE `Plugin` DISABLE KEYS */;
INSERT INTO `Plugin` VALUES ('plugin.bbcoder.php',0,'plugin.bbcoder.php',1,'plugin','plugin'),('plugin.patches.php',1,'plugin.patches.php',2,'plugin','plugin'),('plugin.preloader.php',1,'plugin.preloader.php',3,'plugin','plugin'),('plugin.sitevars.php',1,'plugin.sitevars.php',4,'plugin','plugin'),('plugin.snippets.php',1,'plugin.snippets.php',5,'plugin','plugin'),('plugin.ads.php',1,'plugin.ads.php',6,'plugin','plugin'),('plugin.gadgets.php',1,'plugin.gadgets.php',7,'plugin','plugin'),('plugin.magpie.php',1,'plugin.magpie.php',8,'plugin','plugin'),('plugin.theme.php',1,'plugin.theme.php',9,'plugin','plugin'),('plugin.embed-video.php',0,'plugin.embed-video.php',10,'plugin','plugin');
/*!40000 ALTER TABLE `Plugin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Skin`
--

DROP TABLE IF EXISTS `Skin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Skin` (
  `id` char(255) NOT NULL,
  `published` int(11) DEFAULT NULL,
  `name` char(255) DEFAULT NULL,
  `type` char(255) DEFAULT '',
  `objtype` char(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Skin`
--

LOCK TABLES `Skin` WRITE;
/*!40000 ALTER TABLE `Skin` DISABLE KEYS */;
INSERT INTO `Skin` VALUES ('techjunkie',0,'techjunkie','skin','skin'),('paper-aeroplane',1,'paper-aeroplane','skin','skin');
/*!40000 ALTER TABLE `Skin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Snippet`
--

DROP TABLE IF EXISTS `Snippet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Snippet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) DEFAULT NULL,
  `snippetType` char(255) DEFAULT NULL,
  `content` text,
  `type` char(255) DEFAULT '',
  `objtype` char(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Snippet`
--

LOCK TABLES `Snippet` WRITE;
/*!40000 ALTER TABLE `Snippet` DISABLE KEYS */;
INSERT INTO `Snippet` VALUES (1,'buysellads_script','text','PCEtLSBCdXlTZWxsQWRzLmNvbSBBZCBDb2RlIC0tPg0KPHNjcmlwdCB0eXBlPSJ0ZXh0L2phdmFzY3JpcHQiPg0KKGZ1bmN0aW9uKCl7DQogIHZhciBic2EgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdzY3JpcHQnKTsNCiAgICAgYnNhLnR5cGUgPSAndGV4dC9qYXZhc2NyaXB0JzsNCiAgICAgYnNhLmFzeW5jID0gdHJ1ZTsNCiAgICAgYnNhLnNyYyA9ICcvL3MzLmJ1eXNlbGxhZHMuY29tL2FjL2JzYS5qcyc7DQogIChkb2N1bWVudC5nZXRFbGVtZW50c0J5VGFnTmFtZSgnaGVhZCcpWzBdfHxkb2N1bWVudC5nZXRFbGVtZW50c0J5VGFnTmFtZSgnYm9keScpWzBdKS5hcHBlbmRDaGlsZChic2EpOw0KfSkoKTsNCjwvc2NyaXB0Pg0KPCEtLSBFTkQgQnV5U2VsbEFkcy5jb20gQWQgQ29kZSAtLT4=','snippets','snippets'),(2,'hostican_banner','text','PGRpdiBjbGFzcz0iaG9zdGljYW5fNDY4eDYwIj4NCjxkaXYgY2xhc3M9ImJsdXJiIj5QbGVhc2UgU3VwcG9ydDxiciAvPk91ciBTcG9uc29yczwvZGl2Pg0KPHAgY2xhc3M9ImJhbm5lci1hZC1kaXYiPjxhIGhyZWY9Imh0dHA6Ly90cmFja2luZy5ob3N0aWNhbi5jb20vP3JlZj1za3libHVlY2FudmFzJnNpemU9NDY4eDYwIiBvbm1vdXNlb3Zlcj0id2luZG93LnN0YXR1cyA9ICdodHRwOi8vd3d3Lmhvc3RpY2FuLmNvbS8nOyByZXR1cm4gdHJ1ZTsiIG9ubW91c2VvdXQ9IndpbmRvdy5zdGF0dXMgPSAnJzsgcmV0dXJuIHRydWU7IiBzdHlsZT0iYm9yZGVyOiBtZWRpdW0gbm9uZSA7Ij48aW1nIHNyYz0iaHR0cDovL21lZGlhLmhvc3RpY2FuLmNvbS9zaGFyZWQvaGljNDY4NjAuZ2lmIiAvPjwvYT48L3A+DQo8L2Rpdj4NCjwhLS0NCjxwIHN0eWxlPSJjbGVhcjogYm90aDsgZm9udC1zaXplOiAxMnB4OyB0ZXh0LWFsaWduOiBsZWZ0OyI+KiBJbnN0YWxsYXRpb24gcHJvdmlkZWQgYnkgU2t5Qmx1ZUNhbnZhcywgbm90IGJ5IEhvc3RJQ2FuLmNvbS4gSW5zdGFsbGF0aW9uIGRvZXMgbm90IGluY2x1ZGUgY3VzdG9taXphdGlvbiBvciBjb250ZW50L2RhdGEgZW50cnkuPC9wPg0KLS0+','snippets','snippets'),(3,'test_snippet','text','VGhpcyBpcyBhIHRlc3Qgc25pcHBldC4=','snippets','snippets'),(4,'buysellads_script','text','PCEtLSBCdXlTZWxsQWRzLmNvbSBBZCBDb2RlIC0tPg0KPHNjcmlwdCB0eXBlPSJ0ZXh0L2phdmFzY3JpcHQiPg0KKGZ1bmN0aW9uKCl7DQogIHZhciBic2EgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdzY3JpcHQnKTsNCiAgICAgYnNhLnR5cGUgPSAndGV4dC9qYXZhc2NyaXB0JzsNCiAgICAgYnNhLmFzeW5jID0gdHJ1ZTsNCiAgICAgYnNhLnNyYyA9ICcvL3MzLmJ1eXNlbGxhZHMuY29tL2FjL2JzYS5qcyc7DQogIChkb2N1bWVudC5nZXRFbGVtZW50c0J5VGFnTmFtZSgnaGVhZCcpWzBdfHxkb2N1bWVudC5nZXRFbGVtZW50c0J5VGFnTmFtZSgnYm9keScpWzBdKS5hcHBlbmRDaGlsZChic2EpOw0KfSkoKTsNCjwvc2NyaXB0Pg0KPCEtLSBFTkQgQnV5U2VsbEFkcy5jb20gQWQgQ29kZSAtLT4=','snippets','snippets');
/*!40000 ALTER TABLE `Snippet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(255) DEFAULT NULL,
  `password` char(255) DEFAULT NULL,
  `email` char(255) DEFAULT NULL,
  `name` char(255) DEFAULT NULL,
  `groups` char(255) DEFAULT NULL,
  `block` char(255) DEFAULT NULL,
  `lastlogin` char(255) DEFAULT NULL,
  `tempkey` char(255) DEFAULT NULL,
  `tempkeyexpiration` char(255) DEFAULT NULL,
  `type` char(255) DEFAULT '',
  `objtype` char(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` VALUES (1,'anonymous','','visitor@foobar.com','Visitor','3','0','','','','users','users'),(2,'admin','d033e22ae348aeb5660fc2140aec35850c4da997','scott@skybluecanvas.com','Admin User','1,2,3','0','','','','users','users'),(3,'scott','5bc708fe702b9c9ca0f59473ba92b414e079f6fb','scott@skybluecanvas.com','Scott','2,3','0','1275928566','','','users','users');
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Usergroup`
--

DROP TABLE IF EXISTS `Usergroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Usergroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) DEFAULT NULL,
  `siteadmin` int(11) DEFAULT NULL,
  `type` char(255) DEFAULT '',
  `objtype` char(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Usergroup`
--

LOCK TABLES `Usergroup` WRITE;
/*!40000 ALTER TABLE `Usergroup` DISABLE KEYS */;
INSERT INTO `Usergroup` VALUES (1,'Administrators',1,'usergroups','usergroups'),(2,'Contributors',0,'usergroups','usergroups'),(3,'Public',0,'usergroups','usergroups');
/*!40000 ALTER TABLE `Usergroup` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-10-16 12:44:04
