<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get chamados from controller
$chamados = $this->chamados ?? [];

// Calculate summary stats
$totalChamados = count($chamados);
$urgenciaAlta = 0;
$emAndamento = 0;
$concluidos = 0;

foreach ($chamados as $c) {
    if ($c['urgencia'] === 'alta' || $c['urgencia'] === 'critica') {
        $urgenciaAlta++;
    }
    if ($c['status'] === 'em_andamento') {
        $emAndamento++;
    }
    if ($c['status'] === 'concluido') {
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
                                <h6 class="mb-0">Total Chamados</h6>
                                <h3 class="mb-0"><?php echo $totalChamados; ?></h3>
                            </div>
                            <i data-lucide="phone-call" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-0 bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Urgência Alta</h6>
                                <h3 class="mb-0"><?php echo $urgenciaAlta; ?></h3>
                            </div>
                            <i data-lucide="alert-triangle" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-0 bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Em Andamento</h6>
                                <h3 class="mb-0"><?php echo $emAndamento; ?></h3>
                            </div>
                            <i data-lucide="clock" style="width: 48px; height: 48px;"></i>
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
            <!-- Chamados List -->
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary">
                                <i data-lucide="phone-call" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;"></i>
                                Chamados de Resgate
                            </h5>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary active" onclick="filterChamados('todos')">Todos</button>
                                <button class="btn btn-sm btn-outline-primary" onclick="filterChamados('pendente')">Pendentes</button>
                                <button class="btn btn-sm btn-outline-primary" onclick="filterChamados('em_andamento')">Em Andamento</button>
                                <button class="btn btn-sm btn-outline-primary" onclick="filterChamados('concluido')">Concluídos</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (empty($chamados)): ?>
                            <div class="text-center py-4">
                                <i data-lucide="inbox" style="width: 64px; height: 64px; color: #dee2e6;"></i>
                                <p class="text-muted mt-3">Nenhum chamado recebido ainda.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light text-secondary">
                                        <tr style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <th>ID</th>
                                            <th>Tipo</th>
                                            <th>Urgência</th>
                                            <th>Assunto</th>
                                            <th>Localização</th>
                                            <th>ONG</th>
                                            <th>Data</th>
                                            <th>Status</th>
                                            <th class="text-end">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($chamados as $c): ?>
                                        <tr data-chamado-id="<?php echo $c['id']; ?>" data-status="<?php echo $c['status']; ?>">
                                            <td>#<?php echo $c['id']; ?></td>
                                            <td>
                                                <span class="badge bg-secondary"><?php echo ucfirst($c['tipo']); ?></span>
                                            </td>
                                            <td>
                                                <?php
                                                $urgenciaClass = 'bg-success';
                                                if ($c['urgencia'] === 'alta') $urgenciaClass = 'bg-danger';
                                                elseif ($c['urgencia'] === 'critica') $urgenciaClass = 'bg-dark';
                                                elseif ($c['urgencia'] === 'media') $urgenciaClass = 'bg-warning';
                                                ?>
                                                <span class="badge <?php echo $urgenciaClass; ?>"><?php echo ucfirst($c['urgencia']); ?></span>
                                            </td>
                                            <td><?php echo htmlspecialchars($c['assunto']); ?></td>
                                            <td><?php echo htmlspecialchars($c['localizacao']); ?></td>
                                            <td><?php echo htmlspecialchars($c['ong_nome'] ?? 'N/A'); ?></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($c['data_criacao'])); ?></td>
                                            <td>
                                                <?php
                                                $statusClass = 'bg-secondary';
                                                if ($c['status'] === 'pendente') $statusClass = 'bg-warning';
                                                elseif ($c['status'] === 'em_andamento') $statusClass = 'bg-info';
                                                elseif ($c['status'] === 'concluido') $statusClass = 'bg-success';
                                                ?>
                                                <span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst(str_replace('_', ' ', $c['status'])); ?></span>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-primary" onclick="abrirChamado(<?php echo $c['id']; ?>)">
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

<!-- Modal para Visualizar Chamado -->
<div class="modal fade" id="chamadoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i data-lucide="phone-call" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;"></i>
                    Detalhes do Chamado
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Tipo:</strong> <span id="chamadoTipo"></span></p>
                        <p><strong>Urgência:</strong> <span id="chamadoUrgencia"></span></p>
                        <p><strong>Origem:</strong> <span id="chamadoOrigem"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Localização:</strong> <span id="chamadoLocal"></span></p>
                        <p><strong>Criado em:</strong> <span id="chamadoData"></span></p>
                        <p><strong>Status:</strong> <span id="chamadoStatus"></span></p>
                    </div>
                </div>
                <hr>
                <h6>Descrição</h6>
                <p id="chamadoDescricao" style="white-space: pre-wrap;"></p>
                <hr>
                <div id="chamadoFotos" class="row g-2 mt-3"></div>
                <hr>
                <h6>Informações do Contato</h6>
                <p><strong>Nome:</strong> <span id="chamadoContatoNome"></span></p>
                <p><strong>Telefone:</strong> <span id="chamadoContatoTelefone"></span></p>
                <hr>
                <h6>Histórico</h6>
                <div id="chamadoHistorico"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-warning" onclick="atualizarStatus('em_andamento')">Iniciar Atendimento</button>
                <button type="button" class="btn btn-success" onclick="atualizarStatus('concluido')">Concluir</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Adicionar Observação -->
<div class="modal fade" id="observacaoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i data-lucide="message-square" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;"></i>
                    Adicionar Observação
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="observacaoForm">
                    <div class="mb-3">
                        <label for="observacaoTexto" class="form-label fw-bold">Observação</label>
                        <textarea class="form-control" id="observacaoTexto" name="observacao" rows="4" 
                                  placeholder="Descreva o progresso do atendimento..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-info" onclick="salvarObservacao()">
                    <i data-lucide="save" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;"></i>
                    Salvar Observação
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Store chamados data in JavaScript
const chamadosData = <?php echo json_encode($chamados); ?>;
let currentChamadoId = null;

document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') { 
        lucide.createIcons(); 
    }
});

