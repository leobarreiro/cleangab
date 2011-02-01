-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: Nov 06, 2010 as 11:47 AM
-- Versão do Servidor: 5.1.47
-- Versão do PHP: 5.2.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `leopo074_pagfaber`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

DROP TABLE IF EXISTS `cliente`;
CREATE TABLE `cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `sigla` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `dt_criacao` datetime NOT NULL,
  `rg_iest` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `cpf_cnpj` varchar(18) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'F' COMMENT 'J / F',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sigla` (`sigla`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `cliente`
--

INSERT INTO `cliente` (`id`, `nome`, `sigla`, `dt_criacao`, `rg_iest`, `cpf_cnpj`, `tipo`) VALUES
(1, 'Barreiro e Barreiro Ltda.', 'efaber', '2010-10-14 00:38:00', '', '04.799.699/0001-52', 'J'),
(2, 'Fórum de Justiça Arbitral', 'fja', '2008-07-31 19:02:10', '', '08.741.059/0001-42', 'J'),
(3, 'Rosa e Machado Ferragens e Serviços Ltda', 'banhart', '2007-03-07 00:00:00', '', '03.926.965/0001-06', 'J');

-- --------------------------------------------------------

--
-- Estrutura da tabela `contrato`
--

DROP TABLE IF EXISTS `contrato`;
CREATE TABLE `contrato` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `codigo_referencia` char(8) COLLATE utf8_unicode_ci NOT NULL,
  `periodicidade` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'MENSAL',
  `primeiro_vencimento` date NOT NULL,
  `dia_vencimento` char(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '05',
  PRIMARY KEY (`id`),
  KEY `cliente_contrato_fk` (`cliente_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `contrato`
--

INSERT INTO `contrato` (`id`, `cliente_id`, `codigo_referencia`, `periodicidade`, `primeiro_vencimento`, `dia_vencimento`) VALUES
(1, 2, '13243546', 'MENSAL', '2010-10-20', '20'),
(2, 3, '03100901', 'MENSAL', '2007-03-01', '05');

-- --------------------------------------------------------

--
-- Estrutura da tabela `endereco`
--

DROP TABLE IF EXISTS `endereco`;
CREATE TABLE `endereco` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ativo` tinyint(1) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `logradouro` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `numero` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `complemento` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cep` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `bairro` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `cidade` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `uf` char(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_endereco_fk` (`cliente_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `endereco`
--

INSERT INTO `endereco` (`id`, `ativo`, `cliente_id`, `logradouro`, `numero`, `complemento`, `cep`, `bairro`, `cidade`, `uf`) VALUES
(1, 1, 1, 'Trv. Escobar', '489', 'apto.207', '91910-400', 'Camaquã', 'Porto Alegre', 'RS'),
(2, 1, 2, 'Rua Padre Mororo', '421', 'D', '60015-220', 'Centro', 'Fortaleza', 'CE'),
(3, 1, 3, 'Av. Nonoai', '430', '', '91720-000', 'Nonoai', 'Porto Alegre', 'RS');

-- --------------------------------------------------------

--
-- Estrutura da tabela `fatura`
--

DROP TABLE IF EXISTS `fatura`;
CREATE TABLE `fatura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contrato_id` int(11) NOT NULL,
  `data_vencimento` date NOT NULL,
  `valor_cobrado` decimal(8,2) NOT NULL,
  `valor_pago` decimal(8,2) NOT NULL,
  `data_pagamento` date NOT NULL,
  `demonstrativo_1` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `demonstrativo_2` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `demonstrativo_3` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `instrucao_1` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `instrucao_2` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `instrucao_3` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `instrucao_4` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contrato_fatura_fk` (`contrato_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `fatura`
--

INSERT INTO `fatura` (`id`, `contrato_id`, `data_vencimento`, `valor_cobrado`, `valor_pago`, `data_pagamento`, `demonstrativo_1`, `demonstrativo_2`, `demonstrativo_3`, `instrucao_1`, `instrucao_2`, `instrucao_3`, `instrucao_4`) VALUES
(1, 1, '2010-10-22', 196.00, 196.00, '2010-10-20', 'Referente a serviços prestados de 14/09/2010 a 13/11/2010', 'Em caso de dúvidas, entre em contato conosco pelo telefone (51) 3084.2189', 'ou pelo e-mail ciel@cielnews.com', 'Após o vencimento, cobrar multa de mora de 2%', 'Não receber após 10 dias do vencimento.', NULL, NULL),
(3, 2, '2010-10-25', 56.00, 56.00, '2010-10-25', 'Referente a serviços prestados em agosto e setembro de 2010. ', 'Em caso de dúvidas, entre em contato conosco pelo telefone (51) 3084.2189', 'ou pelo e-mail ciel@cielnews.com', 'Após o vencimento, cobrar multa de mora de 2%.', 'Não receber após 10 dias do vencimento.', NULL, NULL);
