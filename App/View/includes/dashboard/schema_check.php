<?php

use FW\DB\Connection;

if (!function_exists('app_dashboard_schema_check')) {
    function app_dashboard_schema_check(): void
    {
        $notices = [];

        try {
            if (!class_exists(Connection::class)) {
                return;
            }

            $conn = (new Connection())->getConn();
            if (!$conn instanceof \PDO) {
                return;
            }

            $conn->exec("SET NAMES utf8");

            $requiredTables = [
                'especie' => <<<SQL
CREATE TABLE IF NOT EXISTS `especie` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL,
                'raca' => <<<SQL
CREATE TABLE IF NOT EXISTS `raca` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(100) NOT NULL,
    `fk_especie_id` INT(11) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL,
                'animal' => <<<SQL
CREATE TABLE IF NOT EXISTS `animal` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(100) NOT NULL,
    `data_nascimento` DATE NULL,
    `sexo` ENUM('m','f','n/a') NOT NULL DEFAULT 'n/a',
    `fk_especie_id` INT(11) NULL,
    `cor` VARCHAR(50) NULL,
    `castrado` TINYINT(1) NOT NULL DEFAULT 0,
    `descricao` TEXT NULL,
    `porte` VARCHAR(20) NULL,
    `localizacao` VARCHAR(100) NULL,
    `foto` VARCHAR(255) NULL,
    `status` ENUM('disponivel','reservado') NOT NULL DEFAULT 'disponivel'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL,
                'animal_raca' => <<<SQL
CREATE TABLE IF NOT EXISTS `animal_raca` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `fk_raca_id` INT(11) NULL,
    `fk_animal_id` INT(11) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL,
                'ong_animal' => <<<SQL
CREATE TABLE IF NOT EXISTS `ong_animal` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `fk_ong_id` INT(11) NULL,
    `fk_animal_id` INT(11) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL,
                'solicitacao_adocao' => <<<SQL
CREATE TABLE IF NOT EXISTS `solicitacao_adocao` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `data` DATETIME NULL,
    `status` ENUM('a','i','p') NULL,
    `motivo` TEXT NULL,
    `fk_adotante_id` INT(11) NULL,
    `fk_animal_id` INT(11) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL,
            ];

            foreach ($requiredTables as $tableName => $ddl) {
                if (!table_exists($conn, $tableName)) {
                    $conn->exec($ddl);
                    $notices[] = sprintf('Tabela `%s` criada.', $tableName);
                }
            }

            if (table_exists($conn, 'animal')) {
                $columns = [
                    'nome' => 'VARCHAR(100) NOT NULL DEFAULT ""',
                    'data_nascimento' => 'DATE NULL',
                    'sexo' => 'ENUM(\'m\',\'f\',\'n/a\') NOT NULL DEFAULT \'n/a\'',
                    'fk_especie_id' => 'INT(11) NULL',
                    'cor' => 'VARCHAR(50) NULL',
                    'castrado' => 'TINYINT(1) NOT NULL DEFAULT 0',
                    'descricao' => 'TEXT NULL',
                    'porte' => 'VARCHAR(20) NULL',
                    'localizacao' => 'VARCHAR(100) NULL',
                    'foto' => 'VARCHAR(255) NULL',
                    'status' => 'ENUM(\'disponivel\',\'reservado\') NOT NULL DEFAULT \'disponivel\'',
                ];

                foreach ($columns as $column => $definition) {
                    if (!column_exists($conn, 'animal', $column)) {
                        $conn->exec(sprintf('ALTER TABLE `animal` ADD COLUMN `%s` %s;', $column, $definition));
                        $notices[] = sprintf('Coluna `%s` adicionada em `animal`.', $column);
                    }
                }

                if (column_exists($conn, 'animal', 'sexo') && !column_type_contains($conn, 'animal', 'sexo', "'n/a'")) {
                    $conn->exec("ALTER TABLE `animal` MODIFY `sexo` ENUM('m','f','n/a') NOT NULL DEFAULT 'n/a';");
                    $notices[] = 'Coluna `sexo` ajustada para permitir o valor n/a.';
                }

                if (column_exists($conn, 'animal', 'status') && !column_type_contains($conn, 'animal', 'status', "'reservado'")) {
                    $conn->exec("ALTER TABLE `animal` MODIFY `status` ENUM('disponivel','reservado') NOT NULL DEFAULT 'disponivel';");
                    $notices[] = 'Coluna `status` ajustada para suportar os valores do dashboard.';
                }
            }

            if (!empty($notices)) {
                echo '<div class="alert alert-info mb-3" style="font-size:0.95rem;"><strong>Verificação de esquema:</strong><br>' . implode('<br>', array_map('htmlspecialchars', $notices)) . '</div>';
            }

        } catch (\Throwable $e) {
            echo '<div class="alert alert-warning mb-3" style="font-size:0.95rem;">Não foi possível verificar automaticamente o esquema do banco de dados: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
}

if (!function_exists('table_exists')) {
    function table_exists(\PDO $conn, string $tableName): bool
    {
        if (!preg_match('/^[A-Za-z0-9_]+$/', $tableName)) {
            return false;
        }
        $stmt = $conn->prepare("SHOW TABLES LIKE :table");
        $stmt->bindValue(':table', $tableName);
        $stmt->execute();
        return (bool) $stmt->fetchColumn();
    }
}

if (!function_exists('column_exists')) {
    function column_exists(\PDO $conn, string $tableName, string $columnName): bool
    {
        if (!preg_match('/^[A-Za-z0-9_]+$/', $tableName) || !preg_match('/^[A-Za-z0-9_]+$/', $columnName)) {
            return false;
        }
        $stmt = $conn->prepare("SHOW COLUMNS FROM `$tableName` LIKE :column");
        $stmt->bindValue(':column', $columnName);
        $stmt->execute();
        return (bool) $stmt->fetchColumn();
    }
}

if (!function_exists('column_type_contains')) {
    function column_type_contains(\PDO $conn, string $tableName, string $columnName, string $search): bool
    {
        if (!preg_match('/^[A-Za-z0-9_]+$/', $tableName) || !preg_match('/^[A-Za-z0-9_]+$/', $columnName)) {
            return false;
        }
        $stmt = $conn->prepare("SHOW COLUMNS FROM `$tableName` LIKE :column");
        $stmt->bindValue(':column', $columnName);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            return false;
        }
        return strpos($row['Type'] ?? '', $search) !== false;
    }
}

app_dashboard_schema_check();
