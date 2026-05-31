<?php
// Mostrar erros para diagnóstico enquanto a página estiver com HTTP 500
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Autoloader Composer para classes App\* (quando acessado diretamente)
$autoload = __DIR__ . '/../../vendor/autoload.php';
if (file_exists($autoload)) {
	require_once $autoload;
}
// Inicia buffer de saída para permitir redirecionamentos (header) durante includes
if (ob_get_level() === 0) ob_start();
/**
 * AmigoPet - Gerenciar Animais (Wrapper)
 * Localização: ~/App/View/manage_animais.php
 */

include 'includes/dashboard/header.php';
include 'includes/dashboard/menu.php';
include 'includes/dashboard/navbar.php';

include 'includes/contents/manage_animais_content.php';

include 'includes/dashboard/footer.php';
?>