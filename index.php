<?php
use Dotenv\Dotenv;

try {
    require_once 'vendor/autoload.php';
    session_start();

    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $route = new \App\Route();
} catch (\Throwable $ex) {
    http_response_code(500);
    $errorMsg = "Error: " . $ex->getMessage() . " in " . $ex->getFile() . " line " . $ex->getLine();
    error_log($errorMsg);
    die($errorMsg);
}
