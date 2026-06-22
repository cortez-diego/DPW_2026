-- Create table for rescue calls (chamados)
CREATE TABLE IF NOT EXISTS chamado (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    fk_ong_id INT(11) NULL,
    fk_denuncia_id INT(11) NULL,
    tipo ENUM('resgate', 'abandono', 'maus_tratos', 'perdido', 'encontrado', 'outro') NOT NULL,
    urgencia ENUM('baixa', 'media', 'alta', 'critica') DEFAULT 'media',
    assunto VARCHAR(255) NOT NULL,
    localizacao VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    status ENUM('pendente', 'em_andamento', 'concluido', 'cancelado') DEFAULT 'pendente',
    origem ENUM('manual', 'denuncia') DEFAULT 'manual',
    contato_nome VARCHAR(255) NULL,
    contato_telefone VARCHAR(20) NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_chamado_ong (fk_ong_id),
    INDEX idx_chamado_denuncia (fk_denuncia_id),
    INDEX idx_chamado_status (status),
    INDEX idx_chamado_urgencia (urgencia)
);

-- Create table for chamado observations
CREATE TABLE IF NOT EXISTS chamado_observacao (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    fk_chamado_id INT(11) NOT NULL,
    fk_usuario_id INT(11) NOT NULL,
    observacao TEXT NOT NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_observacao_chamado (fk_chamado_id),
    INDEX idx_observacao_usuario (fk_usuario_id)
);
