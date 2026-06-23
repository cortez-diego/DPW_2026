<?php
require 'vendor/autoload.php';

$env = parse_ini_file('.env');
$host = $env['DB_HOST'] ?? 'localhost';
$dbname = $env['DB_NAME'] ?? 'amigopet';
$username = $env['DB_USER'] ?? 'root';
$password = $env['DB_PASS'] ?? '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check ong table structure
    $stmt = $conn->query("DESCRIBE ong");
    echo "ONG Table Structure:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
    
    echo "\n\nSample ONG records:\n";
    $stmt = $conn->query("SELECT * FROM ong LIMIT 3");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
