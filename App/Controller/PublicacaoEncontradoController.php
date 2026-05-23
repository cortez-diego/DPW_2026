<?php

namespace App\Controller;

use FW\Controller\Action;
use App\DAO\PublicacaoEncontradoDAO;
use App\Model\PublicacaoEncontradoModel;

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
        $this->getView()->title        = 'Cadastro de Publicação';
        $this->getView()->title_pagina = 'Cadastro de Publicação';

        $this->render('../dashboard/publicacao_encontrado_cadastro', 'dashboard');
    }

    public function cadastrar()
    {
        $model = new PublicacaoEncontradoModel();
        $model->__set('fk_animal_id',     $_POST['fk_animal_id']     ?? null);
        $model->__set('fk_login_id',      $_POST['fk_login_id']      ?? null);
        $model->__set('data_encontro',    $_POST['data_encontro']    ?? null);
        $model->__set('condicao_fisca',   $_POST['condicao_fisca']   ?? '');
        $model->__set('acoes_realizadas', $_POST['acoes_realizadas'] ?? '');
        $model->__set('status',           $_POST['status']           ?? 'aguardando_acolhimento');

        $dao = new PublicacaoEncontradoDAO();
        $dao->inserir($model);

        header('Location: /dashboard/publicacao_encontrado/listar');
        die();
    }

    public function editar($params)
    {
        $id  = $params['id'] ?? ($params[0] ?? null);
        $dao = new PublicacaoEncontradoDAO();
        $publicacao = $dao->buscarPorId($id);

        $this->getView()->title        = 'Editar Publicação';
        $this->getView()->title_pagina = 'Editar Publicação';
        $this->getView()->publicacao   = $publicacao;

        $this->render('../dashboard/publicacao_encontrado_editar', 'dashboard');
    }

    public function alterar()
    {
        $model = new PublicacaoEncontradoModel();
        $model->__set('id',              $_POST['id']              ?? null);
        $model->__set('data_encontro',   $_POST['data_encontro']   ?? null);
        $model->__set('condicao_fisca',  $_POST['condicao_fisca']  ?? '');
        $model->__set('acoes_realizadas',$_POST['acoes_realizadas']?? '');
        $model->__set('status',          $_POST['status']          ?? 'aguardando_acolhimento');

        $dao = new PublicacaoEncontradoDAO();
        $dao->alterar($model);

        header('Location: /dashboard/publicacao_encontrado/listar');
        die();
    }

    public function excluir()
    {
        $id  = $_POST['id'] ?? null;
        $dao = new PublicacaoEncontradoDAO();
        $dao->excluir($id);

        header('Location: /dashboard/publicacao_encontrado/listar');
        die();
    }

    public function validaAutenticacao()
    {
        // Implementar lógica de autenticação se necessário
    }
}
