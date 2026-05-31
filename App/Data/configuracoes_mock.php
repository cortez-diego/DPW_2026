<?php
/**
 * AmigoPet - Mock de Banco de Dados das Configurações
 * Localização: ~/App/Data/configuracoes_mock.php
 * Este ficheiro centraliza as configurações do sistema para simular um banco de dados real.
 */

// Configurações Globais do Sistema
$configGeralMock = [
    'dashboard_titulo' => 'Painel de Adoção 🐾',
    'dashboard_subtitulo' => 'Bem-vindo ao painel central do AmigoPet.'
];

// Mock de Publicações e Notícias
$publicacoesMock = [
    ['id' => 1, 'titulo' => 'Max - Golden Retriever', 'tipo' => 'Adoção', 'autor' => 'ONG Vida Animal', 'data' => '15/05/2026', 'status' => 'Ativo'],
    ['id' => 2, 'titulo' => 'Feira de Adoção', 'tipo' => 'Notícia', 'autor' => 'Admin', 'data' => '14/05/2026', 'status' => 'Ativo'],
    ['id' => 3, 'titulo' => 'Luna - Gato Siamês', 'tipo' => 'Adoção', 'autor' => 'Gatinhos Felizes', 'data' => '10/05/2026', 'status' => 'Oculto']
];

// Mock do Carrossel de Animais
$carrosselMock = [
    ['id' => 101, 'nome' => 'Max', 'especie' => 'Cachorro', 'imagem' => 'https://images.unsplash.com/photo-1552053831-71594a27632d?q=80&w=100'],
    ['id' => 102, 'nome' => 'Luna', 'especie' => 'Gato', 'imagem' => 'https://images.unsplash.com/photo-1513245543132-31f507417b26?q=80&w=100'],
    ['id' => 103, 'nome' => 'Bolinha', 'especie' => 'Cachorro', 'imagem' => 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?q=80&w=100']
];

// Mock de Logs de Auditoria
$logsMock = [
    ['data' => '20/05/2026 10:45', 'user' => 'Admin (ID: 1)', 'acao' => 'Baniu o usuário ID: 45', 'ip' => '192.168.1.10'],
    ['data' => '19/05/2026 15:20', 'user' => 'Moderador (ID: 3)', 'acao' => 'Aprovou o cadastro da ONG Patinhas', 'ip' => '172.16.0.5'],
    ['data' => '19/05/2026 09:10', 'user' => 'Admin (ID: 1)', 'acao' => 'Alterou título do Dashboard', 'ip' => '192.168.1.10'],
    ['data' => '18/05/2026 18:30', 'user' => 'Sistema', 'acao' => 'Backup automático concluído', 'ip' => 'localhost']
];
?>