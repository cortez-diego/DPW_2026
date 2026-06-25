<?php
/**
 * AmigoPet - Navbar com Simulador de Cargos
 * Localização: ~/App/View/Includes/dashboard/navbar.php
 */

// Inicia a sessão para o simulador funcionar (Se não houver cargo, padrão é 'usuario')
if (session_status() === PHP_SESSION_NONE) { session_start(); }

/** * LÓGICA DE SIMULAÇÃO 
 * Para o Backend: Substituir estas linhas pela verificação real de autenticação/sessão do usuário.
 * Ex: $userRole = $_SESSION['user']['role'];
 */
if (isset($_GET['sim_role'])) {
    $_SESSION['sim_user_role'] = $_GET['sim_role'];
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?')); // Limpa a URL
    exit;
}

$userRole = $_SESSION['sim_user_role'] ?? 'usuario';

function filterNotificationsForRole(array $notifications, string $role): array {
    return array_values(array_filter($notifications, function($notification) use ($role) {
        if (!isset($notification['roles']) || empty($notification['roles'])) {
            return true;
        }
        return in_array($role, $notification['roles'], true);
    }));
}

// Carrega notificações para o resumo do sino da navbar
$notificacoesMock = [];
$mockFile = __DIR__ . '/../../../Data/notificacoes_mock.php';
if (file_exists($mockFile)) {
    include $mockFile;
}

