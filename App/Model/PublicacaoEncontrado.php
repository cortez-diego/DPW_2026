<?php

/*
 * @Author Willian de Oliveira Marques
 * 
 * - Construção do modelo da tabela PublicacaoEncontrado
 * - Usuário logados podem registrar um animal -> fk_login_id
 * - Usuário precisa informar a localização, condição física inicial, 
 * ações imediatas tomadas 
 * - O registro vai gerar um Status "aguardando acolhimento"
 */
namespace App\Model;

class PublicacaoEncontradoModel {

    private $id;
    private $fk_animal_id;
    private $fk_login_id;
    private $data_encontro;
    private $condicao_fisca;
    private $acoes_realizadas;
    private $status;

    // Métodos mágicos para acessar e definir propriedades
    public function __set($nome, $valor) {
        $this->$nome = $valor;
    }

    public function __get($nome) {
        return $this->$nome;
    }
}

?>