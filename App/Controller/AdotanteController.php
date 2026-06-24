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
        // Se houver dados via POST, o Controller intercepta e salva na hora!
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || !empty($_POST)) {
            try {
                $model = new AdotanteModel();
                
                $model->__set('nome',            $_POST['nome']            ?? '');
                $model->__set('cpf',             $_POST['cpf']             ?? '');
                $model->__set('data_nascimento', $_POST['data_nascimento'] ?? null);
                $model->__set('cep',             $_POST['cep']             ?? '');
                $model->__set('estado',          $_POST['estado']          ?? '');
                $model->__set('cidade',          $_POST['cidade']          ?? '');
                $model->__set('bairro',          $_POST['bairro']          ?? '');
                $model->__set('logradouro',      $_POST['logradouro']      ?? '');
                $model->__set('numero',          $_POST['numero']          ?? '');
                $model->__set('complemento',     $_POST['complemento']     ?? '');
                $model->__set('telefone_1',      $_POST['telefone_1']      ?? '');
                $model->__set('telefone_2',      $_POST['telefone_2']      ?? '');
                $model->__set('status',          $_POST['status']          ?? 'bom');
                $model->__set('fk_login_id', 1);

                $dao = new AdotanteDAO();
                $dao->inserir($model);

                // O pulo do gato: cospe o script de saída limpa antes do layout do professor renderizar
                echo "<script>window.location.href = '/dashboard/adotante/listar';</script>";
                exit();
            } catch (\Exception $e) {
                echo "<h3>Erro ao salvar no banco de dados:</h3><pre>" . $e->getMessage() . "</pre>";
                die();
            }
        }

        // Se for acesso normal (GET), renderiza o formulário padrão
        $this->getView()->title       = 'Cadastro de Adotante';
        $this->getView()->title_pagina = 'Cadastro de Adotante';

        $this->render('../dashboard/adotante_cadastro', 'dashboard');
    }

    public function cadastrar()
    {
        // Garante suporte se o roteador chamar o método no infinitivo
        $this->cadastro();
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