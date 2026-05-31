<?php 

namespace App\DAO;

use App\DAO;
use App\Model\SolicitacaoAdocaoModel;
use FW\Controller\FuncoesGlobais;

class SolicitacaoAdocaoDAO extends DAO {
    private function mapRowToModel(array $row): SolicitacaoAdocaoModel
    {
        $m = new SolicitacaoAdocaoModel();
        $m->__set('solAdc_id',       $row['id']              ?? null);
        $m->__set('solAdc_data',     $row['data']            ?? null);
        $m->__set('solAdc_status',   $row['status']          ?? null);
        $m->__set('solAdc_motivo',   $row['motivo']          ?? null);
        $m->__set('fk_adotante_id',  $row['fk_adotante_id']  ?? null);
        $m->__set('fk_animal_id',    $row['fk_animal_id']    ?? null);
        return $m;
    }

    public function inserir($obj)
    {
        try {
            $solAdc_data     = $obj->__get('solAdc_data');
            $solAdc_status   = $obj->__get('solAdc_status');
            $solAdc_motivo   = $obj->__get('solAdc_motivo');
            $fk_adotante_id  = $obj->__get('fk_adotante_id');
            $fk_animal_id    = $obj->__get('fk_animal_id');

            $sql = "INSERT INTO solicitacao_adocao (
                        `data`,
                        `status`,
                        `motivo`,
                        fk_adotante_id,
                        fk_animal_id
                    ) VALUES (
                        :data,
                        :status,
                        :motivo,
                        :fk_adotante_id,
                        :fk_animal_id
                    )";

            $conn = $this->getConn();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':data',    $solAdc_data);
            $stmt->bindValue(':status',  $solAdc_status);
            $stmt->bindValue(':motivo',  $solAdc_motivo);
            $stmt->bindValue(':fk_adotante_id', $fk_adotante_id, \PDO::PARAM_INT);
            $stmt->bindValue(':fk_animal_id',   $fk_animal_id,   \PDO::PARAM_INT);
            $stmt->execute();

            return (int) $conn->lastInsertId();
        } catch (\PDOException $ex) {
            header('Location:/error103');
            die();
        }
    }

    public function listar()
    {
        try {
            $sql = "SELECT id, `data`, `status`, `motivo`, fk_adotante_id, fk_animal_id FROM solicitacao_adocao ORDER BY `data` DESC";
            $stmt = $this->getConn()->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $list = [];
            foreach ($rows as $r) {
                $list[] = $this->mapRowToModel($r);
            }
            return $list;
        } catch (\PDOException $ex) {
            header('Location:/error103');
            die();
        }
    }

    public function buscarPorId($id)
    {
        try {
            $sql = "SELECT id, `data`, `status`, `motivo`, fk_adotante_id, fk_animal_id FROM solicitacao_adocao WHERE id = :id LIMIT 1";
            $stmt = $this->getConn()->prepare($sql);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!$row) {
                return false;
            }
            return $this->mapRowToModel($row);
        } catch (\PDOException $ex) {
            header('Location:/error103');
            die();
        }
    }

    public function alterar($obj)
    {
        try {
            $id              = $obj->__get('solAdc_id');
            $solAdc_data     = $obj->__get('solAdc_data');
            $solAdc_status   = $obj->__get('solAdc_status');
            $solAdc_motivo   = $obj->__get('solAdc_motivo');
            $fk_adotante_id  = $obj->__get('fk_adotante_id');
            $fk_animal_id    = $obj->__get('fk_animal_id');

            $sql = "UPDATE solicitacao_adocao SET
                        `data`     = :data,
                        `status`   = :status,
                        `motivo`   = :motivo,
                        fk_adotante_id  = :fk_adotante_id,
                        fk_animal_id    = :fk_animal_id
                    WHERE id = :id";

            $stmt = $this->getConn()->prepare($sql);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->bindValue(':data', $solAdc_data);
            $stmt->bindValue(':status', $solAdc_status);
            $stmt->bindValue(':motivo', $solAdc_motivo);
            $stmt->bindValue(':fk_adotante_id', $fk_adotante_id, \PDO::PARAM_INT);
            $stmt->bindValue(':fk_animal_id', $fk_animal_id, \PDO::PARAM_INT);
            $stmt->execute();
        } catch (\PDOException $ex) {
            header('Location:/error103');
            die();
        }
    }

    public function excluir($id)
    {
        try {
            $sql = "DELETE FROM solicitacao_adocao WHERE id = :id";
            $stmt = $this->getConn()->prepare($sql);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
        } catch (\PDOException $ex) {
            header('Location:/error103');
            die();
        }
    }

}