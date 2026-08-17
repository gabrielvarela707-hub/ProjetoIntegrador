-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11/08/2026 às 19:30
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `adatech_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `contatos`
--

CREATE TABLE `contatos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `mensagem` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pendente',
  `data_cadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `contatos`
--

INSERT INTO `contatos` (`id`, `nome`, `email`, `telefone`, `mensagem`, `criado_em`, `status`, `data_cadastro`) VALUES
(2, 'Carlos', 'carlos@gmail.com', '11993415433', 'Teste 2', '2026-08-06 19:23:24', 'Pendente', '2026-08-10 16:03:45');

-- --------------------------------------------------------

--
-- Estrutura para tabela `orcamentos`
--

CREATE TABLE `orcamentos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `assunto` varchar(255) NOT NULL,
  `mensagem` text DEFAULT NULL,
  `data_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pendente',
  `data_cadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `orcamentos`
--

INSERT INTO `orcamentos` (`id`, `nome`, `email`, `assunto`, `mensagem`, `data_envio`, `status`, `data_cadastro`) VALUES
(1, 'Gabriel', 'gabriel@gmail.com', 'Notebook Dell Inspiron 15', 'teste', '2026-08-03 19:31:17', 'Pendente', '2026-08-10 16:03:45'),
(2, 'Moises ', 'moises@gmail.com', 'Notebook Dell Inspiron 15', 'Teste', '2026-08-04 19:29:54', 'Pendente', '2026-08-10 16:03:45'),
(3, 'moises', 'moises2@gmail.com', 'Notebook Dell Inspiron 15', '', '2026-08-05 19:03:52', 'Pendente', '2026-08-10 16:03:45');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `token_recuperacao` varchar(255) DEFAULT NULL,
  `token_validade` datetime DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `nivel` varchar(20) NOT NULL DEFAULT 'cliente',
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `genero` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Ativo',
  `data_cadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `token_recuperacao`, `token_validade`, `criado_em`, `nivel`, `telefone`, `endereco`, `cpf`, `data_nascimento`, `genero`, `status`, `data_cadastro`) VALUES
(1, 'Gabriel Varela ', 'gabriel@gmail.com', '$2y$10$xGZJnqOAgeRwtWEOsYpIZ.VJrTp/7gDjLizN3nn9hA7zB/I7W0ZHa', NULL, NULL, '2026-08-03 18:08:02', 'admin', '(11) 99341-5423', 'Av. Edgar Facó', NULL, NULL, NULL, 'Ativo', '2026-08-10 16:03:45'),
(5, 'VINICIUS', 'oito@gmail.com', '$2y$10$WGIjSamvWn1WGBKscV1LPeXWz8Oq/HdBT5Sq0qGpfeQM5SCFVcxni', NULL, NULL, '2026-08-06 18:42:46', 'cliente', NULL, NULL, NULL, NULL, NULL, 'Ativo', '2026-08-10 16:03:45'),
(6, 'Carlos', 'Carlos@gmail.com', '$2y$10$xQFZuRLFm4ctztlDY0DSO.eROH28sx0nKRKQ8r1hnx2OOl56ULvoC', NULL, NULL, '2026-08-06 19:22:44', 'cliente', '(11)99342-5423', 'PERUS', '32249566702', '2000-07-12', 'masculino', 'Ativo', '2026-08-10 16:03:45');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `contatos`
--
ALTER TABLE `contatos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `orcamentos`
--
ALTER TABLE `orcamentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `contatos`
--
ALTER TABLE `contatos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `orcamentos`
--
ALTER TABLE `orcamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
