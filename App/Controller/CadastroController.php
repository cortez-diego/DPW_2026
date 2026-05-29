<?php 

namespace App\Controller;

use FW\Controller\Action;
use App\DAO\AdotanteDAO;
use App\Model\AdotanteModel;

class CadastroController extends Action
{

 public function cadastro()
    {
        $this->getView()->title       = 'Cadastro de Usuários';
        $this->getView()->title_pagina = 'Criação de Login';

        $this->render('../dashboard/cadastro', 'dashboard');
    }

    public function cadastrar()
    {
        $adodanteModel = new AdotanteModel();
        $adodanteModel->__set('nome',   $_POST['nome']            ?? '');
        $adodanteModel->__set('cpf',    $_POST['cpf']             ?? '');
        $adodanteModel->__set('data_nascimento',     $_POST['data_nascimento'] ?? null);
        $adodanteModel->__set('cep',    $_POST['cep']             ?? '');
        $adodanteModel->__set('estado', $_POST['estado']          ?? '');
        $adodanteModel->__set('cidade', $_POST['cidade']          ?? '');
        $adodanteModel->__set('bairro', $_POST['bairro']          ?? '');
        $adodanteModel->__set('logradouro',  $_POST['logradouro']  ?? '');
        $adodanteModel->__set('numero',      $_POST['numero']      ?? '');
        $adodanteModel->__set('complemento', $_POST['complemento'] ?? '');
        $adodanteModel->__set('telefone_1',   $_POST['telefone_1'] ?? '');
        $adodanteModel->__set('telefone_2',   $_POST['telefone_2'] ?? '');

        $loginDAO = new LoginDAO();
        $loginModel = new LoginModel();

        $loginId = $loginDAO->inserir($adodanteModel);
        $adodanteModel->__set('fk_login_id', $loginId);
        
        $adotanteDao = new AdotanteDAO();
        $adotanteDao->inserir($adodanteModel);

        header('Location: /dashboard/adotante/listar');
        die();
    }

    public function validaAutenticacao()
    {
    }
}