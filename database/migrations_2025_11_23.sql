/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE IF NOT EXISTS `biologer-master` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `biologer-master`;

CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(191) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `subject_type` varchar(191) DEFAULT NULL,
  `event` varchar(191) DEFAULT NULL,
  `causer_id` int(11) DEFAULT NULL,
  `causer_type` varchar(191) DEFAULT NULL,
  `properties` text DEFAULT NULL,
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `creator_id` int(10) unsigned DEFAULT NULL,
  `creator_name` varchar(191) NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT 0,
  `reads` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `announcements_creator_id_foreign` (`creator_id`),
  CONSTRAINT `announcements_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `announcement_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `announcement_id` int(10) unsigned NOT NULL,
  `locale` varchar(191) NOT NULL,
  `title` varchar(191) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `announcement_translations_announcement_id_locale_unique` (`announcement_id`,`locale`),
  KEY `announcement_translations_locale_index` (`locale`),
  CONSTRAINT `announcement_translations_announcement_id_foreign` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `conservation_documents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conservation_documents_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `conservation_document_taxon` (
  `doc_id` int(10) unsigned NOT NULL,
  `taxon_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`doc_id`,`taxon_id`),
  KEY `conservation_document_taxon_taxon_id_foreign` (`taxon_id`),
  CONSTRAINT `conservation_document_taxon_doc_id_foreign` FOREIGN KEY (`doc_id`) REFERENCES `conservation_documents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `conservation_document_taxon_taxon_id_foreign` FOREIGN KEY (`taxon_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `conservation_document_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `doc_id` int(10) unsigned NOT NULL,
  `locale` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conservation_document_translations_doc_id_locale_unique` (`doc_id`,`locale`),
  CONSTRAINT `conservation_document_translations_doc_id_foreign` FOREIGN KEY (`doc_id`) REFERENCES `conservation_documents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `conservation_legislations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conservation_legislations_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `conservation_legislation_taxon` (
  `leg_id` int(10) unsigned NOT NULL,
  `taxon_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`leg_id`,`taxon_id`),
  KEY `conservation_legislation_taxon_taxon_id_foreign` (`taxon_id`),
  CONSTRAINT `conservation_legislation_taxon_leg_id_foreign` FOREIGN KEY (`leg_id`) REFERENCES `conservation_legislations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `conservation_legislation_taxon_taxon_id_foreign` FOREIGN KEY (`taxon_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `conservation_legislation_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `leg_id` int(10) unsigned NOT NULL,
  `locale` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conservation_legislation_translations_leg_id_locale_unique` (`leg_id`,`locale`),
  CONSTRAINT `conservation_legislation_translations_leg_id_foreign` FOREIGN KEY (`leg_id`) REFERENCES `conservation_legislations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `exports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `type` varchar(191) NOT NULL,
  `filter` text NOT NULL,
  `columns` text NOT NULL,
  `filename` varchar(191) NOT NULL,
  `status` varchar(191) NOT NULL,
  `locale` varchar(191) NOT NULL,
  `with_header` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) DEFAULT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `field_observations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `taxon_suggestion` varchar(255) DEFAULT NULL,
  `license` smallint(5) unsigned NOT NULL,
  `unidentifiable` tinyint(1) NOT NULL DEFAULT 0,
  `found_dead` tinyint(1) DEFAULT NULL,
  `found_dead_note` text DEFAULT NULL,
  `time` time DEFAULT NULL,
  `observed_by_id` int(10) unsigned DEFAULT NULL,
  `identified_by_id` int(10) unsigned DEFAULT NULL,
  `timed_count_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `atlas_code` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `field_observations_observed_by_id_foreign` (`observed_by_id`),
  KEY `field_observations_identified_by_id_foreign` (`identified_by_id`),
  KEY `field_observations_timed_count_id_foreign` (`timed_count_id`),
  CONSTRAINT `field_observations_identified_by_id_foreign` FOREIGN KEY (`identified_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `field_observations_observed_by_id_foreign` FOREIGN KEY (`observed_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `field_observations_timed_count_id_foreign` FOREIGN KEY (`timed_count_id`) REFERENCES `timed_count_observations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `imports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `for_user_id` int(10) unsigned DEFAULT NULL,
  `type` varchar(191) NOT NULL,
  `status` varchar(191) NOT NULL,
  `path` varchar(191) NOT NULL,
  `columns` text NOT NULL,
  `lang` varchar(191) NOT NULL,
  `has_heading` tinyint(1) NOT NULL DEFAULT 0,
  `options` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `literature_observations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `time` time DEFAULT NULL,
  `original_date` varchar(191) DEFAULT NULL,
  `original_locality` varchar(191) DEFAULT NULL,
  `original_elevation` varchar(191) DEFAULT NULL,
  `original_coordinates` varchar(191) DEFAULT NULL,
  `original_identification_validity` tinyint(4) NOT NULL,
  `other_original_data` text DEFAULT NULL,
  `collecting_start_year` smallint(5) unsigned DEFAULT NULL,
  `collecting_start_month` tinyint(3) unsigned DEFAULT NULL,
  `collecting_end_year` smallint(5) unsigned DEFAULT NULL,
  `collecting_end_month` tinyint(3) unsigned DEFAULT NULL,
  `georeferenced_by` varchar(191) DEFAULT NULL,
  `georeferenced_date` date DEFAULT NULL,
  `minimum_elevation` smallint(6) DEFAULT NULL,
  `maximum_elevation` smallint(6) DEFAULT NULL,
  `publication_id` int(10) unsigned NOT NULL,
  `is_original_data` tinyint(1) NOT NULL DEFAULT 1,
  `cited_publication_id` int(10) unsigned DEFAULT NULL,
  `place_where_referenced_in_publication` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `literature_observations_publication_id_foreign` (`publication_id`),
  KEY `literature_observations_cited_publication_id_foreign` (`cited_publication_id`),
  CONSTRAINT `literature_observations_cited_publication_id_foreign` FOREIGN KEY (`cited_publication_id`) REFERENCES `publications` (`id`),
  CONSTRAINT `literature_observations_publication_id_foreign` FOREIGN KEY (`publication_id`) REFERENCES `publications` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(191) NOT NULL,
  `notifiable_type` varchar(191) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `provider` varchar(191) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `oauth_personal_access_clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `observations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `taxon_id` int(10) unsigned DEFAULT NULL,
  `original_identification` text DEFAULT NULL,
  `year` smallint(6) DEFAULT NULL,
  `month` tinyint(3) unsigned DEFAULT NULL,
  `day` tinyint(3) unsigned DEFAULT NULL,
  `location` varchar(191) DEFAULT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `accuracy` int(10) unsigned DEFAULT NULL,
  `mgrs10k` varchar(191) DEFAULT NULL,
  `elevation` smallint(6) DEFAULT NULL,
  `observer` varchar(191) DEFAULT NULL,
  `identifier` varchar(191) DEFAULT NULL,
  `sex` varchar(191) DEFAULT NULL,
  `stage_id` int(10) unsigned DEFAULT NULL,
  `note` text DEFAULT NULL,
  `number` int(10) unsigned DEFAULT NULL,
  `project` varchar(191) DEFAULT NULL,
  `habitat` varchar(191) DEFAULT NULL,
  `found_on` varchar(191) DEFAULT NULL,
  `details_type` varchar(191) NOT NULL,
  `details_id` bigint(20) unsigned NOT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_by_id` int(10) unsigned DEFAULT NULL,
  `client_name` varchar(191) DEFAULT NULL,
  `dataset` varchar(191) DEFAULT NULL,
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

CREATE TABLE IF NOT EXISTS `observation_observation_type` (
  `observation_id` bigint(20) unsigned NOT NULL,
  `type_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`observation_id`,`type_id`),
  KEY `observation_observation_type_type_id_foreign` (`type_id`),
  CONSTRAINT `observation_observation_type_observation_id_foreign` FOREIGN KEY (`observation_id`) REFERENCES `observations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `observation_observation_type_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `observation_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `observation_photo` (
  `observation_id` bigint(20) unsigned NOT NULL,
  `photo_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`observation_id`,`photo_id`),
  KEY `observation_photo_photo_id_foreign` (`photo_id`),
  CONSTRAINT `observation_photo_observation_id_foreign` FOREIGN KEY (`observation_id`) REFERENCES `observations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `observation_photo_photo_id_foreign` FOREIGN KEY (`photo_id`) REFERENCES `photos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `observation_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `observation_types_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `observation_type_taxon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `observation_type_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `locale` varchar(191) NOT NULL,
  `observation_type_id` int(10) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `observation_type_translations_observation_type_id_foreign` (`observation_type_id`),
  KEY `observation_type_translations_locale_index` (`locale`),
  CONSTRAINT `observation_type_translations_observation_type_id_foreign` FOREIGN KEY (`observation_type_id`) REFERENCES `observation_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `pending_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `notification_id` char(36) NOT NULL,
  `notifiable_type` varchar(191) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `notification` text NOT NULL,
  `sent_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pending_notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `photos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `author` varchar(255) NOT NULL,
  `license` smallint(5) unsigned NOT NULL,
  `metadata` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `publications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(191) NOT NULL,
  `year` smallint(5) unsigned NOT NULL,
  `authors` text DEFAULT NULL,
  `editors` text DEFAULT NULL,
  `name` text DEFAULT NULL,
  `title` text NOT NULL,
  `issue` varchar(191) DEFAULT NULL,
  `place` varchar(191) DEFAULT NULL,
  `publisher` varchar(191) DEFAULT NULL,
  `page_count` smallint(5) unsigned DEFAULT NULL,
  `page_range` varchar(191) DEFAULT NULL,
  `doi` varchar(191) DEFAULT NULL,
  `link` text DEFAULT NULL,
  `attachment_id` int(10) unsigned DEFAULT NULL,
  `created_by_id` int(10) unsigned DEFAULT NULL,
  `citation` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `publications_created_by_id_foreign` (`created_by_id`),
  CONSTRAINT `publications_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `publication_attachments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(191) NOT NULL,
  `original_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `red_lists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `red_lists_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `red_list_taxon` (
  `red_list_id` int(10) unsigned NOT NULL,
  `taxon_id` int(10) unsigned NOT NULL,
  `category` varchar(30) NOT NULL,
  PRIMARY KEY (`red_list_id`,`taxon_id`),
  KEY `red_list_taxon_taxon_id_foreign` (`taxon_id`),
  CONSTRAINT `red_list_taxon_red_list_id_foreign` FOREIGN KEY (`red_list_id`) REFERENCES `red_lists` (`id`) ON DELETE CASCADE,
  CONSTRAINT `red_list_taxon_taxon_id_foreign` FOREIGN KEY (`taxon_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `red_list_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `red_list_id` int(10) unsigned NOT NULL,
  `locale` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `red_list_translations_red_list_id_locale_unique` (`red_list_id`,`locale`),
  CONSTRAINT `red_list_translations_red_list_id_foreign` FOREIGN KEY (`red_list_id`) REFERENCES `red_lists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `role_user` (
  `role_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`user_id`),
  KEY `role_user_user_id_foreign` (`user_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `stages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stages_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `stage_taxon` (
  `stage_id` int(10) unsigned NOT NULL,
  `taxon_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`stage_id`,`taxon_id`),
  KEY `stage_taxon_taxon_id_foreign` (`taxon_id`),
  CONSTRAINT `stage_taxon_stage_id_foreign` FOREIGN KEY (`stage_id`) REFERENCES `stages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stage_taxon_taxon_id_foreign` FOREIGN KEY (`taxon_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `synonyms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `author` varchar(50) DEFAULT NULL,
  `taxon_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `synonyms_taxon_id_foreign` (`taxon_id`),
  CONSTRAINT `synonyms_taxon_id_foreign` FOREIGN KEY (`taxon_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `taxa` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `rank` varchar(191) NOT NULL,
  `rank_level` int(10) unsigned NOT NULL,
  `author` varchar(191) DEFAULT NULL,
  `fe_old_id` int(10) unsigned DEFAULT NULL,
  `fe_id` varchar(191) DEFAULT NULL,
  `restricted` tinyint(1) NOT NULL DEFAULT 0,
  `allochthonous` tinyint(1) NOT NULL DEFAULT 0,
  `invasive` tinyint(1) NOT NULL DEFAULT 0,
  `uses_atlas_codes` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ancestors_names` varchar(1000) DEFAULT NULL,
  `taxonomy_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `taxa_taxonomy_id_unique` (`taxonomy_id`),
  KEY `taxa_rank_index` (`rank`),
  KEY `taxa_rank_level_index` (`rank_level`),
  KEY `taxa_name_index` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `taxon_ancestors` (
  `model_id` int(10) unsigned NOT NULL,
  `ancestor_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`model_id`,`ancestor_id`),
  KEY `taxon_ancestors_ancestor_id_foreign` (`ancestor_id`),
  CONSTRAINT `taxon_ancestors_ancestor_id_foreign` FOREIGN KEY (`ancestor_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `taxon_ancestors_model_id_foreign` FOREIGN KEY (`model_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `taxon_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `taxon_id` int(10) unsigned NOT NULL,
  `locale` varchar(191) NOT NULL,
  `native_name` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `taxon_translations_taxon_id_locale_unique` (`taxon_id`,`locale`),
  KEY `taxon_translations_locale_index` (`locale`),
  CONSTRAINT `taxon_translations_taxon_id_foreign` FOREIGN KEY (`taxon_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `taxon_user` (
  `taxon_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`taxon_id`,`user_id`),
  KEY `taxon_user_user_id_foreign` (`user_id`),
  CONSTRAINT `taxon_user_taxon_id_foreign` FOREIGN KEY (`taxon_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `taxon_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `taxon_view_group` (
  `taxon_id` int(10) unsigned NOT NULL,
  `view_group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`taxon_id`,`view_group_id`),
  KEY `taxon_view_group_view_group_id_foreign` (`view_group_id`),
  CONSTRAINT `taxon_view_group_taxon_id_foreign` FOREIGN KEY (`taxon_id`) REFERENCES `taxa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `taxon_view_group_view_group_id_foreign` FOREIGN KEY (`view_group_id`) REFERENCES `view_groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `timed_count_observations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `year` smallint(6) DEFAULT NULL,
  `month` tinyint(3) unsigned DEFAULT NULL,
  `day` tinyint(3) unsigned DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `count_duration` int(10) unsigned DEFAULT NULL,
  `cloud_cover` smallint(5) unsigned DEFAULT NULL,
  `atmospheric_pressure` double unsigned DEFAULT NULL,
  `humidity` int(10) unsigned DEFAULT NULL,
  `temperature` double DEFAULT NULL,
  `wind_direction` enum('N','NE','E','SE','S','SW','W','NW') DEFAULT NULL,
  `wind_speed` smallint(5) unsigned DEFAULT NULL,
  `habitat` text DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `area` int(10) unsigned DEFAULT NULL,
  `route_length` int(10) unsigned DEFAULT NULL,
  `observer` varchar(191) DEFAULT NULL,
  `observed_by_id` int(10) unsigned DEFAULT NULL,
  `created_by_id` int(10) unsigned DEFAULT NULL,
  `view_groups_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `timed_count_observations_observed_by_id_foreign` (`observed_by_id`),
  KEY `timed_count_observations_created_by_id_foreign` (`created_by_id`),
  KEY `timed_count_observations_view_groups_id_foreign` (`view_groups_id`),
  CONSTRAINT `timed_count_observations_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
  CONSTRAINT `timed_count_observations_observed_by_id_foreign` FOREIGN KEY (`observed_by_id`) REFERENCES `users` (`id`),
  CONSTRAINT `timed_count_observations_view_groups_id_foreign` FOREIGN KEY (`view_groups_id`) REFERENCES `view_groups` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `fcm_token` varchar(191) DEFAULT NULL,
  `institution` varchar(191) DEFAULT NULL,
  `settings` text DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `view_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `image_url` varchar(191) DEFAULT NULL,
  `sort_order` int(10) unsigned DEFAULT NULL,
  `only_observed_taxa` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image_path` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `view_groups_parent_id_index` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `view_group_taxa` (
	`taxon_id` INT(10) UNSIGNED NOT NULL,
	`view_group_id` INT(10) UNSIGNED NOT NULL
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `view_group_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `view_group_id` int(10) unsigned NOT NULL,
  `locale` varchar(191) NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `view_group_translations_view_group_id_locale_unique` (`view_group_id`,`locale`),
  KEY `view_group_translations_locale_index` (`locale`),
  CONSTRAINT `view_group_translations_view_group_id_foreign` FOREIGN KEY (`view_group_id`) REFERENCES `view_groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `view_group_taxa`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `view_group_taxa` AS (select `taxa`.`id` AS `taxon_id`,`taxon_view_group`.`view_group_id` AS `view_group_id` from (`taxa` join `taxon_view_group` on(`taxon_view_group`.`taxon_id` = `taxa`.`id`))) union (select `ancestors`.`id` AS `taxon_id`,`taxon_view_group`.`view_group_id` AS `view_group_id` from ((`taxa` `ancestors` join `taxon_ancestors` on(`taxon_ancestors`.`model_id` = `ancestors`.`id`)) join `taxon_view_group` on(`taxon_ancestors`.`ancestor_id` = `taxon_view_group`.`taxon_id`)))
;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
