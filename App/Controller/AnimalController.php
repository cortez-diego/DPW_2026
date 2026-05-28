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
    private string $uploadDir = 'resources/dashboard/images/animais/';

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
        $dao->inserir($model);

        header('Location: /dashboard/animal/listar');
        die();
    }

    public function editar($params)
    {
        $id = $params['id'] ?? ($params[0] ?? null);

        $animalDAO     = new AnimalDAO();
        $especieDAO    = new EspecieDAO();
        $racaDAO       = new RacaDAO();
        $animalRacaDAO = new AnimalRacaDAO();

        $animal    = $animalDAO->buscarPorId($id);
        $especieId = (int) $animal->__get('fk_especie_id');

        $racas = $especieId
            ? $racaDAO->listarPorEspecie($especieId)
            : $racaDAO->listar();

        $racasVinculadas = array_map(
            fn($ar) => (int) $ar->fk_raca_id,
            $animalRacaDAO->listarPorAnimal((int) $id)
        );

        $this->getView()->title           = 'Editar Animal';
        $this->getView()->title_pagina    = 'Editar Animal';
        $this->getView()->animal          = $animal;
        $this->getView()->especies        = $especieDAO->listar();
        $this->getView()->racas           = $racas;
        $this->getView()->racasVinculadas = $racasVinculadas;
        $this->getView()->params          = $params;

        $this->render('../dashboard/animal_editar', 'dashboard');
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