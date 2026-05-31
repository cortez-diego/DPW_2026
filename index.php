<?php
use Dotenv\Dotenv;

// Habilita registro de erros no arquivo temporário
$logFile = '/tmp/amigopet_index_debug.log';
$timestamp = date('Y-m-d H:i:s');
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', $logFile);

file_put_contents($logFile, "[$timestamp] === INDEX.PHP STARTED ===\n", FILE_APPEND);
file_put_contents($logFile, "[$timestamp] REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "\n", FILE_APPEND);
file_put_contents($logFile, "[$timestamp] PHP Version: " . phpversion() . "\n", FILE_APPEND);

try {
    file_put_contents($logFile, "[$timestamp] Loading autoload...\n", FILE_APPEND);
    require_once 'vendor/autoload.php';

    file_put_contents($logFile, "[$timestamp] Starting session...\n", FILE_APPEND);
    session_start();

    file_put_contents($logFile, "[$timestamp] Loading Dotenv...\n", FILE_APPEND);
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    file_put_contents($logFile, "[$timestamp] Creating Route object...\n", FILE_APPEND);

    $route = new \App\Route();
    file_put_contents($logFile, "[$timestamp] === INDEX.PHP COMPLETED ===\n", FILE_APPEND);
} catch (\Throwable $ex) {
    file_put_contents($logFile, "[$timestamp] EXCEPTION: " . $ex->getMessage() . "\n", FILE_APPEND);
    file_put_contents($logFile, "[$timestamp] File: " . $ex->getFile() . ":" . $ex->getLine() . "\n", FILE_APPEND);
    file_put_contents($logFile, "[$timestamp] Stack: " . $ex->getTraceAsString() . "\n", FILE_APPEND);
    http_response_code(500);
    die("500 Internal Server Error");
}
