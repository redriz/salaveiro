-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 18-Nov-2026 às 18:11
-- Versão do servidor: 10.11.16-MariaDB
-- versão do PHP: 8.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de dados: `bdsalaveiro_adonairodriguez_c27`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias_produto`
--

CREATE TABLE `categorias_produto` (
  `id_categoria` smallint(5) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `categorias_produto`
--

INSERT INTO `categorias_produto` (`id_categoria`, `nome`, `descricao`) VALUES
(1, 'Flor de Sal', 'Produtos de flor de sal.'),
(2, 'Sal Marinho', 'Produtos de sal marinho tradicional.'),
(3, 'Sal Aromatizado', 'Produtos com ervas, algas ou especiarias.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(10) UNSIGNED NOT NULL,
  `nome` varchar(150) NOT NULL,
  `nif` char(9) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `email` varchar(180) NOT NULL,
  `morada` varchar(255) NOT NULL,
  `estado` enum('Ativo','Inativo') NOT NULL DEFAULT 'Ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nome`, `nif`, `telefone`, `email`, `morada`, `estado`) VALUES
(1, 'Sabores da Ria, Lda.', '501234567', '234100001', 'compras@saboresdaria.pt', 'Aveiro', 'Ativo'),
(2, 'Mercado Atlântico, Lda.', '502345678', '234100002', 'geral@mercadoatlantico.pt', 'Porto', 'Ativo'),
(3, 'Restaurante O Moliceiro', '503456789', '234100003', 'compras@omoliceiro.pt', 'Aveiro', 'Ativo'),
(4, 'Casa Gourmet, Lda.', '504567890', '234100004', 'encomendas@casagourmet.pt', 'Lisboa', 'Ativo'),
(5, 'Sabores Portugueses, SA', '505678901', '234100005', 'compras@saboresportugueses.pt', 'Coimbra', 'Ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `colheitas`
--

CREATE TABLE `colheitas` (
  `id_colheita` int(10) UNSIGNED NOT NULL,
  `id_tanque` int(10) UNSIGNED NOT NULL,
  `id_trabalhador` int(10) UNSIGNED NOT NULL,
  `id_utilizador_registo` int(10) UNSIGNED NOT NULL,
  `data_colheita` date NOT NULL,
  `quantidade_kg` decimal(12,2) UNSIGNED NOT NULL,
  `observacoes` varchar(500) DEFAULT NULL,
  `criado_em` datetime NOT NULL DEFAULT current_timestamp()
) ;

--
-- Extraindo dados da tabela `colheitas`
--

INSERT INTO `colheitas` (`id_colheita`, `id_tanque`, `id_trabalhador`, `id_utilizador_registo`, `data_colheita`, `quantidade_kg`, `observacoes`, `criado_em`) VALUES
(1, 1, 1, 2, '2027-11-02', 1240.00, 'Colheita realizada durante a manhã.', '2026-11-18 09:23:53'),
(2, 2, 1, 2, '2027-11-03', 1360.00, NULL, '2026-11-18 09:23:53'),
(3, 3, 2, 3, '2027-11-04', 1180.00, 'Humidade moderada.', '2026-11-18 09:23:53'),
(4, 5, 3, 2, '2027-11-05', 1620.00, NULL, '2026-11-18 09:23:53'),
(5, 6, 3, 2, '2027-11-06', 1510.00, NULL, '2026-11-18 09:23:53'),
(6, 8, 6, 3, '2027-11-07', 1280.00, 'Produção de flor de sal.', '2026-11-18 09:23:53'),
(7, 9, 6, 3, '2027-11-08', 1425.00, NULL, '2026-11-18 09:23:53'),
(8, 1, 5, 1, '2027-11-10', 1170.00, 'Registo supervisionado.', '2026-11-18 09:23:53'),
(9, 3, 2, 3, '2027-11-12', 1090.00, NULL, '2026-11-18 09:23:53'),
(10, 5, 3, 2, '2027-11-15', 1740.00, 'Boa qualidade visual.', '2026-11-18 09:23:53');

-- --------------------------------------------------------

--
-- Estrutura da tabela `encomendas`
--

CREATE TABLE `encomendas` (
  `id_encomenda` int(10) UNSIGNED NOT NULL,
  `id_cliente` int(10) UNSIGNED NOT NULL,
  `id_utilizador_registo` int(10) UNSIGNED NOT NULL,
  `data_encomenda` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` enum('Pendente','Confirmada','Em preparação','Expedida','Concluída','Cancelada') NOT NULL DEFAULT 'Pendente',
  `observacoes` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `encomendas`
--

INSERT INTO `encomendas` (`id_encomenda`, `id_cliente`, `id_utilizador_registo`, `data_encomenda`, `estado`, `observacoes`) VALUES
(1, 1, 2, '2027-11-02 10:15:00', 'Confirmada', NULL),
(2, 2, 2, '2027-11-03 14:20:00', 'Em preparação', 'Entrega prioritária.'),
(3, 3, 3, '2027-11-05 09:30:00', 'Expedida', NULL),
(4, 4, 2, '2027-11-07 16:45:00', 'Pendente', NULL),
(5, 5, 1, '2027-11-09 11:10:00', 'Concluída', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `encomenda_itens`
--

CREATE TABLE `encomenda_itens` (
  `id_encomenda` int(10) UNSIGNED NOT NULL,
  `id_produto` int(10) UNSIGNED NOT NULL,
  `quantidade` int(10) UNSIGNED NOT NULL,
  `preco_unitario` decimal(10,2) UNSIGNED NOT NULL
) ;

--
-- Extraindo dados da tabela `encomenda_itens`
--

INSERT INTO `encomenda_itens` (`id_encomenda`, `id_produto`, `quantidade`, `preco_unitario`) VALUES
(1, 1, 20, 4.50),
(1, 3, 15, 2.75),
(2, 2, 12, 8.50),
(2, 5, 10, 5.90),
(3, 3, 30, 2.75),
(3, 4, 20, 3.10),
(4, 1, 15, 4.50),
(4, 6, 8, 6.20),
(5, 2, 25, 8.50),
(5, 5, 18, 5.90);

-- --------------------------------------------------------

--
-- Estrutura da tabela `funcoes`
--

CREATE TABLE `funcoes` (
  `id_funcao` smallint(5) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `funcoes`
--

INSERT INTO `funcoes` (`id_funcao`, `nome`, `descricao`) VALUES
(1, 'Marnoto', 'Responsável pela produção e recolha tradicional do sal.'),
(2, 'Operador', 'Registo e acompanhamento das operações.'),
(3, 'Técnico de Manutenção', 'Manutenção de tanques e equipamentos.'),
(4, 'Supervisor', 'Supervisão das equipas e da produção.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `perfis`
--

CREATE TABLE `perfis` (
  `id_perfil` tinyint(3) UNSIGNED NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `perfis`
--

INSERT INTO `perfis` (`id_perfil`, `nome`, `descricao`) VALUES
(1, 'Administrador', 'Acesso global à aplicação.'),
(2, 'Operador', 'Acesso às operações de produção e gestão comercial.'),
(3, 'Consultor', 'Acesso apenas para consulta.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id_produto` int(10) UNSIGNED NOT NULL,
  `id_categoria` smallint(5) UNSIGNED NOT NULL,
  `nome` varchar(150) NOT NULL,
  `peso_kg` decimal(8,3) UNSIGNED NOT NULL,
  `preco_venda` decimal(10,2) UNSIGNED NOT NULL,
  `stock_unidades` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `estado` enum('Ativo','Inativo') NOT NULL DEFAULT 'Ativo'
) ;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id_produto`, `id_categoria`, `nome`, `peso_kg`, `preco_venda`, `stock_unidades`, `estado`) VALUES
(1, 1, 'Flor de Sal Tradicional 250 g', 0.250, 4.50, 180, 'Ativo'),
(2, 1, 'Flor de Sal Tradicional 500 g', 0.500, 8.50, 120, 'Ativo'),
(3, 2, 'Sal Marinho Grosso 1 kg', 1.000, 2.75, 300, 'Ativo'),
(4, 2, 'Sal Marinho Fino 1 kg', 1.000, 3.10, 360, 'Ativo'),
(5, 3, 'Sal com Ervas 250 g', 0.250, 5.90, 95, 'Ativo'),
(6, 3, 'Sal com Algas 250 g', 0.250, 6.20, 80, 'Ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `salinas`
--

CREATE TABLE `salinas` (
  `id_salina` int(10) UNSIGNED NOT NULL,
  `nome` varchar(150) NOT NULL,
  `localizacao` varchar(180) NOT NULL,
  `area_hectares` decimal(8,2) UNSIGNED NOT NULL,
  `descricao` varchar(500) DEFAULT NULL,
  `estado` enum('Ativa','Inativa','Manutenção') NOT NULL DEFAULT 'Ativa'
) ;

--
-- Extraindo dados da tabela `salinas`
--

INSERT INTO `salinas` (`id_salina`, `nome`, `localizacao`, `area_hectares`, `descricao`, `estado`) VALUES
(1, 'Salina da Troncalhada', 'Aveiro', 9.40, 'Salina tradicional próxima do centro de Aveiro.', 'Ativa'),
(2, 'Salina de São Roque', 'Aveiro', 7.80, 'Salina localizada junto ao Canal de São Roque.', 'Ativa'),
(3, 'Salina da Marinha Velha', 'Ílhavo', 11.20, 'Unidade de produção tradicional.', 'Ativa'),
(4, 'Salina da Ribeira', 'Aveiro', 6.50, 'Salina em intervenção técnica.', 'Manutenção'),
(5, 'Salina do Esteiro', 'Gafanha da Nazaré', 8.90, 'Salina dedicada à produção artesanal.', 'Ativa');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tanques`
--

CREATE TABLE `tanques` (
  `id_tanque` int(10) UNSIGNED NOT NULL,
  `id_salina` int(10) UNSIGNED NOT NULL,
  `codigo` varchar(40) NOT NULL,
  `area_m2` decimal(10,2) UNSIGNED NOT NULL,
  `capacidade_maxima_kg` decimal(12,2) UNSIGNED NOT NULL,
  `estado` enum('Ativo','Inativo','Manutenção') NOT NULL DEFAULT 'Ativo'
) ;

--
-- Extraindo dados da tabela `tanques`
--

INSERT INTO `tanques` (`id_tanque`, `id_salina`, `codigo`, `area_m2`, `capacidade_maxima_kg`, `estado`) VALUES
(1, 1, 'TR-01', 850.00, 7500.00, 'Ativo'),
(2, 1, 'TR-02', 920.00, 8100.00, 'Ativo'),
(3, 2, 'SR-01', 780.00, 6800.00, 'Manutenção'),
(5, 3, 'MV-01', 1100.00, 9800.00, 'Ativo'),
(6, 3, 'MV-02', 1040.00, 9200.00, 'Ativo'),
(7, 4, 'RB-01', 650.00, 5600.00, 'Inativo'),
(8, 5, 'ES-01', 890.00, 7900.00, 'Ativo'),
(9, 5, 'ES-02', 940.00, 8300.00, 'Ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `trabalhadores`
--

CREATE TABLE `trabalhadores` (
  `id_trabalhador` int(10) UNSIGNED NOT NULL,
  `id_funcao` smallint(5) UNSIGNED NOT NULL,
  `id_salina` int(10) UNSIGNED DEFAULT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(180) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `data_admissao` date NOT NULL,
  `estado` enum('Ativo','Inativo') NOT NULL DEFAULT 'Ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `trabalhadores`
--

INSERT INTO `trabalhadores` (`id_trabalhador`, `id_funcao`, `id_salina`, `nome`, `email`, `telefone`, `data_admissao`, `estado`) VALUES
(1, 1, 1, 'António Costa', 'antonio.costa@salaveiro.pt', '912100001', '2020-03-10', 'Ativo'),
(2, 1, 2, 'Beatriz Almeida', 'beatriz.almeida@salaveiro.pt', '912100002', '2021-05-18', 'Ativo'),
(3, 2, 3, 'Carlos Rocha', 'carlos.rocha@salaveiro.pt', '912100003', '2022-01-12', 'Ativo'),
(4, 3, 4, 'Daniela Santos', 'daniela.santos@salaveiro.pt', '912100004', '2019-09-01', 'Ativo'),
(5, 4, 1, 'Eduardo Martins', 'eduardo.martins@salaveiro.pt', '912100005', '2018-07-15', 'Ativo'),
(6, 1, 5, 'Filipa Moreira', 'filipa.moreira@salaveiro.pt', '912100006', '2023-04-20', 'Ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizadores`
--

CREATE TABLE `utilizadores` (
  `id_utilizador` int(10) UNSIGNED NOT NULL,
  `id_perfil` tinyint(3) UNSIGNED NOT NULL,
  `id_trabalhador` int(10) UNSIGNED DEFAULT NULL,
  `nome` varchar(150) NOT NULL,
  `username` varchar(80) NOT NULL,
  `email` varchar(180) NOT NULL,
  `palavra_passe` varchar(255) NOT NULL,
  `estado` enum('Ativo','Inativo') NOT NULL DEFAULT 'Ativo',
  `ultimo_acesso` datetime DEFAULT NULL,
  `criado_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `utilizadores`
--

INSERT INTO `utilizadores` (`id_utilizador`, `id_perfil`, `id_trabalhador`, `nome`, `username`, `email`, `palavra_passe`, `estado`, `ultimo_acesso`, `criado_em`) VALUES
(1, 1, 5, 'Eduardo Martins', 'admin', 'admin@salaveiro.pt', '$2y$12$T9phMS/OxYozUawXOnkKP.P0sCpzaGrYvALAzaZbxJjwJVufJ2S1W', 'Ativo', '2026-11-18 17:09:56', '2026-11-18 09:23:53'),
(2, 2, 3, 'Carlos Rocha', 'operador1', 'operador1@salaveiro.pt', '$2y$12$T9phMS/OxYozUawXOnkKP.P0sCpzaGrYvALAzaZbxJjwJVufJ2S1W', 'Ativo', '2026-11-18 13:53:11', '2026-11-18 09:23:53'),
(3, 2, 2, 'Beatriz Almeida', 'operador2', 'operador2@salaveiro.pt', '$2y$12$T9phMS/OxYozUawXOnkKP.P0sCpzaGrYvALAzaZbxJjwJVufJ2S1W', 'Ativo', NULL, '2026-11-18 09:23:53'),
(5, 3, NULL, 'Consultora Externa', 'consultor2', 'consultor2@salaveiro.pt', '$2y$12$T9phMS/OxYozUawXOnkKP.P0sCpzaGrYvALAzaZbxJjwJVufJ2S1W', 'Ativo', NULL, '2026-11-18 09:23:53');

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `vw_producao_por_salina`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `vw_producao_por_salina` (
`id_salina` int(10) unsigned
,`salina` varchar(150)
,`numero_colheitas` bigint(21)
,`quantidade_total_kg` decimal(34,2)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `vw_totais_encomenda`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `vw_totais_encomenda` (
`id_encomenda` int(10) unsigned
,`cliente` varchar(150)
,`registada_por` varchar(150)
,`data_encomenda` datetime
,`estado` enum('Pendente','Confirmada','Em preparação','Expedida','Concluída','Cancelada')
,`valor_total` decimal(42,2)
);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `categorias_produto`
--
ALTER TABLE `categorias_produto`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `uq_categorias_produto_nome` (`nome`);

--
-- Índices para tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `uq_clientes_nif` (`nif`),
  ADD UNIQUE KEY `uq_clientes_email` (`email`),
  ADD KEY `idx_clientes_nome` (`nome`);

--
-- Índices para tabela `colheitas`
--
ALTER TABLE `colheitas`
  ADD PRIMARY KEY (`id_colheita`),
  ADD KEY `fk_colheitas_tanques` (`id_tanque`),
  ADD KEY `fk_colheitas_trabalhadores` (`id_trabalhador`),
  ADD KEY `fk_colheitas_utilizadores` (`id_utilizador_registo`),
  ADD KEY `idx_colheitas_data` (`data_colheita`);

--
-- Índices para tabela `encomendas`
--
ALTER TABLE `encomendas`
  ADD PRIMARY KEY (`id_encomenda`),
  ADD KEY `fk_encomendas_clientes` (`id_cliente`),
  ADD KEY `fk_encomendas_utilizadores` (`id_utilizador_registo`),
  ADD KEY `idx_encomendas_data` (`data_encomenda`),
  ADD KEY `idx_encomendas_estado` (`estado`);

--
-- Índices para tabela `encomenda_itens`
--
ALTER TABLE `encomenda_itens`
  ADD PRIMARY KEY (`id_encomenda`,`id_produto`),
  ADD KEY `fk_encomenda_itens_produtos` (`id_produto`);

--
-- Índices para tabela `funcoes`
--
ALTER TABLE `funcoes`
  ADD PRIMARY KEY (`id_funcao`),
  ADD UNIQUE KEY `uq_funcoes_nome` (`nome`);

--
-- Índices para tabela `perfis`
--
ALTER TABLE `perfis`
  ADD PRIMARY KEY (`id_perfil`),
  ADD UNIQUE KEY `uq_perfis_nome` (`nome`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id_produto`),
  ADD UNIQUE KEY `uq_produtos_nome_peso` (`nome`,`peso_kg`),
  ADD KEY `fk_produtos_categorias` (`id_categoria`),
  ADD KEY `idx_produtos_nome` (`nome`);

--
-- Índices para tabela `salinas`
--
ALTER TABLE `salinas`
  ADD PRIMARY KEY (`id_salina`),
  ADD UNIQUE KEY `uq_salinas_nome` (`nome`);

--
-- Índices para tabela `tanques`
--
ALTER TABLE `tanques`
  ADD PRIMARY KEY (`id_tanque`),
  ADD UNIQUE KEY `uq_tanques_salina_codigo` (`id_salina`,`codigo`),
  ADD KEY `idx_tanques_estado` (`estado`);

--
-- Índices para tabela `trabalhadores`
--
ALTER TABLE `trabalhadores`
  ADD PRIMARY KEY (`id_trabalhador`),
  ADD UNIQUE KEY `uq_trabalhadores_email` (`email`),
  ADD KEY `fk_trabalhadores_funcoes` (`id_funcao`),
  ADD KEY `fk_trabalhadores_salinas` (`id_salina`),
  ADD KEY `idx_trabalhadores_nome` (`nome`);

--
-- Índices para tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD PRIMARY KEY (`id_utilizador`),
  ADD UNIQUE KEY `uq_utilizadores_username` (`username`),
  ADD UNIQUE KEY `uq_utilizadores_email` (`email`),
  ADD UNIQUE KEY `uq_utilizadores_trabalhador` (`id_trabalhador`),
  ADD KEY `fk_utilizadores_perfis` (`id_perfil`),
  ADD KEY `idx_utilizadores_estado` (`estado`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias_produto`
--
ALTER TABLE `categorias_produto`
  MODIFY `id_categoria` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `colheitas`
--
ALTER TABLE `colheitas`
  MODIFY `id_colheita` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `encomendas`
--
ALTER TABLE `encomendas`
  MODIFY `id_encomenda` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `funcoes`
--
ALTER TABLE `funcoes`
  MODIFY `id_funcao` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `perfis`
--
ALTER TABLE `perfis`
  MODIFY `id_perfil` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id_produto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `salinas`
--
ALTER TABLE `salinas`
  MODIFY `id_salina` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tanques`
--
ALTER TABLE `tanques`
  MODIFY `id_tanque` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `trabalhadores`
--
ALTER TABLE `trabalhadores`
  MODIFY `id_trabalhador` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  MODIFY `id_utilizador` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

-- --------------------------------------------------------

--
-- Estrutura para vista `vw_producao_por_salina`
--
DROP TABLE IF EXISTS `vw_producao_por_salina`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_producao_por_salina`  AS SELECT `s`.`id_salina` AS `id_salina`, `s`.`nome` AS `salina`, count(`c`.`id_colheita`) AS `numero_colheitas`, coalesce(sum(`c`.`quantidade_kg`),0) AS `quantidade_total_kg` FROM ((`salinas` `s` left join `tanques` `t` on(`t`.`id_salina` = `s`.`id_salina`)) left join `colheitas` `c` on(`c`.`id_tanque` = `t`.`id_tanque`)) GROUP BY `s`.`id_salina`, `s`.`nome` ;

-- --------------------------------------------------------

--
-- Estrutura para vista `vw_totais_encomenda`
--
DROP TABLE IF EXISTS `vw_totais_encomenda`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_totais_encomenda`  AS SELECT `e`.`id_encomenda` AS `id_encomenda`, `c`.`nome` AS `cliente`, `u`.`nome` AS `registada_por`, `e`.`data_encomenda` AS `data_encomenda`, `e`.`estado` AS `estado`, coalesce(sum(`ei`.`quantidade` * `ei`.`preco_unitario`),0) AS `valor_total` FROM (((`encomendas` `e` join `clientes` `c` on(`c`.`id_cliente` = `e`.`id_cliente`)) join `utilizadores` `u` on(`u`.`id_utilizador` = `e`.`id_utilizador_registo`)) left join `encomenda_itens` `ei` on(`ei`.`id_encomenda` = `e`.`id_encomenda`)) GROUP BY `e`.`id_encomenda`, `c`.`nome`, `u`.`nome`, `e`.`data_encomenda`, `e`.`estado` ;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `colheitas`
--
ALTER TABLE `colheitas`
  ADD CONSTRAINT `fk_colheitas_tanques` FOREIGN KEY (`id_tanque`) REFERENCES `tanques` (`id_tanque`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_colheitas_trabalhadores` FOREIGN KEY (`id_trabalhador`) REFERENCES `trabalhadores` (`id_trabalhador`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_colheitas_utilizadores` FOREIGN KEY (`id_utilizador_registo`) REFERENCES `utilizadores` (`id_utilizador`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `encomendas`
--
ALTER TABLE `encomendas`
  ADD CONSTRAINT `fk_encomendas_clientes` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_encomendas_utilizadores` FOREIGN KEY (`id_utilizador_registo`) REFERENCES `utilizadores` (`id_utilizador`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `encomenda_itens`
--
ALTER TABLE `encomenda_itens`
  ADD CONSTRAINT `fk_encomenda_itens_encomendas` FOREIGN KEY (`id_encomenda`) REFERENCES `encomendas` (`id_encomenda`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_encomenda_itens_produtos` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id_produto`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_produtos_categorias` FOREIGN KEY (`id_categoria`) REFERENCES `categorias_produto` (`id_categoria`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tanques`
--
ALTER TABLE `tanques`
  ADD CONSTRAINT `fk_tanques_salinas` FOREIGN KEY (`id_salina`) REFERENCES `salinas` (`id_salina`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `trabalhadores`
--
ALTER TABLE `trabalhadores`
  ADD CONSTRAINT `fk_trabalhadores_funcoes` FOREIGN KEY (`id_funcao`) REFERENCES `funcoes` (`id_funcao`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_trabalhadores_salinas` FOREIGN KEY (`id_salina`) REFERENCES `salinas` (`id_salina`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD CONSTRAINT `fk_utilizadores_perfis` FOREIGN KEY (`id_perfil`) REFERENCES `perfis` (`id_perfil`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_utilizadores_trabalhadores` FOREIGN KEY (`id_trabalhador`) REFERENCES `trabalhadores` (`id_trabalhador`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
