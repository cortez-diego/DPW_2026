<?php

namespace App\Controller;

use FW\Controller\Action;
use App\DAO\SolicitacaoAdocaoDAO;
use App\DAO\AnimalDAO;
use App\Model\SolicitacaoAdocaoModel;

class SolicitacaoAdocaoController extends Action
{
    /*public function listar()
    {
        $dao = new SolicitacaoAdocaoDAO();
        $solicitacoesadocao = $dao->listar();

        $this->getView()->title       = 'SolicitacoesAdocao';
        $this->getView()->title_pagina = 'Listar Solicitações de Adoção';
        $this->getView()->adotantes   = $solicitacoesadocao;

        $this->render('../dashboard/solicitacaoadocao_listar', 'dashboard');
    }*/

    public function cadastro($params)
    {
        $id  = $params['id'] ?? ($params[0] ?? null);
        $dao = new AnimalDAO();
        $animal = $dao->buscarPorId($id);

        $this->getView()->title       = 'Solicitação de Adoção';
        $this->getView()->title_pagina = 'Solicitação de Adoção';
        $this->getView()->animal     = $animal;

        $this->render('../dashboard/solicitacao_adocao', 'dashboard');
    }

    public function cadastrar()
    {
        $model = new SolicitacaoAdocaoModel();
        //$model->__set('solAdc_data',   $_POST['data']            ?? '');
        $model->__set('solAdc_status',    $_POST['status']             ?? '');
        $model->__set('solAdc_motivo',     $_POST['motivo']     ?? '');
        //$model->__set('fk_adotante_id',    $_POST['adotante_id']             ?? '');
        //$model->__set('fk_animal_id',    $_POST['animal_id']             ?? '');

        $dao = new SolicitacaoAdocaoDAO();
        $dao->inserir($model);

        header('Location: /dashboard');
        die();
    }

    /*public function editar($params)
    {
        $id  = $params['id'] ?? ($params[0] ?? null);
        $dao = new AdotanteDAO();
        $adotante = $dao->buscarPorId($id);

        $this->getView()->title        = 'Editar Adotante';
        $this->getView()->title_pagina = 'Editar Adotante';
        $this->getView()->adotante     = $adotante;

        $this->render('../dashboard/adotante_editar', 'dashboard');
    }*/

    /*public function alterar()
    {
        $model = new AdotanteModel();
        $model->__set('adt_id',   $_POST['id']             ?? null);
        $model->__set('adt_nome', $_POST['nome']            ?? '');
        $model->__set('adt_cpf',  $_POST['cpf']             ?? '');
        $model->__set('adt_dn',   $_POST['data_nascimento'] ?? null);
        $model->__set('adt_cep',  $_POST['cep']             ?? '');
        $model->__set('adt_estado', $_POST['estado']        ?? '');
        $model->__set('adt_cidade', $_POST['cidade']        ?? '');
        $model->__set('adt_bairro', $_POST['bairro']        ?? '');
        $model->__set('adt_logradouro',  $_POST['logradouro']  ?? '');
        $model->__set('adt_numero',      $_POST['numero']      ?? '');
        $model->__set('adt_complemento', $_POST['complemento'] ?? '');
        $model->__set('adt_tel1',   $_POST['telefone_1'] ?? '');
        $model->__set('adt_tel2',   $_POST['telefone_2'] ?? '');
        $model->__set('adt_status', $_POST['status']     ?? 'bom');

        $dao = new AdotanteDAO();
        $dao->alterar($model);

        header('Location: /dashboard/adotante/listar');
        die();
    }*/

    /*public function excluir()
    {
        $id  = $_POST['id'] ?? null;
        $dao = new AdotanteDAO();
        $dao->excluir($id);

        header('Location: /dashboard/adotante/listar');
        die();
    }*/

    public function validaAutenticacao()
    {
    }
}
