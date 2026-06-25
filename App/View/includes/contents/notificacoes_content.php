<?php
/**
 * AmigoPet - Conteúdo das Notificações
 * Localização: ~/App/View/includes/contents/notificacoes_content.php
 */

// Simulação de notificações (No backend, buscar do banco com filtro de data)
// Datas baseadas em 2026 (Ano atual do sistema)
$notificacoes = [];
$mockFile = __DIR__ . '/../../../Data/notificacoes_mock.php';
if (file_exists($mockFile)) {
    include $mockFile;
}

if (!isset($userRole)) {
    $userRole = $_SESSION['sim_user_role'] ?? 'usuario';
}

if (!function_exists('filterNotificationsForRole')) {
    function filterNotificationsForRole(array $notifications, string $role): array {
        return array_values(array_filter($notifications, function($notification) use ($role) {
            if (!isset($notification['roles']) || empty($notification['roles'])) {
                return true;
            }
            return in_array($role, $notification['roles'], true);
        }));
    }
}

if (empty($notificacoes) && isset($notificacoesMock)) {
    $notificacoes = $notificacoesMock;
}

$notificacoes = filterNotificationsForRole($notificacoes, $userRole);
$selectedNotifId = isset($_GET['notif']) ? intval($_GET['notif']) : null;
?>

<style>
    .notif-card {
        background: white;
        border-radius: 15px;
        border: 1px solid #f0f0f0;
        padding: 18px;
        margin-bottom: 12px;
        transition: transform 0.25s ease, border-color 0.25s ease, background-color 0.25s ease, box-shadow 0.25s ease;
        display: flex;
        align-items: flex-start;
        gap: 15px;
        position: relative;
        cursor: pointer;
    }

    .notif-card:hover {
        border-color: var(--primary-green);
        background-color: #fafafa;
    }

    .notif-card.click-animate {
        transform: translateY(-8px);
        box-shadow: 0 18px 45px rgba(32, 41, 61, 0.12);
    }

    .selected-notif-panel {
        border-radius: 18px;
        border: 1px solid #f0f0f0;
        background: white;
        box-shadow: 0 16px 40px rgba(32, 41, 61, 0.08);
        opacity: 0;
        transform: translateY(26px);
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .selected-notif-panel.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .notif-card.unseen {
        border-left: 4px solid var(--primary-green);
        background-color: rgba(111, 207, 151, 0.02);
    }

    .notif-card.selected {
        border-left: 4px solid var(--secondary-orange);
        background-color: rgba(242, 153, 74, 0.08);
    }

    .selected-notif-panel .card-body {
        padding: 1.5rem;
    }

    .selected-notif-panel .preview-title {
        font-size: 1.05rem;
        font-weight: 700;
    }

    .selected-notif-panel .preview-message {
        margin-top: 0.8rem;
        line-height: 1.65;
    }

    .selected-notif-panel .preview-meta {
        margin-top: 1rem;
        color: #6c757d;
        font-size: 0.85rem;
    }

    .selected-notif-panel.d-none {
        display: none;
    }

    .notif-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .icon-adocao { background: rgba(111, 207, 151, 0.1); color: var(--primary-green); }
    .icon-reporte { background: rgba(242, 153, 74, 0.1); color: var(--secondary-orange); }
    .icon-sistema { background: rgba(86, 204, 242, 0.1); color: var(--info-blue); }

    .notif-time {
        font-size: 0.75rem;
        color: #ADB5BD;
        margin-top: 5px;
    }

    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 15px;
        border: 1px solid #f0f0f0;
        margin-bottom: 25px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        display: none;
    }
</style>

