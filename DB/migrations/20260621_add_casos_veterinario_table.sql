-- Create casos_veterinario table for veterinarian case evaluations
CREATE TABLE IF NOT EXISTS caso_veterinario (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    fk_animal_id INT(11) NOT NULL,
    fk_ong_id INT(11) NOT NULL,
    descricao TEXT NOT NULL,
    prioridade ENUM('baixa', 'media', 'alta', 'urgente') NOT NULL DEFAULT 'media',
    status ENUM('pendente', 'em_avaliacao', 'concluido') NOT NULL DEFAULT 'pendente',
    diagnostico TEXT NULL,
    tratamento TEXT NULL,
    observacoes TEXT NULL,
    data_envio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_avaliacao DATETIME NULL,
    data_atualizacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_fk_animal_id (fk_animal_id),
    INDEX idx_fk_ong_id (fk_ong_id),
    INDEX idx_status (status),
    INDEX idx_prioridade (prioridade),
    INDEX idx_data_envio (data_envio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add foreign key constraints
ALTER TABLE caso_veterinario 
ADD CONSTRAINT fk_caso_veterinario_animal 
FOREIGN KEY (fk_animal_id) REFERENCES animal(id) 
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE caso_veterinario 
ADD CONSTRAINT fk_caso_veterinario_ong 
FOREIGN KEY (fk_ong_id) REFERENCES ong(id) 
ON DELETE CASCADE ON UPDATE CASCADE;
