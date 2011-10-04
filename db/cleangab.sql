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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `city` (`id`, `name`, `state_id`) VALUES
(1,	'São Paulo',	2),
(2,	'Curitiba',	3),
(3,	'Porto Alegre',	1),
(4,	'Rio de Janeiro',	4),
(5,	'Recife',	5),
(6,	'Florianópolis',	6);

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

DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE latin1_general_ci NOT NULL,
  `city_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `city_id` (`city_id`),
  CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `customer` (`id`, `name`, `city_id`) VALUES
(1,	'Exxon Mobil',	2),
(2,	'Getnet Tecnologia',	3),
(3,	'Banco Topázio',	3),
(4,	'Kraft Foods',	1);

DROP TABLE IF EXISTS `hifi`;
CREATE TABLE `hifi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(128) COLLATE latin1_general_ci NOT NULL,
  `customer` varchar(128) COLLATE latin1_general_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `approved` datetime DEFAULT NULL,
  `status` set('draft','released','approved','suspended','rejected','analyzed','blocked') COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `hifi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `hifi` (`id`, `uuid`, `name`, `customer`, `user_id`, `created`, `approved`, `status`) VALUES
(1,	'LT111004',	'Linus Torvalds',	'Getnet Tecnologia',	1,	'2011-10-04 14:07:16',	NULL,	'draft');

DROP TABLE IF EXISTS `hifi_version`;
CREATE TABLE `hifi_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hifi_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `general` text COLLATE latin1_general_ci NOT NULL,
  `work_contract` text COLLATE latin1_general_ci NOT NULL,
  `benefits` text COLLATE latin1_general_ci NOT NULL,
  `special_benefits` text COLLATE latin1_general_ci,
  `provisioning` text COLLATE latin1_general_ci NOT NULL,
  `contributions` text COLLATE latin1_general_ci NOT NULL,
  `comissions` text COLLATE latin1_general_ci NOT NULL,
  `taxes` text COLLATE latin1_general_ci NOT NULL,
  `overview` text COLLATE latin1_general_ci,
  `status` set('draft','released','approved','suspended','rejected','analyzed','blocked') COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hifi_id` (`hifi_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `hifi_version_ibfk_1` FOREIGN KEY (`hifi_id`) REFERENCES `hifi` (`id`),
  CONSTRAINT `hifi_version_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `hifi_version` (`id`, `hifi_id`, `user_id`, `created`, `general`, `work_contract`, `benefits`, `special_benefits`, `provisioning`, `contributions`, `comissions`, `taxes`, `overview`, `status`) VALUES
(1,	1,	1,	'2011-10-04 14:06:30',	'O:8:\"stdClass\":12:{s:2:\"id\";b:0;s:11:\"colaborador\";s:14:\"Linus Torvalds\";s:7:\"cliente\";s:17:\"Getnet Tecnologia\";s:6:\"status\";s:5:\"draft\";s:4:\"uuid\";s:8:\"LT111004\";s:11:\"margemlucro\";s:5:\"27.00\";s:15:\"taxahoracliente\";s:6:\"160.00\";s:9:\"valornota\";s:8:\"28160.00\";s:14:\"datareferencia\";s:10:\"2011-10-04\";s:8:\"customes\";s:8:\"18262.72\";s:9:\"custohora\";s:6:\"103.77\";s:10:\"lucrobruto\";s:7:\"7602.24\";}',	'O:8:\"stdClass\":6:{s:6:\"funcao\";s:27:\"Analista de Desenvolvimento\";s:5:\"nivel\";s:1:\"S\";s:6:\"cidade\";s:1:\"3\";s:12:\"salariobruto\";s:8:\"10000.00\";s:12:\"cargahoraria\";s:3:\"176\";s:8:\"vigencia\";s:2:\"24\";}',	'O:8:\"stdClass\":18:{s:12:\"valerefeicao\";s:6:\"240.00\";s:10:\"segurovida\";s:5:\"18.00\";s:11:\"vt_customes\";s:6:\"108.00\";s:9:\"vt_diaria\";s:4:\"5.40\";s:11:\"vt_desconto\";s:6:\"108.00\";s:16:\"vt_contrapartida\";s:4:\"0.00\";s:13:\"idade_titular\";s:2:\"50\";s:13:\"custo_titular\";s:6:\"498.90\";s:10:\"idade_dep1\";s:2:\"40\";s:10:\"custo_dep1\";s:5:\"64.28\";s:10:\"idade_dep2\";b:0;s:10:\"custo_dep2\";b:0;s:10:\"idade_dep3\";b:0;s:10:\"custo_dep3\";b:0;s:10:\"idade_dep4\";b:0;s:10:\"custo_dep4\";b:0;s:19:\"planosaude_subtotal\";s:6:\"563.18\";s:8:\"subtotal\";s:6:\"821.18\";}',	'O:8:\"stdClass\":4:{s:6:\"laptop\";s:6:\"166.00\";s:5:\"radio\";s:5:\"30.00\";s:2:\"pa\";s:5:\"55.00\";s:8:\"subtotal\";s:6:\"251.00\";}',	'O:8:\"stdClass\":8:{s:14:\"decimoterceiro\";s:6:\"833.33\";s:18:\"fgtsdecimoterceiro\";s:5:\"66.67\";s:18:\"inssdecimoterceiro\";s:6:\"223.33\";s:6:\"ferias\";s:6:\"833.33\";s:15:\"adicionalferias\";s:6:\"277,78\";s:8:\"rescisao\";s:7:\"1233.33\";s:12:\"reclamatoria\";s:6:\"100.00\";s:8:\"subtotal\";s:7:\"3567.78\";}',	'O:8:\"stdClass\":3:{s:4:\"fgts\";s:6:\"800.00\";s:4:\"inss\";s:7:\"2680.00\";s:8:\"subtotal\";s:7:\"3480.00\";}',	'O:8:\"stdClass\":26:{s:12:\"overhead_pct\";s:4:\"4.00\";s:8:\"overhead\";s:7:\"1126.40\";s:21:\"profit_after_overhead\";s:7:\"6475.84\";s:20:\"accountexecutive_pct\";s:4:\"6.50\";s:21:\"localsalesmanager_pct\";s:4:\"0.75\";s:24:\"regionalsalesmanager_pct\";s:4:\"0.75\";s:24:\"nationalsalesmanager_pct\";s:4:\"0.75\";s:18:\"countrymanager_pct\";s:4:\"0.75\";s:9:\"other_pct\";s:4:\"4.00\";s:12:\"subtotal_pct\";d:13.5;s:16:\"accountexecutive\";s:6:\"420.93\";s:17:\"localsalesmanager\";s:5:\"48.57\";s:20:\"regionalsalesmanager\";s:5:\"48.57\";s:20:\"nationalsalesmanager\";s:5:\"48.57\";s:14:\"countrymanager\";s:5:\"48.57\";s:5:\"other\";s:6:\"259.03\";s:8:\"subtotal\";s:6:\"874.24\";s:11:\"overhead_hr\";s:4:\"6.40\";s:24:\"profit_after_overhead_hr\";s:5:\"36.79\";s:19:\"accountexecutive_hr\";s:4:\"2.39\";s:20:\"localsalesmanager_hr\";s:4:\"0.28\";s:23:\"regionalsalesmanager_hr\";s:4:\"0.28\";s:23:\"nationalsalesmanager_hr\";s:4:\"0.28\";s:17:\"countrymanager_hr\";s:4:\"0.28\";s:8:\"other_hr\";s:4:\"1.47\";s:11:\"subtotal_hr\";s:4:\"4.97\";}',	'O:8:\"stdClass\":7:{s:6:\"cidade\";s:1:\"3\";s:5:\"issqn\";s:6:\"563.20\";s:4:\"csll\";s:6:\"281.60\";s:6:\"cofins\";s:6:\"844.80\";s:3:\"pis\";s:6:\"183.04\";s:4:\"irpj\";s:6:\"422.40\";s:8:\"subtotal\";s:7:\"2295.04\";}',	'O:8:\"stdClass\":11:{s:18:\"work_contract_year\";s:9:\"219152.64\";s:16:\"labor_taxes_year\";s:8:\"41760.00\";s:17:\"provisioning_year\";s:8:\"42813.36\";s:21:\"general_benefits_year\";s:7:\"9854.16\";s:21:\"special_benefits_year\";s:7:\"3012.00\";s:10:\"taxes_year\";s:8:\"27540.48\";s:17:\"gross_profit_year\";s:8:\"91226.88\";s:13:\"overhead_year\";s:8:\"13516.80\";s:15:\"comissions_year\";s:8:\"10490.88\";s:23:\"profit_after_comissions\";s:7:\"5601.60\";s:28:\"profit_after_comissions_year\";s:8:\"67219.20\";}',	'draft'),
(2,	1,	1,	'2011-10-04 14:07:16',	'O:8:\"stdClass\":12:{s:2:\"id\";s:1:\"1\";s:11:\"colaborador\";s:14:\"Linus Torvalds\";s:7:\"cliente\";s:17:\"Getnet Tecnologia\";s:6:\"status\";s:5:\"draft\";s:4:\"uuid\";s:8:\"LT111004\";s:11:\"margemlucro\";s:5:\"26.20\";s:15:\"taxahoracliente\";s:6:\"160.00\";s:9:\"valornota\";s:8:\"28800.00\";s:14:\"datareferencia\";s:10:\"2011-10-04\";s:8:\"customes\";s:8:\"18907.78\";s:9:\"custohora\";s:6:\"105.04\";s:10:\"lucrobruto\";s:7:\"7545.02\";}',	'O:8:\"stdClass\":6:{s:6:\"funcao\";s:27:\"Analista de Desenvolvimento\";s:5:\"nivel\";s:1:\"S\";s:6:\"cidade\";s:1:\"3\";s:12:\"salariobruto\";s:8:\"10000.00\";s:12:\"cargahoraria\";s:3:\"180\";s:8:\"vigencia\";s:2:\"24\";}',	'O:8:\"stdClass\":18:{s:12:\"valerefeicao\";s:6:\"240.00\";s:10:\"segurovida\";s:5:\"18.00\";s:11:\"vt_customes\";s:6:\"108.00\";s:9:\"vt_diaria\";s:4:\"5.40\";s:11:\"vt_desconto\";s:6:\"108.00\";s:16:\"vt_contrapartida\";s:4:\"0.00\";s:13:\"idade_titular\";s:2:\"50\";s:13:\"custo_titular\";s:6:\"498.90\";s:10:\"idade_dep1\";s:2:\"40\";s:10:\"custo_dep1\";s:5:\"64.28\";s:10:\"idade_dep2\";b:0;s:10:\"custo_dep2\";b:0;s:10:\"idade_dep3\";b:0;s:10:\"custo_dep3\";b:0;s:10:\"idade_dep4\";b:0;s:10:\"custo_dep4\";b:0;s:19:\"planosaude_subtotal\";s:6:\"563.18\";s:8:\"subtotal\";s:6:\"821.18\";}',	'O:8:\"stdClass\":4:{s:6:\"laptop\";s:6:\"166.00\";s:5:\"radio\";s:5:\"30.00\";s:2:\"pa\";s:5:\"55.00\";s:8:\"subtotal\";s:6:\"251.00\";}',	'O:8:\"stdClass\":8:{s:14:\"decimoterceiro\";s:6:\"833.33\";s:18:\"fgtsdecimoterceiro\";s:5:\"66.67\";s:18:\"inssdecimoterceiro\";s:6:\"223.33\";s:6:\"ferias\";s:6:\"833.33\";s:15:\"adicionalferias\";s:6:\"277,78\";s:8:\"rescisao\";s:7:\"1233.33\";s:12:\"reclamatoria\";s:6:\"100.00\";s:8:\"subtotal\";s:7:\"3567.78\";}',	'O:8:\"stdClass\":3:{s:4:\"fgts\";s:6:\"800.00\";s:4:\"inss\";s:7:\"2680.00\";s:8:\"subtotal\";s:7:\"3480.00\";}',	'O:8:\"stdClass\":26:{s:12:\"overhead_pct\";s:4:\"4.00\";s:8:\"overhead\";s:7:\"1152.00\";s:21:\"profit_after_overhead\";s:7:\"6393.02\";s:20:\"accountexecutive_pct\";s:4:\"6.50\";s:21:\"localsalesmanager_pct\";s:4:\"0.75\";s:24:\"regionalsalesmanager_pct\";s:4:\"0.75\";s:24:\"nationalsalesmanager_pct\";s:4:\"0.75\";s:18:\"countrymanager_pct\";s:4:\"0.75\";s:9:\"other_pct\";s:4:\"4.00\";s:12:\"subtotal_pct\";d:13.5;s:16:\"accountexecutive\";s:6:\"415.55\";s:17:\"localsalesmanager\";s:5:\"47.95\";s:20:\"regionalsalesmanager\";s:5:\"47.95\";s:20:\"nationalsalesmanager\";s:5:\"47.95\";s:14:\"countrymanager\";s:5:\"47.95\";s:5:\"other\";s:6:\"255.72\";s:8:\"subtotal\";s:6:\"863.06\";s:11:\"overhead_hr\";s:4:\"6.40\";s:24:\"profit_after_overhead_hr\";s:5:\"35.52\";s:19:\"accountexecutive_hr\";s:4:\"2.31\";s:20:\"localsalesmanager_hr\";s:4:\"0.27\";s:23:\"regionalsalesmanager_hr\";s:4:\"0.27\";s:23:\"nationalsalesmanager_hr\";s:4:\"0.27\";s:17:\"countrymanager_hr\";s:4:\"0.27\";s:8:\"other_hr\";s:4:\"1.42\";s:11:\"subtotal_hr\";s:4:\"4.79\";}',	'O:8:\"stdClass\":7:{s:6:\"cidade\";s:1:\"3\";s:5:\"issqn\";s:6:\"576.00\";s:4:\"csll\";s:6:\"288.00\";s:6:\"cofins\";s:6:\"864.00\";s:3:\"pis\";s:6:\"187.20\";s:4:\"irpj\";s:6:\"432.00\";s:8:\"subtotal\";s:7:\"2347.20\";}',	'O:8:\"stdClass\":11:{s:18:\"work_contract_year\";s:9:\"226893.36\";s:16:\"labor_taxes_year\";s:8:\"41760.00\";s:17:\"provisioning_year\";s:8:\"42813.36\";s:21:\"general_benefits_year\";s:7:\"9854.16\";s:21:\"special_benefits_year\";s:7:\"3012.00\";s:10:\"taxes_year\";s:8:\"28166.40\";s:17:\"gross_profit_year\";s:8:\"90540.24\";s:13:\"overhead_year\";s:8:\"13824.00\";s:15:\"comissions_year\";s:8:\"10356.72\";s:23:\"profit_after_comissions\";s:7:\"5529.96\";s:28:\"profit_after_comissions_year\";s:8:\"66359.52\";}',	'draft');

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


DROP TABLE IF EXISTS `medical_plan`;
CREATE TABLE `medical_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `age_min` int(11) NOT NULL,
  `age_max` int(11) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `medical_plan` (`id`, `age_min`, `age_max`, `cost`) VALUES
