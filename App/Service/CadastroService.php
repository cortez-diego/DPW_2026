<?php

namespace App\Service;

use App\DAO\AdotanteDAO;
use App\DAO\LoginDAO;
use App\Exception\CpfJaCadastradoException;
use App\Exception\EmailJaCadastradoException;

class CadastroService {

    public static function cadastrarAdotante($loginModel, $adotanteModel) {
        $loginDao = new LoginDAO();
        $adotanteDao = new AdotanteDAO();
        
        $email = $loginModel->__get('email');
        $cpf = $adotanteModel->__get('cpf');

        if ($loginDao->buscarPorEmail($email)) {
            throw new EmailJaCadastradoException('O email informado já possui um cadastro!');
        }

        if ($adotanteDao->buscarPorCPF($cpf)) {
           throw new CpfJaCadastradoException('O CPF informado já possui um cadastro!');
        }

        $loginId = $loginDao->inserir($loginModel);
        $adotanteModel->__set('fk_login_id', $loginId);

        try {
            $adotanteDao->inserirComExcecao($adotanteModel);
        } catch (\PDOException $ex) {
           $loginDao->excluir($loginId);
           throw $ex;
        }
    }
}