-- Migration alternativa: mesmo objetivo, sem CREATE PROCEDURE
-- Cria índice e constraint apenas quando ausentes usando PREPARE/EXECUTE

START TRANSACTION;

-- (Reaplicável) Adiciona colunas caso ainda não existam
ALTER TABLE `animal`
  ADD COLUMN IF NOT EXISTS `fk_especie_id` int(11) DEFAULT NULL AFTER `data_nascimento`,
  ADD COLUMN IF NOT EXISTS `cor` varchar(100) DEFAULT NULL AFTER `fk_especie_id`,
  ADD COLUMN IF NOT EXISTS `castrado` tinyint(1) NOT NULL DEFAULT 0 AFTER `cor`,
  ADD COLUMN IF NOT EXISTS `descricao` text DEFAULT NULL AFTER `castrado`;

-- Insere espécies encontradas na coluna texto `especie` na tabela `especie`
INSERT INTO `especie` (`nome`)
SELECT DISTINCT TRIM(`especie`) AS nome
FROM `animal`
WHERE `especie` IS NOT NULL AND TRIM(`especie`) <> ''
  AND TRIM(`especie`) NOT IN (SELECT IFNULL(TRIM(nome), '') FROM `especie`);

-- Popula fk_especie_id juntando pelo nome da espécie
UPDATE `animal` a
JOIN `especie` e ON TRIM(e.nome) = TRIM(a.especie)
SET a.fk_especie_id = e.id
WHERE a.especie IS NOT NULL AND TRIM(a.especie) <> '';

-- Criar índice se não existir (monta comando condicionalmente)
SET @idx_sql = (
  SELECT IF(COUNT(*)=0,
    'ALTER TABLE `animal` ADD INDEX `fk_animal_especie_idx` (`fk_especie_id`)',
    'SELECT 1')
  FROM INFORMATION_SCHEMA.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'animal' AND INDEX_NAME = 'fk_animal_especie_idx'
);
PREPARE stmt FROM @idx_sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Criar constraint FK se não existir
SET @fk_sql = (
  SELECT IF(COUNT(*)=0,
    'ALTER TABLE `animal` ADD CONSTRAINT `fk_animal_especie` FOREIGN KEY (`fk_especie_id`) REFERENCES `especie`(`id`) ON DELETE SET NULL',
    'SELECT 1')
  FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
  WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = 'animal' AND CONSTRAINT_NAME = 'fk_animal_especie'
);
PREPARE stmt2 FROM @fk_sql; EXECUTE stmt2; DEALLOCATE PREPARE stmt2;

COMMIT;

-- Fim da migração alternativa
