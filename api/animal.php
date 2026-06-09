<?php
// API simples para retornar dados de um animal como JSON
require_once __DIR__ . '/../vendor/autoload.php';

use App\DAO\AnimalDAO;

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'missing_id']);
    exit;
}

$dao = new AnimalDAO();
$animal = $dao->buscarPorId($id);
if (!$animal) {
    http_response_code(404);
    echo json_encode(['error' => 'not_found']);
    exit;
}

// Buscar as 5 imagens do animal
$imagens = $dao->buscarImagens($id);

// Mapear model para array simples
$result = [
    'animal' => [
        'id' => $animal->__get('id'),
        'nome' => $animal->__get('nome'),
        'descricao' => $animal->__get('descricao'),
        'foto' => $animal->__get('foto'),
        'sexo' => $animal->__get('sexo'),
        'porte' => $animal->__get('porte'),
        'idade_meses' => $animal->__get('idade_meses'),
        'idade' => $animal->__get('idade_meses') !== null ? (function($m){
            $m = (int)$m; $anos = intdiv($m,12); $meses = $m%12;
            return trim(($anos>0?($anos.' ano'.($anos>1?'s':'')):'').($anos>0&&$meses>0?' ':'').($meses>0?($meses.' mês'.($meses>1?'es':'')):''));
        })($animal->__get('idade_meses')) : '',
        'especie_nome' => $animal->__get('especie_nome'),
        'racas' => $animal->__get('racas'),
        'cor' => $animal->__get('cor'),
        'status' => $animal->__get('status'),
        'ong_nome' => $animal->__get('ong_nome')
    ],
    'imagens' => $imagens
];

header('Content-Type: application/json; charset=utf-8');
echo json_encode($result);
