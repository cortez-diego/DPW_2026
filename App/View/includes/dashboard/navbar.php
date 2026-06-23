<?php
/**
 * AmigoPet - Navbar
 * Localização: ~/App/View/Includes/dashboard/navbar.php
 */

// Inicia a sessão se necessário
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Usa o tipo de usuário real da sessão
$userRole = $_SESSION['tipo_usuario'] ?? 'usuario';
$userId = $_SESSION['id'] ?? null;

// Buscar ONG ou Clínica vinculada ao usuário
$vinculado = null;
try {
    require_once __DIR__ . '/../../../../vendor/autoload.php';
    $conexao = new \FW\DB\Connection();
    $conn = $conexao->getConn();

    if ($userRole === 'ong' && $userId) {
        // Buscar ONG vinculada
        $sql = "SELECT id, nome, cnpj FROM ong WHERE id = (SELECT fk_ong_id FROM login WHERE id = :user_id)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $vinculado = $stmt->fetch(\PDO::FETCH_ASSOC);
    } elseif ($userRole === 'veterinario' && $userId) {
        // Buscar Clínica vinculada
        $sql = "SELECT c.id, c.nome, c.cnpj FROM clinica c 
                INNER JOIN vet_clinica vc ON c.id = vc.fk_clinica_id 
                WHERE vc.fk_veterinario_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $vinculado = $stmt->fetch(\PDO::FETCH_ASSOC);
    }
} catch (\PDOException $e) {
    error_log("Error fetching linked entity: " . $e->getMessage());
}

// Carrega notificações para o resumo do sino da navbar
$notificacoesMock = [];
$mockFile = __DIR__ . '/../../../Data/notificacoes_mock.php';
if (file_exists($mockFile)) {
    include $mockFile;
}

// Ordena as notificações mais recentes primeiro
usort($notificacoesMock, function($a, $b) {
    return strtotime($b['data']) <=> strtotime($a['data']);
});
$latestNotifications = array_slice($notificacoesMock, 0, 3);
$unreadCount = count(array_filter($notificacoesMock, function($n) { return !$n['lida']; }));
?>

<nav class="navbar navbar-expand-lg navbar-amigopet">
    <div class="container-fluid">
        <!-- Logo e Nome -->
        <a class="navbar-brand" href="/dashboard">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 10C13.1046 10 14 9.10457 14 8C14 6.89543 13.1046 6 12 6C10.8954 6 10 6.89543 10 8C10 9.10457 10.8954 10 12 10Z" fill="#F2994A"/>
                <path d="M7 9C8.10457 9 9 8.10457 9 7C9 5.89543 8.10457 5 7 5C5.89543 5 5 5.89543 5 7C5 8.10457 5.89543 9 7 9Z" fill="#6FCF97"/>
                <path d="M17 9C18.1046 9 19 8.10457 19 7C19 5.89543 18.1046 5 17 5C15.8954 5 15 5.89543 15 7C15 8.10457 15.8954 9 17 9Z" fill="#6FCF97"/>
                <path d="M12 21C15.3137 21 18 18.3137 18 15C18 11.6863 15.3137 9 12 9C8.68629 9 6 11.6863 6 15C6 18.3137 8.68629 21 12 21Z" fill="#F2994A"/>
            </svg>
            <span><span class="brand-text-amigo">Amigo</span><span class="brand-text-pet">Pet</span></span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link nav-link-inicio active" href="/dashboard">Início</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button type="button" class="notification-btn dropdown-toggle btn btn-link p-0" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Notificações">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        <?php if ($unreadCount > 0): ?>
                            <span class="notification-badge"><?php echo $unreadCount; ?></span>
                        <?php endif; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end py-2 shadow" aria-labelledby="notificationDropdown" style="min-width: 320px;">
                        <li class="px-3 py-2 d-flex justify-content-between align-items-center border-bottom">
                            <span class="fw-bold">Notificações recentes</span>
                            <small class="text-muted"><?php echo $unreadCount > 0 ? $unreadCount . ' não lidas' : 'Sem novas'; ?></small>
                        </li>
                        <?php if (empty($latestNotifications)): ?>
                            <li class="px-3 py-3 text-center text-muted">Nenhuma notificação disponível.</li>
                        <?php else: ?>
                            <?php foreach ($latestNotifications as $notif): ?>
                                <li>
                                    <a href="notificacoes.php" class="dropdown-item py-2">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="fw-semibold"><?php echo htmlspecialchars($notif['titulo']); ?></div>
                                                <div class="text-muted small"><?php echo htmlspecialchars($notif['mensagem']); ?></div>
                                            </div>
                                            <?php if (!$notif['lida']): ?>
                                                <span class="badge bg-success rounded-pill ms-2" style="font-size: 0.6rem;">Nova</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-end text-muted small mt-1"><?php echo date('d/m H:i', strtotime($notif['data'])); ?></div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="notificacoes.php">Ver todas as notificações</a></li>
                    </ul>
                </div>
                
                <div class="dropdown">
                    <button type="button" class="btn btn-link p-0" id="userProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="position: relative; padding: 0;">
                        <div style="width: 35px; height: 35px; border-radius: 50%; background: var(--primary-green); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.8rem;" title="<?php echo htmlspecialchars($_SESSION['nome'] ?? 'Usuário'); ?>">
                            <?php echo strtoupper(substr($_SESSION['nome'] ?? 'U', 0, 1)); ?>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; bottom: 0; right: 0; background: var(--primary-green); border-radius: 50%; padding: 2px;">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end py-2 shadow" aria-labelledby="userProfileDropdown" style="min-width: 280px;">
                        <li class="px-3 py-2 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--primary-green); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem;">
                                    <?php echo strtoupper(substr($_SESSION['nome'] ?? 'U', 0, 1)); ?>
                                </div>
                                <div>
                                    <div class="fw-bold"><?php echo htmlspecialchars($_SESSION['nome'] ?? 'Usuário'); ?></div>
                                    <div class="text-muted small text-capitalize"><?php echo htmlspecialchars($userRole); ?></div>
                                    <?php if ($vinculado): ?>
                                        <div class="small text-primary mt-1">
                                            <i data-lucide="building-2" style="width: 12px; height: 12px; display: inline;"></i>
                                            <?php echo htmlspecialchars($vinculado['nome']); ?>
                                        </div>
                                        <div class="small text-muted"><?php echo htmlspecialchars($vinculado['cnpj']); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="/perfil/editar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                Editar Perfil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 text-danger" href="/logout">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                Sair
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>