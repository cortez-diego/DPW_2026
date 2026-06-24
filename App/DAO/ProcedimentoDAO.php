<?php

namespace App\DAO;

use App\DAO;
use App\Model\ProcedimentoModel;
use FW\Controller\FuncoesGlobais;

class ProcedimentoDAO extends DAO
{
    private function inserir($obj) {
        try {
            $nome        = $obj->__get('nome');
            $tipo        = $obj->__get('tipo');
            $data        = $obj->__get('data');
            $veterinario = $obj->__get('veterinario');
            $obs         = $obj->__get('obs');
            $anexo       = $obj->__get('anexo');
            $fk_animal   = $obj->__get('fk_animal');

            $sql = "INSERT INTO procedimento (
                        nome,
                        tipo,
                        data,
                        veterinario,
                        obs,
                        anexo,
                        fk_animal
                    ) VALUES (
                        :nome,
                        :tipo,
                        :data,
                        :veterinario,
                        :obs,
                        :anexo,
                        :fk_animal
                    )";

            $stmt = $this->getConn()->prepare($sql);
            $stmt->bindValue(':nome',        $nome);
            $stmt->bindValue(':tipo',        $tipo);
            $stmt->bindValue(':data',        $data);
            $stmt->bindValue(':veterinario', $veterinario);
            $stmt->bindValue(':obs',         $obs);
            $stmt->bindValue(':anexo',       $anexo);
            $stmt->bindValue(':fk_animal',   $fk_animal);
            $stmt->execute();

            return (int) $this->getConn()->lastInsertId();


        } catch (\PDOException $ex) {
            header('Location:/error103');
            die();
        }
    }

    public function excluir($id)
    {
        try {
            $sql = "DELETE FROM animal WHERE id = :id";

            $stmt = $this->getConn()->prepare($sql);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();

        } catch (\PDOException $ex) {
            header('Location:/error103');
            die();
        }
    }

    public function alterar($obj) {
        try {
            $id          = $obj->__get('id');
            $nome        = $obj->__get('nome');
            $tipo        = $obj->__get('tipo');
            $data        = $obj->__get('data');
            $veterinario = $obj->__get('veterinario');
            $obs         = $obj->__get('obs');
            $anexo       = $obj->__get('anexo');
            $fk_animal   = $obj->__get('fk_animal');

            $sql = "UPDATE procedimento
                    SET
                        nome = :nome,
                        tipo = :tipo,
                        data = :data,
                        veterinario = :veterinario,
                        obs = :obs,
                        anexo = :anexo,
                        fk_animal = :fk_animal
                    WHERE id = :id";

            $stmt = $this->getConn()->prepare($sql);
            $stmt->bindValue(':id',          $id);
            $stmt->bindValue(':nome',        $nome);
            $stmt->bindValue(':tipo',        $tipo);
            $stmt->bindValue(':data',        $data);
            $stmt->bindValue(':veterinario', $veterinario);
            $stmt->bindValue(':obs',         $obs);
            $stmt->bindValue(':anexo',       $anexo);
            $stmt->bindValue(':fk_animal',   $fk_animal);
            $stmt->execute();

            return (int) $this->getConn()->lastInsertId();


        } catch (\PDOException $ex) {
            header('Location:/error103');
            die();
        }
    }

    public function listar() {
        $procedimentos = array();

        $sql = "SELECT
                    p.id, p.nome, p.tipo, p.data_realizacao, p.observacoes,
                    p.anexo, v.id, v.nome AS veterinario_nome, a.id,
                    a.nome AS animal_nome
                FROM procedimento p
                INNER JOIN veterinario v ON v.id = p.fk_veterinario_id
                INNER JOIN animal      a ON a.id = p.fk_animal_id
                ORDER BY p.data_realizacao DESC";

        $stmt = $this->getConn()->prepare($sql);
        $stmt->execute();

        $resultado = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($resultado as $row) {
            $procedimentoModel = new ProcedimentoModel();

            $global = new FuncoesGlobais();
            $global->popularModel($procedimentoModel, $row);

            array_push($procedimentos, $procedimentoModel);
        }

        return $procedimentos;
    }

    public function buscarPorId($id) {
         $procedimentos = array();

        $sql = "SELECT
                    p.id, p.nome, p.tipo, p.data_realizacao, p.observacoes,
                    p.anexo, v.id, v.nome AS veterinario_nome, a.id,
                    a.nome AS animal_nome
                FROM procedimento p
                INNER JOIN veterinario v ON v.id = p.fk_veterinario_id
                INNER JOIN animal      a ON a.id = p.fk_animal_id
                WHERE p.id = :id
                ORDER BY p.data_realizacao DESC";

        $stmt = $this->getConn()->prepare($sql);
        $stmt->execute();

        $resultado = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($resultado as $row) {
            $procedimentoModel = new ProcedimentoModel();

            $global = new FuncoesGlobais();
            $global->popularModel($procedimentoModel, $row);

            array_push($procedimentos, $procedimentoModel);
        }

        return $procedimentos;

    }

}
?>