(1,	0,	18,	144.76),
(2,	19,	23,	193.39),
(3,	24,	28,	258.36),
(4,	29,	33,	288.43),
(5,	34,	38,	293.66),
(6,	39,	43,	321.39),
(7,	44,	48,	378.63),
(8,	49,	53,	498.90),
(9,	54,	58,	553.19),
(10,	59,	120,	868.58);

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
  `description` varchar(128) COLLATE latin1_general_ci DEFAULT NULL,
  `value` text COLLATE latin1_general_ci NOT NULL,
  `type` set('percent','string','integer','decimal') COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `option` (`id`, `name`, `description`, `value`, `type`) VALUES
(1,	'app_version',	NULL,	'0.1',	'string'),
(2,	'medical_plan_employee',	NULL,	'100',	'percent'),
(3,	'medical_plan_dependent',	NULL,	'20',	'percent');

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
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


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


DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `description` varchar(256) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `role` (`id`, `name`, `description`) VALUES
(1,	'Analista de Negócios',	''),
(2,	'Analista de Sistemas',	''),
(3,	'Analista de Desenvolvimento',	''),
(4,	'Programador Web',	''),
(5,	'Programador Power Builder',	'');

DROP TABLE IF EXISTS `role_city`;
CREATE TABLE `role_city` (
  `role_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  KEY `role_id` (`role_id`),
  KEY `city_id` (`city_id`),
  CONSTRAINT `role_city_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
  CONSTRAINT `role_city_ibfk_2` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`)
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `state` (`id`, `country_id`, `name`, `short_name`) VALUES
(1,	1,	'Rio Grande do Sul',	'RS'),
(2,	1,	'São Paulo',	'SP'),
(3,	1,	'Paraná',	'PR'),
(4,	1,	'Rio de Janeiro',	'RJ'),
(5,	1,	'Pernambuco',	'PE'),
(6,	1,	'Santa Catarina',	'SC');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `tax` (`id`, `name`, `percent`, `value`, `coverage`, `city_id`, `state_id`, `country_id`) VALUES
(1,	'ISSQN',	1,	2,	'city',	1,	NULL,	NULL),
(2,	'ISSQN',	1,	2,	'city',	2,	NULL,	NULL),
(3,	'ISSQN',	1,	2,	'city',	3,	NULL,	NULL),
(4,	'ISSQN',	1,	2,	'city',	4,	NULL,	NULL),
(5,	'ISSQN',	1,	5,	'city',	5,	NULL,	NULL),
(6,	'ISSQN',	1,	4,	'city',	6,	NULL,	NULL);

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
  `user` char(32) COLLATE latin1_general_ci NOT NULL,
  `passwd` char(32) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `uuid` char(128) COLLATE latin1_general_ci DEFAULT NULL,
  `renew_passwd` int(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `active` int(1) NOT NULL,
  `first_page` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`),
  KEY `user_2` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=203 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `user` (`id`, `user`, `passwd`, `name`, `email`, `uuid`, `renew_passwd`, `created`, `active`, `first_page`) VALUES
