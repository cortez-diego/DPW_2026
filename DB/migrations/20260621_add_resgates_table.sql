-- Create resgates table for managing animal rescues
CREATE TABLE IF NOT EXISTS resgate (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    fk_chamado_id INT(11) NULL,
    estado_animal ENUM('saudavel', 'ferido', 'precisa_atencao', 'critico') NOT NULL DEFAULT 'saudavel',
    destino VARCHAR(255) NULL,
    status_resgate ENUM('pendente', 'em_andamento', 'concluido') NOT NULL DEFAULT 'pendente',
    observacoes TEXT NULL,
    data_resgate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_fk_chamado_id (fk_chamado_id),
    INDEX idx_status_resgate (status_resgate),
    INDEX idx_data_resgate (data_resgate)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add foreign key constraint to chamado table
ALTER TABLE resgate 
ADD CONSTRAINT fk_resgate_chamado 
FOREIGN KEY (fk_chamado_id) REFERENCES chamado(id) 
ON DELETE SET NULL ON UPDATE CASCADE;
