<?php

namespace App\Controller;

use FW\Controller\Action;
use App\DAO\AnimalDAO;
use App\DAO\EspecieDAO;
use App\DAO\RacaDAO;
use App\DAO\AnimalRacaDAO;
use App\Model\AnimalModel;

class AnimalController extends Action
{
    // Pasta de upload relativa à raiz do projeto
    private $uploadDir = 'resources/dashboard/images/animais/';

    public function listar()
    {
        $dao     = new AnimalDAO();
        $animais = $dao->listar();

        $this->getView()->title        = 'Animais';
        $this->getView()->title_pagina = 'Listar Animais';
        $this->getView()->animais      = $animais;

        $this->render('../dashboard/animal_listar', 'dashboard');
    }

    public function cadastro()
    {
        $especieDAO = new EspecieDAO();

        $this->getView()->title        = 'Cadastro de Animal';
        $this->getView()->title_pagina = 'Cadastro de Animal';
        $this->getView()->especies     = $especieDAO->listar();

        $this->render('../dashboard/animal_cadastro', 'dashboard');
    }

    public function cadastrar()
    {
        $foto = $this->processarUploadFoto();

        $model = new AnimalModel();
        $model->__set('nome',            $_POST['nome']            ?? '');
        $model->__set('data_nascimento', $_POST['data_nascimento'] ?? null);
        $model->__set('sexo',            $_POST['sexo']            ?? '');
        $model->__set('fk_especie_id',   $_POST['fk_especie_id']   ?? null);
        $model->__set('cor',             $_POST['cor']             ?? '');
        $model->__set('castrado',        !empty($_POST['castrado']));
        $model->__set('descricao',       $_POST['descricao']       ?? '');
        $model->__set('porte',           $_POST['porte']           ?? '');
        $model->__set('localizacao',     $_POST['localizacao']     ?? '');
        $model->__set('foto',            $foto);
        $model->__set('status',          $_POST['status']          ?? 'disponivel');

        $dao = new AnimalDAO();
        $animalId = $dao->inserir($model);

        // Vincular raça se fornecida
        if (!empty($_POST['fk_raca_id'])) {
            $animalRacaDAO = new AnimalRacaDAO();
            $animalRacaDAO->vincular($animalId, (int) $_POST['fk_raca_id']);
        }

        header('Location: /dashboard/animal/listar');
        die();
    }

    public function editar($params)
    {
        $logFile = __DIR__ . '/../../controller_debug.log';
        $timestamp = date('Y-m-d H:i:s');
        
        try {
            file_put_contents($logFile, "[$timestamp] AnimalController::editar called\n", FILE_APPEND);
            file_put_contents($logFile, "[$timestamp] params: " . print_r($params, true) . "\n", FILE_APPEND);
            
            $id = null;
            if (is_array($params)) {
                $id = $params['id'] ?? ($params[0] ?? null);
            } else {
                $id = $params;
            }
            
            file_put_contents($logFile, "[$timestamp] extracted id: " . var_export($id, true) . "\n", FILE_APPEND);

            $id = is_numeric($id) ? (int) $id : null;
            if (!$id) {
                file_put_contents($logFile, "[$timestamp] Invalid ID, redirecting\n", FILE_APPEND);
                header('Location: /dashboard/animal/listar');
                die();
            }

            $animalDAO     = new AnimalDAO();
            $animal        = $animalDAO->buscarPorId($id);
            
            file_put_contents($logFile, "[$timestamp] animal found: " . ($animal ? 'yes' : 'no') . "\n", FILE_APPEND);

            if (!$animal) {
                file_put_contents($logFile, "[$timestamp] Animal not found, redirecting\n", FILE_APPEND);
                header('Location: /dashboard/animal/listar');
                die();
            }

            $especieDAO    = new EspecieDAO();
            $racaDAO       = new RacaDAO();
            $animalRacaDAO = new AnimalRacaDAO();

            $especieId = (int) $animal->__get('fk_especie_id');

            $racasAll = $racaDAO->listar();
            $racas = $especieId
                ? $racaDAO->listarPorEspecie($especieId)
                : $racasAll;

            $animalRacas = $animalRacaDAO->listarPorAnimal($id);
            $racasVinculadas = [];
            foreach ($animalRacas as $ar) {
                $racasVinculadas[] = (int) $ar->fk_raca_id;
            }

            file_put_contents($logFile, "[$timestamp] All data loaded successfully\n", FILE_APPEND);

            $this->getView()->title           = 'Editar Animal';
            $this->getView()->title_pagina    = 'Editar Animal';
            $this->getView()->animal          = $animal;
            $this->getView()->especies        = $especieDAO->listar();
            $this->getView()->racas           = $racas;
            $this->getView()->racasAll        = $racasAll;
            $this->getView()->racasVinculadas = $racasVinculadas;
            $this->getView()->params          = $params;

            file_put_contents($logFile, "[$timestamp] About to render template\n", FILE_APPEND);
            $this->render('../dashboard/animal_editar', 'dashboard');
            file_put_contents($logFile, "[$timestamp] Template rendered successfully\n", FILE_APPEND);
            
        } catch (\Throwable $ex) {
            file_put_contents($logFile, "[$timestamp] Exception: " . $ex->getMessage() . "\n", FILE_APPEND);
            file_put_contents($logFile, "[$timestamp] Stack: " . $ex->getTraceAsString() . "\n", FILE_APPEND);
            throw $ex;
        }
    }

