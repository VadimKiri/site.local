CREATE DATABASE `ads`;

CREATE TABLE `ads` (
  `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `created_at` TIMESTAMP NOT NULL,
  `text` text NOT NULL,
  `contacts` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
