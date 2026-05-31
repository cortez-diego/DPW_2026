<?php
/**
 * AmigoPet - Dashboard Principal
 * Localização: ~/App/View/dashboard.php
 * Este arquivo serve como o "Layout" ou "Template" principal.
 */

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Autoloader Composer para classes App\* quando acessada diretamente
$autoload = __DIR__ . '/../../vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}
if (ob_get_level() === 0) ob_start();

// Caminhos corrigidos para a pasta de includes
include 'includes/dashboard/header.php';
include 'includes/dashboard/menu.php';
include 'includes/dashboard/navbar.php';

include 'includes/contents/animal_profile_content.php';

include 'includes/dashboard/footer.php';
?>