    public function alterar()
    {
        $fotoAtual = $_POST['foto_atual'] ?? '';
        $foto      = $this->processarUploadFoto($fotoAtual);

        $model = new AnimalModel();
        $model->__set('id',              $_POST['id']              ?? null);
        $model->__set('nome',            $_POST['nome']            ?? '');
        $model->__set('data_nascimento', $_POST['data_nascimento'] ?? null);
        $model->__set('sexo',            $_POST['sexo']            ?? '');
        $model->__set('fk_especie_id',   $_POST['fk_especie_id']   ?? null);
        $model->__set('cor',             $_POST['cor']             ?? '');
        $model->__set('castrado',        !empty($_POST['castrado']));
        $model->__set('descricao',       $_POST['descricao']       ?? '');
        $model->__set('porte',           $_POST['porte']           ?? '');
        $model->__set('localizacao',     $_POST['localizacao']     ?? '');
        $model->__set('foto',            $foto);
        $model->__set('status',          $_POST['status']          ?? 'disponivel');

        $dao = new AnimalDAO();
        $dao->alterar($model);

        $racaIds = $_POST['fk_raca_id'] ?? [];
        if (!is_array($racaIds)) {
            $racaIds = [$racaIds];
        }
        $racaIds = array_filter(array_map('intval', $racaIds));

        $animalRacaDAO = new AnimalRacaDAO();
        $animalRacaDAO->sincronizar((int) $model->__get('id'), $racaIds);

        header('Location: /dashboard/animal/listar');
        die();
    }

    public function excluir()
    {
        $id = $_POST['id'] ?? null;

        // Remove foto do servidor antes de excluir o registro
        $dao    = new AnimalDAO();
        $animal = $dao->buscarPorId($id);
        if ($animal && $animal->__get('foto')) {
            $this->removerFoto($animal->__get('foto'));
        }

        $dao->excluir($id);

        header('Location: /dashboard/animal/listar');
        die();
    }

    // ------------------------------------------------------------------ //
    //  Upload de foto
    // ------------------------------------------------------------------ //

    /**
     * Processa o upload da foto do animal.
     * Se nenhum arquivo for enviado, retorna a foto atual.
     *
     * @param  string $fotoAtual  Caminho da foto já salva (edição)
     * @return string             Caminho relativo salvo no banco
     */
    private function processarUploadFoto(string $fotoAtual = ''): string
    {
        // Nenhum arquivo enviado ou erro de upload
        if (empty($_FILES['foto']['name']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            return $fotoAtual;
        }

        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

        if (!in_array($extensao, $extensoesPermitidas)) {
            return $fotoAtual; // extensão inválida — mantém foto atual
        }

        // Nome único para evitar colisões
        $nomeArquivo = uniqid('animal_', true) . '.' . $extensao;
        $destino     = $this->uploadDir . $nomeArquivo;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
            // Remove foto antiga ao substituir
            if ($fotoAtual) {
                $this->removerFoto($fotoAtual);
            }
            return $destino;
        }

        return $fotoAtual;
    }

    /**
     * Remove o arquivo de foto do servidor.
     */
    private function removerFoto(string $caminho): void
    {
        if ($caminho && file_exists($caminho)) {
            unlink($caminho);
        }
    }

    public function validaAutenticacao()
    {
        if (
            !isset($_SESSION['id'])   || $_SESSION['id']   == '' ||
            !isset($_SESSION['nome']) || $_SESSION['nome'] == ''
        ) {
            header('Location: /login');
            die();
        }
    }
}