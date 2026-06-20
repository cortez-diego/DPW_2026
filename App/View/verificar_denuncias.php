<?php
/**
 * AmigoPet - Verificar Denúncias
 * Localização: ~/App/View/verificar_denuncias.php
 */

ob_start();
include 'includes/dashboard/header.php';
include 'includes/dashboard/menu.php';
include 'includes/dashboard/navbar.php';

// Controle de acesso: administradores, moderadores, ONGs e veterinários
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tipoUsuario = $_SESSION['tipo_usuario'] ?? 'adotante';

// Mapeia o tipo_usuario do banco para os roles do sistema
$roleMap = [
    'administrador' => 'admin',
    'ong' => 'ong',
    'veterinario' => 'vet',
    'rastreador' => 'campo',
    'adotante' => 'usuario'
];

$role = $roleMap[$tipoUsuario] ?? 'usuario';

if (!in_array($role, ['admin', 'ong', 'vet'])) {
    header('Location: /dashboard');
    exit;
}

include 'includes/contents/verificar_denuncias_content.php';

include 'includes/dashboard/footer.php';
ob_end_flush();
?>
