-- TTU CS Portal schema
CREATE DATABASE IF NOT EXISTS `ttu_cs_portal` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ttu_cs_portal`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('admin') NOT NULL DEFAULT 'admin',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `files` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NULL,
  `original_name` VARCHAR(255) NOT NULL,
  `extension` VARCHAR(10) NOT NULL,
  `mime_type` VARCHAR(150) NOT NULL,
  `size_bytes` BIGINT UNSIGNED NOT NULL,
  `category` ENUM('HND','Diploma','BTech') NOT NULL,
  `year` TINYINT UNSIGNED NOT NULL,
  `semester` TINYINT UNSIGNED NOT NULL,
  `is_public` TINYINT(1) NOT NULL DEFAULT 1,
  `uploaded_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uploaded_by` INT UNSIGNED NULL,
  `file_data` LONGBLOB NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_category_year_sem` (`category`,`year`,`semester`),
  CONSTRAINT `fk_files_users` FOREIGN KEY (`uploaded_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed admin user (password will be set by setup script)
-- INSERT INTO users (username, password_hash, role) VALUES ('admin', '<hash>', 'admin');
