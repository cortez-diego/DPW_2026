<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get casos from controller
$casos = $this->casos ?? [];

// Calculate summary stats
$totalCasos = count($casos);
$pendentes = 0;
$emAvaliacao = 0;
$concluidos = 0;

foreach ($casos as $c) {
    if ($c['status'] === 'pendente') {
        $pendentes++;
    } elseif ($c['status'] === 'em_avaliacao') {
        $emAvaliacao++;
    } elseif ($c['status'] === 'concluido') {
        $concluidos++;
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
                                <h6 class="mb-0">Total Casos</h6>
                                <h3 class="mb-0"><?php echo $totalCasos; ?></h3>
                            </div>
                            <i data-lucide="file-text" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-0 bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Pendentes</h6>
                                <h3 class="mb-0"><?php echo $pendentes; ?></h3>
                            </div>
                            <i data-lucide="clock" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-0 bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Em Avaliação</h6>
                                <h3 class="mb-0"><?php echo $emAvaliacao; ?></h3>
                            </div>
                            <i data-lucide="stethoscope" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-0 bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Concluídos</h6>
                                <h3 class="mb-0"><?php echo $concluidos; ?></h3>
                            </div>
                            <i data-lucide="check-circle" style="width: 48px; height: 48px;"></i>
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
                            <h5 class="mb-0 text-secondary">Casos para Avaliação</h5>
                            <div>
                                <button class="btn btn-sm btn-outline-primary" onclick="filterCasos('todos')">Todos</button>
                                <button class="btn btn-sm btn-outline-warning" onclick="filterCasos('pendente')">Pendentes</button>
                                <button class="btn btn-sm btn-outline-info" onclick="filterCasos('em_avaliacao')">Em Avaliação</button>
                                <button class="btn btn-sm btn-outline-success" onclick="filterCasos('concluido')">Concluídos</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (empty($casos)): ?>
                            <div class="text-center py-4">
                                <i data-lucide="inbox" style="width: 64px; height: 64px; color: #dee2e6;"></i>
                                <p class="text-muted mt-3">Nenhum caso para avaliação.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light text-secondary">
                                        <tr style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <th>ID</th>
                                            <th>Animal</th>
                                            <th>ONG</th>
                                            <th>Data Envio</th>
                                            <th>Status</th>
                                            <th class="text-end">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($casos as $c): ?>
                                        <tr data-caso-id="<?php echo $c['id']; ?>" data-status="<?php echo $c['status']; ?>">
                                            <td>#<?php echo $c['id']; ?></td>
                                            <td>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($c['animal_nome'] ?? 'N/A'); ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($c['especie'] ?? '') . ' - ' . htmlspecialchars($c['raca'] ?? ''); ?></small>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($c['ong_nome'] ?? 'N/A'); ?></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($c['data_envio'])); ?></td>
                                            <td>
                                                <?php
                                                $statusClass = 'bg-secondary';
                                                if ($c['status'] === 'pendente') $statusClass = 'bg-warning';
                                                elseif ($c['status'] === 'em_avaliacao') $statusClass = 'bg-info';
                                                elseif ($c['status'] === 'concluido') $statusClass = 'bg-success';
                                                ?>
                                                <span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst(str_replace('_', ' ', $c['status'])); ?></span>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-primary" onclick="abrirCaso(<?php echo $c['id']; ?>)">
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

<!-- Modal para Visualizar Caso -->
<div class="modal fade" id="casoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i data-lucide="stethoscope" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;"></i>
                    Detalhes do Caso
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="casoDetails">
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

<!-- Modal para Avaliar Caso -->
<div class="modal fade" id="avaliarCasoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i data-lucide="clipboard-check" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;"></i>
                    Avaliar Caso
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="avaliarCasoForm">
                    <input type="hidden" id="caso_id" name="caso_id">
                    <div class="mb-3">
                        <label for="diagnostico" class="form-label">Diagnóstico</label>
                        <textarea class="form-control" id="diagnostico" name="diagnostico" rows="3" required placeholder="Descreva o diagnóstico do animal..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tratamento" class="form-label">Tratamento Recomendado</label>
                        <textarea class="form-control" id="tratamento" name="tratamento" rows="3" required placeholder="Descreva o tratamento recomendado..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="em_avaliacao">Em Avaliação</option>
                                <option value="concluido">Concluído</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="prioridade" class="form-label">Prioridade</label>
                            <select class="form-select" id="prioridade" name="prioridade" required>
                                <option value="baixa">Baixa</option>
                                <option value="media">Média</option>
                                <option value="alta">Alta</option>
                                <option value="urgente">Urgente</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="observacoes" class="form-label">Observações Adicionais</label>
                        <textarea class="form-control" id="observacoes" name="observacoes" rows="2" placeholder="Outras observações..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="salvarAvaliacao()">Salvar Avaliação</button>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize Lucide icons
lucide.createIcons();

// Filter casos by status
function filterCasos(status) {
    const rows = document.querySelectorAll('tbody tr[data-caso-id]');
    rows.forEach(row => {
        if (status === 'todos' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Open caso details modal
function abrirCaso(casoId) {
    const modal = new bootstrap.Modal(document.getElementById('casoModal'));
    const detailsDiv = document.getElementById('casoDetails');
    
    detailsDiv.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Carregando...</span></div></div>';
    modal.show();
    
    // Fetch caso details via AJAX
    fetch('/casos-veterinario-detalhes?id=' + casoId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                detailsDiv.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informações do Animal</h6>
                            <p><strong>Nome:</strong> ${data.caso.animal_nome || 'N/A'}</p>
                            <p><strong>Espécie:</strong> ${data.caso.especie || 'N/A'}</p>
                            <p><strong>Raça:</strong> ${data.caso.raca || 'N/A'}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Informações do Caso</h6>
                            <p><strong>ONG:</strong> ${data.caso.ong_nome || 'N/A'}</p>
                            <p><strong>Status:</strong> ${data.caso.status || 'N/A'}</p>
                            <p><strong>Prioridade:</strong> ${data.caso.prioridade || 'N/A'}</p>
                            <p><strong>Data Envio:</strong> ${new Date(data.caso.data_envio).toLocaleString('pt-BR')}</p>
                        </div>
                    </div>
                    ${data.caso.descricao ? `<div class="mt-3"><h6>Descrição</h6><p>${data.caso.descricao}</p></div>` : ''}
                    ${data.caso.diagnostico ? `
                        <div class="mt-3 border-top pt-3">
                            <h6 class="text-success">Avaliação Veterinária</h6>
                            <p><strong>Diagnóstico:</strong> ${data.caso.diagnostico}</p>
                            <p><strong>Tratamento:</strong> ${data.caso.tratamento}</p>
                            ${data.caso.observacoes ? `<p><strong>Observações:</strong> ${data.caso.observacoes}</p>` : ''}
                            ${data.caso.data_avaliacao ? `<p><small class="text-muted">Data da avaliação: ${new Date(data.caso.data_avaliacao).toLocaleString('pt-BR')}</small></p>` : ''}
                        </div>
                    ` : ''}
                    ${data.caso.status !== 'concluido' && !data.caso.diagnostico ? `
                        <div class="mt-3">
                            <button class="btn btn-success" onclick="abrirAvaliarCaso(${data.caso.id})">Avaliar Caso</button>
                        </div>
                    ` : ''}
                `;
            } else {
                detailsDiv.innerHTML = '<div class="alert alert-danger">Erro ao carregar detalhes do caso.</div>';
            }
        })
        .catch(error => {
            detailsDiv.innerHTML = '<div class="alert alert-danger">Erro ao carregar detalhes do caso.</div>';
        });
}

// Open avaliar caso modal
function abrirAvaliarCaso(casoId) {
    bootstrap.Modal.getInstance(document.getElementById('casoModal')).hide();
    document.getElementById('caso_id').value = casoId;
    new bootstrap.Modal(document.getElementById('avaliarCasoModal')).show();
}

// Save avaliacao
function salvarAvaliacao() {
    const form = document.getElementById('avaliarCasoForm');
    const formData = new FormData(form);
    
    fetch('/casos-veterinario-salvar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('avaliarCasoModal')).hide();
            alert('Avaliação salva com sucesso!');
            location.reload();
        } else {
            alert('Erro ao salvar avaliação: ' + (data.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        alert('Erro ao salvar avaliação.');
    });
}
</script>
