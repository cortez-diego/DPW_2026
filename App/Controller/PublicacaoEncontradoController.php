<?php

namespace App\Controller;

use App\DAO\AnimalDAO;
use FW\Controller\Action;
use App\DAO\PublicacaoEncontradoDAO;
use App\Model\PublicacaoEncontrado;

class PublicacaoEncontradoController extends Action
{
    public function listar()
    {
        $dao = new PublicacaoEncontradoDAO();
        $publicacoes = $dao->listar();

        $this->getView()->title        = 'Publicações de Animais Encontrados';
        $this->getView()->title_pagina = 'Listar Publicações';
        $this->getView()->publicacoes  = $publicacoes;

        $this->render('../dashboard/publicacao_encontrado_listar', 'dashboard');
    }

    public function cadastro()
    {
        $animalDAO = new AnimalDAO();

        $this->getView()->title        = 'Cadastro de Publicação';
        $this->getView()->title_pagina = 'Cadastro de Publicação';
        $this->getView()->animais = $animalDAO->listar();

        $this->render('../dashboard/publicacao_encontrado_cadastro', 'dashboard');
    }

    public function cadastrar()
    {
        // $this->validaAutenticacao();
        $model = new PublicacaoEncontrado();
        $model->__set('fk_animal_id',     $_POST['fk_animal_id']     ?? null);
         $model->__set('fk_login_id', 1);
        // $model->__set('fk_login_id', $_SESSION['id']);
        $model->__set('data_encontro',    $_POST['data_encontro']    ?? null);
        $model->__set('condicao_fisica',   $_POST['condicao_fisica']   ?? '');
        $model->__set('acoes_realizadas', $_POST['acoes_realizadas'] ?? '');
        $model->__set('status',           $_POST['status']           ?? 'aguardando acolhimento');

        $dao = new PublicacaoEncontradoDAO();
        $dao->inserir($model);

        header('Location: /dashboard/publicacao/listar');
        die();
    }

    public function editar($params)
    {
        $animalDAO = new AnimalDAO();
        $id  = $params['id'] ?? ($params[0] ?? null);
        $dao = new PublicacaoEncontradoDAO();
        $publicacao = $dao->buscarPorId($id);

        $this->getView()->title        = 'Editar Publicação';
        $this->getView()->title_pagina = 'Editar Publicação';
        $this->getView()->animais = $animalDAO->listar();
        $this->getView()->publicacao   = $publicacao;
        

        $this->render('../dashboard/publicacao_encontrado_editar', 'dashboard');
    }

    public function alterar()
    {
        $model = new PublicacaoEncontrado();
        $model->__set('id',              $_POST['id']              ?? null);
        $model->__set('fk_animal_id',    $_POST['fk_animal_id'] ?? null);
        $model->__set('data_encontro',   $_POST['data_encontro']   ?? null);
        $model->__set('condicao_fisica', $_POST['condicao_fisica']  ?? '');
        $model->__set('acoes_realizadas',$_POST['acoes_realizadas']?? '');
        $model->__set('status',          $_POST['status']          ?? 'aguardando acolhimento');

        $dao = new PublicacaoEncontradoDAO();
        $dao->alterar($model);

        header('Location: /dashboard/publicacao/listar');
        die();
    }

    public function excluir()
    {
        $id  = $_POST['id'] ?? null;
        $dao = new PublicacaoEncontradoDAO();
        $dao->excluir($id);

        header('Location: /dashboard/publicacao/listar');
        die();
    }

    public function validaAutenticacao()
    {
        if (
            !isset($_SESSION['id']) ||
            $_SESSION['id'] == ''
        ) {
        header('Location: /login');
            die();
        }
    }
}
