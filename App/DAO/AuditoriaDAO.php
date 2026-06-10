<?php

namespace App\DAO;

use App\DAO;

class AuditoriaDAO extends DAO
{
    public function registrar(string $cargo, string $acao, ?string $detalhes = null)
    {
        try {
            $sql = "INSERT INTO auditoria (cargo, acao, detalhes) VALUES (:cargo, :acao, :detalhes)";
            $stmt = $this->getConn()->prepare($sql);
            $stmt->bindValue(':cargo', $cargo);
            $stmt->bindValue(':acao', $acao);
            $stmt->bindValue(':detalhes', $detalhes);
            $stmt->execute();
            return (int) $this->getConn()->lastInsertId();
        } catch (\PDOException $ex) {
            // Não redireciona para evitar erro de headers already sent
            error_log('Erro ao registrar auditoria: ' . $ex->getMessage());
            return false;
        }
    }

    public function listar($limit = 100)
    {
        try {
            $sql = "SELECT * FROM auditoria ORDER BY tempo DESC LIMIT :l";
            $stmt = $this->getConn()->prepare($sql);
            $stmt->bindValue(':l', (int)$limit, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $ex) {
            header('Location:/error103');
            die();
        }
    }

    // Implementação dos métodos abstratos da classe base
    public function inserir($obj)
    {
        // Auditoria não segue o padrão de model, use registrar() em vez disso
        throw new \BadMethodCallException('Use registrar() em vez de inserir() para AuditoriaDAO');
    }

    public function excluir($obj)
    {
        throw new \BadMethodCallException('Método não implementado para AuditoriaDAO');
    }

    public function alterar($obj)
    {
        throw new \BadMethodCallException('Método não implementado para AuditoriaDAO');
    }

    public function buscarPorId($obj)
    {
        throw new \BadMethodCallException('Método não implementado para AuditoriaDAO');
    }
}
