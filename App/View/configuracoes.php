<?php
/**
 * AmigoPet - Página de Configurações do Sistema (Admin)
 * Localização: ~/App/View/configuracoes.php
 */

include 'includes/dashboard/header.php';
include 'includes/dashboard/menu.php';
include 'includes/dashboard/navbar.php';

/**
 * CONTROLE DE ACESSO:
 * Apenas administradores podem aceder a esta página de configurações globais.
 */
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

if ($role !== 'admin') {
    header('Location: /dashboard');
    exit;
}

include 'includes/contents/configuracoes_content.php'; 

include 'includes/dashboard/footer.php';
?>