-- Create table for donations
CREATE TABLE IF NOT EXISTS doacao (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    fk_ong_id INT(11) NOT NULL,
    tipo ENUM('dinheiro', 'alimentos', 'medicamentos', 'materiais', 'outros') NOT NULL,
    valor DECIMAL(10, 2) NULL,
    descricao TEXT NOT NULL,
    doador VARCHAR(255) NULL,
    data DATE NOT NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create table for expenses
CREATE TABLE IF NOT EXISTS despesa (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    fk_ong_id INT(11) NOT NULL,
    categoria ENUM('veterinario', 'medicamentos', 'alimentacao', 'higiene', 'transporte', 'instalacoes', 'outros') NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    descricao TEXT NOT NULL,
    fk_animal_id INT(11) NULL,
    data DATE NOT NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Add indexes for faster queries
CREATE INDEX idx_doacao_ong ON doacao(fk_ong_id);
CREATE INDEX idx_doacao_data ON doacao(data);
CREATE INDEX idx_despesa_ong ON despesa(fk_ong_id);
CREATE INDEX idx_despesa_animal ON despesa(fk_animal_id);
CREATE INDEX idx_despesa_data ON despesa(data);
