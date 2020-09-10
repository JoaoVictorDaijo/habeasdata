-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 20/08/2020 às 16:09
-- Versão do servidor: 10.4.13-MariaDB
-- Versão do PHP: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `habeas`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `assunto`
--

CREATE TABLE `assunto` (
  `id_assunto` int(11) NOT NULL,
  `descricao` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `assunto`
--

INSERT INTO `assunto` (`id_assunto`, `descricao`) VALUES
(1, 'Liminar'),
(2, 'Indenização'),
(3, 'Seguro'),
(4, 'Dissolução'),
(5, 'Alienação Fiduciária'),
(6, 'Seguro'),
(7, 'Dissolução'),
(8, 'Prestação de Serviços'),
(9, 'Contratos Bancários'),
(10, 'Indenização por Dano Material'),
(11, 'Multas e demais Sanções'),
(12, 'Acidente de Trânsito'),
(13, 'Despesas Condominiais'),
(14, 'Inclusão Indevida no Cadastro de Inadimplentes'),
(15, 'Defeito, nulidade ou anulação'),
(16, 'Cheque'),
(17, 'Contratos Bancários'),
(18, 'Adoção'),
(19, 'Assalto');

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `assunto`
--
ALTER TABLE `assunto`
  ADD PRIMARY KEY (`id_assunto`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `assunto`
--
ALTER TABLE `assunto`
  MODIFY `id_assunto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
