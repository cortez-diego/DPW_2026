<?php

namespace FW\DB;

class Connection
{

    private $conn;
    private $dbname;
    private $host;
    private $user;
    private $pass;

    public function __construct()
    {
        // Tenta carregar variáveis de ambiente a partir de .env (quando executado fora do front controller)
        $root = realpath(__DIR__ . '/../../..');
        if ($root && file_exists($root . '/.env') && !class_exists('Dotenv\Dotenv')) {
            // autoload talvez não tenha sido incluído — tente incluir vendor/autoload.php
            $autoload = $root . '/vendor/autoload.php';
            if (file_exists($autoload)) {
                require_once $autoload;
            }
        }
        
        // Tenta carregar .env primeiro (base configuration)
        if ($root && file_exists($root . '/.env') && class_exists('Dotenv\Dotenv')) {
            try {
                \Dotenv\Dotenv::createImmutable($root)->safeLoad();
            } catch (\Throwable $e) {
                // ignore: se falhar, tentará .env.local abaixo
            }
        }
        
        // Tenta carregar .env.local depois (para sobrescrever configurações locais)
        // Usamos createMutable() para permitir sobrescrita de variáveis
        if ($root && file_exists($root . '/.env.local') && class_exists('Dotenv\Dotenv')) {
            try {
                \Dotenv\Dotenv::createMutable($root, '.env.local')->load();
            } catch (\Throwable $e) {
                // ignore: se falhar, usará valores do .env ou getenv/$_ENV abaixo
            }
        }

        $this->dbname = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: null;
        $this->host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: null;
        $this->user = $_ENV['DB_USER'] ?? getenv('DB_USER') ?: null;
        $this->pass = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?: null;
        try {
            $this->conn = new \PDO(
                "mysql:dbname=" . $this->dbname . ";host=" . $this->host . ";charset=utf8",
                $this->user,
                $this->pass
            );
        } catch (\PDOException $ex) {
            // Log the error instead of dying
            error_log("Database connection error: " . $ex->getMessage());
            // Set conn to null so the application can handle the lack of connection
            $this->conn = null;
        }
    }

    public function getConn()
    {
        return $this->conn;
    }
}
