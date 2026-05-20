<?php
/**
 * Mock de denúncias
 * Estrutura usada por reportar_content.php e verificar_denuncias_content.php
 */

$denunciasMock = [
    [
        'id' => 1001,
        'tipo' => 'abandonado',
        'urgencia' => 'alta',
        'assunto' => 'Cachorro abandonado na Praça Central',
        'localizacao' => 'Praça Central, Centro',
        'descricao' => 'Cachorro magro, sem coleira, parece ferido na pata traseira.',
        'fotos' => [],
        'criado_em' => '15/05/2026 14:32',
        'status' => 'Pendente',
        'respostas' => [
            [ 'texto' => 'Equipe notificada, enviamos um voluntário.', 'por' => 'ONG Vida Animal', 'cargo' => 'ong', 'data' => '15/05/2026 15:10' ]
        ],
        'reporter_id' => 'demo_user_1',
        'reporter_name' => 'Marina'
    ],
    [
        'id' => 1002,
        'tipo' => 'perdido',
        'urgencia' => 'media',
        'assunto' => 'Gato perdido - Procura-se',
        'localizacao' => 'Rua das Flores, Bairro Jardim',
        'descricao' => 'Gato siamês visto pela última vez ontem à noite.',
        'fotos' => [],
        'criado_em' => '12/05/2026 09:20',
        'status' => 'Resolvido',
        'respostas' => [],
        'reporter_id' => 'demo_user_2',
        'reporter_name' => 'Lucas'
    ]
];

?>
