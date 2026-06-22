<?php

namespace App\Controller;

use FW\Controller\Action;
use FW\DB\Connection;

class MigrationController extends Action
{
    public function index()
    {
        // This is a simple migration runner - should be protected in production
        // For now, it's accessible to run the avatar/bio columns migration
        
        $migrationFile = __DIR__ . '/../../DB/migrations/20260620_add_profile_columns.sql';
        
        if (!file_exists($migrationFile)) {
            echo "Migration file not found: $migrationFile";
            return;
        }
        
        $sql = file_get_contents($migrationFile);
        
        try {
            $conexao = new Connection();
            $conn = $conexao->getConn();
            
            // Split SQL by semicolon and execute each statement
            $statements = explode(';', $sql);
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    $conn->exec($statement);
                }
            }
            
            echo "Migration executed successfully!";
        } catch (\PDOException $e) {
            echo "Migration failed: " . $e->getMessage();
        }
    }

    public function visitas()
    {
        // Run the visits table migration
        
        $migrationFile = __DIR__ . '/../../DB/migrations/20260621_add_visits_table.sql';
        
        if (!file_exists($migrationFile)) {
            echo "Migration file not found: $migrationFile";
            return;
        }
        
        $sql = file_get_contents($migrationFile);
        
        try {
            $conexao = new Connection();
            $conn = $conexao->getConn();
            
            // Split SQL by semicolon and execute each statement
            $statements = explode(';', $sql);
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    $conn->exec($statement);
                }
            }
            
            echo "Visits table migration executed successfully!";
        } catch (\PDOException $e) {
            echo "Visits table migration failed: " . $e->getMessage();
        }
    }

    public function doacoes()
    {
        // Run the donations and expenses tables migration
        
        $migrationFile = __DIR__ . '/../../DB/migrations/20260621_add_donations_expenses_tables.sql';
        
        if (!file_exists($migrationFile)) {
            echo "Migration file not found: $migrationFile";
            return;
        }
        
        $sql = file_get_contents($migrationFile);
        
        try {
            $conexao = new Connection();
            $conn = $conexao->getConn();
            
            // Split SQL by semicolon and execute each statement
            $statements = explode(';', $sql);
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    $conn->exec($statement);
                }
            }
            
            echo "Donations and expenses tables migration executed successfully!";
        } catch (\PDOException $e) {
            echo "Donations and expenses tables migration failed: " . $e->getMessage();
        }
    }

    public function voluntarios()
    {
        // Run the volunteers tables migration
        
        $migrationFile = __DIR__ . '/../../DB/migrations/20260621_add_volunteers_tables.sql';
        
        if (!file_exists($migrationFile)) {
            echo "Migration file not found: $migrationFile";
            return;
        }
        
        $sql = file_get_contents($migrationFile);
        
        try {
            $conexao = new Connection();
            $conn = $conexao->getConn();
            
            // Split SQL by semicolon and execute each statement
            $statements = explode(';', $sql);
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    $conn->exec($statement);
                }
            }
            
            echo "Volunteers tables migration executed successfully!";
        } catch (\PDOException $e) {
            echo "Volunteers tables migration failed: " . $e->getMessage();
        }
    }

    public function chamados()
    {
        // Run the chamados table migration
        
        $migrationFile = __DIR__ . '/../../DB/migrations/20260621_add_chamados_table.sql';
        
        if (!file_exists($migrationFile)) {
            echo "Migration file not found: $migrationFile";
            return;
        }
        
        $sql = file_get_contents($migrationFile);
        
        try {
            $conexao = new Connection();
            $conn = $conexao->getConn();
            
            // Split SQL by semicolon and execute each statement
            $statements = explode(';', $sql);
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    $conn->exec($statement);
                }
            }
            
            echo "Chamados table migration executed successfully!";
        } catch (\PDOException $e) {
            echo "Chamados table migration failed: " . $e->getMessage();
        }
    }

    public function chamadosAlter()
    {
        // Run the chamados table alter migration to make fk_ong_id nullable
        
        $migrationFile = __DIR__ . '/../../DB/migrations/20260621_alter_chamados_table.sql';
        
        if (!file_exists($migrationFile)) {
            echo "Migration file not found: $migrationFile";
            return;
        }
        
        $sql = file_get_contents($migrationFile);
        
        try {
            $conexao = new Connection();
            $conn = $conexao->getConn();
            
            $conn->exec($sql);
            
            echo "Chamados table alter migration executed successfully!";
        } catch (\PDOException $e) {
            echo "Chamados table alter migration failed: " . $e->getMessage();
        }
    }

    public function resgates()
    {
        // Run the resgates table migration
        
        $migrationFile = __DIR__ . '/../../DB/migrations/20260621_add_resgates_table.sql';
        
        if (!file_exists($migrationFile)) {
            echo "Migration file not found: $migrationFile";
            return;
        }
        
        $sql = file_get_contents($migrationFile);
        
        try {
            $conexao = new Connection();
            $conn = $conexao->getConn();
            
            // Split SQL by statements to handle the ALTER TABLE separately
            $statements = explode(';', $sql);
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    try {
                        $conn->exec($statement);
                    } catch (\PDOException $e) {
                        // Ignore foreign key errors if the constraint already exists
                        if (strpos($e->getMessage(), 'foreign key constraint') === false) {
                            throw $e;
                        }
                    }
                }
            }
            
            echo "Resgates table migration executed successfully!";
        } catch (\PDOException $e) {
            echo "Resgates table migration failed: " . $e->getMessage();
        }
    }

    public function validaAutenticacao()
    {
        // Migration controller doesn't require authentication
        // This method is required by the parent class but does nothing for this public endpoint
    }
}
