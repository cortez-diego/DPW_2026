<?php
/**
 * AmigoPet - Mock de Logs de Auditoria
 * Localização: ~/App/Data/auditoria_mock.php
 * Logs destinados à aba de auditoria nas configurações (visível para admin).
 */

$logsMock = [
    [
        'data' => '2026-05-20 10:45:12',
        'user' => 'Admin (ID:1)',
        'acao' => 'Usuário criado: ID 45 - nome: Joana',
        'ip' => '192.168.1.10'
    ],
    [
        'data' => '2026-05-19 15:20:03',
        'user' => 'Moderador (ID:3)',
        'acao' => 'Publicação removida: ID 122',
        'ip' => '172.16.0.5'
    ],
    [
        'data' => '2026-05-18 09:10:45',
        'user' => 'Sistema',
        'acao' => 'Backup automático concluído',
        'ip' => '127.0.0.1'
    ],
    [
        'data' => '2026-05-17 11:02:22',
        'user' => 'ONG Patinhas (ID:12)',
        'acao' => 'Animal colocado em adoção: ID 101 - Max',
        'ip' => '200.137.10.5'
    ],
    [
        'data' => '2026-05-16 08:44:01',
        'user' => 'Veterinário (ID:7)',
        'acao' => 'Vacina registrada: ID 101 - Raiva',
        'ip' => '10.0.0.8'
    ]
];
