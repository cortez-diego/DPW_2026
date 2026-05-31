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
$role = $_SESSION['sim_user_role'] ?? 'usuario';
if ($role !== 'admin') {
    echo "<script>alert('Acesso negado. Apenas administradores podem acessar esta área.'); window.location.href='dashboard.php';</script>";
    exit;
}

include 'includes/contents/configuracoes_content.php'; 

include 'includes/dashboard/footer.php';
?>