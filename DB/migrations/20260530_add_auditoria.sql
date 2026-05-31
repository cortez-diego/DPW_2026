-- Migration: adiciona tabela auditoria

CREATE TABLE IF NOT EXISTS auditoria (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    tempo DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    cargo VARCHAR(50) NULL,
    acao VARCHAR(100) NOT NULL,
    detalhes TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Índice para buscas por tempo
CREATE INDEX idx_auditoria_tempo ON auditoria(tempo);