(1,	'lbarreiro',	'e10adc3949ba59abbe56e057f20f883e',	'Leopoldo Barreiro',	'leopoldo.barreiro@disys.com',	'ZMU61XFC4OVj0504q7xrfqh80656d0w1o67rny',	0,	NULL,	1,	'hifi_list'),
(2,	'riboli',	'd41d8cd98f00b204e9800998ecf8427e',	'Eduardo Riboli',	'eduardo.riboli@disys.com',	'XDP47IDH2XHu5636c3jfguo84838q6g5t80nee',	0,	'2011-08-31 06:42:38',	1,	NULL),
(3,	'hillary',	'e10adc3949ba59abbe56e057f20f883e',	'Hillary A. Moran',	'conteste@proteste.org',	'AYR78SGZ8BLz1668g8semuk84478u4z8l65gjv',	0,	'2010-05-03 06:15:44',	1,	'hifi_list'),
(4,	'Sonia',	'e10adc3949ba59abbe56e057f20f883e',	'Sonia D. Camacho',	'sucuranela@calango.net',	'KSD83SBT5GZk9232d2uwwoc26212d8o5n73xkk',	0,	'2010-07-24 18:26:00',	1,	NULL),
(5,	'Marvin',	'd41d8cd98f00b204e9800998ecf8427e',	'Marvin M. Willie',	'peder.nonummy.ut@metus.com',	'CPW62FNS6ERg7086t6rufnr35817c1b1u98bdi',	1,	'2011-11-10 23:17:26',	1,	NULL),
(6,	'Jorden',	'd41d8cd98f00b204e9800998ecf8427e',	'Jorden Yogurten',	'elementum@terra.com',	'HLS03NDN8XHj3367d6zpgzg36200i4p6w36gwv',	0,	'2010-01-15 23:19:04',	1,	NULL),
(7,	'Orla',	'd41d8cd98f00b204e9800998ecf8427e',	'Sandra Gallowa',	'nec.eleifend@lectus.ca.uk',	'LCN34FOI0IBt3186w7eeogt45781s3z1l54xsb',	0,	'2009-06-22 11:21:54',	1,	NULL),
(8,	'Gray',	'd41d8cd98f00b204e9800998ecf8427e',	'Rose de Cervantes',	'rose.cervantes@loremvehiculaet.org',	'FMT60GNS3SLr9933h7pjjaz11050w0q0o37org',	0,	'2011-10-19 11:17:54',	1,	NULL),
(9,	'Wynne',	'd41d8cd98f00b204e9800998ecf8427e',	'Timothy Free',	'timothyfrye@terra.com.br',	'ZTH51ZCU2JTf0417l4thjcm24837u4y7e22hfk',	0,	'2010-06-08 23:15:32',	1,	NULL),
(10,	'Priscilla',	'd41d8cd98f00b204e9800998ecf8427e',	'Jillian W. Jensen',	'taciturno@venon.net',	'QOW87DCA5EMh0112y7xeexu63657v1u4t62ouj',	1,	'2012-03-01 05:39:47',	0,	NULL),
(11,	'Kylynn',	'BQO27UDO7IT',	'Lucas K. Burt',	'Nullam.vitae.diam@dignissimmagnaa.org',	'EMB91IMY6BHg1983s6mhivb99497l6h6c35gqz',	0,	'2012-01-14 12:13:23',	0,	NULL),
(12,	'Aline',	'MPD44MCA9KJ',	'Quinn H. Hoover',	'vulputate.nisi.sem@Cras.com',	'VSY90LER4JZm8562z0jabtj81650w9l7q36rvt',	0,	'2009-11-08 21:45:16',	0,	NULL),
(13,	'Brenda',	'ITY93FXM5XN',	'Eric J. Foley',	'ut.mi@Uttinciduntorci.ca',	'VAO44EDG9MOb8307s1petns08058y1r7e22pjj',	1,	'2009-08-14 05:49:21',	0,	NULL),
(14,	'Shoshana',	'4297f44b13955235245b2497399d7a93',	'Kay J. Franco Mr.',	'iaculis.aliquet@Praesenteunulla.com',	'BVB20HVK6STn1118m5gbowk01745r6v7k89lev',	1,	'2010-03-12 01:09:12',	1,	NULL),
(15,	'Keiko',	'QKQ75MFD7HS',	'MacKenzie U. Newman',	'est.Nunc.laoreet@Donec.edu',	'VDW20VSF6PCf2489b3dfcky70745y8q7z40ais',	0,	'2011-09-05 17:46:11',	1,	NULL),
(16,	'Felix',	'IEN80LWJ5CM',	'Aileen E. Atkins',	'vel@nonjustoProin.org',	'MFL46MMA2SZn0108f4spcvb18225x8g3b78tei',	1,	'2011-04-23 00:11:38',	0,	NULL),
(17,	'Alisa',	'TXB99XDL6TN',	'Jade Q. Rodgers',	'ullamcorper@erosProinultrices.ca',	'RDI78EMA4FJn9269f5brduf65895r7n3x80szn',	1,	'2010-05-31 21:21:16',	0,	NULL),
(18,	'Paula',	'CAS36QQD7HH',	'Joseph X. Johnson',	'nec@loremsitamet.ca',	'OEI40KEA9XMr0543d0ahthp27430h9c8e43evn',	0,	'2009-12-19 23:43:57',	1,	NULL),
(19,	'Herrod',	'RLK28UJW8QQ',	'Blaze W. Delacruz',	'turpis.In.condimentum@mauris.edu',	'EBW34FMH7RBc3455i3xfdov27615w0n0d43ppv',	1,	'2012-04-14 15:46:42',	0,	NULL),
(20,	'Judah',	'YKW49KTG3YJ',	'Skyler P. Rogers',	'ipsum.sodales@Maurisquisturpis.com',	'VRJ90ROR5LZa3488i2ppooy43508h2d0x40zep',	1,	'2009-07-30 07:11:28',	1,	NULL),
(21,	'Alexander',	'RRK05GLV8AJ',	'Guinevere V. Wilkerson',	'arcu.Sed.eu@seddolorFusce.ca',	'DQX01YBH5SZo3694f2xaayk38204c5g2g12jci',	1,	'2009-01-29 18:48:36',	1,	NULL),
(22,	'Alvin',	'PDL20XMH8MI',	'Phoebe S. Sparks',	'et.netus@iaculisodioNam.edu',	'KIK64RAO9RDa2245j3zrflz93763o6o9a96mpq',	0,	'2011-06-19 11:57:36',	1,	NULL),
(23,	'Eagan',	'EQO40BVL0MQ',	'Karen P. Kerr',	'dui.nec.urna@suscipit.ca',	'XUS97IKI9LXg6570p8xbxks48221n4w4g63zyt',	0,	'2011-05-27 08:08:11',	1,	NULL),
(24,	'Charissa',	'FEU79WIV7KT',	'Chaney F. Boone',	'dignissim@infelis.ca',	'YBH26QFQ3MOo7734s2uoaei12526z4n9n81flj',	1,	'2011-01-12 03:49:20',	1,	NULL),
(26,	'Kameko',	'UXK69KEB2RY',	'Kaden U. Cain',	'auctor.nunc@faucibusleoin.ca',	'WYT24DWE1DPr9505m4sotkn25272e3b0g82rzd',	1,	'2009-11-12 03:33:43',	0,	NULL),
(27,	'Denton',	'LUE98SLJ4ZC',	'Sandra J. Kirk',	'lacinia.mattis@eget.ca',	'JSA65HHM4DQr5485h5yqayt84112e8f5n21ank',	1,	'2009-11-09 14:51:19',	1,	NULL),
(28,	'Kiona',	'DPC54QWA5NA',	'Joshua W. Edwards',	'erat.Vivamus.nisi@dictum.org',	'WKB36FKE6XCe0626d9mdmzd92096b0d9m22dkr',	0,	'2011-05-26 18:18:18',	0,	NULL),
(29,	'Leroy',	'KFQ04GFK7JW',	'Naida V. Lane',	'ridiculus.mus.Proin@nibhvulputatemauris.org',	'VRM28TLH4TIa1562a9mkqvg33028v7l6m99isj',	1,	'2011-03-07 15:38:01',	0,	NULL),
(30,	'Beck',	'YFG48MLU9WL',	'Risa U. Burke',	'nonummy@lobortis.edu',	'VNQ24OQE9GIa1526k7cfsyr50462m7h3g95smf',	1,	'2009-02-04 10:26:17',	0,	NULL),
(31,	'Jesse',	'FLR76IFK4KD',	'Hamilton Q. Bowers',	'placerat.Cras@faucibus.edu',	'BAI29XZM1WWq1328z6bkbmn45181m5d5q48pju',	0,	'2011-01-07 14:27:10',	0,	NULL),
(32,	'Melyssa',	'YCT24QFA7PB',	'Christine M. Burns',	'In.faucibus.Morbi@risusa.org',	'YAG54BDV8EHn1423v9ovqex82365s4h6m51ypi',	1,	'2009-11-28 17:22:37',	0,	NULL),
(33,	'Kareem',	'BZQ42ZHE5DV',	'Moses R. Anderson',	'placerat.velit.Quisque@magnis.edu',	'TVT23YFZ2YLu0334q3uvxxr59352p0j3w15gbu',	0,	'2012-04-20 17:19:09',	0,	NULL),
(34,	'Patricia',	'QBT04GRV0NS',	'Thaddeus H. Clements',	'Donec.tempor.est@risusDonecegestas.edu',	'XMK76ZAQ7GYn8029n5piodb43327f3w1v29lgx',	0,	'2010-02-24 23:15:29',	0,	NULL),
(35,	'Cameron',	'BOA08YOJ5XX',	'Meredith J. Wolf',	'metus.Vivamus.euismod@aliquetPhasellus.org',	'YQS79PFE5HFr3764p6zzexw88282v5v7f55dct',	0,	'2010-04-17 15:53:43',	1,	NULL),
(36,	'Arsenio',	'MHK29VWQ8VU',	'Octavius Q. Odom',	'ipsum.Curabitur@etnetus.org',	'BRM21AKG7SWw1124d0huxco67226r0k7t80yxk',	0,	'2011-11-19 05:50:28',	1,	NULL),
(37,	'Blaine',	'BOH28SKX9EV',	'Barry M. Powers',	'Suspendisse.dui.Fusce@eunibhvulputate.org',	'UXJ57JMR7RJx7919s8kqxom92982q3w4h20cpm',	1,	'2011-07-26 18:40:07',	0,	NULL),
(38,	'Cassady',	'DHX04XTJ3KG',	'Stephen G. Snider',	'in@facilisis.com',	'MHX75XWE3QOo7847h4nrsxx57175g1z7m84auj',	0,	'2009-05-23 08:41:05',	0,	NULL),
(39,	'Yardley',	'TTS52MWQ9NJ',	'Stella Y. House',	'est.ac@nullaatsem.ca',	'XDO30BLU4QZk3719y5vpojy48904p2g5j88jxi',	0,	'2011-02-21 16:35:35',	0,	NULL),
(40,	'Vielka',	'ESH98GWK0MY',	'Jackson J. Hester',	'Donec@sociisnatoque.ca',	'QYL07IKQ6PZp7696m1nwgzv64297q6r2o02ilr',	1,	'2011-01-28 17:08:13',	0,	NULL),
(41,	'Dennis',	'YSA65RCT1ZZ',	'Celeste R. Sampson',	'magna.Cras@Morbisit.org',	'GYY80GEM9DPf0226w8uqbtp25285x1g1b20dme',	1,	'2012-02-04 16:55:02',	0,	NULL),
(42,	'Ella',	'FPJ48BJR8ZT',	'Lana Z. Mccarty',	'in@IncondimentumDonec.ca',	'YZY02YDR1TCk9674m8ueqtx23832r0z6b96gxt',	1,	'2011-04-30 02:29:03',	0,	NULL),
(43,	'Jessica',	'ZKL61WDZ6HQ',	'Hope V. Cook',	'odio.semper.cursus@pharetra.ca',	'FYJ28ZMU7KUe4160j4fjdnz57838z2o1f94cxy',	1,	'2011-01-10 12:58:38',	1,	NULL),
(44,	'Gretchen',	'QUU84WFX0PA',	'Lisandra O. May',	'fringilla@Namtempordiam.com',	'DSO36ALN9HDi9917i9zfbof61895r1x8u41kmp',	1,	'2010-03-01 00:06:39',	0,	NULL),
(45,	'Talon',	'BUV98PGW5MX',	'Brittany O. Palmer',	'eu@elitEtiam.edu',	'QRA41EWQ7UBq8848t0bqqon72007p8y2m96qcm',	0,	'2009-03-05 10:53:33',	1,	NULL),
(46,	'Eden',	'IND29YZB0PR',	'Kim L. Buchanan',	'ante.dictum.cursus@neceleifend.ca',	'HMZ74QKN1RKk0952x4eymuq08080o3v8v86glb',	1,	'2010-07-25 18:28:17',	1,	NULL),
(47,	'Nora',	'EYS04YAY1YK',	'Tatyana L. Mclean',	'aliquet.lobortis.nisi@habitant.com',	'PRD44TRX7MLn6327v1tuise23690w8h4n38zdo',	1,	'2009-04-18 08:00:28',	1,	NULL),
(48,	'Ali',	'ILR76KOW1IE',	'Sierra K. Kirby',	'egestas@liberoest.ca',	'FLK78HTZ8QYw9356b9fxdnc90411w3c1t48pup',	1,	'2011-02-27 13:02:24',	1,	NULL),
(49,	'Colette',	'NME80VYZ0WC',	'Lacy C. Wilkins',	'accumsan.convallis@scelerisque.com',	'NVL23HXC8MSc9128f0vfdsi76331p0m1w94ffi',	0,	'2010-09-28 16:10:31',	0,	NULL),
(50,	'Raymond',	'BFU24RLO5RS',	'Darryl K. Winters',	'Vivamus.nisi.Mauris@metusInnec.ca',	'TPD65OVT8EWq2044n1xcqov85289y9x6m03aod',	1,	'2009-01-27 22:56:12',	0,	NULL),
(51,	'Emma',	'PBN65YFQ8IG',	'Holmes D. Cameron',	'euismod.urna.Nullam@tinciduntnibhPhasellus.com',	'WUY38GJJ3DUs7814j1qazyg30434j2t2a66uyz',	0,	'2010-10-10 03:30:17',	0,	NULL),
(52,	'Breanna',	'ZBW82HGK9GJ',	'Sierra T. Klein',	'nunc.id.enim@Nulla.com',	'IDN38DDU1IDg3101k3kvhqe11900b4i3p87kzv',	0,	'2010-09-04 18:57:57',	0,	NULL),
(53,	'Camille',	'HJL44QXV0SC',	'Montana F. Tate',	'eu.metus.In@consectetuer.ca',	'TGG31YUC7ZGy2547b0qwbjy72150m4z2t25wej',	1,	'2011-12-05 12:42:49',	1,	NULL),
(54,	'Gabriel',	'FXP72JVI0SJ',	'Grady C. Neal',	'eu.metus@Nuncullamcorpervelit.com',	'ERN98DTA4SZv9847e7omuhw19977r4b8d57zsn',	0,	'2012-05-26 17:08:09',	0,	NULL),
(55,	'Dana',	'ZAE84XAF4MZ',	'Orla D. Gilbert',	'Morbi.sit.amet@inconsequatenim.ca',	'JNU40NLU0MZh4043l3iruvr51714s6k0d25ftz',	0,	'2011-12-23 20:59:38',	1,	NULL),
(56,	'Jackson',	'ABX04GLV5CP',	'Herman D. Ray',	'Proin.sed@massa.edu',	'YLH06VHK7IEg3213s4tnzwd36291p9k5k76bnx',	0,	'2009-02-04 05:30:35',	0,	NULL),
(57,	'Dakota',	'POO87WND6BC',	'Jordan M. Cooke',	'ipsum@faucibusMorbi.edu',	'KRQ83GWX1IGk9897o8hrliu95200s3c1a69kyu',	0,	'2010-06-08 22:18:16',	0,	NULL),
(58,	'Brendan',	'ONB34UXG0PS',	'Hilda Q. Hale',	'sed.pede.nec@feugiatnonlobortis.com',	'AAN07LKR2TUj3246s2pzger65362a5p0p10iol',	1,	'2011-06-15 10:27:08',	1,	NULL),
(59,	'Channing',	'UEM05EUU7UB',	'Unity S. Hunter',	'pellentesque.tellus@parturientmontes.ca',	'XHV43WUX3USm5896m1kgweh76024j9f2f07pxo',	1,	'2009-10-13 02:33:49',	0,	NULL),
(60,	'Freya',	'GSX16NPU8WR',	'Aristotle M. Carter',	'at.augue.id@cursusnonegestas.org',	'SIU93NNH0BNk2224x7asswj70841p5s3x52hbt',	1,	'2009-05-29 10:30:22',	0,	NULL),
(61,	'Raven',	'BNB28MEP4WH',	'Flavia B. Dyer',	'dolor@milorem.org',	'CFA97GGT0VWu8381f3juugc15146r9j7e37xqz',	1,	'2012-04-17 10:52:39',	1,	NULL),
(62,	'Renee',	'OLR93WDQ2YL',	'Jeanette O. Banks',	'turpis.Aliquam.adipiscing@ac.ca',	'YYQ13KAZ3RRe2181l7hbygn42034b9j0y01hke',	0,	'2009-02-05 13:19:00',	1,	NULL),
(63,	'Suki',	'YYC07HMN5OM',	'Sage W. Bernard',	'Aenean@ut.edu',	'GYG29KNI5UCm7058w8hjlwv29730z9h2w54vyz',	0,	'2010-06-15 06:32:15',	1,	NULL),
(64,	'Quinn',	'HZN35IEL6OX',	'Jerry E. Harper',	'Cras.pellentesque@scelerisquelorem.edu',	'NTJ42VFF8KSp6719h4esasq51892k3i9d63kno',	1,	'2010-05-16 01:10:30',	0,	NULL),
(65,	'Warren',	'IYP00PIM7AN',	'James A. Neal',	'Ut.sagittis@luctusaliquetodio.ca',	'ICU47DXI6NRq8628r8fdpgq08663q3g9m04gej',	1,	'2010-05-27 05:25:40',	1,	NULL),
(66,	'Sybil',	'GNS87POP8SF',	'Amity G. Griffith',	'elementum.at.egestas@gravidanuncsed.org',	'FOQ76CZU4ORi8207z6gpmzu53043w8b0j68att',	1,	'2009-06-27 03:06:28',	0,	NULL),
(67,	'Felicia',	'MOD65VRG0GT',	'Audra H. Baldwin',	'augue.scelerisque@eratnonummyultricies.ca',	'OWA95VYH5TNx2009q8pxywr08949s2w2c82qse',	1,	'2011-01-11 15:37:14',	0,	NULL),
(68,	'Vivien',	'LEE52DEB7CA',	'Lucas B. Rich',	'at.arcu@Craseu.com',	'TIT81ZNX1LEc9334o4pqerq74621e7q9b37gjh',	0,	'2009-04-09 07:23:09',	1,	NULL),
(69,	'Peter',	'IQR69CFJ6VO',	'Harding I. Jacobson',	'vitae.aliquam.eros@Duisat.com',	'JQG40BRJ3VXc1577l8zvovj93674i7u6l00lct',	1,	'2009-12-07 23:58:57',	0,	NULL),
(70,	'Knox',	'ROR23MRE4MT',	'Emerald R. Simmons',	'Cras.dictum@scelerisquemollis.ca',	'FVN68PCZ3WLx5957i0lmnyg77833i4d5h61egg',	0,	'2009-02-15 17:29:14',	1,	NULL),
(71,	'Byron',	'PMS50MXC9KP',	'Sonia J. Morris',	'enim.Sed@leoVivamusnibh.ca',	'FCK57PRA8VPz4070d1yfdju09165y5k1r86lkj',	0,	'2010-08-21 23:46:27',	0,	NULL),
(72,	'Chelsea',	'BGQ19QGD5MH',	'Uma H. Erickson',	'porttitor.interdum.Sed@esttemporbibendum.com',	'NVP26HQB6CHv1542m0nqrax52247d9d6u78lam',	0,	'2012-02-08 20:45:47',	1,	NULL),
(73,	'Lila',	'UNT39AUB5LS',	'Lane I. Goodman',	'malesuada.malesuada.Integer@etrutrumnon.org',	'TSJ54FYM7LEh2605r0isqtl21706o6j3j78vxv',	0,	'2011-10-21 00:14:31',	1,	NULL),
(74,	'Deborah',	'NXM29ZWB3PS',	'Aubrey Y. Barber',	'mi@blanditNamnulla.com',	'QFS09OWW3FMq7409j0uljkd44062b5p7t27rvp',	1,	'2010-04-25 12:17:41',	0,	NULL),
(75,	'Roary',	'AVN49MVZ2GJ',	'Melinda V. Hensley',	'aliquet@diamDuis.org',	'ZXH71AKB6YGk9094a4gzsnj39144r7z6r23wgu',	1,	'2010-06-16 16:21:27',	1,	NULL),
(77,	'Rhona',	'GEK29GOY3NJ',	'Nehru U. Snider',	'dolor.nonummy@est.edu',	'WRM90JOW7CAo3195e1ddnqn15384l2r3y12ynv',	1,	'2010-05-27 19:12:51',	0,	NULL),
(78,	'Timothy',	'd41d8cd98f00b204e9800998ecf8427e',	'Liberty P. Salina',	'Aliquam@rutrumjusto.org',	'TFX63EQX9LEx2912r9lpxtz45556a1g7j14tib',	1,	'2012-05-08 03:41:08',	0,	NULL),
(80,	'Tyrone',	'DOV76NST6XI',	'Rose C. England',	'Sed.id.risus@magnaa.com',	'QZB36KWA1OHc7894n4fhddp94696j5d9p23fpf',	0,	'2009-04-14 22:38:25',	1,	NULL),
(81,	'Quyn',	'NXM38ZWI2DL',	'Gloria K. Peters',	'Phasellus.dolor.elit@felis.ca',	'XPT48CRK2XFb4157y5pgtts14088k3q2y47tnm',	0,	'2010-05-21 10:05:12',	1,	NULL),
(82,	'Hamilton',	'JWU44OLZ1SS',	'Teegan V. Hays',	'ac.tellus@doloregestasrhoncus.ca',	'BXF92ZSU4WPv2467w1dvivo22457d8q1s81aux',	1,	'2011-12-21 03:13:03',	0,	NULL),
(83,	'Quynn',	'KBA86TTV8PD',	'Octavius J. Cash',	'Integer.id.magna@orciconsectetuereuismod.org',	'TIW41BMZ9HFs6275y4htjxx00206e7h9c14ggx',	0,	'2010-08-18 00:17:49',	0,	NULL),
(84,	'Cheryl',	'MYX76LSD7MN',	'Martha P. Greer',	'bibendum.ullamcorper.Duis@pedeultrices.ca',	'JPT47ESA0ZFh4112v6xzjjm07073r0e0q95kcg',	0,	'2012-04-13 07:51:20',	0,	NULL),
(85,	'Dahlia',	'RLR86WRF7QP',	'Carly P. Bridges',	'Praesent.eu.nulla@ut.ca',	'IKX93LJK6VPy5063q4zwhqm47038j5t6z66ica',	1,	'2011-06-15 00:38:16',	1,	NULL),
(86,	'Selma',	'ISI33RAV6WC',	'Emily N. Gomez',	'vehicula@luctusvulputatenisi.ca',	'LQM42DUJ1MEz8831f0ebsad93608n9p9o04wff',	0,	'2011-08-01 12:13:45',	0,	NULL),
(87,	'Neve',	'KJN36RNS8OK',	'Sigourney K. Palmer',	'lobortis@Nuncpulvinar.ca',	'JQW97HTY5ZHf4677j3gbfvm05938j3t7z66syk',	1,	'2011-01-23 17:41:23',	0,	NULL),
(88,	'Hiram',	'YQV53NYT7AZ',	'Lawrence A. Lang',	'in@miAliquam.ca',	'SSN63DKH5ASy9375t2mmbnl60474v7g5l72uxp',	0,	'2011-04-19 00:38:29',	0,	NULL),
(89,	'Maile',	'TLP12DWM6JO',	'Carter F. Sanchez',	'lectus@nasceturridiculusmus.edu',	'QYQ07WWQ4XDc6567t8qgmav68081y5g6m86etn',	1,	'2012-03-05 23:58:57',	0,	NULL),
(90,	'Imogene',	'YGC59XHT5NF',	'Kimberly V. Glenn',	'iaculis.aliquet@nequeInornare.com',	'FSX66PWJ1SNo6761t5lmbzs63596f4z4f90vny',	0,	'2010-03-09 17:22:26',	1,	NULL),
(91,	'Margaret',	'SEH58LGJ8SL',	'Sigourney B. Hinton',	'Duis@augue.edu',	'NVQ77SOG6TWh4059w0efyxq73693t8n2q10jvp',	1,	'2010-07-11 06:39:54',	0,	NULL),
(92,	'Fredericka',	'MUU53TKK9PI',	'Sarah D. Delaney',	'gravida@purus.org',	'XZY51YXY5NKw0165o3yyhnh17326m7j3t28wfu',	1,	'2010-08-12 03:21:47',	1,	NULL),
(93,	'Flynn',	'FPI32XYQ8WY',	'Graham B. Cantrell',	'dictum@eusem.edu',	'DEK84HSR0MKk6070h6axmwv80895g7p3y92kgy',	0,	'2010-10-12 14:21:59',	0,	NULL),
(94,	'Irene',	'MUD55FUF2SR',	'Blossom N. Love',	'in@auctorvelit.org',	'HGA22GQM1RWq1676e9ljfea37541a1v3k85qce',	0,	'2010-10-12 22:09:41',	0,	NULL),
(95,	'Scott',	'ZYZ66RUR1DX',	'Edward F. Cohen',	'Curae;.Donec.tincidunt@Donecest.ca',	'UYB28PWX7ZFp9156v2kmjoj67564g7g0t32oxd',	0,	'2012-04-16 06:46:45',	1,	NULL),
(96,	'Bo',	'RGC78SPC1CM',	'Matthew D. Hurst',	'pede.Nunc@scelerisquenequesed.com',	'PCJ33PXH7JZv0298b6ketmq49770o6h1t64dgj',	0,	'2009-12-29 00:33:48',	1,	NULL),
(97,	'Olivia',	'KVT67OSL9WE',	'Jared O. Justice',	'lorem.lorem@seddictum.ca',	'ATY73KXQ7IQm7423g8cruyw24466u0m8v45fum',	1,	'2012-02-11 19:03:58',	1,	NULL),
(98,	'Risa',	'NBO93YGD0YX',	'Whoopi D. Rose',	'in.faucibus@sedsapienNunc.edu',	'XNV79KYS8VHr3323s1ovfns13855r5o5c33auh',	1,	'2010-08-23 01:24:33',	1,	NULL),
(99,	'Brandon',	'AJL35TCU3XZ',	'Shaeleigh D. Monroe',	'sit@Donecdignissimmagna.edu',	'GGU36DJL4BDo0494c7owcmw91906r7v9a62qtp',	0,	'2009-05-19 23:26:53',	0,	NULL),
(100,	'Montana',	'FSU77GUL8RN',	'Emmanuel M. Kirkland',	'luctus.felis@iderat.edu',	'IVC34JZE9BIq2040t6ufnlt89224t2q0b73nbj',	1,	'2009-12-07 22:36:31',	0,	NULL),
(101,	'Ivan',	'ART01TVU5AH',	'Aaron C. Riddle',	'neque.tellus.imperdiet@Suspendisse.org',	'OAV67FDV5UVa8578q5skbsr04704c4f6m02fgj',	0,	'2009-03-18 05:27:22',	1,	NULL),
(102,	'Chancellor',	'WWZ36WAD5KF',	'Eric H. Bradshaw',	'Phasellus.nulla.Integer@Nunc.ca',	'TMQ25WLT2MPl3447b3tfwdk12791a2c7t70hpm',	0,	'2010-05-19 20:18:33',	1,	NULL),
(103,	'Willa',	'QIH07ADB7IY',	'Travis C. Randall',	'Sed@ad.com',	'JJG10HKR7MNs1802p0jqvro74688t1j1n62oxr',	0,	'2011-09-07 04:38:50',	1,	NULL),
(104,	'Calvin',	'TKH54MEM8VH',	'Keith A. Gutierrez',	'egestas.a.scelerisque@cursuspurusNullam.ca',	'BFY58EDU8JMa1827h7ytqtb11011a9y0f86bbv',	1,	'2009-06-08 11:20:51',	1,	NULL),
(105,	'Coby',	'IXJ12CMZ0FQ',	'Quin U. Fischer',	'varius.Nam.porttitor@Crasconvallisconvallis.com',	'XZN18EEY0AWf3755z6zzsfp45338h3h1j82gzl',	0,	'2009-11-11 04:20:50',	0,	NULL),
(106,	'Jayme',	'AFU88IVM3UE',	'Kay R. Harrison',	'Donec@sem.edu',	'KFG70NZA9EAl1220b5vnwqs30347k3w8o05ppo',	1,	'2011-06-21 04:06:44',	1,	NULL),
(107,	'Nero',	'QAX82XYV7MS',	'Harper C. Cooper',	'Etiam.imperdiet@velturpis.ca',	'WBU41JAT8QIp2341q4dlepe58294u1k6f11jod',	0,	'2011-06-03 19:48:50',	0,	NULL),
(108,	'Rhoda',	'LAC85HLY4XC',	'Adena Q. Bender',	'ligula.Aenean.gravida@Maurisnon.edu',	'TQU98XIJ0LVw9897j2ehqck61044r6p4h33chs',	0,	'2010-02-09 14:36:21',	0,	NULL),
(109,	'Chaney',	'HEC11VNI0VY',	'Basil P. Dale',	'tempus@Donec.ca',	'SHJ06TDX4PGr8470n6nwpiv75498m6t1x12oxi',	0,	'2011-09-27 06:55:26',	0,	NULL),
(110,	'Urielle',	'SCU18VRL3NB',	'Micah T. Good',	'ligula@sapienNuncpulvinar.edu',	'IVL29RUW9HGx9371e5uqwhs56474e8r5q17ouo',	0,	'2010-10-13 01:24:10',	1,	NULL),
(111,	'Serina',	'RRV03YEX7VU',	'Adele J. Lewis',	'aliquam.adipiscing@cursus.edu',	'YTS58MDQ0OKw2072l6kjjge37507q1z5w61sdg',	0,	'2010-05-08 19:54:48',	0,	NULL),
(112,	'Raphael',	'UBA87GHK6RU',	'Tarik E. Sawyer',	'mus.Proin.vel@est.ca',	'ILN11QXG8HLq0568h8esnvm46619v5y1a43dcf',	0,	'2011-11-02 20:28:01',	1,	NULL),
(113,	'Josiah',	'VPO32LQC6IQ',	'Isaiah B. Rosario',	'enim@non.com',	'TAL74TWO9GNm1004i4ckbkb51166h6x0s37fbq',	1,	'2009-05-02 02:45:39',	0,	NULL),
(114,	'Tate',	'HMA35LSY5DA',	'Uta N. Colon',	'eget@metus.org',	'UQQ59KSA0NBy8592c3xaybb82595r7e4j79ifs',	0,	'2011-01-30 00:59:24',	0,	NULL),
(115,	'Cooper',	'LEB01GCN1DL',	'Claudia H. Chaney',	'Nunc.quis@adipiscing.com',	'ZDV39URD5BKs4574r0pfesr38985f8y2y75xpg',	0,	'2012-06-07 03:44:20',	0,	NULL),
(116,	'Drew',	'LVT95GRP8XT',	'Keiko D. Lynn',	'et.rutrum@vitaealiquetnec.org',	'TYY77NPH7RJa0170t0tjwrc32277f7l9r36kxh',	1,	'2011-03-07 05:37:46',	1,	NULL),
(117,	'Wang',	'BRA24CAM8JI',	'Kieran S. Jefferson',	'condimentum.Donec.at@tellusAenean.ca',	'GYX93OIG8ZWv0582z9hlvru31418y6u2p61xgl',	0,	'2009-08-21 19:12:07',	0,	NULL),
(118,	'Iona',	'AXM57UOS0AO',	'Odysseus Y. Rocha',	'risus.quis.diam@in.edu',	'TMK31OGK9KMl4490e6oxtpm43436o0v2n26ruw',	0,	'2011-10-24 14:06:03',	1,	NULL),
(119,	'Diana',	'OFR91RDV2AP',	'Sawyer B. Key',	'mattis@Crasvulputate.com',	'LQT99KPS2QNn1721w9gsehh07327t0e1r91pis',	0,	'2011-01-18 00:45:49',	1,	NULL),
(120,	'Gail',	'UDN53QKG9CL',	'Ciaran L. Guzman',	'nulla.In.tincidunt@consectetuereuismod.com',	'RWQ38TXE4FTu9825g9xnvay03301a7o6q19lyx',	1,	'2011-12-13 03:01:20',	0,	NULL),
(121,	'Ayanna',	'JDB24PMJ5ZF',	'Kaseem G. Wall',	'nec.tellus.Nunc@adipiscing.com',	'AOZ67ZCS8SBy0203q6vzdvf67450s5k7c33wim',	1,	'2009-03-22 17:16:41',	0,	NULL),
(123,	'Alexandra',	'FOV74LKY5VW',	'Aquila A. Chapman',	'vestibulum@scelerisque.ca',	'ONM31TZQ6VPq0147p9dnlzk51471r2d2s65vjn',	0,	'2012-01-26 00:49:24',	1,	NULL),
(124,	'Giselle',	'BPK20DVZ2IK',	'Dominic X. Pitts',	'enim.Suspendisse@ligulaeu.ca',	'VSA53OKR0WQl9281i9xhggr59591g5y0g96oma',	0,	'2011-11-14 08:35:29',	1,	NULL),
(125,	'Stephen',	'XAU18WLV7SB',	'Anjolie P. Hendricks',	'sed@cursusNunc.edu',	'BCI30ZWO9DDw0161z5pvihw37447a5z0s24tgh',	0,	'2009-08-28 23:59:33',	0,	NULL),
(126,	'Hollee',	'KCK57OOG1JO',	'Jael Y. Diaz',	'ac@risusQuisque.org',	'YWV61GLK5TQy1737m9gtbqi58265q5i6l12ojs',	1,	'2012-04-19 16:13:32',	1,	NULL),
(127,	'Isadora',	'QVP31IWB5QD',	'Cody Q. Wilkins',	'Sed@parturient.ca',	'XZN31UXM4WJy4158j8tklko63176m3n3i07lup',	1,	'2009-07-26 08:58:27',	0,	NULL),
(128,	'Jamalia',	'HFY47VVP5GA',	'Yardley R. Romero',	'gravida.mauris@maurisInteger.com',	'UMW14OOH1LOp7752n3bczhd90820f2n9s30fyq',	1,	'2012-02-02 13:22:35',	0,	NULL),
(129,	'Avye',	'CRV82BJP1MO',	'Hope N. Mercado',	'vestibulum.neque@commodohendreritDonec.org',	'ACQ74SYK3LMk5124j3uykhn88033u9z8a55mhn',	1,	'2011-04-24 05:46:24',	1,	NULL),
(131,	'Rogan',	'OXP23WHR9YR',	'Seth R. Mccoy',	'ultrices@luctusfelis.ca',	'MCM66INP6KPn9402v9onymf70871j2x8j35zsa',	1,	'2010-02-23 08:05:02',	0,	NULL),
(132,	'Inga',	'EXK60QNZ1AX',	'Miriam R. Dejesus',	'ut.erat.Sed@euismodet.com',	'TUI11VCX8RHh8275f3aeobb22793f8w9r10wae',	1,	'2009-08-26 21:31:04',	1,	NULL),
(133,	'Solomon',	'BFS29GMG2RV',	'Fallon B. Pruitt',	'sagittis.felis@non.org',	'XVQ50SKB9EYt2145b7aizre34289g8p1s27tzd',	1,	'2010-02-09 20:35:14',	1,	NULL),
(134,	'Emery',	'TQO91BZW0IW',	'Owen D. Keller',	'nibh@aliquam.ca',	'YQC69UJW9ETf9478o8wkweg66473l8i3m40jek',	0,	'2011-05-04 08:40:06',	0,	NULL),
(135,	'Leandra',	'AHN40JYW2JS',	'Howard I. Mckinney',	'sed@ante.com',	'XUV31WTJ2ZMw9733v7jrzsj33743u9t6s60xmu',	0,	'2012-05-31 16:27:30',	1,	NULL),
(136,	'Rama',	'TPU59EJS5AS',	'Colleen C. Harrington',	'sit.amet@egestasSed.ca',	'MPO22QDR4KLf1097z1bsrbk84435b2l5v07arx',	1,	'2009-08-20 05:01:00',	0,	NULL),
(137,	'Fallon',	'CHM12FDK2VB',	'Ferdinand Y. Donovan',	'In.scelerisque.scelerisque@euodio.ca',	'OUJ38DJL0BNi1883c0lmgho55532w6b4m48hnf',	1,	'2011-12-09 20:06:51',	1,	NULL),
(138,	'Dominique',	'HWL06TES1RZ',	'Jena O. Dudley',	'venenatis.a.magna@risusquisdiam.org',	'PIC07XJG1CUj3684i5qauia25523m5a0v11oao',	1,	'2011-05-19 01:19:55',	0,	NULL),
(139,	'Martena',	'ONA23MOY1PT',	'Jameson A. Mccarty',	'dictum@semper.org',	'UKR65WEO3ZSb5207p5horwh14315p5e3a80osf',	0,	'2011-11-10 17:15:19',	0,	NULL),
(140,	'Catherine',	'VES02LQU7FM',	'Lysandra Q. Kent',	'at@magnaSuspendissetristique.com',	'KVU47VZL0COe8789z3pucug72871r4n0h24zcm',	0,	'2009-04-23 05:21:21',	0,	NULL),
(141,	'Kenyon',	'AIL72KTO9NR',	'Randall J. Rodriguez',	'sed@vulputate.com',	'PRL89YWC3HFy3530s7vhtjy51368l7e0l59knt',	0,	'2009-05-10 21:12:40',	1,	NULL),
(142,	'Aristotle',	'SVJ21LXY2FR',	'McKenzie F. Byrd',	'nunc.risus.varius@egettinciduntdui.ca',	'PUY03WLW6YTr6508t5urvzj41525j8l9r30afl',	1,	'2011-10-14 07:57:41',	1,	NULL),
(143,	'Hop',	'QNB25YYC7QX',	'Molly J. Alston',	'Praesent.interdum.ligula@sedpede.ca',	'NTY21YGI3DEv7689g7yjmog15691g2d7b09eaf',	1,	'2009-07-19 21:47:32',	0,	NULL),
(144,	'Glenna',	'RGD31CQX0ZK',	'Lila P. Curtis',	'Sed@Vivamuseuismod.edu',	'VNW85VBQ0OBl2256j6ghrfr24242v8i6i15xao',	1,	'2012-02-05 05:02:20',	0,	NULL),
(145,	'Brynn',	'OMJ80ZFS2NJ',	'Iliana Q. Cochran',	'non.luctus.sit@arcu.com',	'XKX25LDP0RQd2150e4hxsug19994a0r9m51bzh',	1,	'2010-08-22 22:32:03',	0,	NULL),
(146,	'Whoopi',	'HDE46GQE5NW',	'Ebony A. Rojas',	'Donec@montes.org',	'CIQ58URW0IWb6020r4svzgs20747s1l8l04iwb',	0,	'2009-01-07 07:07:59',	1,	NULL),
(147,	'Hanna',	'BAZ02BTR7OQ',	'Zeus X. Erickson',	'dolor@cubiliaCurae;.org',	'NEN92XHB9MAj5036g4jyeyp11421v9x3c43hip',	0,	'2012-05-24 15:13:32',	0,	NULL),
(148,	'Amy',	'KRP40FSM5QR',	'Jemima V. Cross',	'lectus.Cum.sociis@et.org',	'ZTW01EJM7TUu1436n5izdyq51428n1j5v25zjy',	0,	'2010-01-17 03:36:00',	0,	NULL),
(149,	'Nevada',	'GXW72MGW7FZ',	'Orson O. Stafford',	'at.velit.Pellentesque@condimentumDonecat.ca',	'YBJ72YTQ8GTp4642y9mutru45339e0u0d18lcq',	0,	'2009-05-08 13:19:56',	1,	NULL),
(151,	'Ariel',	'YKE77TQT7LU',	'Alma P. Witt',	'lobortis@commodoipsumSuspendisse.ca',	'ACU38KUC2NLh4842q1vkyhe92739m2x4i68ecp',	0,	'2010-01-13 18:57:42',	0,	NULL),
(152,	'Benedict',	'SPO50TFF5PE',	'Stewart D. Roach',	'eget@enimnon.edu',	'SZN06IFT9LBa1657s3yxynb73298r0x3z43rsp',	0,	'2010-06-12 00:34:48',	1,	NULL),
(153,	'Anne',	'MQG12ZWN9TL',	'Ivory G. Sawyer',	'eu.erat.semper@tincidunt.org',	'FNB29MWQ0HHd7939d3urgod73344p0d8p10nrw',	0,	'2011-09-08 16:04:34',	1,	NULL),
(154,	'Chadwick',	'LWZ32XMO7EV',	'Donna N. Finley',	'magnis.dis@etmalesuadafames.com',	'BEB63BEB9IUd7611w0plstg90767t7z7x04hdq',	0,	'2009-08-12 03:04:25',	0,	NULL),
(155,	'Wayne',	'CFS73UQI3BB',	'Cailin B. Flores',	'mauris@risusQuisquelibero.ca',	'KWP39BPA6YCq0338q1lznmb67088u7q2p25ngd',	1,	'2011-11-08 01:25:38',	1,	NULL),
(156,	'Athena',	'ROD08XQA3QN',	'Rylee F. Norris',	'fermentum.convallis@a.org',	'UBU42IYN4MLn1961v5vwxlk71539r7m4u28bov',	1,	'2012-01-03 21:13:14',	0,	NULL),
(157,	'Fay',	'ZOM91QUT4QR',	'Ulla K. Baird',	'malesuada@nibhdolornonummy.com',	'NKT36QJG4XGu8828z9ptjgl22839q2f1s94jpu',	1,	'2009-06-18 04:49:01',	0,	NULL),
(158,	'Nathaniel',	'WVO60JQZ9KJ',	'Daniel Q. Solis',	'condimentum.Donec@afacilisisnon.com',	'GQM95XHE7XFk7093c3bcill53000b5j2d82sun',	1,	'2010-07-20 05:02:34',	0,	NULL),
(159,	'Ina',	'BXE08OFF6HO',	'Ann D. Randall',	'In.lorem@urna.ca',	'TUE96LPK9MXk5856r1vxsdm78617w5p6i75zfd',	0,	'2009-02-27 22:32:44',	0,	NULL),
(160,	'MacKenzie',	'HPD11UUH6RA',	'Quincy U. Carson',	'ligula.Donec@variusNamporttitor.org',	'NAH88VVA9FFp5776y5kfdcf92050y9r4z92ttd',	1,	'2011-01-16 06:13:00',	1,	NULL),
(161,	'Germaine',	'AHW80NTO9YS',	'Alec P. Clemons',	'nec@non.edu',	'DYP18WWQ9RIi6624r7ffrey63595s2r8e29bcw',	1,	'2009-10-25 15:59:44',	0,	NULL),
(162,	'Katell',	'ALE66QJK8PC',	'Alfreda U. Lambert',	'accumsan.sed@massaVestibulumaccumsan.ca',	'AOR28QHY5ZAa9426e8apsqr71411g0z2q64lhs',	0,	'2010-11-26 14:31:49',	1,	NULL),
(163,	'Mona',	'KVR80JBX3QQ',	'Adria K. Franco',	'eu@musProin.edu',	'OHG19SQF4ZAc3872x1iuozl52860j0b9i31hat',	1,	'2009-06-09 16:04:43',	0,	NULL),
(164,	'Palmer',	'NOD33KHM7CA',	'Norman O. Lawson',	'euismod.urna.Nullam@vehicula.org',	'HCQ64TXL4KWg9433t7pfosg22825b2h3j99urw',	0,	'2012-04-27 05:14:31',	0,	NULL),
(165,	'Elton',	'BKG47OGP1LD',	'Steven K. Padilla',	'amet@ultriciessem.org',	'YYY38XCO8HTc3138z9cocos92007t7i7r20ofd',	0,	'2012-06-02 11:33:06',	0,	NULL),
(167,	'Ebony',	'UCT03GTF8UW',	'Whitney W. Farrell',	'a.enim@turpisAliquam.com',	'IEX89FJQ9AHg8398r2qwalt97638b5i3t22smq',	0,	'2009-07-19 01:16:19',	0,	NULL),
(168,	'Meredith',	'QRN96KRW0OW',	'Justine W. Freeman',	'Cras@Aliquam.ca',	'VLJ56PGD2OOx1241w8wswko37466r7q4e00wqg',	0,	'2010-10-04 11:52:41',	1,	NULL),
(169,	'Sasha',	'OTA81FQZ0IW',	'Jenna F. Vasquez',	'iaculis@purusNullamscelerisque.edu',	'ONN75OPD8PZq1769y8pyyyu48062w4h4z81njs',	1,	'2012-01-15 16:25:36',	0,	NULL),
(171,	'Abbot',	'WEQ31RJM0TV',	'Nelle E. Jacobson',	'et.libero@sagittis.ca',	'OWQ18GVJ2ABg8282j5pvqjr38877d8q6s28pnq',	1,	'2010-03-03 21:11:08',	1,	NULL),
(172,	'Marshall',	'AZN28UPL4LB',	'Phillip K. Bradley',	'Vestibulum.accumsan@quis.edu',	'ZAT72OIU1HOv3535k1ewnqx27386l2r4g41nyl',	0,	'2011-10-31 08:06:09',	0,	NULL),
(173,	'Oleg',	'WYA32PUK7QY',	'Hayley G. Maynard',	'libero@condimentum.com',	'JKT58WDT3LWa3806c6mnldl87796v6y1b67vnx',	1,	'2009-08-09 14:29:44',	1,	NULL),
(174,	'Forrest',	'MWQ29IKP8XA',	'Xaviera V. Gomez',	'augue.scelerisque.mollis@aauctornon.ca',	'KGA49UHL6XGa3166b9bquyq72443f7f6z20xai',	0,	'2010-07-08 21:55:51',	1,	NULL),
(175,	'Clinton',	'PSP73GTH2JB',	'Dane V. Alexander',	'dui.Fusce@Proin.org',	'ABH93IXI6FBw7745c6gjzql26271n6u5r05zkk',	0,	'2010-02-05 05:30:46',	0,	NULL),
(176,	'Madeson',	'JXK13XFB1PA',	'Rosalyn G. Barron',	'non.feugiat@temporarcuVestibulum.ca',	'PJJ24XHV2PXx9831e2iflxf29472l2c0q43bjp',	1,	'2009-01-02 19:49:43',	1,	NULL),
(177,	'Clarke',	'UOF69IUE6ZP',	'Kirby W. Combs',	'eget.magna@liberoet.edu',	'WXD53LOS0MJg2948w7rajqq98544c2o9d74mdb',	0,	'2011-10-15 13:36:37',	0,	NULL),
(178,	'Kaye',	'PLV58SJQ3KA',	'Abigail Y. Baldwin',	'Nullam.enim.Sed@pedeultrices.org',	'QWP75BGT0MYq0345g1yxnjx53839g6o8n16dfx',	1,	'2011-10-04 10:54:22',	0,	NULL),
(179,	'Hayfa',	'UXL13BLM0IZ',	'Grace N. Craig',	'Phasellus.libero.mauris@magnanecquam.com',	'NGE03VDP7AWc7859e6kqpsq99179z3g4q45agr',	1,	'2011-12-06 08:56:02',	1,	NULL),
(180,	'Moses',	'MBM26LLG8BW',	'Kaye V. Pearson',	'Nam.nulla@Namporttitor.ca',	'YQJ50WOH5EGg6379y1ifzkb51899l3w3z29acn',	0,	'2011-04-30 12:14:52',	1,	NULL),
(181,	'Nehru',	'PXI86GWU5BU',	'Abdul H. Brooks',	'elementum@fringilla.com',	'PZV51UPG3YXa5834l5gfeiz14234o4w1k67pmi',	1,	'2009-11-09 05:54:28',	1,	NULL),
(182,	'Eaton',	'TUU51FZT4FX',	'Richard C. Cook',	'felis@Praesenteu.edu',	'VMF21YUQ2QLo4239j5zcbea83008m7x2g15kbi',	0,	'2011-01-08 18:07:55',	0,	NULL),
(183,	'William',	'MXJ72ICW2FX',	'Jarrod U. Dodson',	'nec.ligula.consectetuer@ullamcorpervelitin.ca',	'QQD87QHV9IEx8025i5rexxc31814u8i4n42gbo',	0,	'2009-10-11 08:15:40',	0,	NULL),
(185,	'Lars',	'd41d8cd98f00b204e9800998ecf8427e',	'Cara F. Mcleod',	'eu.nibh.vulputate@ante.org',	'PUW90RSO3WYx4063t8uhbms20926a7g6o16pui',	0,	'2011-08-29 13:49:08',	0,	NULL),
(186,	'Chase',	'BZQ16ZXL7FN',	'David T. Walter',	'Duis@mattisvelitjusto.ca',	'SFN33DXC4AFq0222v2egbju23561l5c1t64btk',	0,	'2012-04-14 16:19:42',	0,	NULL),
(187,	'Illiana',	'JVN50TKJ9RK',	'Evan S. Hopper',	'vel.mauris@eu.com',	'WXP55CQH4UMx1247k2dtdvd42802e8q0u25ijg',	1,	'2010-01-02 19:17:13',	1,	NULL),
(188,	'Allegra',	'BTF31YTK0MN',	'Timon Z. Morris',	'dui@Maecenas.org',	'YAR72GBQ4QLn7270k6vvthj69384j2c2g70oab',	0,	'2009-11-22 20:07:05',	1,	NULL),
(189,	'Nissim',	'QBK87MGM3BF',	'Lisandra X. Henry',	'et.ultrices@enimnislelementum.edu',	'ZCJ28KUW4YKe5454g0rsjsx91858o5y5q37mto',	0,	'2012-03-31 07:46:51',	0,	NULL),
(190,	'Kasper',	'TJC19NKA1CJ',	'Sybill J. Bailey',	'et@Aliquameratvolutpat.com',	'KVY01TMX3VCv5497j4ejpgt42141a3o4e54jgy',	0,	'2012-02-08 16:24:43',	1,	NULL),
(191,	'Regan',	'IKJ73CET2OJ',	'Kellie V. Fox',	'sed.est.Nunc@mi.com',	'QNW80IEH2LWb7245p5sjugs35519v1k4q23rpn',	1,	'2010-02-12 10:39:24',	1,	NULL),
(194,	'Stuart',	'NYO32YZC1YS',	'Connor P. Nielsen',	'euismod.et.commodo@dictum.edu',	'PLR24AJW1QIa1254n4lqqki80969z2c6s78eud',	0,	'2012-04-17 11:33:35',	1,	NULL),
(196,	'Cailin',	'IHW15FMD6WP',	'Riley G. Stout',	'tempor@Donec.edu',	'JPL85WLW6YUu2064q8ytmuj26531u6i1g79uqk',	1,	'2011-04-02 21:41:49',	0,	NULL),
(197,	'Giacomo',	'MAQ56CEN0YA',	'Reed H. Franco',	'nisi.sem@Craseget.org',	'HWU56TIH1ANn5510g2gtgft05054r0c9y94orv',	1,	'2012-04-07 02:48:17',	1,	NULL),
(198,	'Sheila',	'JJJ95LEU6YB',	'Thor D. Patterson',	'semper.tellus@acturpis.com',	'SYJ68WFN7BJo4793h3ocbne92339y2k6e82arl',	1,	'2009-08-07 01:40:58',	0,	NULL),
(199,	'Justin',	'YYY68IZF8CG',	'Hamish E. Ewing',	'pretium.et@Namligula.org',	'UZD97KAM8PWj5721q5evmhb85241v0r6a76ueq',	0,	'2010-01-18 14:20:29',	0,	NULL),
(200,	'Clementine',	'CKS86XNH1JT',	'Herman O. Hopkins',	'orci.consectetuer.euismod@dui.org',	'ATE05UVT3CUp2131w2qdjaw74471l8s4p85dqi',	1,	'2009-08-28 15:33:50',	0,	NULL),
(201,	'mramos',	'',	'Marlize Ramos',	'marlize.ramos@disys.com',	NULL,	0,	'2011-09-08 16:47:03',	1,	NULL),
(202,	'usuario',	'e959088c6049f1104c84c9bde5560a13',	'Usuário de Teste',	'leopoldo.barreiro@disys.com',	'4e81c23c108a9',	0,	NULL,	1,	'hifi_list');

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


-- 2011-10-04 15:00:03
