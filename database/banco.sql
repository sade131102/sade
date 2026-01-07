CREATE DATABASE controle_estudos CHARACTER SET utf8mb4;
USE controle_estudos;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE atividades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('disciplina','curso','projeto') NOT NULL,
    nome VARCHAR(255) NOT NULL,
    instituicao VARCHAR(255),
    finalidade VARCHAR(100),
    carga_prevista INT,
    ementa TEXT
);

CREATE TABLE estudos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    atividade_id INT NOT NULL,
    data DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    presenca ENUM('Presença','Falta') NOT NULL,
    conteudo TEXT,
    ano_letivo YEAR NOT NULL,
    FOREIGN KEY (atividade_id) REFERENCES atividades(id)
);

CREATE TABLE metas_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    meta_diaria DECIMAL(5,2) NOT NULL DEFAULT 1.00,
    meta_semanal DECIMAL(5,2) NOT NULL DEFAULT 5.00,
    meta_mensal DECIMAL(6,2) NOT NULL DEFAULT 20.00,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE (usuario_id)
);
CREATE TABLE badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    nome VARCHAR(100) NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    icone VARCHAR(50) NOT NULL
);
CREATE TABLE badges_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    badge_id INT NOT NULL,
    conquistado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (usuario_id, badge_id)
);

