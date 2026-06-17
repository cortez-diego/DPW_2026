<?php

namespace App\Controller;

use FW\Controller\Action;
use App\Model\AdotanteModel;
use App\Model\LoginModel;
use App\DAO\AdotanteDAO;
use App\DAO\LoginDAO;
use FW\Controller\FuncoesGlobais;

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

        $globalfunction = new FuncoesGlobais();
        
        $nome = $_POST['nome'] ?? '';
        $cpf = $_POST['cpf'] ?? '';
        $data_nascimento = $_POST['data_nascimento'] ?? '';
        $cep = $_POST['cep'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $senha_confirmacao = $_POST['senha_confirmacao'] ?? '';
        $estado =  $_POST['estado'] ?? '';
        $cidade = $_POST['cidade'] ?? '';
        $bairro = $_POST['bairro'] ?? '';
        $logradouro = $_POST['logradouro']  ?? '';
        $numero = $_POST['numero'] ?? null;
        $complemento = $_POST['complemento'] ?? '';
        $telefone_1 =  $_POST['telefone_1'] ?? '';
        $telefone_2 = $_POST['telefone_2'] ?? '';

        $telefone_limpo_1 = $globalfunction->limparTelefone($telefone_1);
        $telefone_limpo_2 = $globalfunction->limparTelefone($telefone_2);

        if(empty($nome) || empty($cpf) || empty($email)) {
            header("Location: /cadastro?erro=1");
            die();
        }

        if(empty($senha) || empty($senha_confirmacao)) {
            header("Location: /cadastro?erro=2");
            die();
        }
        
        if($senha !== $senha_confirmacao) {
            header('Location: /cadastro?erro=3');
            die();
        }

        if(empty($data_nascimento)) {
            header('Location: /cadastro?erro=4');
            die();
        }

        if(empty($telefone_limpo_1)) {
            header('Location: /cadastro?erro=5');
            die();
        }

        if($numero === '') {
            $numero = null;
        }


        $loginDao = new LoginDAO();
        $verificacaoEmail = $loginDao->buscarPorEmail($email);
        
        if($verificacaoEmail) {
            header('Location: /cadastro?erro=6');
            die();
        }

        $adotanteDao = new AdotanteDAO();
        $verificacaoCpf = $adotanteDao->buscarPorCPF($cpf);
        
        if($verificacaoCpf) {
            header('Location: /cadastro?erro=8');
            die();
        }

        $loginModel = new LoginModel();
        $loginModel->__set('email', $email);
        $loginModel->__set('senha', $senha);

        $loginId = $loginDao->inserir($loginModel);

        $adotanteModel = new AdotanteModel();
        $adotanteModel->__set('nome',   $nome);
        $adotanteModel->__set('cpf',    $cpf);
        $adotanteModel->__set('data_nascimento', $data_nascimento);
        $adotanteModel->__set('cep',    $cep);
        $adotanteModel->__set('estado', $estado);
        $adotanteModel->__set('cidade', $cidade);
        $adotanteModel->__set('bairro', $bairro);
        $adotanteModel->__set('logradouro',  $logradouro);
        $adotanteModel->__set('numero', $numero);
        $adotanteModel->__set('complemento', $complemento);
        $adotanteModel->__set('telefone_1', $telefone_limpo_1);
        $adotanteModel->__set('telefone_2', $telefone_limpo_2);

        $adotanteModel->__set('fk_login_id', $loginId);
        try{
            $adotanteDao->inserirComExcecao($adotanteModel);
        } catch(\PDOException $ex) {
            $loginDao->excluir($loginId);
            header('Location: /cadastro?erro=9');
            die();
        }

        header('Location: /');
        die();
       
    }

    public function validaAutenticacao() {}
}
