<?php

namespace App\Controller;

use FW\Controller\Action;
use App\DAO\AdotanteDAO;
use App\Model\AdotanteModel;

class AdotanteController extends Action
{
    public function listar()
    {
        $dao = new AdotanteDAO();
        $adotantes = $dao->listar();

        $this->getView()->title       = 'Adotantes';
        $this->getView()->title_pagina = 'Listar Adotantes';
        $this->getView()->adotantes   = $adotantes;

        $this->render('../dashboard/adotante_listar', 'dashboard');
    }

    public function cadastro()
    {
        $this->getView()->title       = 'Cadastro de Adotante';
        $this->getView()->title_pagina = 'Cadastro de Adotante';

        $this->render('../dashboard/adotante_cadastro', 'dashboard');
    }

    public function cadastrar()
    {
        $model = new AdotanteModel();
        $model->__set('nome',   $_POST['nome']            ?? '');
        $model->__set('cpf',    $_POST['cpf']             ?? '');
        $model->__set('data_nascimento',     $_POST['data_nascimento'] ?? null);
        $model->__set('cep',    $_POST['cep']             ?? '');
        $model->__set('estado', $_POST['estado']          ?? '');
        $model->__set('cidade', $_POST['cidade']          ?? '');
        $model->__set('bairro', $_POST['bairro']          ?? '');
        $model->__set('logradouro',  $_POST['logradouro']  ?? '');
        $model->__set('numero',      $_POST['numero']      ?? '');
        $model->__set('complemento', $_POST['complemento'] ?? '');
        $model->__set('telefone_1',   $_POST['telefone_1'] ?? '');
        $model->__set('telefone_2',   $_POST['telefone_2'] ?? '');
        $model->__set('status', $_POST['status']     ?? 'bom');

        $dao = new AdotanteDAO();
        $adotanteId = $dao->inserir($model);

        header('Location: /dashboard/adotante/listar');
        die();
    }

    public function cadastroPublico()
    {
        $this->getView()->title = 'Cadastro';
        $this->getView()->title_pagina = 'Cadastro de Usuário';

        $this->render('cadastro', null);
    }

    public function cadastrarPublico()
    {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $confirmarSenha = $_POST['confirmar_senha'] ?? '';

        // Validar senha
        if ($senha !== $confirmarSenha) {
            header('Location: /cadastro?erro=1');
            die();
        }

        if (strlen($senha) < 8) {
            header('Location: /cadastro?erro=2');
            die();
        }

        // Verificar se email já existe
        $loginDAO = new \App\DAO\LoginDAO();
        if ($loginDAO->buscarPorEmail($email)) {
            header('Location: /cadastro?erro=3');
            die();
        }

        // Inserir login
        $loginModel = new \App\Model\LoginModel();
        $loginModel->__set('email', $email);
        $loginModel->__set('senha', $senha);
        $loginModel->__set('status', 'a');
        $loginModel->__set('tipo_usuario', 'adotante');
        $loginId = $loginDAO->inserir($loginModel);

        // Inserir adotante
        $adotanteModel = new AdotanteModel();
        $adotanteModel->__set('nome', $_POST['nome'] ?? '');
        $adotanteModel->__set('cpf', $_POST['cpf'] ?? '');
        $adotanteModel->__set('data_nascimento', $_POST['data_nascimento'] ?? null);
        $adotanteModel->__set('cep', $_POST['cep'] ?? '');
        $adotanteModel->__set('estado', $_POST['estado'] ?? '');
        $adotanteModel->__set('cidade', $_POST['cidade'] ?? '');
        $adotanteModel->__set('bairro', $_POST['bairro'] ?? '');
        $adotanteModel->__set('logradouro', $_POST['logradouro'] ?? '');
        $adotanteModel->__set('numero', $_POST['numero'] ?? '');
        $adotanteModel->__set('complemento', $_POST['complemento'] ?? '');
        $adotanteModel->__set('telefone_1', $_POST['telefone_1'] ?? '');
        $adotanteModel->__set('telefone_2', $_POST['telefone_2'] ?? '');
        $adotanteModel->__set('status', 'a');
        $adotanteModel->__set('fk_login_id', $loginId);

        $adotanteDAO = new AdotanteDAO();
        $adotanteDAO->inserir($adotanteModel);

        header('Location: /login?sucesso=1');
        die();
    }

    public function editar($params)
    {
        $id  = $params['id'] ?? ($params[0] ?? null);
        $dao = new AdotanteDAO();
        $adotante = $dao->buscarPorId($id);

        $this->getView()->title        = 'Editar Adotante';
        $this->getView()->title_pagina = 'Editar Adotante';
        $this->getView()->adotante     = $adotante;

        $this->render('../dashboard/adotante_editar', 'dashboard');
    }

    public function alterar()
    {
        $model = new AdotanteModel();
        $model->__set('id',   $_POST['id']             ?? null);
        $model->__set('nome', $_POST['nome']            ?? '');
        $model->__set('cpf',  $_POST['cpf']             ?? '');
        $model->__set('data_nascimento',   $_POST['data_nascimento'] ?? null);
        $model->__set('cep',  $_POST['cep']             ?? '');
        $model->__set('estado', $_POST['estado']        ?? '');
        $model->__set('cidade', $_POST['cidade']        ?? '');
        $model->__set('bairro', $_POST['bairro']        ?? '');
        $model->__set('logradouro',  $_POST['logradouro']  ?? '');
        $model->__set('numero',      $_POST['numero']      ?? '');
        $model->__set('complemento', $_POST['complemento'] ?? '');
        $model->__set('telefone_1',   $_POST['telefone_1'] ?? '');
        $model->__set('telefone_2',   $_POST['telefone_2'] ?? '');
        $model->__set('status', $_POST['status']     ?? 'bom');

        $dao = new AdotanteDAO();
        $dao->alterar($model);

        header('Location: /dashboard/adotante/listar');
        die();
    }

    public function excluir()
    {
        $id  = $_POST['id'] ?? null;
        $dao = new AdotanteDAO();
        $dao->excluir($id);

        header('Location: /dashboard/adotante/listar');
        die();
    }

    public function validaAutenticacao()
    {
    }
}
