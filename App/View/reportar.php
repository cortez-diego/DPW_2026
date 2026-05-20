<?php
/**
 * AmigoPet - Página de Reportar Casos
 * Localização: ~/App/View/reportar.php
 */

ob_start();
include 'includes/dashboard/header.php';
include 'includes/dashboard/menu.php';
include 'includes/dashboard/navbar.php';

include 'reportar_content.php'; 

include 'includes/dashboard/footer.php';
ob_end_flush();
?>