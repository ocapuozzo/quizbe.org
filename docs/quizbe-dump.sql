-- MySQL dump 10.13  Distrib 5.7.29, for Linux (x86_64)
--
-- Host: localhost    Database: quizbe
-- ------------------------------------------------------
-- Server version	5.7.29-0ubuntu0.18.04.1

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
-- Table structure for table `acl_classes`
--

DROP TABLE IF EXISTS `acl_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_classes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_type` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_69DD750638A36066` (`class_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_classes`
--

LOCK TABLES `acl_classes` WRITE;
/*!40000 ALTER TABLE `acl_classes` DISABLE KEYS */;
/*!40000 ALTER TABLE `acl_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acl_entries`
--

DROP TABLE IF EXISTS `acl_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_entries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_id` int(10) unsigned NOT NULL,
  `object_identity_id` int(10) unsigned DEFAULT NULL,
  `security_identity_id` int(10) unsigned NOT NULL,
  `field_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ace_order` smallint(5) unsigned NOT NULL,
  `mask` int(11) NOT NULL,
  `granting` tinyint(1) NOT NULL,
  `granting_strategy` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `audit_success` tinyint(1) NOT NULL,
  `audit_failure` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_46C8B806EA000B103D9AB4A64DEF17BCE4289BF4` (`class_id`,`object_identity_id`,`field_name`,`ace_order`),
  KEY `IDX_46C8B806EA000B103D9AB4A6DF9183C9` (`class_id`,`object_identity_id`,`security_identity_id`),
  KEY `IDX_46C8B806EA000B10` (`class_id`),
  KEY `IDX_46C8B8063D9AB4A6` (`object_identity_id`),
  KEY `IDX_46C8B806DF9183C9` (`security_identity_id`),
  CONSTRAINT `FK_46C8B8063D9AB4A6` FOREIGN KEY (`object_identity_id`) REFERENCES `acl_object_identities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_46C8B806DF9183C9` FOREIGN KEY (`security_identity_id`) REFERENCES `acl_security_identities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_46C8B806EA000B10` FOREIGN KEY (`class_id`) REFERENCES `acl_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_entries`
--

