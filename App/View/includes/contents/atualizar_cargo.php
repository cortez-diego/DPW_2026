<?php
/**
 * AmigoPet - Atualizar Cargo de Usuário
 * Arquivo separado para processar atualização de cargo fora do framework MVC
 */

// Incluir autoloader do Composer
require_once __DIR__ . '/../../../../vendor/autoload.php';

header('Content-Type: application/json');

$id = $_POST['id'] ?? null;
$tipoUsuario = $_POST['tipo_usuario'] ?? null;

if (!$id || !$tipoUsuario) {
    echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos']);
    exit;
}

try {
    use App\DAO\LoginDAO;
    $loginDAO = new LoginDAO();
    $resultado = $loginDAO->atualizarTipoUsuario($id, $tipoUsuario);

    if ($resultado) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar cargo']);
    }
} catch (\Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
exit;
