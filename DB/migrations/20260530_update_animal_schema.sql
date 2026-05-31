-- Migration: Atualiza tabela `animal` para compatibilidade com DAOs
-- Cria colunas esperadas por AnimalDAO: fk_especie_id, cor, castrado, descricao
-- Migra valores de `animal.especie` para a tabela `especie` e popula fk_especie_id
-- Uso: executar no banco local `eswdev14_dsw_2026`

START TRANSACTION;

-- 1) Adiciona colunas ausentes (só se não existirem)
ALTER TABLE `animal`
  ADD COLUMN IF NOT EXISTS `fk_especie_id` int(11) DEFAULT NULL AFTER `data_nascimento`,
  ADD COLUMN IF NOT EXISTS `cor` varchar(100) DEFAULT NULL AFTER `fk_especie_id`,
  ADD COLUMN IF NOT EXISTS `castrado` tinyint(1) NOT NULL DEFAULT 0 AFTER `cor`,
  ADD COLUMN IF NOT EXISTS `descricao` text DEFAULT NULL AFTER `castrado`;

-- 2) Insere espécies encontradas na coluna texto `especie` na tabela `especie`
INSERT INTO `especie` (`nome`)
SELECT DISTINCT TRIM(`especie`) AS nome
FROM `animal`
WHERE `especie` IS NOT NULL AND TRIM(`especie`) <> ''
  AND TRIM(`especie`) NOT IN (SELECT IFNULL(TRIM(nome), '') FROM `especie`);

-- 3) Popula fk_especie_id juntando pelo nome da espécie
UPDATE `animal` a
JOIN `especie` e ON TRIM(e.nome) = TRIM(a.especie)
SET a.fk_especie_id = e.id
WHERE a.especie IS NOT NULL AND TRIM(a.especie) <> '';

-- 4) Garantir índice/constraint para fk_especie_id
-- Use PROCEDURE temporária para checar INFORMATION_SCHEMA e criar somente se não existirem
DELIMITER $$
DROP PROCEDURE IF EXISTS __add_animal_especie_idx_if_not_exists$$
CREATE PROCEDURE __add_animal_especie_idx_if_not_exists()
BEGIN
  DECLARE cnt INT DEFAULT 0;
  -- índice
  SELECT COUNT(1) INTO cnt FROM INFORMATION_SCHEMA.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'animal' AND INDEX_NAME = 'fk_animal_especie_idx';
  IF cnt = 0 THEN
    ALTER TABLE `animal` ADD INDEX `fk_animal_especie_idx` (`fk_especie_id`);
  END IF;
  -- constraint
  SELECT COUNT(1) INTO cnt FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = 'animal' AND CONSTRAINT_NAME = 'fk_animal_especie';
  IF cnt = 0 THEN
    ALTER TABLE `animal` ADD CONSTRAINT `fk_animal_especie` FOREIGN KEY (`fk_especie_id`) REFERENCES `especie`(`id`) ON DELETE SET NULL;
  END IF;
END$$
CALL __add_animal_especie_idx_if_not_exists()$$
DROP PROCEDURE IF EXISTS __add_animal_especie_idx_if_not_exists$$
DELIMITER ;

-- Observação: deixamos a coluna textual `especie` para compatibilidade; se preferir removê-la,
-- rode: ALTER TABLE animal DROP COLUMN especie;

COMMIT;

-- Fim da migração
