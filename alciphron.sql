-- MySQL dump 10.16  Distrib 10.1.28-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: alciphron
-- ------------------------------------------------------
-- Server version	10.1.28-MariaDB

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
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `commentable_id` int(10) unsigned NOT NULL,
  `commentable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_commentable_id_commentable_type_index` (`commentable_id`,`commentable_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `field_observation_photo`
--

DROP TABLE IF EXISTS `field_observation_photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `field_observation_photo` (
  `field_observation_id` bigint(20) unsigned NOT NULL,
  `photo_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`field_observation_id`,`photo_id`),
  KEY `field_observation_photo_photo_id_foreign` (`photo_id`),
  CONSTRAINT `field_observation_photo_field_observation_id_foreign` FOREIGN KEY (`field_observation_id`) REFERENCES `field_observations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `field_observation_photo_photo_id_foreign` FOREIGN KEY (`photo_id`) REFERENCES `photos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `field_observation_photo`
--

LOCK TABLES `field_observation_photo` WRITE;
/*!40000 ALTER TABLE `field_observation_photo` DISABLE KEYS */;
/*!40000 ALTER TABLE `field_observation_photo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `field_observations`
--

DROP TABLE IF EXISTS `field_observations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `field_observations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxon_suggestion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dynamic_fields` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `field_observations`
--

LOCK TABLES `field_observations` WRITE;
/*!40000 ALTER TABLE `field_observations` DISABLE KEYS */;
/*!40000 ALTER TABLE `field_observations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2016_06_01_000001_create_oauth_auth_codes_table',1),(4,'2016_06_01_000002_create_oauth_access_tokens_table',1),(5,'2016_06_01_000003_create_oauth_refresh_tokens_table',1),(6,'2016_06_01_000004_create_oauth_clients_table',1),(7,'2016_06_01_000005_create_oauth_personal_access_clients_table',1),(8,'2017_07_07_112745_create_taxa_table',1),(9,'2017_07_07_132137_create_observations_table',1),(10,'2017_07_14_065330_create_taxon_ancestors_table',1),(11,'2017_07_16_200431_create_photos_table',1),(12,'2017_07_29_092558_create_field_observations_table',1),(13,'2017_07_29_112006_create_comments_table',1),(14,'2017_07_30_143122_create_field_observation_photo_table',1),(15,'2017_11_13_224839_create_view_groups_table',2),(16,'2017_11_13_224938_create_view_group_taxon_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_access_tokens`
--

LOCK TABLES `oauth_access_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_auth_codes`
--

LOCK TABLES `oauth_auth_codes` WRITE;
/*!40000 ALTER TABLE `oauth_auth_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_clients`
--

LOCK TABLES `oauth_clients` WRITE;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_personal_access_clients_client_id_index` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_personal_access_clients`
--

LOCK TABLES `oauth_personal_access_clients` WRITE;
/*!40000 ALTER TABLE `oauth_personal_access_clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_personal_access_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_refresh_tokens`
--

LOCK TABLES `oauth_refresh_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `observations`
--

DROP TABLE IF EXISTS `observations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `observations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `taxon_id` int(10) unsigned DEFAULT NULL,
  `year` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `day` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` double(15,12) NOT NULL,
  `longitude` double(15,12) NOT NULL,
  `accuracy` int(10) unsigned NOT NULL DEFAULT '1',
  `mgrs10k` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `elevation` smallint(6) NOT NULL DEFAULT '0',
  `details_id` int(10) unsigned NOT NULL,
  `details_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_by_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `observations_details_id_details_type_index` (`details_id`,`details_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `observations`
--

LOCK TABLES `observations` WRITE;
/*!40000 ALTER TABLE `observations` DISABLE KEYS */;
/*!40000 ALTER TABLE `observations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `photos`
--

DROP TABLE IF EXISTS `photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `photos`
--

LOCK TABLES `photos` WRITE;
/*!40000 ALTER TABLE `photos` DISABLE KEYS */;
/*!40000 ALTER TABLE `photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taxa`
--

DROP TABLE IF EXISTS `taxa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `taxa` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ancestry` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_level` int(10) unsigned NOT NULL,
  `fe_old_id` int(10) unsigned DEFAULT NULL,
  `fe_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `taxa_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taxa`
--

LOCK TABLES `taxa` WRITE;
/*!40000 ALTER TABLE `taxa` DISABLE KEYS */;
INSERT INTO `taxa` VALUES (1,NULL,'Animalia',NULL,70,NULL,NULL,'2017-11-12 12:04:11','2017-11-12 12:04:11'),(2,1,'Chordata','1',60,NULL,NULL,'2017-11-12 12:06:04','2017-11-12 12:06:04'),(3,1,'Arthropoda','1',60,NULL,NULL,'2017-11-12 12:06:24','2017-11-12 12:06:24'),(4,2,'Reptilia','1/2',50,NULL,NULL,'2017-11-12 12:07:36','2017-11-12 12:07:36'),(5,2,'Amphibia','1/2',50,NULL,NULL,'2017-11-12 12:08:28','2017-11-12 12:08:28'),(6,4,'Squamata','1/2/4',40,NULL,NULL,'2017-11-12 12:10:02','2017-11-12 12:10:02'),(7,4,'Testudines','1/2/4',40,NULL,NULL,'2017-11-12 12:15:03','2017-11-12 12:15:03'),(8,5,'Anura','1/2/5',40,NULL,NULL,'2017-11-12 12:15:36','2017-11-12 12:15:36'),(9,5,'Caudata','1/2/5',40,NULL,NULL,'2017-11-12 12:15:58','2017-11-12 12:15:58'),(10,6,'Anguidae','1/2/4/6',30,NULL,NULL,'2017-11-12 12:20:09','2017-11-12 12:20:09'),(11,6,'Colubridae','1/2/4/6',30,NULL,NULL,'2017-11-12 12:20:46','2017-11-12 12:20:46'),(12,6,'Gekkonidae','1/2/4/6',30,NULL,NULL,'2017-11-12 12:21:30','2017-11-12 12:21:30'),(13,6,'Lacertidae','1/2/4/6',30,NULL,NULL,'2017-11-12 12:21:59','2017-11-12 12:21:59'),(14,6,'Scincidae','1/2/4/6',30,NULL,NULL,'2017-11-12 12:23:16','2017-11-12 12:23:16'),(15,8,'Bombinatoridae','1/2/5/8',30,NULL,NULL,'2017-11-12 12:24:03','2017-11-12 12:24:03'),(16,8,'Bufonidae','1/2/5/8',30,NULL,NULL,'2017-11-12 12:24:22','2017-11-12 12:24:22'),(17,8,'Hylidae','1/2/5/8',30,NULL,NULL,'2017-11-12 12:24:50','2017-11-12 12:24:50'),(18,7,'Emydidae','1/2/4/7',30,NULL,NULL,'2017-11-12 12:25:06','2017-11-12 12:25:06'),(19,7,'Testudinidae','1/2/4/7',30,NULL,NULL,'2017-11-12 12:25:34','2017-11-12 12:25:34'),(20,6,'Viperidae','1/2/4/6',30,NULL,NULL,'2017-11-12 12:25:53','2017-11-12 12:25:53'),(21,8,'Pelobatidae','1/2/5/8',30,NULL,NULL,'2017-11-12 12:26:18','2017-11-12 12:26:18'),(22,8,'Ranidae','1/2/5/8',30,NULL,NULL,'2017-11-12 12:26:44','2017-11-12 12:26:44'),(23,9,'Salamandridae','1/2/5/9',30,NULL,NULL,'2017-11-12 12:27:08','2017-11-12 12:27:08'),(24,10,'Anguis','1/2/4/6/10',20,NULL,NULL,'2017-11-12 12:27:30','2017-11-12 12:27:30'),(25,15,'Bombina','1/2/5/8/15',20,NULL,NULL,'2017-11-12 12:27:48','2017-11-12 12:27:48'),(26,16,'Bufo','1/2/5/8/16',20,NULL,NULL,'2017-11-12 12:28:02','2017-11-12 12:28:02'),(27,16,'Pseudepidalea','1/2/5/8/16',20,NULL,NULL,'2017-11-12 12:28:29','2017-11-12 12:28:29'),(28,11,'Coronella','1/2/4/6/11',20,NULL,NULL,'2017-11-12 12:28:51','2017-11-12 12:28:51'),(29,11,'Dolichophis','1/2/4/6/11',20,NULL,NULL,'2017-11-12 12:29:20','2017-11-12 12:29:20'),(30,11,'Elaphe','1/2/4/6/11',20,NULL,NULL,'2017-11-12 12:29:39','2017-11-12 12:29:39'),(31,11,'Natrix','1/2/4/6/11',20,NULL,NULL,'2017-11-12 12:29:53','2017-11-12 12:29:53'),(32,11,'Platyceps','1/2/4/6/11',20,NULL,NULL,'2017-11-12 12:30:06','2017-11-12 12:30:06'),(33,11,'Zamenis','1/2/4/6/11',20,NULL,NULL,'2017-11-12 12:30:20','2017-11-12 12:30:20'),(34,18,'Emys','1/2/4/7/18',20,NULL,NULL,'2017-11-12 12:30:38','2017-11-12 12:30:38'),(35,18,'Trachemys','1/2/4/7/18',20,NULL,NULL,'2017-11-12 12:30:56','2017-11-12 12:30:56'),(36,12,'Cyrtopodion','1/2/4/6/12',20,NULL,NULL,'2017-11-12 12:31:14','2017-11-12 12:31:14'),(37,17,'Hyla','1/2/5/8/17',20,NULL,NULL,'2017-11-12 12:31:39','2017-11-12 12:31:39'),(38,13,'Algyroides','1/2/4/6/13',20,NULL,NULL,'2017-11-12 12:31:52','2017-11-12 12:31:52'),(39,13,'Darevskia','1/2/4/6/13',20,NULL,NULL,'2017-11-12 12:32:10','2017-11-12 12:32:10'),(40,13,'Lacerta','1/2/4/6/13',20,NULL,NULL,'2017-11-12 12:32:27','2017-11-12 12:32:27'),(41,13,'Podarcis','1/2/4/6/13',20,NULL,NULL,'2017-11-12 12:32:41','2017-11-12 12:32:41'),(42,13,'Zootoca','1/2/4/6/13',20,NULL,NULL,'2017-11-12 12:32:55','2017-11-12 12:32:55'),(43,21,'Pelobates','1/2/5/8/21',20,NULL,NULL,'2017-11-12 12:33:13','2017-11-12 12:33:13'),(44,22,'Pelophylax','1/2/5/8/22',20,NULL,NULL,'2017-11-12 12:33:40','2017-11-12 12:33:40'),(45,22,'Rana','1/2/5/8/22',20,NULL,NULL,'2017-11-12 12:33:56','2017-11-12 12:33:56'),(46,23,'Lissotriton','1/2/5/9/23',20,NULL,NULL,'2017-11-12 12:34:15','2017-11-12 12:34:15'),(47,23,'Mesotriton','1/2/5/9/23',20,NULL,NULL,'2017-11-12 12:34:38','2017-11-12 12:34:38'),(48,23,'Salamandra','1/2/5/9/23',20,NULL,NULL,'2017-11-12 12:34:52','2017-11-12 12:34:52'),(49,23,'Triturus','1/2/5/9/23',20,NULL,NULL,'2017-11-12 12:35:45','2017-11-12 12:35:45'),(50,14,'Ablepharus','1/2/4/6/14',20,NULL,NULL,'2017-11-12 12:35:58','2017-11-12 12:35:58'),(51,19,'Testudo','1/2/4/7/19',20,NULL,NULL,'2017-11-12 12:36:15','2017-11-12 12:36:15'),(52,20,'Vipera','1/2/4/6/20',20,NULL,NULL,'2017-11-12 12:36:31','2017-11-12 12:36:31'),(53,24,'Anguis fragilis','1/2/4/6/10/24',10,NULL,NULL,'2017-11-12 12:36:53','2017-11-12 12:37:10'),(54,25,'Bombina bombina','1/2/5/8/15/25',10,NULL,NULL,'2017-11-12 12:37:35','2017-11-12 12:37:35'),(55,25,'Bombina variegata','1/2/5/8/15/25',10,NULL,NULL,'2017-11-12 12:37:50','2017-11-12 12:37:50'),(56,26,'Bufo bufo','1/2/5/8/16/26',10,NULL,NULL,'2017-11-12 12:38:08','2017-11-12 12:38:08'),(57,27,'Pseudepidalea viridis','1/2/5/8/16/27',10,NULL,NULL,'2017-11-12 12:38:46','2017-11-12 12:38:46'),(58,28,'Coronella austriaca','1/2/4/6/11/28',10,NULL,NULL,'2017-11-12 12:39:03','2017-11-12 12:39:03'),(59,29,'Dolichophis caspius','1/2/4/6/11/29',10,NULL,NULL,'2017-11-12 12:55:02','2017-11-12 12:55:02'),(60,30,'Elaphe quatuorlineata','1/2/4/6/11/30',10,NULL,NULL,'2017-11-12 12:55:18','2017-11-12 12:55:18'),(61,31,'Natrix natrix','1/2/4/6/11/31',10,NULL,NULL,'2017-11-12 12:55:33','2017-11-12 12:55:33'),(62,31,'Natrix tessellata','1/2/4/6/11/31',10,NULL,NULL,'2017-11-12 12:56:26','2017-11-12 12:56:26'),(63,32,'Platyceps najadum','1/2/4/6/11/32',10,NULL,NULL,'2017-11-12 12:56:57','2017-11-12 12:56:57'),(64,33,'Zamenis longissimus','1/2/4/6/11/33',10,NULL,NULL,'2017-11-12 12:57:29','2017-11-12 12:57:29'),(65,34,'Emys orbicularis','1/2/4/7/18/34',10,NULL,NULL,'2017-11-12 12:57:44','2017-11-12 12:57:44'),(66,35,'Trachemys scripta','1/2/4/7/18/35',10,NULL,NULL,'2017-11-12 12:57:58','2017-11-12 12:57:58'),(67,66,'Trachemys scripta elegans','1/2/4/7/18/35/66',5,NULL,NULL,'2017-11-12 12:58:23','2017-11-12 12:58:23'),(68,36,'Cyrtopodion kotschyi','1/2/4/6/12/36',10,NULL,NULL,'2017-11-12 12:58:54','2017-11-12 12:58:54'),(69,37,'Hyla arborea','1/2/5/8/17/37',10,NULL,NULL,'2017-11-12 12:59:06','2017-11-12 12:59:06'),(70,38,'Algyroides nigropunctatus','1/2/4/6/13/38',10,NULL,NULL,'2017-11-12 12:59:21','2017-11-12 12:59:21'),(71,39,'Darevskia praticola','1/2/4/6/13/39',10,NULL,NULL,'2017-11-12 12:59:36','2017-11-12 12:59:36'),(72,40,'Lacerta agilis','1/2/4/6/13/40',10,NULL,NULL,'2017-11-12 12:59:46','2017-11-12 12:59:46'),(73,40,'Lacerta trilineata','1/2/4/6/13/40',10,NULL,NULL,'2017-11-12 12:59:58','2017-11-12 12:59:58'),(74,40,'Lacerta viridis','1/2/4/6/13/40',10,NULL,NULL,'2017-11-12 13:00:14','2017-11-12 13:00:14'),(75,41,'Podarcis erhardii','1/2/4/6/13/41',10,NULL,NULL,'2017-11-12 13:00:35','2017-11-12 13:00:35'),(76,41,'Podarcis muralis','1/2/4/6/13/41',10,NULL,NULL,'2017-11-12 13:00:47','2017-11-12 13:00:47'),(77,41,'Podarcis tauricus','1/2/4/6/13/41',10,NULL,NULL,'2017-11-12 13:01:00','2017-11-12 13:01:00'),(78,42,'Zootoca vivipara','1/2/4/6/13/42',10,NULL,NULL,'2017-11-12 13:01:15','2017-11-12 13:01:15'),(79,43,'Pelobates fuscus','1/2/5/8/21/43',10,NULL,NULL,'2017-11-12 13:01:28','2017-11-12 13:01:28'),(80,43,'Pelobates syriacus','1/2/5/8/21/43',10,NULL,NULL,'2017-11-12 13:01:45','2017-11-12 13:01:45'),(81,44,'Pelophylax esculentus','1/2/5/8/22/44',10,NULL,NULL,'2017-11-12 13:02:01','2017-11-12 13:02:01'),(82,44,'Pelophylax lessonae','1/2/5/8/22/44',10,NULL,NULL,'2017-11-12 13:02:15','2017-11-12 13:02:15'),(83,44,'Pelophylax ridibundus','1/2/5/8/22/44',10,NULL,NULL,'2017-11-12 13:02:36','2017-11-12 13:02:36'),(84,45,'Rana dalmatina','1/2/5/8/22/45',10,NULL,NULL,'2017-11-12 13:02:54','2017-11-12 13:02:54'),(85,45,'Rana graeca','1/2/5/8/22/45',10,NULL,NULL,'2017-11-12 13:03:05','2017-11-12 13:03:05'),(86,45,'Rana temporaria','1/2/5/8/22/45',10,NULL,NULL,'2017-11-12 13:03:18','2017-11-12 13:03:18'),(87,46,'Lissotriton vulgaris','1/2/5/9/23/46',10,NULL,NULL,'2017-11-12 13:04:04','2017-11-12 13:04:04'),(88,47,'Mesotriton alpestris','1/2/5/9/23/47',10,NULL,NULL,'2017-11-12 13:04:16','2017-11-12 13:04:16'),(89,48,'Salamandra atra','1/2/5/9/23/48',10,NULL,NULL,'2017-11-12 13:04:29','2017-11-12 13:04:29'),(90,48,'Salamandra salamandra','1/2/5/9/23/48',10,NULL,NULL,'2017-11-12 13:04:40','2017-11-12 13:04:40'),(91,49,'Triturus carnifex','1/2/5/9/23/49',10,NULL,NULL,'2017-11-12 13:04:52','2017-11-12 13:04:52'),(92,49,'Triturus cristatus','1/2/5/9/23/49',10,NULL,NULL,'2017-11-12 13:05:05','2017-11-12 13:05:05'),(93,49,'Triturus dobrogicus','1/2/5/9/23/49',10,NULL,NULL,'2017-11-12 13:05:18','2017-11-12 13:05:18'),(94,49,'Triturus karelinii','1/2/5/9/23/49',10,NULL,NULL,'2017-11-12 13:05:29','2017-11-12 13:05:29'),(95,50,'Ablepharus kitaibelii','1/2/4/6/14/50',10,NULL,NULL,'2017-11-12 13:05:45','2017-11-12 13:05:45'),(96,51,'Testudo graeca','1/2/4/7/19/51',10,NULL,NULL,'2017-11-12 13:05:57','2017-11-12 13:05:57'),(97,51,'Testudo hermanni','1/2/4/7/19/51',10,NULL,NULL,'2017-11-12 13:06:09','2017-11-12 13:06:09'),(98,52,'Vipera ammodytes','1/2/4/6/20/52',10,NULL,NULL,'2017-11-12 13:06:21','2017-11-12 13:06:21'),(99,52,'Vipera berus','1/2/4/6/20/52',10,NULL,NULL,'2017-11-12 13:06:35','2017-11-12 13:06:35'),(100,52,'Vipera ursinii','1/2/4/6/20/52',10,NULL,NULL,'2017-11-12 13:06:49','2017-11-12 13:06:49');
/*!40000 ALTER TABLE `taxa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taxon_ancestors`
--

DROP TABLE IF EXISTS `taxon_ancestors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `taxon_ancestors` (
  `model_id` int(10) unsigned NOT NULL,
  `ancestor_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`model_id`,`ancestor_id`),
  KEY `taxon_ancestors_ancestor_id_foreign` (`ancestor_id`),
  CONSTRAINT `taxon_ancestors_ancestor_id_foreign` FOREIGN KEY (`ancestor_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `taxon_ancestors_model_id_foreign` FOREIGN KEY (`model_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taxon_ancestors`
--

LOCK TABLES `taxon_ancestors` WRITE;
/*!40000 ALTER TABLE `taxon_ancestors` DISABLE KEYS */;
/*!40000 ALTER TABLE `taxon_ancestors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Emmett','Block','nen.zivanovic@gmail.com','$2y$10$ggP1Sto7d17MQLRmb209Aei0Yhs5A2iwz67KtJKK6nBfp.LJWKTb.','0e7Vv4wsjBQ7lWVVFB98q6LVEr5DheZMHyFL4R0J1maDVxIeg8x3tbZTlq3w','2017-11-12 13:22:01','2017-11-12 13:22:01');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `view_group_taxon`
--

DROP TABLE IF EXISTS `view_group_taxon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `view_group_taxon` (
  `view_group_id` int(10) unsigned NOT NULL,
  `taxon_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`view_group_id`,`taxon_id`),
  KEY `view_group_taxon_taxon_id_foreign` (`taxon_id`),
  CONSTRAINT `view_group_taxon_taxon_id_foreign` FOREIGN KEY (`taxon_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `view_group_taxon_view_group_id_foreign` FOREIGN KEY (`view_group_id`) REFERENCES `view_groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `view_group_taxon`
--

LOCK TABLES `view_group_taxon` WRITE;
/*!40000 ALTER TABLE `view_group_taxon` DISABLE KEYS */;
/*!40000 ALTER TABLE `view_group_taxon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `view_groups`
--

DROP TABLE IF EXISTS `view_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `view_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `view_groups_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `view_groups`
--

LOCK TABLES `view_groups` WRITE;
/*!40000 ALTER TABLE `view_groups` DISABLE KEYS */;
INSERT INTO `view_groups` VALUES (2,NULL,'Insects',NULL,'2017-11-14 08:30:42','2017-11-14 08:30:42'),(3,2,'Lepidoptera',NULL,'2017-11-14 08:31:15','2017-11-14 08:31:15'),(4,2,'Coleoptera',NULL,'2017-11-14 08:31:35','2017-11-14 08:31:35'),(5,2,'Orthoptera',NULL,'2017-11-14 08:31:50','2017-11-14 08:31:50'),(6,2,'Odonata',NULL,'2017-11-14 08:31:56','2017-11-14 08:31:56'),(7,NULL,'Reptiles & Amphibians',NULL,'2017-11-14 20:00:23','2017-11-14 20:00:23');
/*!40000 ALTER TABLE `view_groups` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-11-14 21:19:02
