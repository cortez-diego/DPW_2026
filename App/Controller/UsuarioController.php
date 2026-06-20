<?php

namespace App\Controller;

use FW\Controller\Action;
use App\DAO\LoginDAO;

class UsuarioController extends Action
{
    public function index()
    {
        $this->getView()->title = 'Gerenciar Usuários';
        $this->getView()->title_pagina = 'Gerenciamento de Usuários';

        $this->render('includes/contents/usuarios_content', 'dashboard');
    }

    public function atualizarCargo()
    {
        header('Content-Type: application/json');

        $id = $_POST['id'] ?? null;
        $tipoUsuario = $_POST['tipo_usuario'] ?? null;

        // Log para debug
        error_log("Controller recebido: ID=$id, Tipo=$tipoUsuario, POST=" . print_r($_POST, true));

        if (!$id || !$tipoUsuario) {
            echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos']);
            exit;
        }

        try {
            $loginDAO = new LoginDAO();
            $resultado = $loginDAO->atualizarTipoUsuario($id, $tipoUsuario);

            if ($resultado) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar cargo - verifique o log de erros do PHP']);
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function validaAutenticacao()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == '') {
            header('Location: /login');
            die();
        }

        // Check if user is administrator for usuarios page
        if ($_SESSION['tipo_usuario'] !== 'administrador') {
            header('Location: /dashboard');
            die();
        }
    }
}
