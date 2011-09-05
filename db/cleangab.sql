-- Adminer 3.2.2 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `city`;
CREATE TABLE `city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE latin1_general_ci NOT NULL,
  `state_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `state_id` (`state_id`),
  CONSTRAINT `city_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `city` (`id`, `name`, `state_id`) VALUES
(1,	'São Paulo',	2),
(2,	'Curitiba',	3),
(3,	'Porto Alegre',	1),
(4,	'Rio de Janeiro',	4),
(5,	'Recife',	5);

DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dpn` char(2) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(64) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `country` (`id`, `dpn`, `name`) VALUES
(1,	'BR',	'Brasil'),
(2,	'US',	'United States');

DROP TABLE IF EXISTS `hifi`;
CREATE TABLE `hifi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` int(11) NOT NULL,
  `customer` varchar(128) COLLATE latin1_general_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `hifi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


DROP TABLE IF EXISTS `hifi_version`;
CREATE TABLE `hifi_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hifi_id` int(11) NOT NULL,
  `general_content` text COLLATE latin1_general_ci NOT NULL,
  `work_contract` text COLLATE latin1_general_ci NOT NULL,
  `benefits` text COLLATE latin1_general_ci NOT NULL,
  `taxes` text COLLATE latin1_general_ci NOT NULL,
  `equipments` text COLLATE latin1_general_ci NOT NULL,
  `provisioning` text COLLATE latin1_general_ci NOT NULL,
  `summary` text COLLATE latin1_general_ci NOT NULL,
  `created` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` set('draft','released','approved','rejected') COLLATE latin1_general_ci NOT NULL,
  `blocked` bit(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hifi_id` (`hifi_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `hifi_version_ibfk_1` FOREIGN KEY (`hifi_id`) REFERENCES `hifi` (`id`),
  CONSTRAINT `hifi_version_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


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


DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission` varchar(32) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(64) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


DROP TABLE IF EXISTS `newsletter`;
CREATE TABLE `newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `hash` varchar(32) COLLATE latin1_general_ci NOT NULL,
  `content` text COLLATE latin1_general_ci NOT NULL,
  `categories` int(11) NOT NULL,
  `posts` int(11) NOT NULL,
  `status` char(10) COLLATE latin1_general_ci NOT NULL,
  `uuid` char(32) COLLATE latin1_general_ci NOT NULL,
  `sent` datetime DEFAULT NULL,
  `recipients` text COLLATE latin1_general_ci,
  PRIMARY KEY (`id`)
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


DROP TABLE IF EXISTS `parameter`;
CREATE TABLE `parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `value` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `type` char(20) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission` char(64) COLLATE latin1_general_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `permission_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `permission` (`id`, `permission`, `user_id`) VALUES
(1,	'user_loginweb',	1),
(2,	'user_list',	1),
(3,	'user_edit',	1),
(4,	'user_new',	1),
(5,	'user_show',	1);

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


DROP TABLE IF EXISTS `state`;
CREATE TABLE `state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `name` varchar(128) COLLATE latin1_general_ci NOT NULL,
  `short_name` char(2) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`),
  CONSTRAINT `state_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `state` (`id`, `country_id`, `name`, `short_name`) VALUES
(1,	1,	'Rio Grande do Sul',	'RS'),
(2,	1,	'São Paulo',	'SP'),
(3,	1,	'Paraná',	'PR'),
(4,	1,	'Rio de Janeiro',	'RJ'),
(5,	1,	'Pernambuco',	'PE');

DROP TABLE IF EXISTS `tax`;
CREATE TABLE `tax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE latin1_general_ci NOT NULL,
  `percent` tinyint(4) NOT NULL DEFAULT '1',
  `value` float NOT NULL,
  `coverage` set('country','state','city') COLLATE latin1_general_ci NOT NULL,
  `city_id` int(11) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `city_id` (`city_id`),
  KEY `state_id` (`state_id`),
  KEY `country_id` (`country_id`),
  CONSTRAINT `tax_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`),
  CONSTRAINT `tax_ibfk_2` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`),
  CONSTRAINT `tax_ibfk_3` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `tax` (`id`, `name`, `percent`, `value`, `coverage`, `city_id`, `state_id`, `country_id`) VALUES
(1,	'ISSQN',	1,	2,	'city',	1,	1,	1),
(2,	'ICMS',	1,	15,	'state',	NULL,	1,	1);

DROP TABLE IF EXISTS `twitter`;
CREATE TABLE `twitter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` char(140) COLLATE latin1_general_ci NOT NULL,
  `full_message` char(140) COLLATE latin1_general_ci NOT NULL,
  `bitlink` char(30) COLLATE latin1_general_ci NOT NULL,
  `tag` char(20) COLLATE latin1_general_ci NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` char(12) COLLATE latin1_general_ci NOT NULL,
  `passwd` char(32) COLLATE latin1_general_ci DEFAULT NULL,
  `name` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `uuid` char(128) COLLATE latin1_general_ci DEFAULT NULL,
  `renew_passwd` int(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `active` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`),
  KEY `user_2` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `user` (`id`, `user`, `passwd`, `name`, `email`, `uuid`, `renew_passwd`, `created`, `active`) VALUES
(1,	'lbarreiro',	NULL,	'Leopoldo Barreiro',	'leopoldo.barreiro@disys.com',	NULL,	1,	'2011-09-01 09:59:27',	1);

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


-- 2011-09-05 17:22:09
