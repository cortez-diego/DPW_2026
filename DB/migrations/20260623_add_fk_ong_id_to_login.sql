-- Adicionar coluna fk_ong_id à tabela login para vincular usuários a ONGs
ALTER TABLE login ADD COLUMN fk_ong_id INT NULL AFTER tipo_usuario;

-- Adicionar índice para melhor performance
ALTER TABLE login ADD INDEX idx_fk_ong_id (fk_ong_id);

-- Adicionar chave estrangeira (opcional, se desejar integridade referencial)
-- ALTER TABLE login ADD CONSTRAINT fk_login_ong FOREIGN KEY (fk_ong_id) REFERENCES ong(id) ON DELETE SET NULL ON UPDATE CASCADE;
