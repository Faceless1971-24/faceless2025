
CREATE TABLE `settings` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_name` VARCHAR(200) DEFAULT NULL,
  `company_address` TEXT,
  `start_time` TIME DEFAULT NULL,
  `end_time` TIME DEFAULT NULL,
  `last_login_time` TIME DEFAULT NULL,
  PRIMARY KEY (`id`)
)ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
