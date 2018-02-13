-- MySQL dump 10.16  Distrib 10.1.31-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: biologer
-- ------------------------------------------------------
-- Server version	10.1.31-MariaDB

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
-- Table structure for table `conservation_list_taxon`
--

DROP TABLE IF EXISTS `conservation_list_taxon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conservation_list_taxon` (
  `conservation_list_id` int(10) unsigned NOT NULL,
  `taxon_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`conservation_list_id`,`taxon_id`),
  KEY `conservation_list_taxon_taxon_id_foreign` (`taxon_id`),
  CONSTRAINT `conservation_list_taxon_conservation_list_id_foreign` FOREIGN KEY (`conservation_list_id`) REFERENCES `conservation_lists` (`id`) ON DELETE CASCADE,
  CONSTRAINT `conservation_list_taxon_taxon_id_foreign` FOREIGN KEY (`taxon_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conservation_list_taxon`
--

LOCK TABLES `conservation_list_taxon` WRITE;
/*!40000 ALTER TABLE `conservation_list_taxon` DISABLE KEYS */;
/*!40000 ALTER TABLE `conservation_list_taxon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conservation_lists`
--

DROP TABLE IF EXISTS `conservation_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conservation_lists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conservation_lists_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conservation_lists`
--

LOCK TABLES `conservation_lists` WRITE;
/*!40000 ALTER TABLE `conservation_lists` DISABLE KEYS */;
/*!40000 ALTER TABLE `conservation_lists` ENABLE KEYS */;
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
  `taxon_suggestion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license` smallint(5) unsigned NOT NULL,
  `unidentifiable` tinyint(1) NOT NULL DEFAULT '0',
  `found_dead` tinyint(1) DEFAULT NULL,
  `found_dead_note` text COLLATE utf8mb4_unicode_ci,
  `time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `field_observations`
--

LOCK TABLES `field_observations` WRITE;
/*!40000 ALTER TABLE `field_observations` DISABLE KEYS */;
INSERT INTO `field_observations` VALUES (2,NULL,10,1,0,NULL,NULL,'2018-02-13 07:21:42','2018-02-13 07:37:13');
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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2016_06_01_000001_create_oauth_auth_codes_table',1),(4,'2016_06_01_000002_create_oauth_access_tokens_table',1),(5,'2016_06_01_000003_create_oauth_refresh_tokens_table',1),(6,'2016_06_01_000004_create_oauth_clients_table',1),(7,'2016_06_01_000005_create_oauth_personal_access_clients_table',1),(8,'2017_07_03_111433_create_stages_table',1),(9,'2017_07_07_112745_create_taxa_table',1),(10,'2017_07_07_132137_create_observations_table',1),(11,'2017_07_14_065330_create_taxon_ancestors_table',1),(12,'2017_07_16_200431_create_photos_table',1),(13,'2017_07_29_092558_create_field_observations_table',1),(14,'2017_07_29_112006_create_comments_table',1),(15,'2017_07_30_143122_create_field_observation_photo_table',1),(16,'2017_11_13_224839_create_view_groups_table',1),(17,'2017_11_13_224938_create_view_group_taxon_table',1),(18,'2017_12_17_004621_create_verification_tokens_table',1),(19,'2017_12_23_002123_create_red_lists_table',1),(20,'2017_12_23_002133_create_conservation_lists_table',1),(21,'2017_12_23_002303_create_red_list_taxon_table',1),(22,'2017_12_23_002659_create_conservation_list_taxon_table',1),(23,'2017_12_27_235046_create_roles_table',1),(24,'2017_12_27_235904_create_role_user_table',1),(25,'2017_12_28_121700_create_taxon_user_table',1),(26,'2018_01_03_130800_create_stage_taxon_table',1),(28,'2018_02_11_130504_create_taxon_translations_table',2);
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
  `accuracy` int(10) unsigned DEFAULT NULL,
  `mgrs10k` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `elevation` smallint(6) NOT NULL DEFAULT '0',
  `observer` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identifier` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sex` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stage_id` int(10) unsigned DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `number` int(10) unsigned DEFAULT NULL,
  `details_id` int(10) unsigned NOT NULL,
  `details_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_by_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `observations_details_id_details_type_index` (`details_id`,`details_type`),
  KEY `observations_created_by_id_foreign` (`created_by_id`),
  KEY `observations_stage_id_foreign` (`stage_id`),
  KEY `observations_approved_at_index` (`approved_at`),
  CONSTRAINT `observations_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
  CONSTRAINT `observations_stage_id_foreign` FOREIGN KEY (`stage_id`) REFERENCES `stages` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `observations`
--

LOCK TABLES `observations` WRITE;
/*!40000 ALTER TABLE `observations` DISABLE KEYS */;
INSERT INTO `observations` VALUES (2,NULL,'2018','2','13',NULL,44.855868807357,20.447387918830,NULL,'34TDQ56',68,'Chance Bergnaum',NULL,NULL,NULL,NULL,NULL,2,'App\\FieldObservation',NULL,1,'2018-02-13 07:21:42','2018-02-13 07:37:13');
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
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license` smallint(5) unsigned NOT NULL,
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
-- Table structure for table `red_list_taxon`
--

DROP TABLE IF EXISTS `red_list_taxon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `red_list_taxon` (
  `red_list_id` int(10) unsigned NOT NULL,
  `taxon_id` int(10) unsigned NOT NULL,
  `category` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`red_list_id`,`taxon_id`),
  KEY `red_list_taxon_taxon_id_foreign` (`taxon_id`),
  CONSTRAINT `red_list_taxon_red_list_id_foreign` FOREIGN KEY (`red_list_id`) REFERENCES `red_lists` (`id`) ON DELETE CASCADE,
  CONSTRAINT `red_list_taxon_taxon_id_foreign` FOREIGN KEY (`taxon_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `red_list_taxon`
--

LOCK TABLES `red_list_taxon` WRITE;
/*!40000 ALTER TABLE `red_list_taxon` DISABLE KEYS */;
/*!40000 ALTER TABLE `red_list_taxon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `red_lists`
--

DROP TABLE IF EXISTS `red_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `red_lists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `red_lists_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `red_lists`
--

LOCK TABLES `red_lists` WRITE;
/*!40000 ALTER TABLE `red_lists` DISABLE KEYS */;
/*!40000 ALTER TABLE `red_lists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_user` (
  `role_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`user_id`),
  KEY `role_user_user_id_foreign` (`user_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_user`
--

LOCK TABLES `role_user` WRITE;
/*!40000 ALTER TABLE `role_user` DISABLE KEYS */;
INSERT INTO `role_user` VALUES (1,1),(1,2),(2,1),(2,3);
/*!40000 ALTER TABLE `role_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','2018-02-02 20:41:10','2018-02-02 20:41:10'),(2,'curator','2018-02-02 20:41:10','2018-02-02 20:41:10');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stage_taxon`
--

DROP TABLE IF EXISTS `stage_taxon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stage_taxon` (
  `stage_id` int(10) unsigned NOT NULL,
  `taxon_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`stage_id`,`taxon_id`),
  KEY `stage_taxon_taxon_id_foreign` (`taxon_id`),
  CONSTRAINT `stage_taxon_stage_id_foreign` FOREIGN KEY (`stage_id`) REFERENCES `stages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stage_taxon_taxon_id_foreign` FOREIGN KEY (`taxon_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stage_taxon`
--

LOCK TABLES `stage_taxon` WRITE;
/*!40000 ALTER TABLE `stage_taxon` DISABLE KEYS */;
INSERT INTO `stage_taxon` VALUES (1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12),(1,13),(1,14),(1,15),(1,16),(1,17),(1,18),(1,19),(1,20),(1,21),(1,22),(1,23),(1,24),(1,25),(1,26),(1,27),(1,28),(1,29),(1,30),(1,31),(1,32),(1,33),(1,34),(1,35),(1,36),(1,37),(1,38),(1,39),(1,40),(1,41),(1,42),(1,43),(1,44),(1,45),(1,46),(1,47),(1,48),(1,49),(1,50),(1,51),(1,52),(1,53),(1,54),(1,55),(1,56),(1,57),(1,58),(1,59),(1,60),(1,61),(1,62),(1,63),(1,64),(1,65),(1,66),(1,67),(1,68),(1,69),(1,70),(1,71),(1,72),(1,73),(1,74),(1,75),(1,76),(1,77),(1,78),(1,79),(1,80),(1,81),(1,82),(1,83),(1,84),(1,85),(1,86),(1,87),(1,88),(1,89),(1,90),(1,91),(1,92),(1,93),(1,94),(1,95),(1,96),(1,97),(1,98),(1,99),(1,100),(2,5),(2,8),(2,9),(2,15),(2,16),(2,17),(2,21),(2,22),(2,23),(2,25),(2,26),(2,27),(2,37),(2,43),(2,44),(2,45),(2,46),(2,47),(2,48),(2,49),(2,54),(2,55),(2,56),(2,57),(2,69),(2,79),(2,80),(2,81),(2,82),(2,83),(2,84),(2,85),(2,86),(2,87),(2,88),(2,89),(2,90),(2,91),(2,92),(2,93),(2,94),(4,4),(4,6),(4,7),(4,10),(4,11),(4,12),(4,13),(4,14),(4,18),(4,19),(4,20),(4,24),(4,28),(4,29),(4,30),(4,31),(4,32),(4,33),(4,34),(4,35),(4,36),(4,38),(4,39),(4,40),(4,41),(4,42),(4,50),(4,51),(4,52),(4,53),(4,58),(4,59),(4,60),(4,61),(4,62),(4,63),(4,64),(4,65),(4,66),(4,67),(4,68),(4,70),(4,71),(4,72),(4,73),(4,74),(4,75),(4,76),(4,77),(4,78),(4,95),(4,96),(4,97),(4,98),(4,99),(4,100),(5,4),(5,5),(5,6),(5,7),(5,8),(5,9),(5,10),(5,11),(5,12),(5,13),(5,14),(5,15),(5,16),(5,17),(5,18),(5,19),(5,20),(5,21),(5,22),(5,23),(5,24),(5,25),(5,26),(5,27),(5,28),(5,29),(5,30),(5,31),(5,32),(5,33),(5,34),(5,35),(5,36),(5,37),(5,38),(5,39),(5,40),(5,41),(5,42),(5,43),(5,44),(5,45),(5,46),(5,47),(5,48),(5,49),(5,50),(5,51),(5,52),(5,53),(5,54),(5,55),(5,56),(5,57),(5,58),(5,59),(5,60),(5,61),(5,62),(5,63),(5,64),(5,65),(5,66),(5,67),(5,68),(5,69),(5,70),(5,71),(5,72),(5,73),(5,74),(5,75),(5,76),(5,77),(5,78),(5,79),(5,80),(5,81),(5,82),(5,83),(5,84),(5,85),(5,86),(5,87),(5,88),(5,89),(5,90),(5,91),(5,92),(5,93),(5,94),(5,95),(5,96),(5,97),(5,98),(5,99),(5,100);
/*!40000 ALTER TABLE `stage_taxon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stages`
--

DROP TABLE IF EXISTS `stages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stages_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stages`
--

LOCK TABLES `stages` WRITE;
/*!40000 ALTER TABLE `stages` DISABLE KEYS */;
INSERT INTO `stages` VALUES (1,'egg','2018-02-02 20:41:10','2018-02-02 20:41:10'),(2,'larva','2018-02-02 20:41:10','2018-02-02 20:41:10'),(3,'pupa','2018-02-02 20:41:10','2018-02-02 20:41:10'),(4,'juvenile','2018-02-02 20:41:10','2018-02-02 20:41:10'),(5,'adult','2018-02-02 20:41:10','2018-02-02 20:41:10');
/*!40000 ALTER TABLE `stages` ENABLE KEYS */;
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
  `rank` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rank_level` int(10) unsigned NOT NULL,
  `author` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ancestry` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fe_old_id` int(10) unsigned DEFAULT NULL,
  `fe_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restricted` tinyint(1) NOT NULL DEFAULT '0',
  `allochthonous` tinyint(1) NOT NULL DEFAULT '0',
  `invasive` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `taxa_name_unique` (`name`),
  KEY `taxa_rank_index` (`rank`),
  KEY `taxa_rank_level_index` (`rank_level`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taxa`
--

LOCK TABLES `taxa` WRITE;
/*!40000 ALTER TABLE `taxa` DISABLE KEYS */;
INSERT INTO `taxa` VALUES (1,NULL,'Animalia','kingdom',70,NULL,'',NULL,NULL,0,0,0,'2017-11-12 08:04:11','2018-02-02 18:13:38'),(2,1,'Chordata','phylum',60,NULL,'1',NULL,NULL,0,0,0,'2017-11-12 08:06:04','2018-02-02 18:13:38'),(3,1,'Arthropoda','phylum',60,NULL,'1',NULL,NULL,0,0,0,'2017-11-12 08:06:24','2018-02-02 18:13:38'),(4,2,'Reptilia','class',50,NULL,'1/2',NULL,NULL,0,0,0,'2017-11-12 08:07:36','2018-02-02 18:13:38'),(5,2,'Amphibia','class',50,NULL,'1/2',NULL,NULL,0,0,0,'2017-11-12 08:08:28','2018-02-02 18:13:38'),(6,4,'Squamata','order',40,NULL,'1/2/4',NULL,NULL,0,0,0,'2017-11-12 08:10:02','2018-02-02 18:13:38'),(7,4,'Testudines','order',40,NULL,'1/2/4',NULL,NULL,0,0,0,'2017-11-12 08:15:03','2018-02-02 18:13:38'),(8,5,'Anura','order',40,NULL,'1/2/5',NULL,NULL,0,0,0,'2017-11-12 08:15:36','2018-02-02 18:13:38'),(9,5,'Caudata','order',40,NULL,'1/2/5',NULL,NULL,0,0,0,'2017-11-12 08:15:58','2018-02-02 18:13:38'),(10,6,'Anguidae','family',30,NULL,'1/2/4/6',NULL,NULL,0,0,0,'2017-11-12 08:20:09','2018-02-02 18:13:38'),(11,6,'Colubridae','family',30,NULL,'1/2/4/6',NULL,NULL,0,0,0,'2017-11-12 08:20:46','2018-02-02 18:13:38'),(12,6,'Gekkonidae','family',30,NULL,'1/2/4/6',NULL,NULL,0,0,0,'2017-11-12 08:21:30','2018-02-02 18:13:38'),(13,6,'Lacertidae','family',30,NULL,'1/2/4/6',NULL,NULL,0,0,0,'2017-11-12 08:21:59','2018-02-02 18:13:38'),(14,6,'Scincidae','family',30,NULL,'1/2/4/6',NULL,NULL,0,0,0,'2017-11-12 08:23:16','2018-02-02 18:13:38'),(15,8,'Bombinatoridae','family',30,NULL,'1/2/5/8',NULL,NULL,0,0,0,'2017-11-12 08:24:03','2018-02-02 18:13:38'),(16,8,'Bufonidae','family',30,NULL,'1/2/5/8',NULL,NULL,0,0,0,'2017-11-12 08:24:22','2018-02-02 18:13:38'),(17,8,'Hylidae','family',30,NULL,'1/2/5/8',NULL,NULL,0,0,0,'2017-11-12 08:24:50','2018-02-02 18:13:38'),(18,7,'Emydidae','family',30,NULL,'1/2/4/7',NULL,NULL,0,0,0,'2017-11-12 08:25:06','2018-02-02 18:13:38'),(19,7,'Testudinidae','family',30,NULL,'1/2/4/7',NULL,NULL,0,0,0,'2017-11-12 08:25:34','2018-02-02 18:13:38'),(20,6,'Viperidae','family',30,NULL,'1/2/4/6',NULL,NULL,0,0,0,'2017-11-12 08:25:53','2018-02-02 18:13:38'),(21,8,'Pelobatidae','family',30,NULL,'1/2/5/8',NULL,NULL,0,0,0,'2017-11-12 08:26:18','2018-02-02 18:13:38'),(22,8,'Ranidae','family',30,NULL,'1/2/5/8',NULL,NULL,0,0,0,'2017-11-12 08:26:44','2018-02-02 18:13:38'),(23,9,'Salamandridae','family',30,NULL,'1/2/5/9',NULL,NULL,0,0,0,'2017-11-12 08:27:08','2018-02-02 18:13:38'),(24,10,'Anguis','genus',20,NULL,'1/2/4/6/10',NULL,NULL,0,0,0,'2017-11-12 08:27:30','2018-02-02 18:13:38'),(25,15,'Bombina','genus',20,NULL,'1/2/5/8/15',NULL,NULL,0,0,0,'2017-11-12 08:27:48','2018-02-02 18:13:38'),(26,16,'Bufo','genus',20,NULL,'1/2/5/8/16',NULL,NULL,0,0,0,'2017-11-12 08:28:02','2018-02-02 18:13:38'),(27,16,'Pseudepidalea','genus',20,NULL,'1/2/5/8/16',NULL,NULL,0,0,0,'2017-11-12 08:28:29','2018-02-02 18:13:38'),(28,11,'Coronella','genus',20,NULL,'1/2/4/6/11',NULL,NULL,0,0,0,'2017-11-12 08:28:51','2018-02-02 18:13:38'),(29,11,'Dolichophis','genus',20,NULL,'1/2/4/6/11',NULL,NULL,0,0,0,'2017-11-12 08:29:20','2018-02-02 18:13:38'),(30,11,'Elaphe','genus',20,NULL,'1/2/4/6/11',NULL,NULL,0,0,0,'2017-11-12 08:29:39','2018-02-02 18:13:38'),(31,11,'Natrix','genus',20,NULL,'1/2/4/6/11',NULL,NULL,0,0,0,'2017-11-12 08:29:53','2018-02-02 18:13:38'),(32,11,'Platyceps','genus',20,NULL,'1/2/4/6/11',NULL,NULL,0,0,0,'2017-11-12 08:30:06','2018-02-02 18:13:38'),(33,11,'Zamenis','genus',20,NULL,'1/2/4/6/11',NULL,NULL,0,0,0,'2017-11-12 08:30:20','2018-02-02 18:13:38'),(34,18,'Emys','genus',20,NULL,'1/2/4/7/18',NULL,NULL,0,0,0,'2017-11-12 08:30:38','2018-02-02 18:13:39'),(35,18,'Trachemys','genus',20,NULL,'1/2/4/7/18',NULL,NULL,0,0,0,'2017-11-12 08:30:56','2018-02-02 18:13:39'),(36,12,'Cyrtopodion','genus',20,NULL,'1/2/4/6/12',NULL,NULL,0,0,0,'2017-11-12 08:31:14','2018-02-02 18:13:39'),(37,17,'Hyla','genus',20,NULL,'1/2/5/8/17',NULL,NULL,0,0,0,'2017-11-12 08:31:39','2018-02-02 18:13:39'),(38,13,'Algyroides','genus',20,NULL,'1/2/4/6/13',NULL,NULL,0,0,0,'2017-11-12 08:31:52','2018-02-02 18:13:39'),(39,13,'Darevskia','genus',20,NULL,'1/2/4/6/13',NULL,NULL,0,0,0,'2017-11-12 08:32:10','2018-02-02 18:13:39'),(40,13,'Lacerta','genus',20,NULL,'1/2/4/6/13',NULL,NULL,0,0,0,'2017-11-12 08:32:27','2018-02-02 18:13:39'),(41,13,'Podarcis','genus',20,NULL,'1/2/4/6/13',NULL,NULL,0,0,0,'2017-11-12 08:32:41','2018-02-02 18:13:39'),(42,13,'Zootoca','genus',20,NULL,'1/2/4/6/13',NULL,NULL,0,0,0,'2017-11-12 08:32:55','2018-02-02 18:13:39'),(43,21,'Pelobates','genus',20,NULL,'1/2/5/8/21',NULL,NULL,0,0,0,'2017-11-12 08:33:13','2018-02-02 18:13:39'),(44,22,'Pelophylax','genus',20,NULL,'1/2/5/8/22',NULL,NULL,0,0,0,'2017-11-12 08:33:40','2018-02-02 18:13:39'),(45,22,'Rana','genus',20,NULL,'1/2/5/8/22',NULL,NULL,0,0,0,'2017-11-12 08:33:56','2018-02-02 18:13:39'),(46,23,'Lissotriton','genus',20,NULL,'1/2/5/9/23',NULL,NULL,0,0,0,'2017-11-12 08:34:15','2018-02-02 18:13:39'),(47,23,'Mesotriton','genus',20,NULL,'1/2/5/9/23',NULL,NULL,0,0,0,'2017-11-12 08:34:38','2018-02-02 18:13:39'),(48,23,'Salamandra','genus',20,NULL,'1/2/5/9/23',NULL,NULL,0,0,0,'2017-11-12 08:34:52','2018-02-02 18:13:39'),(49,23,'Triturus','genus',20,NULL,'1/2/5/9/23',NULL,NULL,0,0,0,'2017-11-12 08:35:45','2018-02-02 18:13:39'),(50,14,'Ablepharus','genus',20,NULL,'1/2/4/6/14',NULL,NULL,0,0,0,'2017-11-12 08:35:58','2018-02-02 22:03:08'),(51,19,'Testudo','genus',20,NULL,'1/2/4/7/19',NULL,NULL,0,0,0,'2017-11-12 08:36:15','2018-02-02 18:13:39'),(52,20,'Vipera','genus',20,NULL,'1/2/4/6/20',NULL,NULL,0,0,0,'2017-11-12 08:36:31','2018-02-02 18:13:39'),(53,24,'Anguis fragilis','species',10,NULL,'1/2/4/6/10/24',NULL,NULL,0,0,0,'2017-11-12 08:36:53','2018-02-02 18:13:39'),(54,25,'Bombina bombina','species',10,NULL,'1/2/5/8/15/25',NULL,NULL,0,0,0,'2017-11-12 08:37:35','2018-02-02 18:13:39'),(55,25,'Bombina variegata','species',10,NULL,'1/2/5/8/15/25',NULL,NULL,0,0,0,'2017-11-12 08:37:50','2018-02-02 18:13:39'),(56,26,'Bufo bufo','species',10,NULL,'1/2/5/8/16/26',NULL,NULL,0,0,0,'2017-11-12 08:38:08','2018-02-02 18:13:39'),(57,27,'Pseudepidalea viridis','species',10,NULL,'1/2/5/8/16/27',NULL,NULL,0,0,0,'2017-11-12 08:38:46','2018-02-02 18:13:39'),(58,28,'Coronella austriaca','species',10,NULL,'1/2/4/6/11/28',NULL,NULL,0,0,0,'2017-11-12 08:39:03','2018-02-02 18:13:39'),(59,29,'Dolichophis caspius','species',10,NULL,'1/2/4/6/11/29',NULL,NULL,0,0,0,'2017-11-12 08:55:02','2018-02-02 18:13:39'),(60,30,'Elaphe quatuorlineata','species',10,NULL,'1/2/4/6/11/30',NULL,NULL,0,0,0,'2017-11-12 08:55:18','2018-02-02 18:13:39'),(61,31,'Natrix natrix','species',10,NULL,'1/2/4/6/11/31',NULL,NULL,0,0,0,'2017-11-12 08:55:33','2018-02-02 18:13:39'),(62,31,'Natrix tessellata','species',10,NULL,'1/2/4/6/11/31',NULL,NULL,0,0,0,'2017-11-12 08:56:26','2018-02-02 18:13:39'),(63,32,'Platyceps najadum','species',10,NULL,'1/2/4/6/11/32',NULL,NULL,0,0,0,'2017-11-12 08:56:57','2018-02-02 18:13:39'),(64,33,'Zamenis longissimus','species',10,NULL,'1/2/4/6/11/33',NULL,NULL,0,0,0,'2017-11-12 08:57:29','2018-02-02 18:13:39'),(65,34,'Emys orbicularis','species',10,NULL,'1/2/4/7/18/34',NULL,NULL,0,0,0,'2017-11-12 08:57:44','2018-02-02 18:13:39'),(66,35,'Trachemys scripta','species',10,NULL,'1/2/4/7/18/35',NULL,NULL,0,0,0,'2017-11-12 08:57:58','2018-02-02 18:13:39'),(67,66,'Trachemys scripta elegans','subspecies',5,NULL,'1/2/4/7/18/35/66',NULL,NULL,0,0,0,'2017-11-12 08:58:23','2018-02-02 18:13:39'),(68,36,'Cyrtopodion kotschyi','species',10,NULL,'1/2/4/6/12/36',NULL,NULL,0,0,0,'2017-11-12 08:58:54','2018-02-02 18:13:39'),(69,37,'Hyla arborea','species',10,NULL,'1/2/5/8/17/37',NULL,NULL,0,0,0,'2017-11-12 08:59:06','2018-02-02 18:13:39'),(70,38,'Algyroides nigropunctatus','species',10,NULL,'1/2/4/6/13/38',NULL,NULL,0,0,0,'2017-11-12 08:59:21','2018-02-02 18:13:39'),(71,39,'Darevskia praticola','species',10,NULL,'1/2/4/6/13/39',NULL,NULL,0,0,0,'2017-11-12 08:59:36','2018-02-02 18:13:39'),(72,40,'Lacerta agilis','species',10,NULL,'1/2/4/6/13/40',NULL,NULL,0,0,0,'2017-11-12 08:59:46','2018-02-02 18:13:39'),(73,40,'Lacerta trilineata','species',10,NULL,'1/2/4/6/13/40',NULL,NULL,0,0,0,'2017-11-12 08:59:58','2018-02-02 18:13:39'),(74,40,'Lacerta viridis','species',10,NULL,'1/2/4/6/13/40',NULL,NULL,0,0,0,'2017-11-12 09:00:14','2018-02-02 18:13:39'),(75,41,'Podarcis erhardii','species',10,NULL,'1/2/4/6/13/41',NULL,NULL,0,0,0,'2017-11-12 09:00:35','2018-02-02 18:13:39'),(76,41,'Podarcis muralis','species',10,NULL,'1/2/4/6/13/41',NULL,NULL,0,0,0,'2017-11-12 09:00:47','2018-02-02 18:13:39'),(77,41,'Podarcis tauricus','species',10,NULL,'1/2/4/6/13/41',NULL,NULL,0,0,0,'2017-11-12 09:01:00','2018-02-02 18:13:39'),(78,42,'Zootoca vivipara','species',10,NULL,'1/2/4/6/13/42',NULL,NULL,0,0,0,'2017-11-12 09:01:15','2018-02-02 18:13:39'),(79,43,'Pelobates fuscus','species',10,NULL,'1/2/5/8/21/43',NULL,NULL,0,0,0,'2017-11-12 09:01:28','2018-02-02 18:13:39'),(80,43,'Pelobates syriacus','species',10,NULL,'1/2/5/8/21/43',NULL,NULL,0,0,0,'2017-11-12 09:01:45','2018-02-02 18:13:39'),(81,44,'Pelophylax esculentus','species',10,NULL,'1/2/5/8/22/44',NULL,NULL,0,0,0,'2017-11-12 09:02:01','2018-02-02 18:13:39'),(82,44,'Pelophylax lessonae','species',10,NULL,'1/2/5/8/22/44',NULL,NULL,0,0,0,'2017-11-12 09:02:15','2018-02-02 18:13:39'),(83,44,'Pelophylax ridibundus','species',10,NULL,'1/2/5/8/22/44',NULL,NULL,0,0,0,'2017-11-12 09:02:36','2018-02-02 18:13:39'),(84,45,'Rana dalmatina','species',10,NULL,'1/2/5/8/22/45',NULL,NULL,0,0,0,'2017-11-12 09:02:54','2018-02-02 18:13:39'),(85,45,'Rana graeca','species',10,NULL,'1/2/5/8/22/45',NULL,NULL,0,0,0,'2017-11-12 09:03:05','2018-02-02 18:13:39'),(86,45,'Rana temporaria','species',10,NULL,'1/2/5/8/22/45',NULL,NULL,0,0,0,'2017-11-12 09:03:18','2018-02-02 18:13:39'),(87,46,'Lissotriton vulgaris','species',10,NULL,'1/2/5/9/23/46',NULL,NULL,0,0,0,'2017-11-12 09:04:04','2018-02-02 18:13:39'),(88,47,'Mesotriton alpestris','species',10,NULL,'1/2/5/9/23/47',NULL,NULL,0,0,0,'2017-11-12 09:04:16','2018-02-02 18:13:39'),(89,48,'Salamandra atra','species',10,NULL,'1/2/5/9/23/48',NULL,NULL,0,0,0,'2017-11-12 09:04:29','2018-02-02 18:13:39'),(90,48,'Salamandra salamandra','species',10,NULL,'1/2/5/9/23/48',NULL,NULL,0,0,0,'2017-11-12 09:04:40','2018-02-02 18:13:40'),(91,49,'Triturus carnifex','species',10,NULL,'1/2/5/9/23/49',NULL,NULL,0,0,0,'2017-11-12 09:04:52','2018-02-02 18:13:40'),(92,49,'Triturus cristatus','species',10,NULL,'1/2/5/9/23/49',NULL,NULL,0,0,0,'2017-11-12 09:05:05','2018-02-02 18:13:40'),(93,49,'Triturus dobrogicus','species',10,NULL,'1/2/5/9/23/49',NULL,NULL,0,0,0,'2017-11-12 09:05:18','2018-02-02 18:13:40'),(94,49,'Triturus karelinii','species',10,NULL,'1/2/5/9/23/49',NULL,NULL,0,0,0,'2017-11-12 09:05:29','2018-02-02 18:13:40'),(95,50,'Ablepharus kitaibelii','species',10,NULL,'1/2/4/6/14/50',NULL,NULL,0,0,0,'2017-11-12 09:05:45','2018-02-02 18:13:40'),(96,51,'Testudo graeca','species',10,NULL,'1/2/4/7/19/51',NULL,NULL,0,0,0,'2017-11-12 09:05:57','2018-02-02 18:13:40'),(97,51,'Testudo hermanni','species',10,NULL,'1/2/4/7/19/51',NULL,NULL,0,0,0,'2017-11-12 09:06:09','2018-02-02 18:13:40'),(98,52,'Vipera ammodytes','species',10,NULL,'1/2/4/6/20/52',NULL,NULL,0,0,0,'2017-11-12 09:06:21','2018-02-02 18:13:40'),(99,52,'Vipera berus','species',10,NULL,'1/2/4/6/20/52',NULL,NULL,0,0,0,'2017-11-12 09:06:35','2018-02-02 18:13:40'),(100,52,'Vipera ursinii','species',10,NULL,'1/2/4/6/20/52',NULL,NULL,0,0,0,'2017-11-12 09:06:49','2018-02-02 18:13:40');
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
INSERT INTO `taxon_ancestors` VALUES (2,1),(3,1),(4,1),(4,2),(5,1),(5,2),(6,1),(6,2),(6,4),(7,1),(7,2),(7,4),(8,1),(8,2),(8,5),(9,1),(9,2),(9,5),(10,1),(10,2),(10,4),(10,6),(11,1),(11,2),(11,4),(11,6),(12,1),(12,2),(12,4),(12,6),(13,1),(13,2),(13,4),(13,6),(14,1),(14,2),(14,4),(14,6),(15,1),(15,2),(15,5),(15,8),(16,1),(16,2),(16,5),(16,8),(17,1),(17,2),(17,5),(17,8),(18,1),(18,2),(18,4),(18,7),(19,1),(19,2),(19,4),(19,7),(20,1),(20,2),(20,4),(20,6),(21,1),(21,2),(21,5),(21,8),(22,1),(22,2),(22,5),(22,8),(23,1),(23,2),(23,5),(23,9),(24,1),(24,2),(24,4),(24,6),(24,10),(25,1),(25,2),(25,5),(25,8),(25,15),(26,1),(26,2),(26,5),(26,8),(26,16),(27,1),(27,2),(27,5),(27,8),(27,16),(28,1),(28,2),(28,4),(28,6),(28,11),(29,1),(29,2),(29,4),(29,6),(29,11),(30,1),(30,2),(30,4),(30,6),(30,11),(31,1),(31,2),(31,4),(31,6),(31,11),(32,1),(32,2),(32,4),(32,6),(32,11),(33,1),(33,2),(33,4),(33,6),(33,11),(34,1),(34,2),(34,4),(34,7),(34,18),(35,1),(35,2),(35,4),(35,7),(35,18),(36,1),(36,2),(36,4),(36,6),(36,12),(37,1),(37,2),(37,5),(37,8),(37,17),(38,1),(38,2),(38,4),(38,6),(38,13),(39,1),(39,2),(39,4),(39,6),(39,13),(40,1),(40,2),(40,4),(40,6),(40,13),(41,1),(41,2),(41,4),(41,6),(41,13),(42,1),(42,2),(42,4),(42,6),(42,13),(43,1),(43,2),(43,5),(43,8),(43,21),(44,1),(44,2),(44,5),(44,8),(44,22),(45,1),(45,2),(45,5),(45,8),(45,22),(46,1),(46,2),(46,5),(46,9),(46,23),(47,1),(47,2),(47,5),(47,9),(47,23),(48,1),(48,2),(48,5),(48,9),(48,23),(49,1),(49,2),(49,5),(49,9),(49,23),(50,1),(50,2),(50,4),(50,6),(50,14),(51,1),(51,2),(51,4),(51,7),(51,19),(52,1),(52,2),(52,4),(52,6),(52,20),(53,1),(53,2),(53,4),(53,6),(53,10),(53,24),(54,1),(54,2),(54,5),(54,8),(54,15),(54,25),(55,1),(55,2),(55,5),(55,8),(55,15),(55,25),(56,1),(56,2),(56,5),(56,8),(56,16),(56,26),(57,1),(57,2),(57,5),(57,8),(57,16),(57,27),(58,1),(58,2),(58,4),(58,6),(58,11),(58,28),(59,1),(59,2),(59,4),(59,6),(59,11),(59,29),(60,1),(60,2),(60,4),(60,6),(60,11),(60,30),(61,1),(61,2),(61,4),(61,6),(61,11),(61,31),(62,1),(62,2),(62,4),(62,6),(62,11),(62,31),(63,1),(63,2),(63,4),(63,6),(63,11),(63,32),(64,1),(64,2),(64,4),(64,6),(64,11),(64,33),(65,1),(65,2),(65,4),(65,7),(65,18),(65,34),(66,1),(66,2),(66,4),(66,7),(66,18),(66,35),(67,1),(67,2),(67,4),(67,7),(67,18),(67,35),(67,66),(68,1),(68,2),(68,4),(68,6),(68,12),(68,36),(69,1),(69,2),(69,5),(69,8),(69,17),(69,37),(70,1),(70,2),(70,4),(70,6),(70,13),(70,38),(71,1),(71,2),(71,4),(71,6),(71,13),(71,39),(72,1),(72,2),(72,4),(72,6),(72,13),(72,40),(73,1),(73,2),(73,4),(73,6),(73,13),(73,40),(74,1),(74,2),(74,4),(74,6),(74,13),(74,40),(75,1),(75,2),(75,4),(75,6),(75,13),(75,41),(76,1),(76,2),(76,4),(76,6),(76,13),(76,41),(77,1),(77,2),(77,4),(77,6),(77,13),(77,41),(78,1),(78,2),(78,4),(78,6),(78,13),(78,42),(79,1),(79,2),(79,5),(79,8),(79,21),(79,43),(80,1),(80,2),(80,5),(80,8),(80,21),(80,43),(81,1),(81,2),(81,5),(81,8),(81,22),(81,44),(82,1),(82,2),(82,5),(82,8),(82,22),(82,44),(83,1),(83,2),(83,5),(83,8),(83,22),(83,44),(84,1),(84,2),(84,5),(84,8),(84,22),(84,45),(85,1),(85,2),(85,5),(85,8),(85,22),(85,45),(86,1),(86,2),(86,5),(86,8),(86,22),(86,45),(87,1),(87,2),(87,5),(87,9),(87,23),(87,46),(88,1),(88,2),(88,5),(88,9),(88,23),(88,47),(89,1),(89,2),(89,5),(89,9),(89,23),(89,48),(90,1),(90,2),(90,5),(90,9),(90,23),(90,48),(91,1),(91,2),(91,5),(91,9),(91,23),(91,49),(92,1),(92,2),(92,5),(92,9),(92,23),(92,49),(93,1),(93,2),(93,5),(93,9),(93,23),(93,49),(94,1),(94,2),(94,5),(94,9),(94,23),(94,49),(95,1),(95,2),(95,4),(95,6),(95,14),(95,50),(96,1),(96,2),(96,4),(96,7),(96,19),(96,51),(97,1),(97,2),(97,4),(97,7),(97,19),(97,51),(98,1),(98,2),(98,4),(98,6),(98,20),(98,52),(99,1),(99,2),(99,4),(99,6),(99,20),(99,52),(100,1),(100,2),(100,4),(100,6),(100,20),(100,52);
/*!40000 ALTER TABLE `taxon_ancestors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taxon_translations`
--

DROP TABLE IF EXISTS `taxon_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `taxon_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `taxon_id` int(10) unsigned NOT NULL,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `native_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `taxon_translations_taxon_id_locale_unique` (`taxon_id`,`locale`),
  KEY `taxon_translations_locale_index` (`locale`),
  CONSTRAINT `taxon_translations_taxon_id_foreign` FOREIGN KEY (`taxon_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taxon_translations`
--

LOCK TABLES `taxon_translations` WRITE;
/*!40000 ALTER TABLE `taxon_translations` DISABLE KEYS */;
INSERT INTO `taxon_translations` VALUES (1,1,'en','animals','Animal kingdom'),(2,1,'sr-Latn','životinje','Životinjsko carstvo'),(4,1,'sr','животиње','Животињско царство');
/*!40000 ALTER TABLE `taxon_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taxon_user`
--

DROP TABLE IF EXISTS `taxon_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `taxon_user` (
  `taxon_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`taxon_id`,`user_id`),
  KEY `taxon_user_user_id_foreign` (`user_id`),
  CONSTRAINT `taxon_user_taxon_id_foreign` FOREIGN KEY (`taxon_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `taxon_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taxon_user`
--

LOCK TABLES `taxon_user` WRITE;
/*!40000 ALTER TABLE `taxon_user` DISABLE KEYS */;
INSERT INTO `taxon_user` VALUES (1,1);
/*!40000 ALTER TABLE `taxon_user` ENABLE KEYS */;
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
  `settings` text COLLATE utf8mb4_unicode_ci,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Chance','Bergnaum','nen.zivanovic@gmail.com','$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm','x4SKU500LRPD6779YqHrWUODVCEtfTS0gPQDpW6ZBEvZh4U8SkH9Tf8Xa9u3','\"[]\"',1,'2018-02-02 20:41:23','2018-02-02 20:41:23'),(2,'Jewell','Olson','admin@example.com','$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm','KkI13mvPtU','\"[]\"',1,'2018-02-02 20:41:23','2018-02-02 20:41:23'),(3,'Reva','Hermann','curator@example.com','$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm','q55DgKH8Gq','\"[]\"',1,'2018-02-02 20:41:23','2018-02-02 20:41:23'),(4,'Tillman','Nader','member@example.com','$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm','jBYjdpbCse','\"[]\"',1,'2018-02-02 20:41:23','2018-02-02 20:41:23');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `verification_tokens`
--

DROP TABLE IF EXISTS `verification_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `verification_tokens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `verification_tokens_token_unique` (`token`),
  KEY `verification_tokens_user_id_foreign` (`user_id`),
  CONSTRAINT `verification_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verification_tokens`
--

LOCK TABLES `verification_tokens` WRITE;
/*!40000 ALTER TABLE `verification_tokens` DISABLE KEYS */;
INSERT INTO `verification_tokens` VALUES (1,1,'bmVuLnppdmFub3ZpY0BnbWFpbC5jb21yRk1BdWtYazVY','2018-02-02 20:41:23','2018-02-02 20:41:23'),(2,2,'YWRtaW5AZXhhbXBsZS5jb20xQUFTZnBhY1V5','2018-02-02 20:41:23','2018-02-02 20:41:23'),(3,3,'Y3VyYXRvckBleGFtcGxlLmNvbTR0R2FKNWdRQTE=','2018-02-02 20:41:23','2018-02-02 20:41:23'),(4,4,'bWVtYmVyQGV4YW1wbGUuY29tSEVKSWVrY25YUQ==','2018-02-02 20:41:23','2018-02-02 20:41:23');
/*!40000 ALTER TABLE `verification_tokens` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `view_groups`
--

LOCK TABLES `view_groups` WRITE;
/*!40000 ALTER TABLE `view_groups` DISABLE KEYS */;
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

-- Dump completed on 2018-02-13  9:08:05
