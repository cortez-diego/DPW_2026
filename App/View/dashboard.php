<?php
/**
 * AmigoPet - Dashboard Principal
 * Localização: ~/App/View/dashboard.php
 * Este arquivo serve como o "Layout" ou "Template" principal.
 */

// Caminhos corrigidos para a pasta de includes
include 'includes/dashboard/header.php';
include 'includes/dashboard/menu.php';
include 'includes/dashboard/navbar.php';

// Conteúdo dinâmico: inclui a view selecionada pelo controller quando disponível
if (!empty($this->view->page)) {
    // Normaliza caminhos com ../ para a pasta correta (ex: "../dashboard/animal_editar")
    $normalized = preg_replace('#^(\./|(\./)*/|(\./)*/\.{2}/)+#', '', $this->view->page);
    $normalized = preg_replace('#^(\./|\.{2}/)+#', '', $normalized);
    $normalized = ltrim($normalized, '/');

    $contentPath = __DIR__ . '/' . $normalized;
    $contentFile = $contentPath . '.php';

    if (file_exists($contentFile)) {
        echo '<div class="main-content">';
        require_once $contentFile;
        echo '</div>';
    } else {
        // fallback para o conteúdo padrão do dashboard
        include 'includes/contents/dashboard_content.php';
    }
} else {
    include 'includes/contents/dashboard_content.php';
}


include 'includes/dashboard/footer.php';
?>