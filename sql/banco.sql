-- ============================================================
-- BANCO DE DADOS: sistema_login
-- Crie e selecione o banco antes de rodar as tabelas
-- ============================================================

CREATE DATABASE IF NOT EXISTS sistema_login
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE sistema_login;

-- ============================================================
-- TABELA: usuarios
-- ============================================================
CREATE TABLE IF NOT EXISTS usuarios (
    id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    nome        VARCHAR(100)    NOT NULL,
    email       VARCHAR(150)    NOT NULL,
    senha       VARCHAR(255)    NOT NULL,          -- hash bcrypt
    criado_em   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- USUÁRIO MySQL com acesso apenas a este banco
-- Troque 'SenhaForte123!' por uma senha segura sua
-- ============================================================
CREATE USER IF NOT EXISTS 'appuser'@'localhost'
    IDENTIFIED BY 'SenhaForte123!';

GRANT SELECT, INSERT, UPDATE, DELETE
    ON sistema_login.*
    TO 'appuser'@'localhost';

FLUSH PRIVILEGES;
