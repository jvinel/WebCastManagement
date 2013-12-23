CREATE DATABASE  IF NOT EXISTS `wcm` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `wcm`;
-- MySQL dump 10.13  Distrib 5.6.13, for Win32 (x86)
--
-- Host: fr0-aptv-p02    Database: wcm
-- ------------------------------------------------------
-- Server version	5.1.34-community-log

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
-- Table structure for table `publishing_points`
--

DROP TABLE IF EXISTS `publishing_points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publishing_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `concurrentplayer` bigint(20) NOT NULL DEFAULT '0',
  `bandwidth` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_SERVER_idx` (`server_id`),
  CONSTRAINT `FK_SERVER` FOREIGN KEY (`server_id`) REFERENCES `servers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publishing_points`
--

LOCK TABLES `publishing_points` WRITE;
/*!40000 ALTER TABLE `publishing_points` DISABLE KEYS */;
INSERT INTO `publishing_points` VALUES (1,1,'Live',0,0),(2,1,'WebTV',0,0),(5,3,'Live',0,0),(6,3,'WebTV',0,0),(7,4,'Live',0,0),(8,4,'WebTV',0,0),(9,5,'Live',0,0),(10,5,'WebTV',0,0),(11,1,'Live_Distant',0,0),(12,1,'Live_Moscow',0,0),(13,1,'Live_Dubai',0,0),(14,1,'Live_Bangalore',0,0),(15,1,'Live_Beijing',0,0),(16,1,'WebTVBangalore',0,0),(17,1,'WebTVDubai',0,0),(18,1,'WebTVOthers',0,0);
/*!40000 ALTER TABLE `publishing_points` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-12-13 16:51:42
