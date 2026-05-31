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
$role = $_SESSION['sim_user_role'] ?? 'usuario';
if (!in_array($role, ['admin', 'moderador', 'ong', 'veterinario'])) {
    echo "<script>alert('Acesso negado. Área restrita a equipe de moderação e ONGs.'); window.location.href='dashboard.php';</script>";
    exit;
}

include 'includes/contents/verificar_denuncias_content.php';

include 'includes/dashboard/footer.php';
ob_end_flush();
?>