<div class="main-content">
    <div class="container-fluid">
        
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Notificações 🔔</h1>
                <p class="text-muted">Fique por dentro de tudo o que acontece no seu AmigoPet.</p>
            </div>
        </div>

        <!-- Filtros e Busca -->
        <div class="filter-section shadow-sm">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted">
                            <i data-lucide="search" style="width: 18px;"></i>
                        </span>
                        <input type="text" id="notifSearch" class="form-control border-start-0 ps-0" placeholder="Pesquisar por assunto ou mensagem...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select id="periodFilter" class="form-select">
                        <option value="all">Todo o período</option>
                        <option value="7d">Últimos 7 dias</option>
                        <option value="30d">Último mês</option>
                        <option value="1y">Último ano</option>
                        <option value="3y">Últimos 3 anos</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary w-100" id="markAllRead">
                        <small class="fw-bold">Lidas</small>
                    </button>
                </div>
            </div>
        </div>

        <div id="selectedNotificationPanel" class="selected-notif-panel d-none mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="preview-title" id="selectedNotifTitle"></div>
                        <div class="text-muted small preview-meta" id="selectedNotifType"></div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="clearSelectedNotification">Fechar</button>
                </div>
                <div class="preview-message text-dark" id="selectedNotifMessage"></div>
                <div class="preview-meta text-end" id="selectedNotifDate"></div>
            </div>
        </div>

        <!-- Lista de Notificações -->
        <div id="notifList">
            <?php foreach ($notificacoes as $n): 
                $icon = 'bell';
                $class = 'icon-sistema';
                $isSelected = $selectedNotifId === intval($n['id']);
                if($n['tipo'] == 'adocao') { $icon = 'heart'; $class = 'icon-adocao'; }
                if($n['tipo'] == 'reporte') { $icon = 'alert-triangle'; $class = 'icon-reporte'; }
            ?>
            <div id="notif-id-<?php echo htmlspecialchars($n['id']); ?>" class="notif-card <?php echo ($n['lida'] || $isSelected) ? '' : 'unseen'; ?><?php echo $isSelected ? ' selected' : ''; ?>" 
                 data-notif-id="<?php echo htmlspecialchars($n['id']); ?>"
                 data-notif-read="<?php echo $n['lida'] ? 'true' : 'false'; ?>"
                 data-notif-title="<?php echo htmlspecialchars($n['titulo']); ?>"
                 data-notif-message="<?php echo htmlspecialchars($n['mensagem']); ?>"
                 data-notif-date="<?php echo date('d/m/Y H:i', strtotime($n['data'])); ?>"
                 data-notif-type="<?php echo htmlspecialchars($n['tipo']); ?>"
                 data-date="<?php echo $n['data']; ?>"
                 data-text="<?php echo strtolower($n['titulo'] . ' ' . $n['mensagem']); ?>"
                 data-notif-href="notificacoes.php?notif=<?php echo urlencode($n['id']); ?>">
                
                <div class="notif-icon <?php echo $class; ?>">
                    <i data-lucide="<?php echo $icon; ?>"></i>
                </div>
                
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <h6 class="mb-1" style="font-weight: 700;"><?php echo $n['titulo']; ?></h6>
                        <?php if(!$n['lida'] && !$isSelected): ?>
                            <span class="badge bg-success rounded-pill notif-new-badge" style="font-size: 0.6rem;">Nova</span>
                        <?php endif; ?>
                    </div>
                    <p class="mb-0 text-muted small"><?php echo $n['mensagem']; ?></p>
                    <div class="notif-time">
                        <i data-lucide="clock" class="me-1" style="width: 12px; height: 12px;"></i>
                        <?php echo date('d/m/Y H:i', strtotime($n['data'])); ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Mensagem de lista vazia -->
        <div id="emptyNotif" class="empty-state">
            <i data-lucide="bell-off" class="text-muted mb-3" style="width: 48px; height: 48px;"></i>
            <h5 class="text-muted">Nenhuma notificação encontrada.</h5>
            <p class="small text-secondary">Tente mudar os filtros de período ou termo de busca.</p>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') { lucide.createIcons(); }

    const searchInput = document.getElementById('notifSearch');
    const periodFilter = document.getElementById('periodFilter');
    let cards = [];
    let selectedPanelHideTimeout = null;
    const emptyMsg = document.getElementById('emptyNotif');

    function filterNotifications() {
        const searchTerm = searchInput.value.toLowerCase();
        const period = periodFilter.value;
        const now = new Date();
        let visibleCount = 0;

        cards.forEach(card => {
            const cardDate = new Date(card.getAttribute('data-date'));
            const cardText = card.getAttribute('data-text');
            
            // Lógica de tempo
            const diffTime = Math.abs(now - cardDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            const diffYears = now.getFullYear() - cardDate.getFullYear();

            let timeMatch = true;
            if (period === '7d') timeMatch = diffDays <= 7;
            else if (period === '30d') timeMatch = diffDays <= 30;
            else if (period === '1y') timeMatch = diffYears <= 1;
            else if (period === '3y') timeMatch = diffYears <= 3;

            // Lógica de texto
            const textMatch = cardText.includes(searchTerm);

            if (timeMatch && textMatch) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        emptyMsg.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    searchInput.addEventListener('input', filterNotifications);
    periodFilter.addEventListener('change', filterNotifications);

    function getReadIdsFromStorage() {
        try {
            const raw = localStorage.getItem('amigopetReadNotifications');
            return raw ? JSON.parse(raw) : [];
        } catch (e) {
            return [];
        }
    }

    function addReadId(notificationId) {
        if (!notificationId) return;
        const readIds = getReadIdsFromStorage();
        const id = String(notificationId);
        if (!readIds.includes(id)) {
            readIds.push(id);
            localStorage.setItem('amigopetReadNotifications', JSON.stringify(readIds));
        }
    }

    function markCardRead(card) {
        if (!card) return;
        card.classList.remove('unseen');
        card.dataset.notifRead = 'true';
        const badge = card.querySelector('.notif-new-badge');
        if (badge) badge.remove();
    }

    function refreshCardReadState() {
        const readIds = getReadIdsFromStorage();
        document.querySelectorAll('.notif-card').forEach(card => {
            const notifId = card.dataset.notifId;
            if (!notifId) return;
            const isAlreadyRead = card.dataset.notifRead === 'true' || readIds.includes(String(notifId));
            if (isAlreadyRead) {
                markCardRead(card);
            }
        });
    }

    function renderSelectedPanel(card) {
        if (!card) return;
        if (selectedPanelHideTimeout) {
            clearTimeout(selectedPanelHideTimeout);
            selectedPanelHideTimeout = null;
        }
        document.getElementById('selectedNotifTitle').textContent = card.dataset.notifTitle || '';
        document.getElementById('selectedNotifMessage').textContent = card.dataset.notifMessage || '';
        document.getElementById('selectedNotifDate').textContent = card.dataset.notifDate || '';
        document.getElementById('selectedNotifType').textContent = card.dataset.notifType ? 'Tipo: ' + card.dataset.notifType : '';
        const panel = document.getElementById('selectedNotificationPanel');
        panel.classList.remove('d-none');
        window.requestAnimationFrame(() => {
            panel.classList.add('visible');
            const targetTop = panel.getBoundingClientRect().top + window.pageYOffset - 90;
            window.scrollTo({ top: Math.max(targetTop, 0), behavior: 'smooth' });
        });
    }

    function hideSelectedPanel(clearUrl = false) {
        if (selectedPanelHideTimeout) {
            clearTimeout(selectedPanelHideTimeout);
        }
        const panel = document.getElementById('selectedNotificationPanel');
        panel.classList.remove('visible');
        selectedPanelHideTimeout = setTimeout(() => panel.classList.add('d-none'), 250);
        const selected = document.querySelector('.notif-card.selected');
        if (selected) selected.classList.remove('selected');
        if (clearUrl) {
            history.replaceState(null, '', window.location.pathname);
        }
    }

    function selectNotification(card) {
        if (!card) return;
        hideSelectedPanel();
        card.classList.add('selected');
        card.classList.add('click-animate');
        setTimeout(() => card.classList.remove('click-animate'), 220);
        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        addReadId(card.dataset.notifId);
        markCardRead(card);
        renderSelectedPanel(card);
        updateNavBadge();
        updateNavbarNotificationState();
        history.replaceState(null, '', '?notif=' + encodeURIComponent(card.dataset.notifId));
    }

    function ensureSelectedRead() {
        const params = new URLSearchParams(window.location.search);
        const selectedId = params.get('notif');
        if (!selectedId) return;

        const selectedCard = document.getElementById('notif-id-' + selectedId);
        if (!selectedCard) return;

        addReadId(selectedCard.dataset.notifId);
        selectNotification(selectedCard);
    }

    document.getElementById('clearSelectedNotification').addEventListener('click', function() {
        hideSelectedPanel(true);
    });

    function attachCardClickHandlers() {
        document.querySelectorAll('.notif-card').forEach(card => {
            card.addEventListener('click', () => {
                selectNotification(card);
            });
        });
    }

    function updateNavbarNotificationState() {
        if (window.AmigoPetNotificationUtils && typeof window.AmigoPetNotificationUtils.updateBadge === 'function') {
            window.AmigoPetNotificationUtils.updateBadge();
        }
    }

    function updateSelectedBadge(card) {
        if (!card) return;
        if (card.querySelector('.notif-new-badge')) {
            addReadId(card.dataset.notifId);
            markCardRead(card);
        }
    }

    function updateNavBadge() {
        const readIds = getReadIdsFromStorage();
        const unreadCount = Array.from(cards).filter(card => {
            const notifId = card.dataset.notifId;
            const isRead = notifId && readIds.includes(String(notifId));
            return notifId && !isRead;
        }).length;

        const badge = document.querySelector('.notification-btn .notification-badge');
        const summary = document.querySelector('.notification-summary');

        if (unreadCount > 0) {
            if (badge) {
                badge.textContent = unreadCount;
            } else {
                const span = document.createElement('span');
                span.className = 'notification-badge';
                span.textContent = unreadCount;
                document.querySelector('.notification-btn').appendChild(span);
            }
            if (summary) summary.textContent = unreadCount + ' não lidas';
        } else {
            if (badge) badge.remove();
            if (summary) summary.textContent = 'Sem novas';
        }
    }

    cards = document.querySelectorAll('.notif-card');
    refreshCardReadState();
    ensureSelectedRead();
    attachCardClickHandlers();
    updateNavBadge();

    document.getElementById('markAllRead').onclick = () => {
        alert('Todas as notificações marcadas como lidas.');
        cards.forEach(c => {
            addReadId(c.dataset.notifId);
            markCardRead(c);
        });

        updateNavBadge();
    };
});
</script>