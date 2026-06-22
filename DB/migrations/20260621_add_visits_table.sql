-- Create table for adoption visits
CREATE TABLE IF NOT EXISTS visita_adocao (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    fk_solicitacao_adocao_id INT(11) NOT NULL,
    fk_usuario_id INT(11) NOT NULL,
    data_visita DATE NOT NULL,
    estado_animal TEXT,
    endereco_visita VARCHAR(255),
    observacoes TEXT,
    nota TEXT,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Add index for faster queries
CREATE INDEX idx_visita_solicitacao ON visita_adocao(fk_solicitacao_adocao_id);
CREATE INDEX idx_visita_usuario ON visita_adocao(fk_usuario_id);
