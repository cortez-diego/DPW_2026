<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get animais and casosEnviados from controller
$animais = $this->animais ?? [];
$casosEnviados = $this->casosEnviados ?? [];
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 text-secondary">Enviar Caso para Veterinário</h5>
                    </div>
                    <div class="card-body">
                        <form id="enviarCasoForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fk_animal_id" class="form-label">Animal</label>
                                    <select class="form-select" id="fk_animal_id" name="fk_animal_id" required>
                                        <option value="">Selecione um animal...</option>
                                        <?php foreach ($animais as $animal): ?>
                                            <option value="<?php echo $animal['id']; ?>">
                                                <?php echo htmlspecialchars($animal['nome'] . ' - ' . $animal['especie'] . ' (' . $animal['raca'] . ')'); ?>
                                            </option>
                                        <?php endforeach; ?>
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
                                <label for="descricao" class="form-label">Descrição do Caso</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="4" required placeholder="Descreva o caso do animal, sintomas, histórico, etc..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="observacoes" class="form-label">Observações Adicionais</label>
                                <textarea class="form-control" id="observacoes" name="observacoes" rows="2" placeholder="Outras informações relevantes..."></textarea>
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn btn-primary" onclick="enviarCaso()">
                                    <i data-lucide="send" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;"></i>
                                    Enviar Caso
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 text-secondary">Casos Enviados</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($casosEnviados)): ?>
                            <div class="text-center py-4">
                                <p class="text-muted">Nenhum caso enviado ainda.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light text-secondary">
                                        <tr style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <th>ID</th>
                                            <th>Animal</th>
                                            <th>Descrição</th>
                                            <th>Prioridade</th>
                                            <th>Status</th>
                                            <th>Data Envio</th>
                                            <th>Avaliação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($casosEnviados as $caso): ?>
                                            <?php
                                                $prioridadeClass = $caso['prioridade'] === 'urgente' ? 'bg-danger' : ($caso['prioridade'] === 'alta' ? 'bg-warning' : ($caso['prioridade'] === 'media' ? 'bg-info' : 'bg-secondary'));
                                                $statusClass = $caso['status'] === 'concluido' ? 'bg-success' : ($caso['status'] === 'em_avaliacao' ? 'bg-info' : 'bg-warning');
                                            ?>
                                            <tr>
                                                <td>#<?php echo $caso['id']; ?></td>
                                                <td>
                                                    <?php echo htmlspecialchars($caso['animal_nome'] ?? 'N/A'); ?>
                                                    <br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($caso['especie'] ?? '') . ' (' . htmlspecialchars($caso['raca'] ?? '') . ')'; ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($caso['descricao'] ? substr($caso['descricao'], 0, 50) . '...' : 'N/A'); ?></td>
                                                <td><span class="badge <?php echo $prioridadeClass; ?>"><?php echo htmlspecialchars($caso['prioridade']); ?></span></td>
                                                <td><span class="badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($caso['status']); ?></span></td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($caso['data_envio'])); ?></td>
                                                <td>
                                                    <?php if ($caso['diagnostico']): ?>
                                                        <button class="btn btn-sm btn-outline-info" onclick="verAvaliacao(<?php echo $caso['id']; ?>)">Ver Avaliação</button>
                                                    <?php else: ?>
                                                        <span class="text-muted">Pendente</span>
                                                    <?php endif; ?>
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

<!-- Modal para ver avaliação -->
<div class="modal fade" id="avaliacaoModal" tabindex="-1" aria-labelledby="avaliacaoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avaliacaoModalLabel">Avaliação Veterinária</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="avaliacaoModalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize Lucide icons
lucide.createIcons();

// Ver avaliação
function verAvaliacao(casoId) {
    const modalBody = document.getElementById('avaliacaoModalBody');
    modalBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Carregando...</span></div></div>';
    
    const modal = new bootstrap.Modal(document.getElementById('avaliacaoModal'));
    modal.show();

    fetch('/casos-veterinario-detalhes?id=' + casoId)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.caso) {
                modalBody.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informações do Animal</h6>
                            <p><strong>Nome:</strong> ${data.caso.animal_nome || 'N/A'}</p>
                            <p><strong>Espécie:</strong> ${data.caso.especie || 'N/A'}</p>
                            <p><strong>Raça:</strong> ${data.caso.raca || 'N/A'}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Informações do Caso</h6>
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
                    ` : '<div class="mt-3 alert alert-warning">Avaliação ainda não realizada.</div>'}
                `;
            } else {
                modalBody.innerHTML = '<div class="alert alert-danger">Erro ao carregar avaliação.</div>';
            }
        })
        .catch(error => {
            modalBody.innerHTML = '<div class="alert alert-danger">Erro ao carregar avaliação.</div>';
        });
}

// Enviar caso
function enviarCaso() {
    const form = document.getElementById('enviarCasoForm');
    const formData = new FormData(form);

    fetch('/enviar-caso-veterinario-salvar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Caso enviado com sucesso!');
            form.reset();
            location.reload();
        } else {
            alert('Erro ao enviar caso: ' + (data.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        alert('Erro ao enviar caso.');
    });
}
</script>
