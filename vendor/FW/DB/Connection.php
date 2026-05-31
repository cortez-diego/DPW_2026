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
        if ($root && file_exists($root . '/.env') && class_exists('Dotenv\Dotenv')) {
            try {
                \Dotenv\Dotenv::createImmutable($root)->safeLoad();
            } catch (\Throwable $e) {
                // ignore: se falhar, usaremos getenv/$_ENV abaixo
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
            echo "Ocorreu erro: " . $ex->getMessage();
            die();
        }
    }

    public function getConn()
    {
        return $this->conn;
    }
}
