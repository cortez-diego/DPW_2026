<?php
/**
 * AmigoPet - Relatórios (Admin / Moderador / ONG)
 * Localização: ~/App/View/relatorios.php
 */

include 'includes/dashboard/header.php';
include 'includes/dashboard/menu.php';
include 'includes/dashboard/navbar.php';

// Controle de acesso: administradores, moderadores e ONGs têm acesso
$role = $_SESSION['sim_user_role'] ?? 'usuario';
if (!in_array($role, ['admin', 'moderador', 'ong'])) {
    echo "<script>alert('Acesso negado. Área restrita a administradores, moderadores e ONGs.'); window.location.href='dashboard.php';</script>";
    exit;
}

include 'includes/contents/relatorios_content.php';

include 'includes/dashboard/footer.php';
?>
