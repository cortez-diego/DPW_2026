<?php
use Dotenv\Dotenv;

try {
    require_once 'vendor/autoload.php';
    session_start();

    // Load .env first (base configuration)
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();

    // Load .env.local to override local settings
    if (file_exists(__DIR__ . '/.env.local')) {
        $dotenvLocal = Dotenv::createMutable(__DIR__, '.env.local');
        $dotenvLocal->load();
    }

    $route = new \App\Route();
} catch (\Throwable $ex) {
    http_response_code(500);
    $errorMsg = "Error: " . $ex->getMessage() . " in " . $ex->getFile() . " line " . $ex->getLine();
    error_log($errorMsg);
    die($errorMsg);
}
