-- MariaDB dump 10.17  Distrib 10.4.7-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: biologer_testing
-- ------------------------------------------------------
-- Server version	10.4.7-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `subject_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` int(11) DEFAULT NULL,
  `causer_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `properties` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_log`
--

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcement_translations`
--

DROP TABLE IF EXISTS `announcement_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcement_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `announcement_id` int(10) unsigned NOT NULL,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `announcement_translations_announcement_id_locale_unique` (`announcement_id`,`locale`),
  KEY `announcement_translations_locale_index` (`locale`),
  CONSTRAINT `announcement_translations_announcement_id_foreign` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcement_translations`
--

LOCK TABLES `announcement_translations` WRITE;
/*!40000 ALTER TABLE `announcement_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `announcement_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `creator_id` int(10) unsigned DEFAULT NULL,
  `creator_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT 0,
  `reads` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `announcements_creator_id_foreign` (`creator_id`),
  CONSTRAINT `announcements_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conservation_document_taxon`
--

DROP TABLE IF EXISTS `conservation_document_taxon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conservation_document_taxon` (
  `doc_id` int(10) unsigned NOT NULL,
  `taxon_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`doc_id`,`taxon_id`),
  KEY `conservation_document_taxon_taxon_id_foreign` (`taxon_id`),
  CONSTRAINT `conservation_document_taxon_doc_id_foreign` FOREIGN KEY (`doc_id`) REFERENCES `conservation_documents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `conservation_document_taxon_taxon_id_foreign` FOREIGN KEY (`taxon_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conservation_document_taxon`
--

LOCK TABLES `conservation_document_taxon` WRITE;
/*!40000 ALTER TABLE `conservation_document_taxon` DISABLE KEYS */;
/*!40000 ALTER TABLE `conservation_document_taxon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conservation_document_translations`
--

DROP TABLE IF EXISTS `conservation_document_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conservation_document_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `doc_id` int(10) unsigned NOT NULL,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conservation_document_translations_doc_id_locale_unique` (`doc_id`,`locale`),
  CONSTRAINT `conservation_document_translations_doc_id_foreign` FOREIGN KEY (`doc_id`) REFERENCES `conservation_documents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conservation_document_translations`
--

LOCK TABLES `conservation_document_translations` WRITE;
/*!40000 ALTER TABLE `conservation_document_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `conservation_document_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conservation_documents`
--

DROP TABLE IF EXISTS `conservation_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conservation_documents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conservation_documents_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conservation_documents`
--

LOCK TABLES `conservation_documents` WRITE;
/*!40000 ALTER TABLE `conservation_documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `conservation_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conservation_legislation_taxon`
--

DROP TABLE IF EXISTS `conservation_legislation_taxon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conservation_legislation_taxon` (
  `leg_id` int(10) unsigned NOT NULL,
  `taxon_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`leg_id`,`taxon_id`),
  KEY `conservation_legislation_taxon_taxon_id_foreign` (`taxon_id`),
  CONSTRAINT `conservation_legislation_taxon_leg_id_foreign` FOREIGN KEY (`leg_id`) REFERENCES `conservation_legislations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `conservation_legislation_taxon_taxon_id_foreign` FOREIGN KEY (`taxon_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conservation_legislation_taxon`
--

LOCK TABLES `conservation_legislation_taxon` WRITE;
/*!40000 ALTER TABLE `conservation_legislation_taxon` DISABLE KEYS */;
/*!40000 ALTER TABLE `conservation_legislation_taxon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conservation_legislation_translations`
--

DROP TABLE IF EXISTS `conservation_legislation_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conservation_legislation_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `leg_id` int(10) unsigned NOT NULL,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conservation_legislation_translations_leg_id_locale_unique` (`leg_id`,`locale`),
  CONSTRAINT `conservation_legislation_translations_leg_id_foreign` FOREIGN KEY (`leg_id`) REFERENCES `conservation_legislations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conservation_legislation_translations`
--

LOCK TABLES `conservation_legislation_translations` WRITE;
/*!40000 ALTER TABLE `conservation_legislation_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `conservation_legislation_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conservation_legislations`
--

DROP TABLE IF EXISTS `conservation_legislations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conservation_legislations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conservation_legislations_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conservation_legislations`
--

LOCK TABLES `conservation_legislations` WRITE;
/*!40000 ALTER TABLE `conservation_legislations` DISABLE KEYS */;
/*!40000 ALTER TABLE `conservation_legislations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exports`
--

DROP TABLE IF EXISTS `exports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filter` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `columns` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `with_header` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exports`
--

LOCK TABLES `exports` WRITE;
/*!40000 ALTER TABLE `exports` DISABLE KEYS */;
/*!40000 ALTER TABLE `exports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
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
  `unidentifiable` tinyint(1) NOT NULL DEFAULT 0,
  `found_dead` tinyint(1) DEFAULT NULL,
  `found_dead_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` time DEFAULT NULL,
  `observed_by_id` int(10) unsigned DEFAULT NULL,
  `identified_by_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `field_observations_observed_by_id_foreign` (`observed_by_id`),
  KEY `field_observations_identified_by_id_foreign` (`identified_by_id`),
  CONSTRAINT `field_observations_identified_by_id_foreign` FOREIGN KEY (`identified_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `field_observations_observed_by_id_foreign` FOREIGN KEY (`observed_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
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
-- Table structure for table `imports`
--

DROP TABLE IF EXISTS `imports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `imports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `for_user_id` int(10) unsigned DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `columns` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `lang` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `has_heading` tinyint(1) NOT NULL DEFAULT 0,
  `options` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imports`
--

LOCK TABLES `imports` WRITE;
/*!40000 ALTER TABLE `imports` DISABLE KEYS */;
/*!40000 ALTER TABLE `imports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `literature_observations`
--

DROP TABLE IF EXISTS `literature_observations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `literature_observations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `time` time DEFAULT NULL,
  `original_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original_locality` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original_elevation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original_coordinates` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original_identification_validity` tinyint(4) NOT NULL,
  `georeferenced_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `georeferenced_date` date DEFAULT NULL,
  `minimum_elevation` smallint(6) DEFAULT NULL,
  `maximum_elevation` smallint(6) DEFAULT NULL,
  `publication_id` int(10) unsigned NOT NULL,
  `is_original_data` tinyint(1) NOT NULL DEFAULT 1,
  `cited_publication_id` int(10) unsigned DEFAULT NULL,
  `place_where_referenced_in_publication` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `literature_observations_publication_id_foreign` (`publication_id`),
  KEY `literature_observations_cited_publication_id_foreign` (`cited_publication_id`),
  CONSTRAINT `literature_observations_cited_publication_id_foreign` FOREIGN KEY (`cited_publication_id`) REFERENCES `publications` (`id`),
  CONSTRAINT `literature_observations_publication_id_foreign` FOREIGN KEY (`publication_id`) REFERENCES `publications` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `literature_observations`
--

LOCK TABLES `literature_observations` WRITE;
/*!40000 ALTER TABLE `literature_observations` DISABLE KEYS */;
/*!40000 ALTER TABLE `literature_observations` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2016_06_01_000001_create_oauth_auth_codes_table',1),(4,'2016_06_01_000002_create_oauth_access_tokens_table',1),(5,'2016_06_01_000003_create_oauth_refresh_tokens_table',1),(6,'2016_06_01_000004_create_oauth_clients_table',1),(7,'2016_06_01_000005_create_oauth_personal_access_clients_table',1),(8,'2017_07_03_111433_create_stages_table',1),(9,'2017_07_07_112745_create_taxa_table',1),(10,'2017_07_07_132137_create_observations_table',1),(11,'2017_07_14_065330_create_taxon_ancestors_table',1),(12,'2017_07_16_200431_create_photos_table',1),(13,'2017_07_29_092558_create_field_observations_table',1),(14,'2017_11_13_224839_create_view_groups_table',1),(15,'2017_12_23_002123_create_red_lists_table',1),(16,'2017_12_23_002133_create_conservation_legislations_table',1),(17,'2017_12_23_002303_create_red_list_taxon_table',1),(18,'2017_12_23_002659_create_conservation_legislation_taxon_table',1),(19,'2017_12_27_235046_create_roles_table',1),(20,'2017_12_27_235904_create_role_user_table',1),(21,'2017_12_28_121700_create_taxon_user_table',1),(22,'2018_01_03_130800_create_stage_taxon_table',1),(23,'2018_02_11_130504_create_taxon_translations_table',1),(24,'2018_02_13_092445_create_conservation_legislation_translations_table',1),(25,'2018_02_13_092457_create_red_list_translations_table',1),(26,'2018_02_13_144227_create_conservation_documents_table',1),(27,'2018_02_13_144250_create_conservation_document_translations_table',1),(28,'2018_02_13_150034_create_conservation_document_taxon_table',1),(29,'2018_02_15_145911_create_jobs_table',1),(30,'2018_02_15_150230_create_failed_jobs_table',1),(31,'2018_02_23_093707_create_activity_log_table',1),(32,'2018_03_13_194618_create_observation_types_table',1),(33,'2018_03_13_195015_create_observation_type_translations_table',1),(34,'2018_03_13_195107_create_observation_observation_type_table',1),(35,'2018_03_13_200029_create_observation_type_taxon_table',1),(36,'2018_03_30_152917_alter_field_observations_table_add_observed_by_id_and_identified_by_id',1),(37,'2018_04_25_230239_create_taxon_view_group_table',1),(38,'2018_04_26_152329_create_view_group_translations_table',1),(39,'2018_04_28_235921_create_observation_photo_table',1),(40,'2018_05_20_184115_create_imports_table',1),(41,'2018_05_23_142538_alter_observations_table_add_original_identification_column',1),(42,'2018_06_10_123144_create_exports_table',1),(43,'2018_09_19_180641_add_dataset_field_to_observations_table',1),(44,'2018_09_24_130431_create_announcements_table',1),(45,'2018_09_24_134838_create_announcement_translations_table',1),(46,'2018_10_13_155756_create_notifications_table',1),(47,'2018_10_15_151245_alter_observations_table_make_elevation_column_nullable',1),(48,'2018_10_24_172456_alter_users_table_replace_verified_column',1),(49,'2018_11_08_215154_alter_imports_table_add_for_user_id_column',1),(50,'2019_03_01_084502_update_conservation_legislations',1),(51,'2019_03_18_125307_alter_view_groups_table_add_only_observed_taxa_column',1),(52,'2019_03_28_082423_alter_observations_table_add_habitat_column',1),(53,'2019_03_30_164124_alter_observations_table_add_client_name_column',1),(54,'2019_04_01_100856_create_publications_table',1),(55,'2019_04_01_112500_create_literature_observations_table',1),(56,'2019_05_20_081920_create_publication_attachments_table',1),(57,'2019_05_21_164011_alter_red_list_taxon_table_alter_category_column',1),(58,'2019_05_25_151411_alter_users_table_add_deleted_at_column',1),(59,'2019_05_25_153640_alter_foreign_keys',1),(60,'2019_05_29_083559_alter_taxa_table_remove_unused_column_ancestry',1),(61,'2019_06_01_165701_alter_publications_table_make_created_by_id_nullable',1),(62,'2019_06_01_170115_alter_announcements_table_make_creator_id_nullable',1),(63,'2019_06_04_080336_alter_observations_table_change_year_column',1),(64,'2019_06_07_075144_alter_taxa_remove_unique_constraint_from_name_column',1),(65,'2019_06_12_075931_add_index_to_observations_table',1),(66,'2019_06_29_132024_alter_imports_table_add_options_columns',1),(67,'2019_07_16_183110_create_pending_notifications_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
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
  `client_id` int(10) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `client_id` int(10) unsigned NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `client_id` int(10) unsigned NOT NULL,
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
-- Table structure for table `observation_observation_type`
--

DROP TABLE IF EXISTS `observation_observation_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `observation_observation_type` (
  `observation_id` bigint(20) unsigned NOT NULL,
  `type_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`observation_id`,`type_id`),
  KEY `observation_observation_type_type_id_foreign` (`type_id`),
  CONSTRAINT `observation_observation_type_observation_id_foreign` FOREIGN KEY (`observation_id`) REFERENCES `observations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `observation_observation_type_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `observation_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `observation_observation_type`
--

LOCK TABLES `observation_observation_type` WRITE;
/*!40000 ALTER TABLE `observation_observation_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `observation_observation_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `observation_photo`
--

DROP TABLE IF EXISTS `observation_photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `observation_photo` (
  `observation_id` bigint(20) unsigned NOT NULL,
  `photo_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`observation_id`,`photo_id`),
  KEY `observation_photo_photo_id_foreign` (`photo_id`),
  CONSTRAINT `observation_photo_observation_id_foreign` FOREIGN KEY (`observation_id`) REFERENCES `observations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `observation_photo_photo_id_foreign` FOREIGN KEY (`photo_id`) REFERENCES `photos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `observation_photo`
--

LOCK TABLES `observation_photo` WRITE;
/*!40000 ALTER TABLE `observation_photo` DISABLE KEYS */;
/*!40000 ALTER TABLE `observation_photo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `observation_type_taxon`
--

DROP TABLE IF EXISTS `observation_type_taxon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `observation_type_taxon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `observation_type_taxon`
--

LOCK TABLES `observation_type_taxon` WRITE;
/*!40000 ALTER TABLE `observation_type_taxon` DISABLE KEYS */;
/*!40000 ALTER TABLE `observation_type_taxon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `observation_type_translations`
--

DROP TABLE IF EXISTS `observation_type_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `observation_type_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `observation_type_id` int(10) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `observation_type_translations_observation_type_id_foreign` (`observation_type_id`),
  KEY `observation_type_translations_locale_index` (`locale`),
  CONSTRAINT `observation_type_translations_observation_type_id_foreign` FOREIGN KEY (`observation_type_id`) REFERENCES `observation_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `observation_type_translations`
--

LOCK TABLES `observation_type_translations` WRITE;
/*!40000 ALTER TABLE `observation_type_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `observation_type_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `observation_types`
--

DROP TABLE IF EXISTS `observation_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `observation_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `observation_types_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `observation_types`
--

LOCK TABLES `observation_types` WRITE;
/*!40000 ALTER TABLE `observation_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `observation_types` ENABLE KEYS */;
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
  `original_identification` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` smallint(6) DEFAULT NULL,
  `month` tinyint(3) unsigned DEFAULT NULL,
  `day` tinyint(3) unsigned DEFAULT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` double(15,12) NOT NULL,
  `longitude` double(15,12) NOT NULL,
  `accuracy` int(10) unsigned DEFAULT NULL,
  `mgrs10k` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `elevation` smallint(6) DEFAULT 0,
  `observer` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identifier` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sex` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stage_id` int(10) unsigned DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` int(10) unsigned DEFAULT NULL,
  `project` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `habitat` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `found_on` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details_id` bigint(20) unsigned NOT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_by_id` int(10) unsigned DEFAULT NULL,
  `client_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dataset` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `observations_details_type_details_id_index` (`details_type`,`details_id`),
  KEY `observations_stage_id_foreign` (`stage_id`),
  KEY `observations_approved_at_index` (`approved_at`),
  KEY `observations_created_by_id_foreign` (`created_by_id`),
  KEY `observations_approved_at_taxon_id_mgrs10k_details_type_index` (`approved_at`,`taxon_id`,`mgrs10k`,`details_type`),
  CONSTRAINT `observations_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `observations_stage_id_foreign` FOREIGN KEY (`stage_id`) REFERENCES `stages` (`id`)
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
-- Table structure for table `pending_notifications`
--

DROP TABLE IF EXISTS `pending_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pending_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `notification_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `notification` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sent_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pending_notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pending_notifications`
--

LOCK TABLES `pending_notifications` WRITE;
/*!40000 ALTER TABLE `pending_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `pending_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `photos`
--

DROP TABLE IF EXISTS `photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license` smallint(5) unsigned NOT NULL,
  `metadata` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
-- Table structure for table `publication_attachments`
--

DROP TABLE IF EXISTS `publication_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publication_attachments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publication_attachments`
--

LOCK TABLES `publication_attachments` WRITE;
/*!40000 ALTER TABLE `publication_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `publication_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publications`
--

DROP TABLE IF EXISTS `publications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` smallint(5) unsigned NOT NULL,
  `authors` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `editors` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issue` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `place` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publisher` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `page_count` smallint(5) unsigned DEFAULT NULL,
  `page_range` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_id` int(10) unsigned DEFAULT NULL,
  `created_by_id` int(10) unsigned DEFAULT NULL,
  `citation` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `publications_created_by_id_foreign` (`created_by_id`),
  CONSTRAINT `publications_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publications`
--

LOCK TABLES `publications` WRITE;
/*!40000 ALTER TABLE `publications` DISABLE KEYS */;
/*!40000 ALTER TABLE `publications` ENABLE KEYS */;
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
  `category` char(30) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `red_list_translations`
--

DROP TABLE IF EXISTS `red_list_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `red_list_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `red_list_id` int(10) unsigned NOT NULL,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `red_list_translations_red_list_id_locale_unique` (`red_list_id`,`locale`),
  CONSTRAINT `red_list_translations_red_list_id_foreign` FOREIGN KEY (`red_list_id`) REFERENCES `red_lists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `red_list_translations`
--

LOCK TABLES `red_list_translations` WRITE;
/*!40000 ALTER TABLE `red_list_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `red_list_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `red_lists`
--

DROP TABLE IF EXISTS `red_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `red_lists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `red_lists_slug_unique` (`slug`)
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stages`
--

LOCK TABLES `stages` WRITE;
/*!40000 ALTER TABLE `stages` DISABLE KEYS */;
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
  `fe_old_id` int(10) unsigned DEFAULT NULL,
  `fe_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restricted` tinyint(1) NOT NULL DEFAULT 0,
  `allochthonous` tinyint(1) NOT NULL DEFAULT 0,
  `invasive` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `taxa_rank_index` (`rank`),
  KEY `taxa_rank_level_index` (`rank_level`),
  KEY `taxa_name_index` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taxa`
--

LOCK TABLES `taxa` WRITE;
/*!40000 ALTER TABLE `taxa` DISABLE KEYS */;
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
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `taxon_translations_taxon_id_locale_unique` (`taxon_id`,`locale`),
  KEY `taxon_translations_locale_index` (`locale`),
  CONSTRAINT `taxon_translations_taxon_id_foreign` FOREIGN KEY (`taxon_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taxon_translations`
--

LOCK TABLES `taxon_translations` WRITE;
/*!40000 ALTER TABLE `taxon_translations` DISABLE KEYS */;
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
/*!40000 ALTER TABLE `taxon_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taxon_view_group`
--

DROP TABLE IF EXISTS `taxon_view_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `taxon_view_group` (
  `taxon_id` int(10) unsigned NOT NULL,
  `view_group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`taxon_id`,`view_group_id`),
  KEY `taxon_view_group_view_group_id_foreign` (`view_group_id`),
  CONSTRAINT `taxon_view_group_taxon_id_foreign` FOREIGN KEY (`taxon_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `taxon_view_group_view_group_id_foreign` FOREIGN KEY (`view_group_id`) REFERENCES `view_groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taxon_view_group`
--

LOCK TABLES `taxon_view_group` WRITE;
/*!40000 ALTER TABLE `taxon_view_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `taxon_view_group` ENABLE KEYS */;
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
  `institution` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `view_group_translations`
--

DROP TABLE IF EXISTS `view_group_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `view_group_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `view_group_id` int(10) unsigned NOT NULL,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `view_group_translations_view_group_id_locale_unique` (`view_group_id`,`locale`),
  KEY `view_group_translations_locale_index` (`locale`),
  CONSTRAINT `view_group_translations_view_group_id_foreign` FOREIGN KEY (`view_group_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `view_group_translations`
--

LOCK TABLES `view_group_translations` WRITE;
/*!40000 ALTER TABLE `view_group_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `view_group_translations` ENABLE KEYS */;
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
  `image_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int(10) unsigned DEFAULT NULL,
  `only_observed_taxa` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
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

-- Dump completed on 2019-08-03 18:09:12
