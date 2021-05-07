-- MySQL dump 10.13  Distrib 5.7.25, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: abacus_classes
-- ------------------------------------------------------
-- Server version	5.5.5-10.3.16-MariaDB


DROP DATABASE IF EXISTS `abacus_classes`;
CREATE DATABASE  `abacus_classes`;
USE `abacus_classes`;

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
-- Table structure for table `auth_tokens`
--

DROP TABLE IF EXISTS `auth_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_tokens` (
  `id` int(11) NOT NULL,
  `selector` char(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token` char(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_tokens`
--

LOCK TABLES `auth_tokens` WRITE;
/*!40000 ALTER TABLE `auth_tokens` DISABLE KEYS */;
INSERT INTO `auth_tokens` VALUES (0,'Kj5Z3QilD/h5','e0f387359d3170a92d29d09fb788fbe2c7b69363be770619600f3e869f1aa591','abcde@gmail.com','2020-10-16 06:26:45'),(0,'F6J3GW1JiOyb','1d7b0f9fa3f3b68c8d0862afc76cdde1777b3207cbe73370cd2842978024c545','abcd@gmail.com','2020-10-20 10:47:52');
/*!40000 ALTER TABLE `auth_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `batch`
--

DROP TABLE IF EXISTS `batch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `batch` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `level` int(1) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `day` enum('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') DEFAULT NULL,
  `timing` time DEFAULT NULL,
  `teacher_id` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_id` (`teacher_id`),
  CONSTRAINT `Batch_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batch`
--

LOCK TABLES `batch` WRITE;
/*!40000 ALTER TABLE `batch` DISABLE KEYS */;
INSERT INTO `batch` VALUES (1,3,'2019-05-23','Monday','09:00:00',4),(2,4,'2019-04-14','Tuesday','10:00:00',1),(4,5,'2019-06-01','Thursday','18:30:00',3),(5,8,'2019-06-01','Friday','18:00:00',5),(6,5,'2019-01-23','Saturday','09:00:00',6),(7,3,'2019-08-10','Sunday','07:00:00',2),(8,3,'2019-08-06','Sunday','09:00:00',1),(11,6,'2019-10-21','Monday','22:30:00',5);
/*!40000 ALTER TABLE `batch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marks`
--

DROP TABLE IF EXISTS `marks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `marks` (
  `Marks` int(4) DEFAULT NULL,
  `test_id` int(5) DEFAULT NULL,
  `student_id` int(4) DEFAULT NULL,
  KEY `test_id` (`test_id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `Marks_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `test` (`id`),
  CONSTRAINT `Marks_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marks`
--

LOCK TABLES `marks` WRITE;
/*!40000 ALTER TABLE `marks` DISABLE KEYS */;
INSERT INTO `marks` VALUES (18,1,1),(15,1,2),(37,2,1),(40,2,2),(20,3,1),(15,3,2);
/*!40000 ALTER TABLE `marks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(320) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `batch_id` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `batch_id` (`batch_id`),
  CONSTRAINT `Student_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `batch` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` VALUES (1,'Yatharth N','1234987654','11a@gmail.com','854fa3747209ca8f95aae3e6c715331404c3f3998d5a978d6f353ed4f7382ef32dcd409198de63fea47c1b3bfbc122a3ce70a55517dea05efad36cf1f7cf0a9b',1),(2,'Divya','7738880377','divykjain@gmail.com','81226a47bcbe4080212a5bf8fac696e575584edbe388ffe30f6737d0dcc6f0ad9cecc70504fce83563f9815105006f7f625f7d577bdf91ac85719cc838aa02b7',1),(3,'Smit','1234967654','13a@gmail.com','9dccae847c30432358d313f3b4ff81a9ec2fc3d2302e974a272c7ccbc8f1660d0486cc97e5a8d8ad569f324aa5f10ee43d2b18715c167e256622fce197f2051a',2),(4,'Vinayak','2234987654','13a@gmail.com','453020873e681d72412c5fcb567fbcd32a44d7f21b4dee9c97aec9d28c45870a78a91981a2ed8f10753d79659e5782caf052b545c47be8a63871ad20e490ef8e',2),(5,'Usha','1334987654','55a@gmail.com','2ba94baff6ea49faa345803934911ca0526bf2368c799f375e4115f3bca990cd2c02769237b90381d80cd97db517e65c41555fc69911f6fff4f1a853e05b206c',7),(6,'Rajvi','5534987654','19a@gmail.com','f901c3fe6b301e34de95e410b4f244c4ecb10d3ef7013902f884ca9df52f153818201687a1500f152f29f506ad68a80c25cbccbbb4a73da2ad6d40adfe105f41',7),(7,'Riddhi','1234987654','41a@gmail.com','854fa3747209ca8f95aae3e6c715331404c3f3998d5a978d6f353ed4f7382ef32dcd409198de63fea47c1b3bfbc122a3ce70a55517dea05efad36cf1f7cf0a9b',4),(8,'Krishna','1236687654','99a@gmail.com','db58d2aa111b3273ca3f5538db91f935998338a891fcffad1aebb07efd72bf0a5ca964b16073cdaa06b27cba270da60f4b8134477006011fbc1436908ae63e90',4),(9,'Shweta','1234778764','91a@gmail.com','ea4de71d65a98953f13adb06384889ad66ac4f068562babf036fd6d62dac450a994a289b67d99eb745667f566bc690221c62cb71570ed3f5e53916021c58a951',8),(10,'Janvi','1444987654','991a@gmail.com','67d622f940171442e599b598ae794ed5fe8e920bae09b750837c697298042b16b1b0d0eb154b910416a27fa790e52519651ea3fea0cab99c6bbea1853e528389',8),(11,'Sakshi','5534987654','00a@gmail.com','f901c3fe6b301e34de95e410b4f244c4ecb10d3ef7013902f884ca9df52f153818201687a1500f152f29f506ad68a80c25cbccbbb4a73da2ad6d40adfe105f41',6),(12,'Vyom','1238887654','78a@gmail.com','c7bbea951af8032a50daa41ede1cfd0c0dd062ddbeffd5acd4d2c9c5fabbb3ff454a15483226a65ca293aaf95c39c0fdd361268ad10e64b17c9d6eece9c1858c',6);
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher`
--

DROP TABLE IF EXISTS `teacher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teacher` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(320) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher`
--

LOCK TABLES `teacher` WRITE;
/*!40000 ALTER TABLE `teacher` DISABLE KEYS */;
INSERT INTO `teacher` VALUES (1,'Trupti B','1234567891','abcd@gmail.com','51527781615bc41e3676005155a8a24311e49c95fd6ffb131a0d35688d85ab789eebcff62ba27b5bfbfccd29ef9d211d00fd3758688ac3f93d3129bd46c373bb'),(2,'Tasneem','1234567819','abc@gmail.com','08fb470055da63ec7126cf92a6395ffa673c897ebf6a8114138e86e483063d0f3eb0395d0ad7c2c9f59e9fa12e20542b54ba0b83fd1e4334de057ed256c1fdde'),(3,'Rashi','1234567198','ab@gmail.com','ce4e7f15b5ae4d13284618d3ec763645c43aa54d645dfc7c87d62abcea70455802ee18a3fc913bed99982405922df6efd073955cb6e7ab852921a5fc7ba1d069'),(4,'Sarita','9876543210','sa@somaiya.edu','ecfae1db70aa30c43ecc66e53de541e6d91d2bec69b649d5ddf4b6a7f2c485f3cc29c1dfcc710f6a2119c1e38b27f1f55594fea80bb8925881fd20324c0e5d52'),(5,'Anita','5678985567','abc@gmail.com','0bf520305c9f3e65efa2bdd2332d31c89faf794944a88a1b74310351f3bface003fa89915e379e54423bccd40e1be5b68e17fee4cb8444826400120cf86944a7'),(6,'Vidya','6789876543','vidya@yahoo.com','a8c1d7f06fa4e3f8e2560dcfac834c92471980a27b051fb61fb6f80524d57278589cb897ebf14db29f0915cf1bdae6a9c60e2b0041269cb64043112f23a47602');
/*!40000 ALTER TABLE `teacher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test`
--

DROP TABLE IF EXISTS `test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `testtype_id` int(4) DEFAULT NULL,
  `batch_id` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `testtype_id` (`testtype_id`),
  KEY `batch_id` (`batch_id`),
  CONSTRAINT `Test_ibfk_1` FOREIGN KEY (`testtype_id`) REFERENCES `testtype` (`id`),
  CONSTRAINT `Test_ibfk_2` FOREIGN KEY (`batch_id`) REFERENCES `batch` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test`
--

LOCK TABLES `test` WRITE;
/*!40000 ALTER TABLE `test` DISABLE KEYS */;
INSERT INTO `test` VALUES (1,'2019-08-23',2,1),(2,'2019-09-13',4,1),(3,'2019-10-18',3,1);
/*!40000 ALTER TABLE `test` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testtype`
--

DROP TABLE IF EXISTS `testtype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testtype` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `max_Marks` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testtype`
--

LOCK TABLES `testtype` WRITE;
/*!40000 ALTER TABLE `testtype` DISABLE KEYS */;
INSERT INTO `testtype` VALUES (2,'UT1',20),(3,'UT2',20),(4,'MidTerm',50),(5,'Finals',80);
/*!40000 ALTER TABLE `testtype` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-10-21 15:33:48
