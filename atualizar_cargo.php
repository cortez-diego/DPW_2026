<?php
/**
 * AmigoPet - Atualizar Cargo de Usuário
 * Arquivo separado para processar atualização de cargo fora do framework MVC
 */

// Desabilitar exibição de erros
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

$id = $_GET['id'] ?? null;
$tipoUsuario = $_GET['tipo_usuario'] ?? null;

if (!$id || !$tipoUsuario) {
    echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos']);
    exit;
}

try {
    // Conexão direta com o banco de dados
    $host = 'localhost';
    $dbname = 'amigopet';
    $username = 'root';
    $password = '';

    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE login SET tipo_usuario = :tipoUsuario WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':tipoUsuario', $tipoUsuario);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (\Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} catch (\Error $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
exit;
