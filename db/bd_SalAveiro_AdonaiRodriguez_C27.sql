-- ============================================================
-- PROVA 12H - SALAVEIRO
-- Base de dados MySQL 8+
-- Nome sugerido: bdSalAveiro_NomeConcorrente_C27
-- ============================================================

DROP DATABASE IF EXISTS bdSalAveiro_NomeConcorrente_C27;

CREATE DATABASE bdSalAveiro_AdonaiRodriguez_C27
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE bdSalAveiro_AdonaiRodriguez_C27;

-- ------------------------------------------------------------
-- Perfis de acesso
-- ------------------------------------------------------------
CREATE TABLE perfis (
    id_perfil TINYINT UNSIGNED AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL,
    descricao VARCHAR(255),

    CONSTRAINT pk_perfis PRIMARY KEY (id_perfil),
    CONSTRAINT uq_perfis_nome UNIQUE (nome)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Salinas
-- ------------------------------------------------------------
CREATE TABLE salinas (
    id_salina INT UNSIGNED AUTO_INCREMENT,
    nome VARCHAR(150) NOT NULL,
    localizacao VARCHAR(180) NOT NULL,
    area_hectares DECIMAL(8,2) UNSIGNED NOT NULL,
    descricao VARCHAR(500),
    estado ENUM('Ativa', 'Inativa', 'Manutenção') NOT NULL DEFAULT 'Ativa',

    CONSTRAINT pk_salinas PRIMARY KEY (id_salina),
    CONSTRAINT uq_salinas_nome UNIQUE (nome),
    CONSTRAINT ck_salinas_area CHECK (area_hectares > 0)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Funções dos trabalhadores
-- ------------------------------------------------------------
CREATE TABLE funcoes (
    id_funcao SMALLINT UNSIGNED AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao VARCHAR(255),

    CONSTRAINT pk_funcoes PRIMARY KEY (id_funcao),
    CONSTRAINT uq_funcoes_nome UNIQUE (nome)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Trabalhadores
-- ------------------------------------------------------------
CREATE TABLE trabalhadores (
    id_trabalhador INT UNSIGNED AUTO_INCREMENT,
    id_funcao SMALLINT UNSIGNED NOT NULL,
    id_salina INT UNSIGNED NULL,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(180) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    data_admissao DATE NOT NULL,
    estado ENUM('Ativo', 'Inativo') NOT NULL DEFAULT 'Ativo',

    CONSTRAINT pk_trabalhadores PRIMARY KEY (id_trabalhador),
    CONSTRAINT uq_trabalhadores_email UNIQUE (email),

    CONSTRAINT fk_trabalhadores_funcoes
        FOREIGN KEY (id_funcao)
        REFERENCES funcoes (id_funcao)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_trabalhadores_salinas
        FOREIGN KEY (id_salina)
        REFERENCES salinas (id_salina)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Utilizadores
-- Cada utilizador pode estar associado a um trabalhador.
-- ------------------------------------------------------------
CREATE TABLE utilizadores (
    id_utilizador INT UNSIGNED AUTO_INCREMENT,
    id_perfil TINYINT UNSIGNED NOT NULL,
    id_trabalhador INT UNSIGNED NULL,
    nome VARCHAR(150) NOT NULL,
    username VARCHAR(80) NOT NULL,
    email VARCHAR(180) NOT NULL,
    palavra_passe VARCHAR(255) NOT NULL,
    estado ENUM('Ativo', 'Inativo') NOT NULL DEFAULT 'Ativo',
    ultimo_acesso DATETIME NULL,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT pk_utilizadores PRIMARY KEY (id_utilizador),
    CONSTRAINT uq_utilizadores_username UNIQUE (username),
    CONSTRAINT uq_utilizadores_email UNIQUE (email),
    CONSTRAINT uq_utilizadores_trabalhador UNIQUE (id_trabalhador),

    CONSTRAINT fk_utilizadores_perfis
        FOREIGN KEY (id_perfil)
        REFERENCES perfis (id_perfil)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_utilizadores_trabalhadores
        FOREIGN KEY (id_trabalhador)
        REFERENCES trabalhadores (id_trabalhador)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tanques
-- ------------------------------------------------------------
CREATE TABLE tanques (
    id_tanque INT UNSIGNED AUTO_INCREMENT,
    id_salina INT UNSIGNED NOT NULL,
    codigo VARCHAR(40) NOT NULL,
    area_m2 DECIMAL(10,2) UNSIGNED NOT NULL,
    capacidade_maxima_kg DECIMAL(12,2) UNSIGNED NOT NULL,
    estado ENUM('Ativo', 'Inativo', 'Manutenção') NOT NULL DEFAULT 'Ativo',

    CONSTRAINT pk_tanques PRIMARY KEY (id_tanque),
    CONSTRAINT uq_tanques_salina_codigo UNIQUE (id_salina, codigo),

    CONSTRAINT fk_tanques_salinas
        FOREIGN KEY (id_salina)
        REFERENCES salinas (id_salina)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT ck_tanques_area CHECK (area_m2 > 0),
    CONSTRAINT ck_tanques_capacidade CHECK (capacidade_maxima_kg > 0)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Colheitas
-- Guarda o trabalhador responsável e o utilizador que registou.
-- ------------------------------------------------------------
CREATE TABLE colheitas (
    id_colheita INT UNSIGNED AUTO_INCREMENT,
    id_tanque INT UNSIGNED NOT NULL,
    id_trabalhador INT UNSIGNED NOT NULL,
    id_utilizador_registo INT UNSIGNED NOT NULL,
    data_colheita DATE NOT NULL,
    quantidade_kg DECIMAL(12,2) UNSIGNED NOT NULL,
    observacoes VARCHAR(500),
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT pk_colheitas PRIMARY KEY (id_colheita),

    CONSTRAINT fk_colheitas_tanques
        FOREIGN KEY (id_tanque)
        REFERENCES tanques (id_tanque)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_colheitas_trabalhadores
        FOREIGN KEY (id_trabalhador)
        REFERENCES trabalhadores (id_trabalhador)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_colheitas_utilizadores
        FOREIGN KEY (id_utilizador_registo)
        REFERENCES utilizadores (id_utilizador)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT ck_colheitas_quantidade CHECK (quantidade_kg > 0)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Categorias de produto
-- ------------------------------------------------------------
CREATE TABLE categorias_produto (
    id_categoria SMALLINT UNSIGNED AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao VARCHAR(255),

    CONSTRAINT pk_categorias_produto PRIMARY KEY (id_categoria),
    CONSTRAINT uq_categorias_produto_nome UNIQUE (nome)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Produtos
-- ------------------------------------------------------------
CREATE TABLE produtos (
    id_produto INT UNSIGNED AUTO_INCREMENT,
    id_categoria SMALLINT UNSIGNED NOT NULL,
    nome VARCHAR(150) NOT NULL,
    peso_kg DECIMAL(8,3) UNSIGNED NOT NULL,
    preco_venda DECIMAL(10,2) UNSIGNED NOT NULL,
    stock_unidades INT UNSIGNED NOT NULL DEFAULT 0,
    estado ENUM('Ativo', 'Inativo') NOT NULL DEFAULT 'Ativo',

    CONSTRAINT pk_produtos PRIMARY KEY (id_produto),
    CONSTRAINT uq_produtos_nome_peso UNIQUE (nome, peso_kg),

    CONSTRAINT fk_produtos_categorias
        FOREIGN KEY (id_categoria)
        REFERENCES categorias_produto (id_categoria)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT ck_produtos_peso CHECK (peso_kg > 0),
    CONSTRAINT ck_produtos_preco CHECK (preco_venda > 0)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Clientes
-- ------------------------------------------------------------
CREATE TABLE clientes (
    id_cliente INT UNSIGNED AUTO_INCREMENT,
    nome VARCHAR(150) NOT NULL,
    nif CHAR(9) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(180) NOT NULL,
    morada VARCHAR(255) NOT NULL,
    estado ENUM('Ativo', 'Inativo') NOT NULL DEFAULT 'Ativo',

    CONSTRAINT pk_clientes PRIMARY KEY (id_cliente),
    CONSTRAINT uq_clientes_nif UNIQUE (nif),
    CONSTRAINT uq_clientes_email UNIQUE (email)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Encomendas
-- Guarda o utilizador que efetuou o registo.
-- ------------------------------------------------------------
CREATE TABLE encomendas (
    id_encomenda INT UNSIGNED AUTO_INCREMENT,
    id_cliente INT UNSIGNED NOT NULL,
    id_utilizador_registo INT UNSIGNED NOT NULL,
    data_encomenda DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado ENUM(
        'Pendente',
        'Confirmada',
        'Em preparação',
        'Expedida',
        'Concluída',
        'Cancelada'
    ) NOT NULL DEFAULT 'Pendente',
    observacoes VARCHAR(500),

    CONSTRAINT pk_encomendas PRIMARY KEY (id_encomenda),

    CONSTRAINT fk_encomendas_clientes
        FOREIGN KEY (id_cliente)
        REFERENCES clientes (id_cliente)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_encomendas_utilizadores
        FOREIGN KEY (id_utilizador_registo)
        REFERENCES utilizadores (id_utilizador)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Itens das encomendas
-- ------------------------------------------------------------
CREATE TABLE encomenda_itens (
    id_encomenda INT UNSIGNED NOT NULL,
    id_produto INT UNSIGNED NOT NULL,
    quantidade INT UNSIGNED NOT NULL,
    preco_unitario DECIMAL(10,2) UNSIGNED NOT NULL,

    CONSTRAINT pk_encomenda_itens
        PRIMARY KEY (id_encomenda, id_produto),

    CONSTRAINT fk_encomenda_itens_encomendas
        FOREIGN KEY (id_encomenda)
        REFERENCES encomendas (id_encomenda)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_encomenda_itens_produtos
        FOREIGN KEY (id_produto)
        REFERENCES produtos (id_produto)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT ck_encomenda_itens_quantidade CHECK (quantidade > 0),
    CONSTRAINT ck_encomenda_itens_preco CHECK (preco_unitario > 0)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Índices auxiliares
-- ------------------------------------------------------------
CREATE INDEX idx_utilizadores_estado
    ON utilizadores (estado);

CREATE INDEX idx_tanques_estado
    ON tanques (estado);

CREATE INDEX idx_trabalhadores_nome
    ON trabalhadores (nome);

CREATE INDEX idx_colheitas_data
    ON colheitas (data_colheita);

CREATE INDEX idx_produtos_nome
    ON produtos (nome);

CREATE INDEX idx_clientes_nome
    ON clientes (nome);

CREATE INDEX idx_encomendas_data
    ON encomendas (data_encomenda);

CREATE INDEX idx_encomendas_estado
    ON encomendas (estado);

-- ------------------------------------------------------------
-- Dados exemplificativos
-- ------------------------------------------------------------

INSERT INTO perfis (nome, descricao) VALUES
('Administrador', 'Acesso global à aplicação.'),
('Operador', 'Acesso às operações de produção e gestão comercial.'),
('Consultor', 'Acesso apenas para consulta.');

INSERT INTO salinas
(nome, localizacao, area_hectares, descricao, estado)
VALUES
('Salina da Troncalhada', 'Aveiro', 9.40, 'Salina tradicional próxima do centro de Aveiro.', 'Ativa'),
('Salina de São Roque', 'Aveiro', 7.80, 'Salina localizada junto ao Canal de São Roque.', 'Ativa'),
('Salina da Marinha Velha', 'Ílhavo', 11.20, 'Unidade de produção tradicional.', 'Ativa'),
('Salina da Ribeira', 'Aveiro', 6.50, 'Salina em intervenção técnica.', 'Manutenção'),
('Salina do Esteiro', 'Gafanha da Nazaré', 8.90, 'Salina dedicada à produção artesanal.', 'Ativa');

INSERT INTO funcoes (nome, descricao) VALUES
('Marnoto', 'Responsável pela produção e recolha tradicional do sal.'),
('Operador', 'Registo e acompanhamento das operações.'),
('Técnico de Manutenção', 'Manutenção de tanques e equipamentos.'),
('Supervisor', 'Supervisão das equipas e da produção.');

INSERT INTO trabalhadores
(id_funcao, id_salina, nome, email, telefone, data_admissao, estado)
VALUES
(1, 1, 'António Costa', 'antonio.costa@salaveiro.pt', '912100001', '2020-03-10', 'Ativo'),
(1, 2, 'Beatriz Almeida', 'beatriz.almeida@salaveiro.pt', '912100002', '2021-05-18', 'Ativo'),
(2, 3, 'Carlos Rocha', 'carlos.rocha@salaveiro.pt', '912100003', '2022-01-12', 'Ativo'),
(3, 4, 'Daniela Santos', 'daniela.santos@salaveiro.pt', '912100004', '2019-09-01', 'Ativo'),
(4, 1, 'Eduardo Martins', 'eduardo.martins@salaveiro.pt', '912100005', '2018-07-15', 'Ativo'),
(1, 5, 'Filipa Moreira', 'filipa.moreira@salaveiro.pt', '912100006', '2023-04-20', 'Ativo');

INSERT INTO utilizadores
(id_perfil, id_trabalhador, nome, username, email, palavra_passe, estado, ultimo_acesso)
VALUES
(1, 5, 'Eduardo Martins', 'admin', 'admin@salaveiro.pt', 'admin123', 'Ativo', '2027-11-01 08:00:00'),
(2, 3, 'Carlos Rocha', 'operador1', 'operador1@salaveiro.pt', 'operador123', 'Ativo', '2027-11-01 08:15:00'),
(2, 2, 'Beatriz Almeida', 'operador2', 'operador2@salaveiro.pt', 'operador123', 'Ativo', NULL),
(3, NULL, 'Consultor Comercial', 'consultor1', 'consultor1@salaveiro.pt', 'consultor123', 'Ativo', NULL),
(3, NULL, 'Consultora Externa', 'consultor2', 'consultor2@salaveiro.pt', 'consultor123', 'Inativo', NULL);

INSERT INTO tanques
(id_salina, codigo, area_m2, capacidade_maxima_kg, estado)
VALUES
(1, 'TR-01', 850.00, 7500.00, 'Ativo'),
(1, 'TR-02', 920.00, 8100.00, 'Ativo'),
(2, 'SR-01', 780.00, 6800.00, 'Ativo'),
(2, 'SR-02', 810.00, 7100.00, 'Manutenção'),
(3, 'MV-01', 1100.00, 9800.00, 'Ativo'),
(3, 'MV-02', 1040.00, 9200.00, 'Ativo'),
(4, 'RB-01', 650.00, 5600.00, 'Inativo'),
(5, 'ES-01', 890.00, 7900.00, 'Ativo'),
(5, 'ES-02', 940.00, 8300.00, 'Ativo');

INSERT INTO colheitas
(id_tanque, id_trabalhador, id_utilizador_registo, data_colheita, quantidade_kg, observacoes)
VALUES
(1, 1, 2, '2027-11-02', 1240.00, 'Colheita realizada durante a manhã.'),
(2, 1, 2, '2027-11-03', 1360.00, NULL),
(3, 2, 3, '2027-11-04', 1180.00, 'Humidade moderada.'),
(5, 3, 2, '2027-11-05', 1620.00, NULL),
(6, 3, 2, '2027-11-06', 1510.00, NULL),
(8, 6, 3, '2027-11-07', 1280.00, 'Produção de flor de sal.'),
(9, 6, 3, '2027-11-08', 1425.00, NULL),
(1, 5, 1, '2027-11-10', 1170.00, 'Registo supervisionado.'),
(3, 2, 3, '2027-11-12', 1090.00, NULL),
(5, 3, 2, '2027-11-15', 1740.00, 'Boa qualidade visual.');

INSERT INTO categorias_produto (nome, descricao) VALUES
('Flor de Sal', 'Produtos de flor de sal.'),
('Sal Marinho', 'Produtos de sal marinho tradicional.'),
('Sal Aromatizado', 'Produtos com ervas, algas ou especiarias.');

INSERT INTO produtos
(id_categoria, nome, peso_kg, preco_venda, stock_unidades, estado)
VALUES
(1, 'Flor de Sal Tradicional 250 g', 0.250, 4.50, 180, 'Ativo'),
(1, 'Flor de Sal Tradicional 500 g', 0.500, 8.50, 120, 'Ativo'),
(2, 'Sal Marinho Grosso 1 kg', 1.000, 2.75, 300, 'Ativo'),
(2, 'Sal Marinho Fino 1 kg', 1.000, 3.10, 260, 'Ativo'),
(3, 'Sal com Ervas 250 g', 0.250, 5.90, 95, 'Ativo'),
(3, 'Sal com Algas 250 g', 0.250, 6.20, 80, 'Ativo');

INSERT INTO clientes
(nome, nif, telefone, email, morada, estado)
VALUES
('Sabores da Ria, Lda.', '501234567', '234100001', 'compras@saboresdaria.pt', 'Aveiro', 'Ativo'),
('Mercado Atlântico, Lda.', '502345678', '234100002', 'geral@mercadoatlantico.pt', 'Porto', 'Ativo'),
('Restaurante O Moliceiro', '503456789', '234100003', 'compras@omoliceiro.pt', 'Aveiro', 'Ativo'),
('Casa Gourmet, Lda.', '504567890', '234100004', 'encomendas@casagourmet.pt', 'Lisboa', 'Ativo'),
('Sabores Portugueses, SA', '505678901', '234100005', 'compras@saboresportugueses.pt', 'Coimbra', 'Ativo');

INSERT INTO encomendas
(id_cliente, id_utilizador_registo, data_encomenda, estado, observacoes)
VALUES
(1, 2, '2027-11-02 10:15:00', 'Confirmada', NULL),
(2, 2, '2027-11-03 14:20:00', 'Em preparação', 'Entrega prioritária.'),
(3, 3, '2027-11-05 09:30:00', 'Expedida', NULL),
(4, 2, '2027-11-07 16:45:00', 'Pendente', NULL),
(5, 1, '2027-11-09 11:10:00', 'Concluída', NULL);

INSERT INTO encomenda_itens
(id_encomenda, id_produto, quantidade, preco_unitario)
VALUES
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

-- ------------------------------------------------------------
-- Vistas
-- ------------------------------------------------------------
CREATE OR REPLACE VIEW vw_producao_por_salina AS
SELECT
    s.id_salina,
    s.nome AS salina,
    COUNT(c.id_colheita) AS numero_colheitas,
    COALESCE(SUM(c.quantidade_kg), 0) AS quantidade_total_kg
FROM salinas s
LEFT JOIN tanques t
    ON t.id_salina = s.id_salina
LEFT JOIN colheitas c
    ON c.id_tanque = t.id_tanque
GROUP BY
    s.id_salina,
    s.nome;

CREATE OR REPLACE VIEW vw_totais_encomenda AS
SELECT
    e.id_encomenda,
    c.nome AS cliente,
    u.nome AS registada_por,
    e.data_encomenda,
    e.estado,
    COALESCE(SUM(ei.quantidade * ei.preco_unitario), 0) AS valor_total
FROM encomendas e
INNER JOIN clientes c
    ON c.id_cliente = e.id_cliente
INNER JOIN utilizadores u
    ON u.id_utilizador = e.id_utilizador_registo
LEFT JOIN encomenda_itens ei
    ON ei.id_encomenda = e.id_encomenda
GROUP BY
    e.id_encomenda,
    c.nome,
    u.nome,
    e.data_encomenda,
    e.estado;

-- ------------------------------------------------------------
-- Consultas SQL exemplificativas
-- ------------------------------------------------------------

-- 1. Produção por salina
SELECT *
FROM vw_producao_por_salina
ORDER BY quantidade_total_kg DESC;

-- 2. Tanques e respetivas salinas
SELECT
    t.id_tanque,
    t.codigo,
    s.nome AS salina,
    t.area_m2,
    t.capacidade_maxima_kg,
    t.estado
FROM tanques t
INNER JOIN salinas s
    ON s.id_salina = t.id_salina
ORDER BY s.nome, t.codigo;

-- 3. Produção por trabalhador
SELECT
    tr.id_trabalhador,
    tr.nome,
    COUNT(c.id_colheita) AS numero_colheitas,
    COALESCE(SUM(c.quantidade_kg), 0) AS quantidade_total_kg
FROM trabalhadores tr
LEFT JOIN colheitas c
    ON c.id_trabalhador = tr.id_trabalhador
GROUP BY
    tr.id_trabalhador,
    tr.nome
ORDER BY quantidade_total_kg DESC;

-- 4. Colheitas e utilizador que efetuou o registo
SELECT
    c.id_colheita,
    s.nome AS salina,
    t.codigo AS tanque,
    tr.nome AS trabalhador_responsavel,
    u.nome AS registada_por,
    c.data_colheita,
    c.quantidade_kg
FROM colheitas c
INNER JOIN tanques t
    ON t.id_tanque = c.id_tanque
INNER JOIN salinas s
    ON s.id_salina = t.id_salina
INNER JOIN trabalhadores tr
    ON tr.id_trabalhador = c.id_trabalhador
INNER JOIN utilizadores u
    ON u.id_utilizador = c.id_utilizador_registo
ORDER BY c.data_colheita DESC;

-- 5. Encomendas e respetivos totais
SELECT *
FROM vw_totais_encomenda
ORDER BY data_encomenda DESC;

-- 6. Produtos presentes em cada encomenda
SELECT
    e.id_encomenda,
    c.nome AS cliente,
    p.nome AS produto,
    ei.quantidade,
    ei.preco_unitario,
    ei.quantidade * ei.preco_unitario AS subtotal
FROM encomendas e
INNER JOIN clientes c
    ON c.id_cliente = e.id_cliente
INNER JOIN encomenda_itens ei
    ON ei.id_encomenda = e.id_encomenda
INNER JOIN produtos p
    ON p.id_produto = ei.id_produto
ORDER BY e.id_encomenda, p.nome;

-- 7. Utilizadores, perfis e trabalhadores associados
SELECT
    u.id_utilizador,
    u.nome,
    u.username,
    u.email,
    p.nome AS perfil,
    tr.nome AS trabalhador_associado,
    u.estado
FROM utilizadores u
INNER JOIN perfis p
    ON p.id_perfil = u.id_perfil
LEFT JOIN trabalhadores tr
    ON tr.id_trabalhador = u.id_trabalhador
ORDER BY p.nome, u.nome;

-- 8. Produção agrupada por mês
SELECT
    DATE_FORMAT(c.data_colheita, '%Y-%m') AS mes,
    COUNT(*) AS numero_colheitas,
    SUM(c.quantidade_kg) AS quantidade_total_kg
FROM colheitas c
GROUP BY DATE_FORMAT(c.data_colheita, '%Y-%m')
ORDER BY mes;
