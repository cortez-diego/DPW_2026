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
$page = '';
if (isset($this) && isset($this->view) && !empty($this->view->page)) {
    $page = $this->view->page;
}

if (!empty($page)) {
    // Normaliza caminhos com ../ para a pasta correta (ex: "../dashboard/animal_editar")
    $normalized = preg_replace('#^(\./|(\./)*/|(\./)*/\.{2}/)+#', '', $page);
    $normalized = preg_replace('#^(\./|\.{2}/)+#', '', $normalized);
    $normalized = ltrim($normalized, '/');

    $contentPath = __DIR__ . '/' . $normalized;
    $contentFile = $contentPath . '.php';

    if (file_exists($contentFile)) {
        $shouldWrap = strpos($normalized, 'includes/contents/') !== 0;
        if ($shouldWrap) {
            echo '<div class="main-content">';
        }
        require_once $contentFile;
        if ($shouldWrap) {
            echo '</div>';
        }
    } else {
        include 'includes/contents/dashboard_content.php';
    }
} else {
    include 'includes/contents/dashboard_content.php';
}

include 'includes/dashboard/footer.php';
?>