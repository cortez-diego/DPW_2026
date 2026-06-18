<?php
/**
 * AmigoPet - Conteúdo de Gerenciamento de Usuários
 * Localização: ~/App/View/includes/contents/usuarios_content.php
 */

// Incluir autoloader do Composer para carregar todas as dependências
require_once __DIR__ . '/../../../../vendor/autoload.php';

use App\DAO\LoginDAO;
use App\DAO\AdotanteDAO;

// Buscar usuários do banco de dados
$loginDAO = new LoginDAO();
$usuariosLista = $loginDAO->listar();

// Mapeamento de tipos de usuário para exibição
// Valores devem corresponder exatamente ao enum do banco de dados
$tiposUsuario = [
    'adotante' => 'Usuário',
    'administrador' => 'Administrador',
    'ong' => 'ONG',
    'rastreador' => 'Rastreador',
    'veterinario' => 'Veterinário'
];

// Buscar dados adicionais dos adotantes
$adotanteDAO = new AdotanteDAO();
$adotantes = [];
foreach ($usuariosLista as $login) {
    $adotante = $adotanteDAO->buscarPorLoginId($login->__get('id'));
    if ($adotante) {
        $adotantes[$login->__get('id')] = $adotante;
    }
}
?>

<style>
    .user-table-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 25px rgba(0,0,0,0.03);
        overflow: hidden;
    }

    .table thead th {
        background: #F9F9F9;
        font-family: 'Poppins', sans-serif;
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #828282;
        padding: 15px 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .table tbody td {
        padding: 15px 20px;
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #E0E0E0;
        background: white;
        color: #828282;
        transition: 0.2s;
        margin-left: 4px;
    }

    .action-btn:hover {
        background: var(--primary-green);
        color: white;
        border-color: var(--primary-green);
    }

    .btn-ban:hover { background: #EB5757; border-color: #EB5757; }
    .btn-notify:hover { background: #F2994A; border-color: #F2994A; }
    .btn-message:hover { background: #56CCF2; border-color: #56CCF2; }

    .stat-badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 6px;
    }

    .status-badge-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }
</style>

<div class="container-fluid">
        
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h1 class="h3" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Gestão de Usuários 👥</h1>
                <p class="text-muted">Administre perfis, acompanhe condutas e processos de adoção.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="d-flex gap-2 justify-content-md-end">
                    <input type="text" id="userSearch" class="form-control" placeholder="Buscar por nome ou email..." style="max-width: 250px; border-radius: 10px;">
                    <button class="btn btn-primary" style="background: var(--primary-green); border: none; border-radius: 10px;">
                        <i data-lucide="filter" style="width: 18px;"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="user-table-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Usuário</th>
                            <th>Contato</th>
                            <th>Cargo</th>
                            <th>Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
                        <?php foreach ($usuariosLista as $u): ?>
                        <?php
                            $adotante = $adotantes[$u->__get('id')] ?? null;
                            $nome = $adotante ? $adotante->__get('nome') : $u->__get('email');
                            $telefone = $adotante ? $adotante->__get('telefone_1') : '';
                            $tipoUsuario = $u->__get('tipo_usuario');
                            $status = $u->__get('status') == 'a' ? 'Ativo' : 'Inativo';
                            $statusClasse = $u->__get('status') == 'a' ? 'bg-success' : 'bg-danger';
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold; color: var(--primary-green);">
                                        <?php echo strtoupper(substr($nome, 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold"><?php echo $nome; ?></div>
                                        <small class="text-muted">ID: #<?php echo str_pad($u->__get('id'), 4, '0', STR_PAD_LEFT); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <small class="d-block"><?php echo $u->__get('email'); ?></small>
                                <small class="text-muted"><?php echo $telefone; ?></small>
                            </td>
                            <td>
                                <select class="form-select form-select-sm" style="font-size: 0.85rem; padding: 4px 8px;" onchange="atualizarCargo(<?php echo $u->__get('id'); ?>, this.value)">
                                    <?php foreach ($tiposUsuario as $key => $label): ?>
                                        <option value="<?php echo $key; ?>" <?php echo $tipoUsuario == $key ? 'selected' : ''; ?>>
                                            <?php echo $label; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <span class="status-badge-dot <?php echo $statusClasse; ?>"></span>
                                <small class="fw-bold"><?php echo $status; ?></small>
                            </td>
                            <td class="text-end">
                                <button class="action-btn btn-message" title="Enviar Mensagem" onclick="alert('Abrindo chat com <?php echo $nome; ?>')">
                                    <i data-lucide="message-square" style="width: 14px;"></i>
                                </button>
                                <button class="action-btn btn-notify" title="Notificar Usuário" onclick="alert('Enviando notificação administrativa para <?php echo $nome; ?>')">
                                    <i data-lucide="bell" style="width: 14px;"></i>
                                </button>
                                <button class="action-btn btn-ban" title="Banir Usuário" onclick="confirm('Deseja realmente banir este usuário?')">
                                    <i data-lucide="slash" style="width: 14px;"></i>
                                </button>
                                <button class="action-btn" title="Ver Detalhes/Bloquear" onclick="alert('Visualizando histórico completo de <?php echo $nome; ?>')">
                                    <i data-lucide="more-horizontal" style="width: 14px;"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') { lucide.createIcons(); }

    const searchInput = document.getElementById('userSearch');
    const tableRows = document.querySelectorAll('#userTableBody tr');

    searchInput.addEventListener('input', function() {
        const term = this.value.toLowerCase().trim();
        tableRows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    });
});

function atualizarCargo(userId, novoTipo) {
    if (confirm('Deseja realmente alterar o cargo deste usuário?')) {
        const formData = new FormData();
        formData.append('id', userId);
        formData.append('tipo_usuario', novoTipo);

        fetch('/usuario/atualizarCargo', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cargo atualizado com sucesso!');
                location.reload();
            } else {
                alert('Erro ao atualizar cargo: ' + data.message);
                location.reload();
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao atualizar cargo');
            location.reload();
        });
    } else {
        // Reverter seleção se cancelado
        location.reload();
    }
}
</script>