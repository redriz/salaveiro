CREATE DATABASE IF NOT EXISTS bdSalAveiro_AdonaiRodriguez_C27;
USE bdSalAveiro_AdonaiRodriguez_C27;

-- ------------------ CRIAÇÃO DE TABELAS -------------------

CREATE TABLE trabalhador (
    id INT AUTO_INCREMENT NOT NULL,
    nome VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(14),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE utilizador (
    id INT AUTO_INCREMENT NOT NULL,
    nome VARCHAR(50) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    trabalhador_id INT DEFAULT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    is_operator BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (trabalhador_id) REFERENCES trabalhador(id),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE salina (
    id INT AUTO_INCREMENT NOT NULL,
    nome VARCHAR(50) NOT NULL,
    trabalhador_id INT DEFAULT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (trabalhador_id) REFERENCES trabalhador(id),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE tanque (
    id INT AUTO_INCREMENT NOT NULL,
    salina_id INT NOT NULL,
    nome VARCHAR(50) NOT NULL,
    estado ENUM('ativo', 'em manutenção', 'inativo') NOT NULL DEFAULT 'ativo',
    UNIQUE (salina_id, nome),
    FOREIGN KEY (salina_id) REFERENCES salina(id) ON DELETE CASCADE,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE categoria_sal (
    id INT AUTO_INCREMENT NOT NULL,
    nome VARCHAR(50) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE produto (
    id INT AUTO_INCREMENT NOT NULL,
    nome VARCHAR(50) NOT NULL,
    descricao TEXT,
    peso DECIMAL(10, 1) NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL,
    categoria_sal_id INT DEFAULT NULL,
    FOREIGN KEY (categoria_sal_id) REFERENCES categoria_sal(id),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cliente (
    id INT AUTO_INCREMENT NOT NULL,
    nome VARCHAR(50) NOT NULL,
    nif VARCHAR(10) NOT NULL,
    email VARCHAR(100) NOT NULL,
    morada VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    telefone VARCHAR(14),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE carrinho (
    id INT AUTO_INCREMENT NOT NULL,
    cliente_id INT DEFAULT NULL,
    produto_id INT DEFAULT NULL,
    quantidade INT NOT NULL,
    CHECK (quantidade > 0),
    FOREIGN KEY (produto_id) REFERENCES produto(id),
    FOREIGN KEY (cliente_id) REFERENCES cliente(id),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE encomenda (
    id INT AUTO_INCREMENT NOT NULL,
    estado ENUM('pendente', 'em processamento', 'enviado', 'entregue', 'cancelado') NOT NULL DEFAULT 'pendente',
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user_id INT DEFAULT NULL,
    carrinho_id INT DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES utilizador(id),
    FOREIGN KEY (carrinho_id) REFERENCES carrinho(id),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------- INSERÇÃO DE DADOS -------------------

INSERT INTO trabalhador (nome, email, telefone) VALUES
('João Silva', 'joao.silva@example.com', '912345678'),
('Maria Santos', 'maria.santos@example.com', '912345679'),
('Carlos Oliveira', 'carlos.oliveira@example.com', '912345680'),
('Ana Costa', 'ana.costa@example.com', '912345681'),
('Pedro Almeida', 'pedro.almeida@example.com', '912345682');

INSERT INTO utilizador (nome, password_hash, trabalhador_id, is_admin, is_operator, is_active) VALUES
('admin', '$2y$12$S/RCqyjNHw3ux1bW0FGWMutVjPMRGb6dYFm7bNLrxaRPmZCBAStnK', 1, TRUE, FALSE, TRUE),
('operador', '$2y$12$S/RCqyjNHw3ux1bW0FGWMutVjPMRGb6dYFm7bNLrxaRPmZCBAStnK', 2, FALSE, TRUE, TRUE),
('usuario1', '$2y$12$S/RCqyjNHw3ux1bW0FGWMutVjPMRGb6dYFm7bNLrxaRPmZCBAStnK', 3, FALSE, FALSE, TRUE),
('usuario2', '$2y$12$S/RCqyjNHw3ux1bW0FGWMutVjPMRGb6dYFm7bNLrxaRPmZCBAStnK', NULL, FALSE, FALSE, TRUE),
('usuario3', '$2y$12$S/RCqyjNHw3ux1bW0FGWMutVjPMRGb6dYFm7bNLrxaRPmZCBAStnK', NULL, FALSE, FALSE, TRUE);

INSERT INTO salina (nome, trabalhador_id, is_active) VALUES
('Salina A', 1, TRUE),
('Salina B', 2, TRUE),
('Salina C', 3, TRUE),
('Salina D', 4, TRUE),
('Salina E', 5, TRUE);

INSERT INTO tanque (salina_id, nome, estado) VALUES
(1, 'Tanque 1', 'ativo'),
(1, 'Tanque 2', 'em manutenção'),
(2, 'Tanque 3', 'ativo'),
(2, 'Tanque 4', 'inativo'),
(3, 'Tanque 5', 'ativo'),
(3, 'Tanque 6', 'ativo'),
(4, 'Tanque 7', 'em manutenção'),
(4, 'Tanque 8', 'ativo'),
(5, 'Tanque 9', 'inativo'),
(5, 'Tanque 10', 'ativo');

INSERT INTO categoria_sal (nome) VALUES
('Sais Alimentares'),
('Sais Aromatizados'),
('Sais Verdes'),
('Sais Bem-Estar');

INSERT INTO produto (nome, descricao, peso, preco, stock, categoria_sal_id) VALUES
('Sal Marinho Grosso', 'Sal marinho de alta qualidade.', 500.0, 3.50, 100, 1),
('Sal Rosa do Himalaia', 'Sal rosa natural do Himalaia.', 250.0, 5.00, 50, 1),
('Sal Aromatizado com Ervas', 'Sal aromatizado com ervas finas.', 200.0, 4.00, 75, 2),
('Sal Verde com Algas', 'Sal verde enriquecido com algas marinhas.', 300.0, 6.00, 30, 3),
('Sal Bem-Estar com Magnésio', 'Sal enriquecido com magnésio para o bem-estar.', 150.0, 7.50, 20, 4);

INSERT INTO cliente (nome, nif, email, morada, is_active, telefone) VALUES
('Cliente A', '123456789', 'cliente.a@example.com', 'Rua Exemplo, 123', TRUE, '912345678'),
('Cliente B', '987654321', 'cliente.b@example.com', 'Avenida Exemplo, 456', TRUE, '912345679'),
('Cliente C', '456789123', 'cliente.c@example.com', 'Travessa Exemplo, 789', TRUE, '912345680'),
('Cliente D', '321654987', 'cliente.d@example.com', 'Praça Exemplo, 101', TRUE, '912345681');

INSERT INTO carrinho (cliente_id, produto_id, quantidade) VALUES
(1, 1, 2),
(1, 3, 1),
(2, 2, 3),
(3, 4, 1),
(4, 5, 2);

INSERT INTO encomenda (estado, user_id, carrinho_id) VALUES
('pendente', 1, 1),
('em processamento', 2, 2),
('enviado', 3, 3),
('entregue', 1, 4),
('cancelado', 2, 5);