<?php

namespace App\DAO;

use App\DAO;
use App\Model\HistoricoAnimalModel;

class HistoricoAnimalDAO extends DAO
{
    public function inserir(HistoricoAnimalModel $m)
    {
        try {
            $descricao = $m->__get('hist_descr');
            $data = $m->__get('hist_data') ?? date('Y-m-d H:i:s');
            $tipo = $m->__get('hist_tipo');
            $fk_animal_id = $m->__get('fk_animal_id');
            $fk_ong_id = $m->__get('fk_ong_id');
            $fk_veterinario_id = $m->__get('fk_veterinario_id');

            $sql = "INSERT INTO historico_animal (descricao, data, tipo, fk_animal_id, fk_ong_id, fk_veterinario_id)
                    VALUES (:descricao, :data, :tipo, :fk_animal_id, :fk_ong_id, :fk_veterinario_id)";

            $stmt = $this->getConn()->prepare($sql);
            $stmt->bindValue(':descricao', $descricao);
            $stmt->bindValue(':data', $data);
            $stmt->bindValue(':tipo', $tipo);
            $stmt->bindValue(':fk_animal_id', $fk_animal_id, \PDO::PARAM_INT);
            $stmt->bindValue(':fk_ong_id', $fk_ong_id, \PDO::PARAM_INT);
            $stmt->bindValue(':fk_veterinario_id', $fk_veterinario_id, \PDO::PARAM_INT);
            $stmt->execute();

            return (int) $this->getConn()->lastInsertId();
        } catch (\PDOException $ex) {
            header('Location:/error103');
            die();
        }
    }

    public function listarPorAnimal(int $animalId)
    {
        try {
            $sql = "SELECT * FROM historico_animal WHERE fk_animal_id = :id ORDER BY data DESC";
            $stmt = $this->getConn()->prepare($sql);
            $stmt->bindValue(':id', $animalId, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $ex) {
            header('Location:/error103');
            die();
        }
    }
}
