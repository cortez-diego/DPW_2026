<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get resgates from controller
$resgates = $this->resgates ?? [];

// Calculate summary stats
$totalResgates = count($resgates);
$animaisResgatados = 0;
$animaisFeridos = 0;
$encaminhados = 0;

foreach ($resgates as $r) {
    if ($r['status_resgate'] === 'concluido') {
        $animaisResgatados++;
    }
    if ($r['estado_animal'] === 'ferido') {
        $animaisFeridos++;
    }
    if (!empty($r['destino'])) {
        $encaminhados++;
    }
}
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Summary Cards -->
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-0 bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Total Resgates</h6>
                                <h3 class="mb-0"><?php echo $totalResgates; ?></h3>
                            </div>
                            <i data-lucide="truck" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-0 bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Resgatados</h6>
                                <h3 class="mb-0"><?php echo $animaisResgatados; ?></h3>
                            </div>
                            <i data-lucide="check-circle" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-0 bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Feridos</h6>
                                <h3 class="mb-0"><?php echo $animaisFeridos; ?></h3>
                            </div>
                            <i data-lucide="heart-pulse" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-0 bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Encaminhados</h6>
                                <h3 class="mb-0"><?php echo $encaminhados; ?></h3>
                            </div>
                            <i data-lucide="map-pin" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-secondary">Histórico de Resgates</h5>
                            <div>
                                <button class="btn btn-sm btn-outline-primary" onclick="filterResgates('todos')">Todos</button>
                                <button class="btn btn-sm btn-outline-success" onclick="filterResgates('concluido')">Concluídos</button>
                                <button class="btn btn-sm btn-outline-warning" onclick="filterResgates('pendente')">Pendentes</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (empty($resgates)): ?>
                            <div class="text-center py-4">
                                <i data-lucide="inbox" style="width: 64px; height: 64px; color: #dee2e6;"></i>
                                <p class="text-muted mt-3">Nenhum resgate registrado ainda.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light text-secondary">
                                        <tr style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <th>ID</th>
                                            <th>Chamado</th>
                                            <th>Estado Animal</th>
                                            <th>Destino</th>
                                            <th>Data Resgate</th>
                                            <th>Status</th>
                                            <th class="text-end">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($resgates as $r): ?>
                                        <tr data-resgate-id="<?php echo $r['id']; ?>" data-status="<?php echo $r['status_resgate']; ?>">
                                            <td>#<?php echo $r['id']; ?></td>
                                            <td>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($r['chamado_assunto'] ?? 'N/A'); ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($r['chamado_localizacao'] ?? 'N/A'); ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                $estadoClass = 'bg-success';
                                                if ($r['estado_animal'] === 'ferido') $estadoClass = 'bg-danger';
                                                elseif ($r['estado_animal'] === 'precisa_atencao') $estadoClass = 'bg-warning';
                                                ?>
                                                <span class="badge <?php echo $estadoClass; ?>"><?php echo ucfirst(str_replace('_', ' ', $r['estado_animal'])); ?></span>
                                            </td>
                                            <td><?php echo htmlspecialchars($r['destino'] ?? 'Não encaminhado'); ?></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($r['data_resgate'])); ?></td>
                                            <td>
                                                <?php
                                                $statusClass = 'bg-secondary';
                                                if ($r['status_resgate'] === 'concluido') $statusClass = 'bg-success';
                                                elseif ($r['status_resgate'] === 'pendente') $statusClass = 'bg-warning';
                                                ?>
                                                <span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst(str_replace('_', ' ', $r['status_resgate'])); ?></span>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-primary" onclick="abrirResgate(<?php echo $r['id']; ?>)">
                                                    <i data-lucide="eye" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle;"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Visualizar Resgate -->