function filterChamados(status) {
    const rows = document.querySelectorAll('tbody tr[data-chamado-id]');
    rows.forEach(row => {
        if (status === 'todos') {
            row.style.display = '';
        } else {
            const rowStatus = row.getAttribute('data-status');
            row.style.display = rowStatus === status ? '' : 'none';
        }
    });

    // Update active button
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
}

function abrirChamado(id) {
    const chamado = chamadosData.find(c => c.id === id);
    if (!chamado) return;

    currentChamadoId = id;

    document.getElementById('chamadoTipo').textContent = chamado.tipo;
    document.getElementById('chamadoUrgencia').textContent = chamado.urgencia;
    document.getElementById('chamadoOrigem').textContent = chamado.origem;
    document.getElementById('chamadoLocal').textContent = chamado.localizacao;
    document.getElementById('chamadoData').textContent = new Date(chamado.data_criacao).toLocaleString('pt-BR');
    document.getElementById('chamadoStatus').textContent = chamado.status;
    document.getElementById('chamadoDescricao').textContent = chamado.descricao;
    document.getElementById('chamadoContatoNome').textContent = chamado.contato_nome || 'N/A';
    document.getElementById('chamadoContatoTelefone').textContent = chamado.contato_telefone || 'N/A';

    const modal = new bootstrap.Modal(document.getElementById('chamadoModal'));
    modal.show();
}

function atualizarStatus(status) {
    // TODO: Implement AJAX call to update chamado status
    alert('Funcionalidade de atualizar status em desenvolvimento.');
}

function salvarObservacao() {
    const form = document.getElementById('observacaoForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // TODO: Implement AJAX call to save observation
    alert('Funcionalidade de salvar observação em desenvolvimento.');
    
    bootstrap.Modal.getInstance(document.getElementById('observacaoModal')).hide();
}
</script>
