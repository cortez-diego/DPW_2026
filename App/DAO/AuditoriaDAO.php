<?php

namespace App\DAO;

use App\DAO;

class AuditoriaDAO extends DAO
{
    public function inserir(string $cargo, string $acao, ?string $detalhes = null)
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
            header('Location:/error103');
            die();
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
}
