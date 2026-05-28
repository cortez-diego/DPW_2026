<?php

namespace App\DAO;

use App\DAO;
use App\Model\AnimalRacaModel;
use FW\Controller\FuncoesGlobais;

class AnimalRacaDAO extends DAO
{
    public function inserir($obj)
    {
        $sql  = "INSERT INTO animal_raca (fk_animal_id, fk_raca_id)
                 VALUES (:fk_animal_id, :fk_raca_id)";
        $stmt = $this->getConn()->prepare($sql);
        $stmt->bindValue(':fk_animal_id', $obj->__get('fk_animal_id'), \PDO::PARAM_INT);
        $stmt->bindValue(':fk_raca_id',   $obj->__get('fk_raca_id'),   \PDO::PARAM_INT);
        $stmt->execute();
        return (int) $this->getConn()->lastInsertId();
    }

    public function excluir($id)
    {
        $sql  = "DELETE FROM animal_raca WHERE id = :id";
        $stmt = $this->getConn()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }

    public function alterar($obj)
    {
        // Pivot não tem alteração
    }

    public function buscarPorId($id)
    {
        try {
            $sql  = "SELECT id, fk_animal_id, fk_raca_id FROM animal_raca WHERE id = :id";
            $stmt = $this->getConn()->prepare($sql);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();

            $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($resultado !== false) {
                $model  = new AnimalRacaModel();
                $global = new FuncoesGlobais();
                $global->popularModel($model, $resultado);
                return $model;
            }

            return false;

        } catch (\PDOException $ex) {
            header('Location:/error103');
            die();
        }
    }

    public function listar()
    {
        try {
            $lista = array();

            $sql  = "SELECT id, fk_animal_id, fk_raca_id FROM animal_raca";
            $stmt = $this->getConn()->prepare($sql);
            $stmt->execute();

            $resultado = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                $model  = new AnimalRacaModel();
                $global = new FuncoesGlobais();
                $global->popularModel($model, $row);
                array_push($lista, $model);
            }

            return $lista;

        } catch (\PDOException $ex) {
            header('Location:/error103');
            die();
        }
    }

    public function vincular(int $animalId, int $racaId): bool
    {
        if ($this->existeVinculo($animalId, $racaId)) {
            return false;
        }

        $sql  = "INSERT INTO animal_raca (fk_animal_id, fk_raca_id)
                 VALUES (:fk_animal_id, :fk_raca_id)";
        $stmt = $this->getConn()->prepare($sql);
        $stmt->bindValue(':fk_animal_id', $animalId, \PDO::PARAM_INT);
        $stmt->bindValue(':fk_raca_id',   $racaId,   \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function desvincular(int $animalId, int $racaId): bool
    {
        $sql  = "DELETE FROM animal_raca
                 WHERE fk_animal_id = :fk_animal_id
                   AND fk_raca_id   = :fk_raca_id";
        $stmt = $this->getConn()->prepare($sql);
        $stmt->bindValue(':fk_animal_id', $animalId, \PDO::PARAM_INT);
        $stmt->bindValue(':fk_raca_id',   $racaId,   \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function sincronizar(int $animalId, array $novosRacaIds): void
    {
        $novos  = array_map('intval', $novosRacaIds);
        $atuais = array_map(
            fn(AnimalRacaModel $ar) => (int) $ar->fk_raca_id,
            $this->listarPorAnimal($animalId)
        );

        foreach (array_diff($atuais, $novos) as $racaId) {
            $this->desvincular($animalId, $racaId);
        }
        foreach (array_diff($novos, $atuais) as $racaId) {
            $this->vincular($animalId, $racaId);
        }
    }

    public function listarPorAnimal(int $animalId): array
    {
        try {
            $lista = array();

            $sql  = "SELECT id, fk_animal_id, fk_raca_id
                     FROM   animal_raca
                     WHERE  fk_animal_id = :fk_animal_id
                     ORDER  BY fk_raca_id";
            $stmt = $this->getConn()->prepare($sql);
            $stmt->bindValue(':fk_animal_id', $animalId, \PDO::PARAM_INT);
            $stmt->execute();

            $resultado = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                $model  = new AnimalRacaModel();
                $global = new FuncoesGlobais();
                $global->popularModel($model, $row);
                array_push($lista, $model);
            }

            return $lista;

        } catch (\PDOException $ex) {
            header('Location:/error103');
            die();
        }
    }

    public function listarPorRaca(int $racaId): array
    {
        try {
            $lista = array();

            $sql  = "SELECT id, fk_animal_id, fk_raca_id
                     FROM   animal_raca
                     WHERE  fk_raca_id = :fk_raca_id
                     ORDER  BY fk_animal_id";
            $stmt = $this->getConn()->prepare($sql);
            $stmt->bindValue(':fk_raca_id', $racaId, \PDO::PARAM_INT);
            $stmt->execute();

            $resultado = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($resultado as $row) {
                $model  = new AnimalRacaModel();
                $global = new FuncoesGlobais();
                $global->popularModel($model, $row);
                array_push($lista, $model);
            }

            return $lista;

        } catch (\PDOException $ex) {
            header('Location:/error103');
            die();
        }
    }

    public function existeVinculo(int $animalId, int $racaId): bool
    {
        $sql  = "SELECT COUNT(*) FROM animal_raca
                 WHERE fk_animal_id = :fk_animal_id
                   AND fk_raca_id   = :fk_raca_id";
        $stmt = $this->getConn()->prepare($sql);
        $stmt->bindValue(':fk_animal_id', $animalId, \PDO::PARAM_INT);
        $stmt->bindValue(':fk_raca_id',   $racaId,   \PDO::PARAM_INT);
        $stmt->execute();
        return (int) $stmt->fetchColumn() > 0;
    }
}