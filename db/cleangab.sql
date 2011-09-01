-- Adminer 3.2.2 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `operation` char(20) COLLATE latin1_general_ci NOT NULL,
  `detail` varchar(128) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


DROP TABLE IF EXISTS `option`;
CREATE TABLE `option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(60) COLLATE latin1_general_ci NOT NULL,
  `value` text COLLATE latin1_general_ci NOT NULL,
  `type` char(20) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission` char(64) COLLATE latin1_general_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `permission_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


DROP TABLE IF EXISTS `provider`;
CREATE TABLE `provider` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `sigla` char(20) COLLATE latin1_general_ci NOT NULL,
  `name` char(60) COLLATE latin1_general_ci NOT NULL,
  `site` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `url_request_token` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `url_authorize` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `url_access_token` varchar(120) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sigla` (`sigla`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` char(12) COLLATE latin1_general_ci NOT NULL,
  `passwd` char(32) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `uuid` char(128) COLLATE latin1_general_ci DEFAULT NULL,
  `renew_passwd` int(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `active` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`),
  KEY `user_2` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


DROP TABLE IF EXISTS `user_provider`;
CREATE TABLE `user_provider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `provider_id` int(4) NOT NULL,
  `access_token` char(128) COLLATE latin1_general_ci NOT NULL,
  `access_token_secret` char(128) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `provider_id` (`provider_id`),
  CONSTRAINT `user_provider_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `user_provider_ibfk_2` FOREIGN KEY (`provider_id`) REFERENCES `provider` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


-- 2011-08-31 23:54:11
