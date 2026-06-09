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
    die("500 Internal Server Error");
}