<div class="modal fade" id="resgateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i data-lucide="truck" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;"></i>
                    Detalhes do Resgate
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="resgateDetails">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Registrar Resgate -->
<div class="modal fade" id="novoResgateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i data-lucide="plus-circle" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;"></i>
                    Registrar Novo Resgate
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="novoResgateForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fk_chamado_id" class="form-label">Chamado</label>
                            <select class="form-select" id="fk_chamado_id" name="fk_chamado_id" required>
                                <option value="">Selecione um chamado...</option>
                                <!-- Chamados serão carregados via AJAX -->
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="estado_animal" class="form-label">Estado do Animal</label>
                            <select class="form-select" id="estado_animal" name="estado_animal" required>
                                <option value="">Selecione...</option>
                                <option value="saudavel">Saudável</option>
                                <option value="ferido">Ferido</option>
                                <option value="precisa_atencao">Precisa de Atenção</option>
                                <option value="critico">Crítico</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="destino" class="form-label">Destino</label>
                            <input type="text" class="form-control" id="destino" name="destino" placeholder="Ex: Clínica Veterinária, Abrigo, etc.">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status_resgate" class="form-label">Status do Resgate</label>
                            <select class="form-select" id="status_resgate" name="status_resgate" required>
                                <option value="pendente">Pendente</option>
                                <option value="em_andamento">Em Andamento</option>
                                <option value="concluido">Concluído</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="observacoes" class="form-label">Observações</label>
                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3" placeholder="Detalhes sobre o resgate..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="salvarResgate()">Salvar Resgate</button>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize Lucide icons
lucide.createIcons();

// Filter resgates by status
function filterResgates(status) {
    const rows = document.querySelectorAll('tbody tr[data-resgate-id]');
    rows.forEach(row => {
        if (status === 'todos' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Open resgate details modal
function abrirResgate(resgateId) {
    const modal = new bootstrap.Modal(document.getElementById('resgateModal'));
    const detailsDiv = document.getElementById('resgateDetails');
    
    detailsDiv.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Carregando...</span></div></div>';
    modal.show();
    
    // Fetch resgate details via AJAX
    fetch('/resgates-detalhes?id=' + resgateId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                detailsDiv.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informações do Chamado</h6>
                            <p><strong>Assunto:</strong> ${data.resgate.chamado_assunto || 'N/A'}</p>
                            <p><strong>Localização:</strong> ${data.resgate.chamado_localizacao || 'N/A'}</p>
                            <p><strong>Contato:</strong> ${data.resgate.contato_nome || 'N/A'} - ${data.resgate.contato_telefone || 'N/A'}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Informações do Resgate</h6>
                            <p><strong>Estado do Animal:</strong> ${data.resgate.estado_animal || 'N/A'}</p>
                            <p><strong>Destino:</strong> ${data.resgate.destino || 'Não encaminhado'}</p>
                            <p><strong>Status:</strong> ${data.resgate.status_resgate || 'N/A'}</p>
                            <p><strong>Data:</strong> ${new Date(data.resgate.data_resgate).toLocaleString('pt-BR')}</p>
                        </div>
                    </div>
                    ${data.resgate.observacoes ? `<div class="mt-3"><h6>Observações</h6><p>${data.resgate.observacoes}</p></div>` : ''}
                `;
            } else {
                detailsDiv.innerHTML = '<div class="alert alert-danger">Erro ao carregar detalhes do resgate.</div>';
            }
        })
        .catch(error => {
            detailsDiv.innerHTML = '<div class="alert alert-danger">Erro ao carregar detalhes do resgate.</div>';
        });
}

// Save new resgate
function salvarResgate() {
    const form = document.getElementById('novoResgateForm');
    const formData = new FormData(form);
    
    fetch('/resgates-salvar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('novoResgateModal')).hide();
            alert('Resgate registrado com sucesso!');
            location.reload();
        } else {
            alert('Erro ao registrar resgate: ' + (data.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        alert('Erro ao registrar resgate.');
    });
}

// Load chamados when modal opens
document.getElementById('novoResgateModal').addEventListener('show.bs.modal', function() {
    const select = document.getElementById('fk_chamado_id');
    select.innerHTML = '<option value="">Selecione um chamado...</option>';
    
    fetch('/chamados-lista')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.chamados) {
                data.chamados.forEach(chamado => {
                    const option = document.createElement('option');
                    option.value = chamado.id;
                    option.textContent = `#${chamado.id} - ${chamado.assunto}`;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Erro ao carregar chamados:', error);
        });
});
</script>
