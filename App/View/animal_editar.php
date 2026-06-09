<?php
/**
 * AmigoPet - Página de Edição de Animal
 * Localização: ~/App/View/animal_editar.php
 * Arquivo principal que monta o layout e chama a view específica de edição.
 * 
 * ATENÇÃO: Você precisa habilitar a extensão GD no PHP para o upload de imagens. No XAMPP:
 * 
 * Abra C:\xampp\php\php.ini
 * Procure por ;extension=gd
 * Remova o ; no início da linha para descomentar
 * Salve e reinicie o Apache
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

$id = $_GET['id'] ?? null;
$id = is_numeric($id) ? (int) $id : null;

if (!$id) {
    header('Location: /dashboard/animal/listar');
    exit;
}

$animalDAO = new App\DAO\AnimalDAO();
$animal = $animalDAO->buscarPorId($id);

if (!$animal) {
    header('Location: /dashboard/animal/listar');
    exit;
}

$especieDAO = new App\DAO\EspecieDAO();
$racaDAO = new App\DAO\RacaDAO();
$animalRacaDAO = new App\DAO\AnimalRacaDAO();

$especieId = (int) $animal->__get('fk_especie_id');
$racasAll = $racaDAO->listar();
$racas = $especieId ? $racaDAO->listarPorEspecie($especieId) : $racasAll;

$animalRacas = $animalRacaDAO->listarPorAnimal($id);
$racasVinculadas = [];
foreach ($animalRacas as $ar) {
    $racasVinculadas[] = (int) $ar->fk_raca_id;
}

$view = new stdClass();
$view->animal = $animal;
$view->especies = $especieDAO->listar();
$view->racas = $racas;
$view->racasAll = $racasAll;
$view->racasVinculadas = $racasVinculadas;
$view->params = ['id' => $id];

$renderer = new class($view) {
    private $view;

    public function __construct($view)
    {
        $this->view = $view;
    }

    public function getView()
    {
        return $this->view;
    }

    public function render($path)
    {
        include $path;
    }
};

include 'includes/dashboard/header.php';
include 'includes/dashboard/menu.php';
include 'includes/dashboard/navbar.php';

$renderer->render(__DIR__ . '/dashboard/animal_editar.php');

include 'includes/dashboard/footer.php';
?>