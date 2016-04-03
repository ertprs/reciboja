-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u1build0.15.04.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Tempo de geração: 22/03/2016 às 12:11
-- Versão do servidor: 5.6.28-0ubuntu0.15.04.1-log
-- Versão do PHP: 5.6.4-4ubuntu6.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de dados: `reciboloja`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `recibo`
--

CREATE TABLE IF NOT EXISTS `recibo` (
`idRecibo` int(11) NOT NULL,
  `nomeEmissor` varchar(255) NOT NULL,
  `enderecoEmissor` varchar(255) NOT NULL,
  `estadoEmissor` varchar(255) NOT NULL,
  `cidadeEmissor` varchar(255) NOT NULL,
  `cepEmissor` varchar(15) NOT NULL,
  `telefoneEmissor` varchar(15) NOT NULL,
  `cpfcnpjEmissor` varchar(15) NOT NULL,
  `inscricaoEmissor` varchar(15) NOT NULL,
  `emailEmissor` varchar(255) NOT NULL,
  `nomeCliente` varchar(255) NOT NULL,
  `cpfcnpjCliente` varchar(15) NOT NULL,
  `numeroRecibo` varchar(255) NOT NULL,
  `data` date NOT NULL,
  `valor` double NOT NULL,
  `descricao` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `recibo`
--
ALTER TABLE `recibo`
 ADD PRIMARY KEY (`idRecibo`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `recibo`
--
ALTER TABLE `recibo`
MODIFY `idRecibo` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
