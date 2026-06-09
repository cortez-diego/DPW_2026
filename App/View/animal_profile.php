<?php
/**
 * AmigoPet - Perfil do Animal
 * Localização: ~/App/View/animal_profile.php
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

// Se o animal foi passado pelo controller, usa ele; senão tenta $_GET
$animal = null;
if (isset($this) && isset($this->view) && isset($this->view->animal)) {
    $animal = $this->view->animal;
}

// Se não veio do controller, tenta pegar do $_GET (compatibilidade)
if (!$animal && isset($_GET['id'])) {
    $dao = new \App\DAO\AnimalDAO();
    $animal = $dao->buscarPorId(intval($_GET['id']));
}

// Passa o animal para o content
$this->getView()->animal = $animal;

include 'includes/contents/animal_profile_content.php';

include 'includes/dashboard/footer.php';
?>