<?php

namespace App\Controller;

use FW\Controller\Action;
use App\DAO\LoginDAO;
use FW\DB\Connection;

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

    public function vincularONG()
    {
        header('Content-Type: application/json');

        $id = $_POST['id'] ?? null;
        $ongId = $_POST['fk_ong_id'] ?? null;

        if (!$id || !$ongId) {
            echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos']);
            exit;
        }

        try {
            $conexao = new Connection();
            $conn = $conexao->getConn();

            // Update login table with fk_ong_id
            $sql = "UPDATE login SET fk_ong_id = :ong_id WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':ong_id' => $ongId, ':id' => $id]);

            // Check if ONG record exists for this user, if not create it
            $sql = "SELECT id FROM ong WHERE id = :ong_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':ong_id' => $ongId]);
            $ongExists = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$ongExists) {
                echo json_encode(['success' => false, 'message' => 'ONG não encontrada']);
                exit;
            }

            echo json_encode(['success' => true]);
        } catch (\PDOException $e) {
            error_log("Error linking ONG: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function vincularClinica()
    {
        header('Content-Type: application/json');

        $id = $_POST['id'] ?? null;
        $clinicaId = $_POST['fk_clinica_id'] ?? null;

        if (!$id || !$clinicaId) {
            echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos']);
            exit;
        }

        try {
            $conexao = new Connection();
            $conn = $conexao->getConn();

            // Check if vet_clinica record exists, if not create it
            $sql = "SELECT id FROM vet_clinica WHERE fk_veterinario_id = :vet_id AND fk_clinica_id = :clinica_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':vet_id' => $id, ':clinica_id' => $clinicaId]);
            $existing = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$existing) {
                // Create vet_clinica relationship
                $sql = "INSERT INTO vet_clinica (fk_veterinario_id, fk_clinica_id) VALUES (:vet_id, :clinica_id)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([':vet_id' => $id, ':clinica_id' => $clinicaId]);
            }

            echo json_encode(['success' => true]);
        } catch (\PDOException $e) {
            error_log("Error linking clinic: " . $e->getMessage());
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
