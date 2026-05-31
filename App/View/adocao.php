<?php
/**
 * AmigoPet - Página de Adoção
 * Localização: ~/App/View/adocao.php
 * Arquivo principal que monta o layout.
 */
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Autoloader Composer para classes App\* quando a view é acessada diretamente
$autoload = __DIR__ . '/../../vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

if (ob_get_level() === 0) ob_start();

include 'includes/dashboard/header.php';
include 'includes/dashboard/menu.php';
include 'includes/dashboard/navbar.php';

include 'includes/contents/adocao_content.php'; 

include 'includes/dashboard/footer.php';
?>