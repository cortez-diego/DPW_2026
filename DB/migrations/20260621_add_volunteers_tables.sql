-- Create table for volunteers
CREATE TABLE IF NOT EXISTS voluntario (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    fk_ong_id INT(11) NOT NULL,
    fk_equipe_id INT(11) NULL,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    disponibilidade ENUM('manha', 'tarde', 'noite', 'fim_semana', 'flexivel') NULL,
    habilidades TEXT NULL,
    status ENUM('ativo', 'pendente', 'inativo') DEFAULT 'pendente',
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create table for teams
CREATE TABLE IF NOT EXISTS equipe (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    fk_ong_id INT(11) NOT NULL,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT NULL,
    fk_responsavel_id INT(11) NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Add indexes for faster queries
CREATE INDEX idx_voluntario_ong ON voluntario(fk_ong_id);
CREATE INDEX idx_voluntario_equipe ON voluntario(fk_equipe_id);
CREATE INDEX idx_voluntario_status ON voluntario(status);
CREATE INDEX idx_equipe_ong ON equipe(fk_ong_id);
CREATE INDEX idx_equipe_responsavel ON equipe(fk_responsavel_id);