$notificacoesMock = filterNotificationsForRole($notificacoesMock, $userRole);

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
        <a class="navbar-brand" href="dashboard.php">
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
                    <a class="nav-link nav-link-inicio active" href="dashboard.php">Início</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <!-- SIMULADOR DE CARGO (Remover no deploy final) -->
                <div class="d-flex align-items-center bg-light px-2 py-1 rounded-3 border">
                    <small class="text-muted me-2" style="font-size: 0.7rem; font-weight: 600; text-transform: uppercase;">Simular:</small>
                    <select class="form-select form-select-sm border-0 bg-transparent fw-bold" onchange="location.href='?sim_role='+this.value" style="font-size: 0.8rem; cursor: pointer; color: var(--secondary-orange);">
                        <option value="usuario" <?php echo $userRole == 'usuario' ? 'selected' : ''; ?>>Usuário</option>
                        <option value="admin" <?php echo $userRole == 'admin' ? 'selected' : ''; ?>>Administrador</option>
                        <option value="ong" <?php echo $userRole == 'ong' ? 'selected' : ''; ?>>ONG</option>
                        <option value="moderador" <?php echo $userRole == 'moderador' ? 'selected' : ''; ?>>Equipe Moderadora</option>
                        <option value="campo" <?php echo $userRole == 'campo' ? 'selected' : ''; ?>>Equipe de Campo</option>
                        <option value="vet" <?php echo $userRole == 'vet' ? 'selected' : ''; ?>>Veterinário</option>
                    </select>
                </div>

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
                    <ul class="dropdown-menu dropdown-menu-end py-2 shadow" aria-labelledby="notificationDropdown" style="min-width: 320px;" data-bs-auto-close="outside">
                        <li class="px-3 py-2 d-flex justify-content-between align-items-center border-bottom">
                            <span class="fw-bold">Notificações recentes</span>
                            <small class="text-muted notification-summary"><?php echo $unreadCount > 0 ? $unreadCount . ' não lidas' : 'Sem novas'; ?></small>
                        </li>
                        <li class="px-3 py-3 d-none" id="bellSelectedPreview">
                            <div class="border rounded-3 p-3 mb-2">
                                <div class="fw-semibold" id="bellPreviewTitle"></div>
                                <div class="text-muted small" id="bellPreviewType"></div>
                                <p class="mb-1 text-muted small" id="bellPreviewMessage"></p>
                                <div class="text-end text-muted small" id="bellPreviewDate"></div>
                                <div class="text-end mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="bellPreviewOpen">Ver detalhes</button>
                                </div>
                            </div>
                        </li>
                        <style>
                            #bellSelectedPreview {
                                opacity: 0;
                                transform: translateY(8px);
                                transition: opacity 0.25s ease, transform 0.25s ease;
                            }

                            #bellSelectedPreview.visible {
                                opacity: 1;
                                transform: translateY(0);
                            }

                            .notification-dropdown-item {
                                transition: transform 0.2s ease, background-color 0.2s ease;
                            }

                            .notification-dropdown-item.click-animate {
                                transform: translateX(4px);
                                background-color: rgba(111, 207, 151, 0.06);
                            }
                        </style>
                        <?php if (empty($latestNotifications)): ?>
                            <li class="px-3 py-3 text-center text-muted">Nenhuma notificação disponível.</li>
                        <?php else: ?>
                            <?php foreach ($latestNotifications as $notif): ?>
                                <li>
                                    <a href="notificacoes.php?notif=<?php echo urlencode($notif['id']); ?>" class="dropdown-item py-2 notification-dropdown-item"
                                       data-notif-id="<?php echo htmlspecialchars($notif['id']); ?>"
                                       data-notif-title="<?php echo htmlspecialchars($notif['titulo']); ?>"
                                       data-notif-message="<?php echo htmlspecialchars($notif['mensagem']); ?>"
                                       data-notif-type="<?php echo htmlspecialchars($notif['tipo']); ?>"
                                       data-notif-date="<?php echo date('d/m H:i', strtotime($notif['data'])); ?>"
                                       data-notif-read="<?php echo $notif['lida'] ? 'true' : 'false'; ?>">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="fw-semibold"><?php echo htmlspecialchars($notif['titulo']); ?></div>
                                                <div class="text-muted small"><?php echo htmlspecialchars($notif['mensagem']); ?></div>
                                            </div>
                                            <?php if (!$notif['lida']): ?>
                                                <span class="badge bg-success rounded-pill ms-2 notif-new-badge" style="font-size: 0.6rem;">Nova</span>
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
                
                <script>
                    (function() {
                        const storageKey = 'amigopetReadNotifications';
                        const notificationData = <?php echo json_encode($notificacoesMock, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;

                        function getReadIds() {
                            try {
                                const raw = localStorage.getItem(storageKey);
                                return raw ? JSON.parse(raw) : [];
                            } catch (e) {
                                return [];
                            }
                        }

                        function setReadIds(ids) {
                            localStorage.setItem(storageKey, JSON.stringify(ids));
                        }

                        function addReadId(id) {
                            const ids = getReadIds();
                            if (!ids.includes(String(id))) {
                                ids.push(String(id));
                                setReadIds(ids);
                            }
                        }

                        function getUnreadCount() {
                            const readIds = getReadIds();
                            return notificationData.filter(notif => {
                                const isManuallyRead = readIds.includes(String(notif.id));
                                return !notif.lida && !isManuallyRead;
                            }).length;
                        }

                        function updateBadge() {
                            const count = getUnreadCount();
                            const badge = document.querySelector('.notification-btn .notification-badge');
                            const summary = document.querySelector('.notification-summary');

                            if (count > 0) {
                                if (badge) {
                                    badge.textContent = count;
                                } else {
                                    const span = document.createElement('span');
                                    span.className = 'notification-badge';
                                    span.textContent = count;
                                    document.querySelector('.notification-btn').appendChild(span);
                                }
                                if (summary) summary.textContent = count + ' não lidas';
                            } else {
                                if (badge) badge.remove();
                                if (summary) summary.textContent = 'Sem novas';
                            }

                            document.querySelectorAll('.notification-dropdown-item').forEach(item => {
                                const notifId = item.dataset.notifId;
                                if (notifId && getReadIds().includes(String(notifId))) {
                                    const newBadge = item.querySelector('.notif-new-badge');
                                    if (newBadge) newBadge.remove();
                                }
                            });
                        }

                        function renderBellPreview(item) {
                            if (!item) return;
                            const title = item.dataset.notifTitle || '';
                            const message = item.dataset.notifMessage || '';
                            const date = item.dataset.notifDate || '';
                            const type = item.dataset.notifType || '';
                            document.getElementById('bellPreviewTitle').textContent = title;
                            document.getElementById('bellPreviewType').textContent = type ? 'Tipo: ' + type : '';
                            document.getElementById('bellPreviewMessage').textContent = message;
                            document.getElementById('bellPreviewDate').textContent = date;
                            const preview = document.getElementById('bellSelectedPreview');
                            preview.classList.remove('d-none');
                            setTimeout(() => preview.classList.add('visible'), 20);
                        }

                        function hideBellPreview() {
                            const preview = document.getElementById('bellSelectedPreview');
                            preview.classList.remove('visible');
                            setTimeout(() => preview.classList.add('d-none'), 250);
                        }

                        function selectBellNotification(item) {
                            if (!item) return;
                            addReadId(item.dataset.notifId);
                            updateBadge();
                            document.querySelectorAll('.notification-dropdown-item').forEach(i => i.classList.remove('active'));
                            item.classList.add('active');
                            item.classList.add('click-animate');
                            setTimeout(() => item.classList.remove('click-animate'), 220);
                            renderBellPreview(item);
                        }

                        function initNotificationLinks() {
                            document.querySelectorAll('.notification-dropdown-item').forEach(item => {
                                item.addEventListener('click', function(event) {
                                    event.preventDefault();
                                    event.stopPropagation();
                                    selectBellNotification(this);
                                });
                            });
                        }

                        function initBellPreviewButton() {
                            const openBtn = document.getElementById('bellPreviewOpen');
                            if (!openBtn) return;
                            openBtn.addEventListener('click', function() {
                                const activeItem = document.querySelector('.notification-dropdown-item.active');
                                if (activeItem) {
                                    window.location.href = activeItem.href;
                                }
                            });
                        }

                        document.addEventListener('DOMContentLoaded', function() {
                            updateBadge();
                            initNotificationLinks();
                            initBellPreviewButton();
                        });

                        window.AmigoPetNotificationUtils = {
                            addReadId,
                            updateBadge,
                            getReadIds,
                            notificationData
                        };
                    })();
                </script>
                
                <div class="d-flex align-items-center">
                    <div style="width: 35px; height: 35px; border-radius: 50%; background: var(--primary-green); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.8rem;">
                        <?php echo strtoupper(substr($userRole, 0, 1)); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>