LOCK TABLES `acl_entries` WRITE;
/*!40000 ALTER TABLE `acl_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `acl_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acl_object_identities`
--

DROP TABLE IF EXISTS `acl_object_identities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_object_identities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_object_identity_id` int(10) unsigned DEFAULT NULL,
  `class_id` int(10) unsigned NOT NULL,
  `object_identifier` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `entries_inheriting` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_9407E5494B12AD6EA000B10` (`object_identifier`,`class_id`),
  KEY `IDX_9407E54977FA751A` (`parent_object_identity_id`),
  CONSTRAINT `FK_9407E54977FA751A` FOREIGN KEY (`parent_object_identity_id`) REFERENCES `acl_object_identities` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_object_identities`
--

LOCK TABLES `acl_object_identities` WRITE;
/*!40000 ALTER TABLE `acl_object_identities` DISABLE KEYS */;
/*!40000 ALTER TABLE `acl_object_identities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acl_object_identity_ancestors`
--

DROP TABLE IF EXISTS `acl_object_identity_ancestors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_object_identity_ancestors` (
  `object_identity_id` int(10) unsigned NOT NULL,
  `ancestor_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`object_identity_id`,`ancestor_id`),
  KEY `IDX_825DE2993D9AB4A6` (`object_identity_id`),
  KEY `IDX_825DE299C671CEA1` (`ancestor_id`),
  CONSTRAINT `FK_825DE2993D9AB4A6` FOREIGN KEY (`object_identity_id`) REFERENCES `acl_object_identities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_825DE299C671CEA1` FOREIGN KEY (`ancestor_id`) REFERENCES `acl_object_identities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_object_identity_ancestors`
--

LOCK TABLES `acl_object_identity_ancestors` WRITE;
/*!40000 ALTER TABLE `acl_object_identity_ancestors` DISABLE KEYS */;
/*!40000 ALTER TABLE `acl_object_identity_ancestors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acl_security_identities`
--

DROP TABLE IF EXISTS `acl_security_identities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_security_identities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `username` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8835EE78772E836AF85E0677` (`identifier`,`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_security_identities`
--

LOCK TABLES `acl_security_identities` WRITE;
/*!40000 ALTER TABLE `acl_security_identities` DISABLE KEYS */;
/*!40000 ALTER TABLE `acl_security_identities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classroom`
--

DROP TABLE IF EXISTS `classroom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classroom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_497D309D6B3CA4B` (`id_user`),
  CONSTRAINT `FK_497D309D6B3CA4B` FOREIGN KEY (`id_user`) REFERENCES `fos_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classroom`
--

LOCK TABLES `classroom` WRITE;
/*!40000 ALTER TABLE `classroom` DISABLE KEYS */;
INSERT INTO `classroom` VALUES (21,1,'SIO22-LDV-SLAM');
/*!40000 ALTER TABLE `classroom` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classroom_scope`
--

DROP TABLE IF EXISTS `classroom_scope`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classroom_scope` (
  `classroom_id` int(11) NOT NULL,
  `scope_id` int(11) NOT NULL,
  PRIMARY KEY (`classroom_id`,`scope_id`),
  KEY `IDX_6C50CA5A6278D5A8` (`classroom_id`),
  KEY `IDX_6C50CA5A682B5931` (`scope_id`),
  CONSTRAINT `FK_6C50CA5A6278D5A8` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_6C50CA5A682B5931` FOREIGN KEY (`scope_id`) REFERENCES `scope` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classroom_scope`
--

LOCK TABLES `classroom_scope` WRITE;
/*!40000 ALTER TABLE `classroom_scope` DISABLE KEYS */;
/*!40000 ALTER TABLE `classroom_scope` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `ancestors` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `depth` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `state` int(11) NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526CE2904019` (`thread_id`),
  KEY `IDX_9474526CF675F31B` (`author_id`),
  CONSTRAINT `FK_9474526CE2904019` FOREIGN KEY (`thread_id`) REFERENCES `thread` (`id`),
  CONSTRAINT `FK_9474526CF675F31B` FOREIGN KEY (`author_id`) REFERENCES `fos_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` VALUES (1,'6','super !','',0,'2015-08-26 22:15:59',0,NULL),(2,'6','a bon ?','1',1,'2015-08-26 22:16:12',1,1),(3,'6','par moi','',0,'2015-08-26 22:25:18',0,NULL),(4,'5','sss','',0,'2015-08-26 22:25:45',1,1),(5,'5','zzzzz re-poster','',0,'2015-08-26 22:26:59',1,1),(6,'5','ssss','',0,'2015-08-26 22:28:38',0,1),(7,'5','Un commentaire','',0,'2015-08-26 22:52:11',0,8),(8,'3','un commentaire','',0,'2015-08-26 22:53:30',0,8),(9,'6','vraiment super ?','1',1,'2015-08-27 08:00:10',0,1),(10,'6','repose ) \"a moi\" 2','3',1,'2015-08-27 08:48:12',0,1),(11,'6','rep de rep','3/10',2,'2015-08-27 08:52:05',0,1),(12,'6','<i>un commentaire</i>','',0,'2015-08-27 09:31:52',0,1),(13,'16','unpost','',0,'2015-08-28 14:52:56',0,8),(14,'10','sqsq','',0,'2015-09-01 05:47:13',0,1),(15,'18','test','',0,'2015-10-13 16:56:29',0,1),(16,'18','test2','',0,'2015-10-13 18:32:11',0,1),(17,'18','ssss','',0,'2015-10-13 18:32:36',0,1),(18,'18','aaaa','',0,'2015-10-13 18:33:27',0,1),(19,'18','qaqaqa','',0,'2015-10-13 18:33:46',0,1),(20,'18','dddd','',0,'2015-10-13 19:20:40',0,1),(21,'18','dsdsd','',0,'2015-10-13 19:21:17',0,1),(22,'29','test','',0,'2015-10-17 10:01:24',0,1),(23,'29','sssss','',0,'2015-10-17 10:02:57',0,1),(24,'29','sss','',0,'2015-10-17 10:03:13',0,1),(25,'29','test','',0,'2015-10-17 10:07:34',0,1),(26,'29','dsdsd','',0,'2015-10-17 10:08:51',0,1),(27,'29','qsqs','',0,'2015-10-17 10:09:54',0,1),(28,'29','azaz','',0,'2015-10-17 10:13:50',0,1),(29,'29','dsdsd','',0,'2015-10-17 10:15:44',0,1),(30,'29','sdsdsd','',0,'2015-10-17 10:18:25',0,1),(31,'29','test comment','',0,'2015-10-17 10:21:02',0,1),(32,'29','sqsq','',0,'2015-10-17 10:21:33',0,1),(33,'29','sqsqs','',0,'2015-10-17 10:21:40',0,1),(34,'29','sqsqsq','',0,'2015-10-17 10:21:48',0,1),(35,'29','sqsqsq','',0,'2015-10-17 10:22:29',0,1),(36,'29','test','',0,'2015-10-17 10:25:32',0,1),(37,'29','aaaaa','',0,'2015-10-17 10:26:39',0,1),(38,'29','sqsqsq','',0,'2015-10-17 10:27:01',0,1),(39,'18','dsdsds','',0,'2015-10-17 10:29:06',0,1),(40,'18','dsdsd','',0,'2015-10-17 10:30:21',0,1),(41,'18','sqdsds','',0,'2015-10-17 10:35:09',0,1),(42,'24','dqsd','',0,'2015-10-17 10:40:51',0,1),(43,'29','sdsdsd','',0,'2015-10-17 10:43:24',0,1),(44,'29','dsdsd','',0,'2015-10-17 10:45:49',0,1),(45,'20','sqsq','',0,'2015-10-17 11:01:01',0,1),(46,'25','ok ,n;n;n;n;n;n;','',0,'2020-03-17 21:57:49',0,18),(47,'25',';mùm;m;m;','46',1,'2020-03-17 21:58:00',0,18);
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fos_user`
--

DROP TABLE IF EXISTS `fos_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fos_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `isteacher` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_957A647992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_957A6479A0D96FBF` (`email_canonical`),
  UNIQUE KEY `UNIQ_957A6479C05FB297` (`confirmation_token`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fos_user`
--

LOCK TABLES `fos_user` WRITE;
/*!40000 ALTER TABLE `fos_user` DISABLE KEYS */;
INSERT INTO `fos_user` VALUES (1,'kpu','kpu','olivier.capuozzo@laposte.net','olivier.capuozzo@laposte.net',1,NULL,'$2y$13$OL5YTPq/7yynC6xbRQ8fkunJsrGdoTDlNx6J7jUj6HtUznYbHwW2m','2020-03-30 18:16:25',NULL,NULL,'a:1:{i:0;s:10:\"ROLE_ADMIN\";}',1),(2,'aa','aa','a@a.a','a@a.a',1,'jvb45myjwmoscscw40400goc0gwoo0k','$2y$13$jvb45myjwmoscscw40400efHBBgDnZtTdweyCyFJEZ.wWDeuAXmhe','2015-09-01 09:52:17',NULL,NULL,'a:1:{i:0;s:12:\"ROLE_MANAGER\";}',1),(3,'profZ','profz','olivier.capuozzo@ac-creteil.fr','olivier.capuozzo@ac-creteil.fr',1,'i9rije0w1e88wo0ggg0k88044ccsk80','$2y$13$i9rije0w1e88wo0ggg0k8uw6JFsSBqhJHWeoU9UfYS4kmF5cZaNmm','2015-08-24 16:21:59','ushUo7cHGHiDVm09334nK3vqwbgkYc_bkJwzik5CUk8','2015-08-29 10:34:39','a:0:{}',1),(4,'kpu2','kpu2','olivier.capuozzo@toto.fr','olivier.capuozzo@toto.fr',1,'tnmqb4j12f40co484kookcww8c8oogo','$2y$13$tnmqb4j12f40co484kookOR39lnDAEEtoZd1KHqlIcpiHX1f9KncG','2015-08-18 10:47:18',NULL,NULL,'a:0:{}',1),(5,'aaa','aaa','aaa@aa.aa','aaa@aa.aa',1,'cyoi5kfix9cks00w0s4k4o4gwwg4oc0','$2y$13$cyoi5kfix9cks00w0s4k4eYGfm.eQJBdwg.HeFp4xpUcxpTaKVEqO','2015-08-18 11:58:04',NULL,NULL,'a:0:{}',0),(6,'aaaa','aaaa','aaaa@aa.aa','aaaa@aa.aa',1,'2nihq13lw2o0so8gs8o4w0okc08ow0k','$2y$13$2nihq13lw2o0so8gs8o4wu65u9aryeGGvC8hZcPaVmfwJueZaKy1y','2015-08-18 11:59:26',NULL,NULL,'a:0:{}',1),(7,'a5','a5','a5@aa.aa','a5@aa.aa',1,'hdg7zi6dgpw0g00gs804oc4kwowsko8','$2y$13$hdg7zi6dgpw0g00gs804oO4XzkXOOpEvB5voQji/X5lnoDeoFzBUi','2015-08-18 12:08:59',NULL,NULL,'a:0:{}',0),(8,'a6','a6','a6@aa.aa','a6@aa.aa',1,'7thctm6zon0gcgogow88wkgw4c0g0co','$2y$13$7thctm6zon0gcgogow88we9vtI.Jybp2DE3rfx29ueQbe3EAlhSs6','2015-09-19 10:04:13',NULL,NULL,'a:0:{}',1),(10,'nicole','nicole','nicole.capuozzo@gmail.com','nicole.capuozzo@gmail.com',0,'3p4yo4ofrq2o4o8wsg4g8ok8o4ks8c4','$2y$13$3p4yo4ofrq2o4o8wsg4g8esQxHXq.ixus0Mp04F2dYojHHrvMooge',NULL,'4akpLPmgqKR-yAz8j_O7F1pGhhhgfqE52tTTOiWZ7Gc',NULL,'a:0:{}',0),(11,'nic','nic','nicole.capuozzo@laposte.net','nicole.capuozzo@laposte.net',0,'7dj24jxb6eosgs80s0w840g4c848k4w','$2y$13$7dj24jxb6eosgs80s0w84uASx6cwDCBR.UBLJoNpHLin8imI8.uva',NULL,'Nt5Pnu5-vqjCh5qEep3E5cL2K7vGrDshU1dMQopEXkc',NULL,'a:0:{}',0),(12,'titi2','titi2','olivier.capuozzo@toto.frs','olivier.capuozzo@toto.frs',0,'4m8tn0ygw7mso4ossww4wg44sc0gk8c','$2y$13$4m8tn0ygw7mso4ossww4we1oNfPpurSer3fcI5bulwBDsjsgZU3NK',NULL,'oY7rg8j0t-gaRJzbtLRNN0U7jGnZNN25VhfT632QsOs',NULL,'a:0:{}',1),(14,'kpu4','kpu4','olivier.capuozzo@reseaucerta.org2','olivier.capuozzo@reseaucerta.org2',0,'ev0em3bs00g84o8kggoo04sscw40c84','$2y$13$ev0em3bs00g84o8kggoo0uXIMa5MxinGIuIWb2gOc2CSbTGfZg3y6',NULL,'8csqwqNWK7WS9qB-lmGgeE1UqcS0NgWtysqqqfPDnd0',NULL,'a:0:{}',0),(16,'kpu7','kpu7','olivier.capuozzo@reseaucerta.org','olivier.capuozzo@reseaucerta.org',0,'abgzpsnx9sg8c4kg8og4sgwc4s4gkkk','$2y$13$abgzpsnx9sg8c4kg8og4se5PLKGgz.Q4LqNjdXpBc32LqM.Mdnbse',NULL,'gi2NxeLMf3WMcThLNAFvSrd2OhIswZ8U_ifFvRNh2j8',NULL,'a:0:{}',0),(17,'profCreteil','profcreteil','ocapuozzo@ac-creteil.fr','ocapuozzo@ac-creteil.fr',0,'i7pgv6pbarwocwsgw4k8k08kkgk40ok','$2y$13$i7pgv6pbarwocwsgw4k8kuURZDQSRAME0msIOnLW5HV6Yg1/LH2YO',NULL,'xcUrTfXq-0G7e_eHuVX2PFIjDzsLZARjca9ZiZOV2Z8',NULL,'a:0:{}',0),(18,'okpu','okpu','olivier.capuozzo@gmail.com','olivier.capuozzo@gmail.com',1,NULL,'$2y$13$Y.r4/Q7NETr9cV87zLPuDu5carqc8/CA6.mKRbB8hkGBQfsUrrUi2','2020-03-30 18:13:03',NULL,NULL,'a:0:{}',0);
/*!40000 ALTER TABLE `fos_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sentence` longtext COLLATE utf8_unicode_ci NOT NULL,
  `datecrea` datetime NOT NULL,
  `codesigners` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idScope` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `designer` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `idClassroom` int(11) DEFAULT NULL,
  `datepub` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B6F7494E176B16CE` (`idScope`),
  KEY `IDX_B6F7494EB5971BCB` (`idClassroom`),
  CONSTRAINT `FK_B6F7494E176B16CE` FOREIGN KEY (`idScope`) REFERENCES `scope` (`id`),
  CONSTRAINT `FK_B6F7494EB5971BCB` FOREIGN KEY (`idClassroom`) REFERENCES `classroom` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question`
--

LOCK TABLES `question` WRITE;
/*!40000 ALTER TABLE `question` DISABLE KEYS */;
INSERT INTO `question` VALUES (37,'<p>soit les propositions suivantes :</p>\r\n<ul>\r\n<li>Une personne (P) peut attraper plusieurs maladies (M)</li>\r\n<li>Sur le carnet de sant&eacute; (C) d\'un personne sont not&eacute;s les vaccinations (V) et leur date d\'injection (DI)</li>\r\n<li>Un vaccin pr&eacute;vient d\'une maladie</li>\r\n<li>La dur&eacute;e moyenne d\'infection d\'une maladie (D) d&eacute;pend de la maladie.</li>\r\n<li>Un personne atteinte d\'une maladie des os peut &eacute;galement attraper une maladie pulmonaire</li>\r\n</ul>\r\n<p>Validez les d&eacute;pendances suivants (le respect des qualit&eacute;s direct, &eacute;l&eacute;mentaire, r&eacute;ciprocit&eacute; ne sont pas exig&eacute;es) :&nbsp;</p>\r\n<p>&nbsp;</p>','2020-03-25 14:06:06',NULL,43,'analyse-1','okpu',21,'2020-03-25 14:44:34'),(38,'<p>soit les propositions suivantes :</p>\r\n<ul>\r\n<li>Une personne (P) peut attraper plusieurs maladies (M)</li>\r\n<li>Sur le carnet de sant&eacute; (C) d\'un personne sont not&eacute;s les vaccinations (V) et leur date d\'injection (DI)</li>\r\n<li>Un vaccin pr&eacute;vient d\'une maladie</li>\r\n<li>La dur&eacute;e moyenne d\'infection d\'une maladie (D) d&eacute;pend de la maladie.</li>\r\n<li>Un personne atteinte d\'une maladie des os peut &eacute;galement attraper une maladie pulmonaire</li>\r\n</ul>\r\n<p>Validez les d&eacute;pendances suivants (le respect des qualit&eacute;s direct, &eacute;l&eacute;mentaire, r&eacute;ciprocit&eacute; ne sont pas exig&eacute;es) :&nbsp;</p>\r\n<p>&nbsp;</p>','2020-03-25 14:44:20',NULL,43,'analyse-2','okpu',21,'2020-03-25 14:44:20'),(40,'<p>soit les propositions suivantes :</p>\r\n<ul>\r\n<li>Une personne (P) peut attraper plusieurs maladies (M)</li>\r\n<li>Sur le carnet de sant&eacute; (C) d\'un personne sont not&eacute;s les vaccinations (V) et leur date d\'injection (DI)</li>\r\n<li>Un vaccin pr&eacute;vient d\'une maladie</li>\r\n<li>La dur&eacute;e moyenne d\'infection d\'une maladie (D) d&eacute;pend de la maladie.</li>\r\n<li>Un personne atteinte d\'une maladie des os peut &eacute;galement attraper une maladie pulmonaire</li>\r\n</ul>\r\n<p>Validez les d&eacute;pendances suivants (le respect des qualit&eacute;s direct, &eacute;l&eacute;mentaire, r&eacute;ciprocit&eacute; ne sont pas exig&eacute;es) :&nbsp;</p>\r\n<p>&nbsp;</p>','2020-03-25 14:50:29',NULL,43,'analyse-3','okpu',21,'2020-03-25 14:50:29'),(44,'<p>soit les proposition suivantes</p>\r\n<ul>\r\n<li>Le chien (C) est un animal (A)</li>\r\n<li>Un chien peut attraper plusieurs maladies (M), un humain (H) &eacute;galement</li>\r\n<li>La dur&eacute;e moyenne d\'infection d\'une maladie (D) d&eacute;pend de la maladie.</li>\r\n</ul>\r\n<p>La proposition suivante : D --&gt;&gt; M</p>','2020-03-25 15:19:41',NULL,43,'analyse-7','okpu',21,'2020-03-25 15:19:41'),(45,'<p>soit les propositions suivantes</p>\r\n<ul>\r\n<li>Le chien (C) est un animal (A)</li>\r\n<li>Un chien peut attraper plusieurs maladies (M), un humain (H) &eacute;galement</li>\r\n<li>La dur&eacute;e moyenne d\'infection d\'une maladie (D) d&eacute;pend de la maladie.</li>\r\n</ul>\r\n<p>Si on consid&egrave;re que certaines maladies sont propres aux animaux et d\'autres aux humains alors</p>\r\n<p>les maladies communes aux deux esp&egrave;ces peuvent &ecirc;tre repr&eacute;sent&eacute;e par le relation :&nbsp;H, A --&gt;&gt; M&nbsp;</p>\r\n<p>Cette relation est-elle :</p>','2020-03-25 15:29:52',NULL,43,'analyse-8','okpu',21,'2020-03-25 15:29:52');
/*!40000 ALTER TABLE `question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rating`
--

DROP TABLE IF EXISTS `rating`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_question` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `value` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `only_one_vote` (`id_user`,`id_question`),
  KEY `IDX_5A108564E62CA5DB` (`id_question`),
  KEY `IDX_5A1085646B3CA4B` (`id_user`),
  CONSTRAINT `FK_5A1085646B3CA4B` FOREIGN KEY (`id_user`) REFERENCES `fos_user` (`id`),
  CONSTRAINT `FK_5A108564E62CA5DB` FOREIGN KEY (`id_question`) REFERENCES `question` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rating`
--

LOCK TABLES `rating` WRITE;
/*!40000 ALTER TABLE `rating` DISABLE KEYS */;
/*!40000 ALTER TABLE `rating` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `response`
--

DROP TABLE IF EXISTS `response`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `response` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `proposition` longtext COLLATE utf8_unicode_ci NOT NULL,
  `feedback` longtext COLLATE utf8_unicode_ci NOT NULL,
  `value` double NOT NULL,
  `idQuestion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3E7B0BFBE5546315` (`idQuestion`),
  CONSTRAINT `FK_3E7B0BFBE5546315` FOREIGN KEY (`idQuestion`) REFERENCES `question` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `response`
--

LOCK TABLES `response` WRITE;
/*!40000 ALTER TABLE `response` DISABLE KEYS */;
INSERT INTO `response` VALUES (57,'<p>C --&gt; V</p>','Non, sur un carnet de santé plusieurs vaccins peuvent y être rapportés !',-2,37),(58,'<p>C --&gt; P</p>','Oui, un carnet de santé est propre à un individu',1,37),(59,'<p>P --&gt;&nbsp;M</p>','Non, une personne peut être atteinte de plusieurs maladies',-2,37),(60,'<p>DI -&gt;&gt; P</p>','Oui, à une date d\'injection donnée, plusieurs personnes peuvent avoir reçu un vaccin.',1,38),(61,'<p>C --&gt; DI</p>','Non, un carnet peut faire l\'objet de plusieurs date d\'injections',-2,38),(62,'<p>P,C,V --&gt;DI</p>','Oui, bien que non directe',1,38),(63,'<p>V --&gt; M, D</p>','OUI, à un vaccin correspond (dans le texte) une seule maladie et sa durée d\'infection',1,40),(64,'<p>V --&gt; M, D, DI</p>','Non, la date d\'injection n\'est pas unique',-2,40),(65,'<p>D --&gt; M</p>','Non, une durée d\'infection peut caractériser plus d\'une maladie',-2,40),(76,'<p>Est bonne</p>','Car une même durée d\'infection peut être partagée par plusieurs maladies',1,44),(77,'<p>N\'est pas correcte</p>','Faux - voir feedback de l\'autre proposition',-2,44),(78,'<p>&eacute;l&eacute;mentaire ?</p>','OUI, car les deux sources sont nécessaires, d\'après la proposition',1,45),(79,'<p>directe ?</p>','NON, car on peut déduire la relation par l\'intersection de H -->> M  et A -->> M',-2,45),(80,'<p>multivalu&eacute;e ?</p>','OUI, il peut y avoir plus d\'une maladie commune aux deux espèces',1,45),(81,'<p>DI --&gt;&gt; V</p>','Oui, à une date d\'injection peut correspondre plusieurs vaccinations',1,38),(82,'<p>M --&gt; D</p>','Oui, dans le texte des propositions',1,40),(83,'<p>D --&gt;&gt; M</p>','Oui, plusieurs maladies peuvent avoir la même durée d\'infection',1,40);
/*!40000 ALTER TABLE `response` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scope`
--

DROP TABLE IF EXISTS `scope`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scope` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `id_classroom` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AF55D3C9488CBA` (`id_classroom`),
  CONSTRAINT `FK_AF55D3C9488CBA` FOREIGN KEY (`id_classroom`) REFERENCES `classroom` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scope`
--

LOCK TABLES `scope` WRITE;
/*!40000 ALTER TABLE `scope` DISABLE KEYS */;
INSERT INTO `scope` VALUES (1,'Prog. Initiation',NULL),(2,'Prog. Objet',NULL),(3,'Prog. Web',NULL),(4,'ssss',NULL),(5,'sqsqsq',NULL),(6,'sqsqsqs',NULL),(7,'test',NULL),(8,'sqsqs',NULL),(9,'qsqsq',NULL),(10,'titi',NULL),(42,'Test Unitaire',21),(43,'Analyse',21);
/*!40000 ALTER TABLE `scope` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `thread`
--

DROP TABLE IF EXISTS `thread`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `thread` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permalink` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_commentable` tinyint(1) NOT NULL,
  `num_comments` int(11) NOT NULL,
  `last_comment_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `thread`
--

LOCK TABLES `thread` WRITE;
/*!40000 ALTER TABLE `thread` DISABLE KEYS */;
INSERT INTO `thread` VALUES ('1','http://quizbe.dev/web/app_dev.php/quiz/fr/question/1',1,0,NULL),('10','http://quizbe.dev/web/app_dev.php/quiz/fr/question/10',1,1,'2015-09-01 05:47:13'),('11','http://localhost:8000/quiz/fr/question/11',1,0,NULL),('12','http://localhost:8000/quiz/fr/question/12',1,0,NULL),('13','http://quizbe.dev/web/app_dev.php/quiz/fr/question/13',1,0,NULL),('14','http://quizbe.dev/web/app_dev.php/quiz/fr/question/14',1,0,NULL),('15','http://quizbe.dev/web/app_dev.php/quiz/fr/question/15',1,0,NULL),('16','http://quizbe.dev/web/app_dev.php/quiz/fr/question/16',1,1,'2015-08-28 14:52:56'),('17','http://quizbe.dev/web/app_dev.php/quiz/fr/question/17',1,0,NULL),('18','http://quizbe.dev/web/app_dev.php/quiz/fr/question/18',1,10,'2015-10-17 10:35:09'),('19','http://quizbe.dev/web/app_dev.php/quiz/fr/question/19',1,0,NULL),('2','http://quizbe.dev/web/app_dev.php/question/2',0,0,NULL),('20','http://quizbe.dev/web/app_dev.php/quiz/fr/question/20',1,1,'2015-10-17 11:01:01'),('23','http://quizbe.dev/web/app_dev.php/quiz/fr/question/23',1,0,NULL),('24','http://quizbe.dev/web/app_dev.php/quiz/fr/question/24',1,1,'2015-10-17 10:40:51'),('25','http://quizbe.dev/web/app_dev.php/quiz/fr/question/25',1,2,'2020-03-17 21:58:00'),('26','http://127.0.0.1:8000/quiz/fr/question/26',1,0,NULL),('28','http://quizbe.dev/web/app_dev.php/quiz/fr/question/28',1,0,NULL),('29','http://quizbe.dev/web/app_dev.php/quiz/fr/question/29',1,19,'2015-10-17 10:45:49'),('3','http://quizbe.dev/web/app_dev.php/question/3',1,1,'2015-08-26 22:53:30'),('36','http://localhost:8000/quiz/fr/question/36',1,0,NULL),('37','http://127.0.0.1:8000/quiz/fr/question/37',1,0,NULL),('38','http://127.0.0.1:8000/quiz/fr/question/38',1,0,NULL),('39','http://127.0.0.1:8000/quiz/fr/question/39',1,0,NULL),('4','http://quizbe.dev/web/app_dev.php/question/4',1,0,NULL),('40','http://127.0.0.1:8000/quiz/fr/question/40',1,0,NULL),('41','http://127.0.0.1:8000/quiz/fr/question/41',1,0,NULL),('44','http://127.0.0.1:8000/quiz/fr/question/44',1,0,NULL),('45','http://127.0.0.1:8000/quiz/fr/question/45',1,0,NULL),('5','http://quizbe.dev/web/app_dev.php/question/5',1,4,'2015-08-26 22:52:11'),('6','http://quizbe.dev/web/app_dev.php/question/6',1,7,'2015-08-27 09:31:52'),('7','http://quizbe.dev/web/app_dev.php/quiz/fr/question/7',1,0,NULL),('8','http://quizbe.dev/web/app_dev.php/quiz/fr/question/8',1,0,NULL);
/*!40000 ALTER TABLE `thread` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_classroom`
--

DROP TABLE IF EXISTS `user_classroom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_classroom` (
  `user_id` int(11) NOT NULL,
  `classroom_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`classroom_id`),
  KEY `IDX_499DBD79A76ED395` (`user_id`),
  KEY `IDX_499DBD796278D5A8` (`classroom_id`),
  CONSTRAINT `FK_499DBD796278D5A8` FOREIGN KEY (`classroom_id`) REFERENCES `classroom` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_499DBD79A76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_classroom`
--

LOCK TABLES `user_classroom` WRITE;
/*!40000 ALTER TABLE `user_classroom` DISABLE KEYS */;
INSERT INTO `user_classroom` VALUES (1,21),(18,21);
/*!40000 ALTER TABLE `user_classroom` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-03-30 18:17:35
