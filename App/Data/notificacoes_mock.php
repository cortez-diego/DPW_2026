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
        'lida' => false,
        'roles' => ['usuario']
    ],
    [
        'id' => 2,
        'titulo' => 'Nova solicitação de coleta',
        'mensagem' => 'Um animal foi reportado e precisa de recolhimento. Verifique a solicitação.',
        'data' => '2026-05-07 14:20:00',
        'tipo' => 'reporte',
        'lida' => false,
        'roles' => ['ong']
    ],
    [
        'id' => 3,
        'titulo' => 'Visita agendada',
        'mensagem' => 'Você tem uma consulta marcada com o pet Amora amanhã às 09h.',
        'data' => '2026-05-09 08:00:00',
        'tipo' => 'sistema',
        'lida' => false,
        'roles' => ['vet']
    ],
    [
        'id' => 4,
        'titulo' => 'Nova denúncia para revisão',
        'mensagem' => 'Uma denúncia foi enviada e aguarda análise da equipe moderadora.',
        'data' => '2026-05-06 18:40:00',
        'tipo' => 'alerta',
        'lida' => true,
        'roles' => ['moderador', 'campo']
    ],
    [
        'id' => 5,
        'titulo' => 'Atualização de segurança',
        'mensagem' => 'O painel recebeu uma atualização de segurança. Verifique as ações pendentes.',
        'data' => '2026-05-05 12:00:00',
        'tipo' => 'sistema',
        'lida' => true,
        'roles' => ['admin']
    ],
    [
        'id' => 6,
        'titulo' => 'Perfil Atualizado',
        'mensagem' => 'As alterações no seu perfil foram salvas com sucesso.',
        'data' => '2026-04-20 09:00:00',
        'tipo' => 'sistema',
        'lida' => true,
        'roles' => ['usuario', 'ong', 'vet', 'moderador', 'campo', 'admin']
    ],
    [
        'id' => 7,
        'titulo' => 'Boas-vindas ao AmigoPet',
        'mensagem' => 'Sua conta foi criada com sucesso. Explore o painel e acompanhe as notificações.',
        'data' => '2024-01-15 11:00:00',
        'tipo' => 'sistema',
        'lida' => true,
        'roles' => ['usuario']
    ],
    [
        'id' => 8,
        'titulo' => 'Relatório mensal disponível',
        'mensagem' => 'O relatório de adoções do mês já está disponível para administradores.',
        'data' => '2026-05-01 09:30:00',
        'tipo' => 'sistema',
        'lida' => true,
        'roles' => ['admin']
    ],
    [
        'id' => 9,
        'titulo' => 'Adoção pendente',
        'mensagem' => 'Há uma nova solicitação de adoção aguardando sua confirmação.',
        'data' => '2026-06-22 11:15:00',
        'tipo' => 'adocao',
        'lida' => false,
        'roles' => ['usuario', 'admin']
    ],
    [
        'id' => 10,
        'titulo' => 'Revisão de denúncia',
        'mensagem' => 'Nova denúncia chegada para revisão da equipe moderadora.',
        'data' => '2026-06-23 16:45:00',
        'tipo' => 'reporte',
        'lida' => false,
        'roles' => ['moderador']
    ],
    [
        'id' => 11,
        'titulo' => 'Consulta agendada',
        'mensagem' => 'Nova consulta marcada para um animal sob sua responsabilidade.',
        'data' => '2026-06-24 08:30:00',
        'tipo' => 'sistema',
        'lida' => false,
        'roles' => ['vet']
    ]
];
