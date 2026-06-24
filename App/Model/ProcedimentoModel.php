<?php
/*
 * Autor: Vagner de Oliveira Lunes
 * Modelo de RF#16 - Sistema de Gerenciamento de Procedimentos Médicos
 */

namespace App\Model;

class ProcedimentoModel
{
    private $id;
    private $nome;
    // Enum (consulta, cirurgia, exame, castracao, outro)
    private $tipo;
    private $data;
    private $veterinario;
    private $obs;
    private $anexo;

    private $fk_animal;

    public function __get($nome) {
        return $this->$nome;
    }

    public function __set($nome, $valor)
    {
        $this->$nome = $valor;
    }
}
?>
