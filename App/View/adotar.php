<?php
/**
 * AmigoPet - Formulário de Adoção
 * Localização: ~/App/View/adotar.php
 * NOTA: Este arquivo não é mais usado. O AdotarController agora renderiza
 * 'includes/contents/adotar_content' com o layout 'dashboard'.
 */

// Redirecionar para o controller correto
$petId = isset($_GET['id']) ? $_GET['id'] : '';
header('Location: /adotar' . ($petId ? '?id=' . $petId : ''));
exit;
?>