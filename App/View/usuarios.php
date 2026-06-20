<?php
/**
 * AmigoPet - Página de Gerenciamento de Usuários
 * Localização: ~/App/View/usuarios.php
 * NOTA: Este arquivo não é mais usado. O UsuarioController agora renderiza
 * 'includes/contents/usuarios_content' com o layout 'dashboard'.
 * O controle de acesso é feito no UsuarioController::validaAutenticacao().
 */

// Redirecionar para o controller correto
header('Location: /usuarios');
exit;
?>