<?php
/**
 * AmigoPet - Mock de Banco de Dados para Notificações
 * Localização: ~/App/Data/notificacoes_mock.php
 * Este ficheiro centraliza as notificações usadas pela navbar e pela página de notificações.
 */

$notificacoesMock = [
    [
        'id' => 1,
        'titulo' => 'Entrevista Marcada!',
        'mensagem' => 'Sua entrevista para adotar o Max foi agendada para o dia 15/05.',
        'data' => '2026-05-08 10:30:00',
        'tipo' => 'adocao',
        'lida' => false
    ],
    [
        'id' => 2,
        'titulo' => 'Novo Reporte Próximo',
        'mensagem' => 'Um novo caso de animal abandonado foi reportado a 2km de sua localização.',
        'data' => '2026-05-07 14:20:00',
        'tipo' => 'reporte',
        'lida' => true
    ],
    [
        'id' => 3,
        'titulo' => 'Perfil Atualizado',
        'mensagem' => 'As alterações no seu perfil foram salvas com sucesso.',
        'data' => '2026-04-20 09:00:00',
        'tipo' => 'sistema',
        'lida' => true
    ],
    [
        'id' => 4,
        'titulo' => 'Campanha de Vacinação 2025',
        'mensagem' => 'Recordamos que a campanha de vacinação do ano passado foi um sucesso!',
        'data' => '2025-06-12 08:00:00',
        'tipo' => 'sistema',
        'lida' => true
    ],
    [
        'id' => 5,
        'titulo' => 'Início da Jornada AmigoPet',
        'mensagem' => 'Bem-vindo à nossa plataforma! Sua conta foi criada em 2024.',
        'data' => '2024-01-15 11:00:00',
        'tipo' => 'sistema',
        'lida' => true
    ],
    [
        'id' => 6,
        'titulo' => 'Evento Histórico 2023',
        'mensagem' => 'Relatório de impacto da feira de adoção de 3 anos atrás.',
        'data' => '2023-09-10 16:45:00',
        'tipo' => 'sistema',
        'lida' => true
    ]
];
