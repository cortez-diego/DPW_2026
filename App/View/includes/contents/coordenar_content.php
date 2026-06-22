<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$adocoes = $adocoes ?? [];
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0 text-primary">
                                    <i data-lucide="clipboard-list" style="width: 24px; height: 24px; display: inline-block; vertical-align: middle;"></i>
                                    Coordenar Adoções
                                </h4>
                                <p class="text-muted mb-0 mt-1">Gerencie as adoções realizadas e registre visitas de acompanhamento</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    <?php if (empty($adocoes)): ?>
                        <div class="text-center py-5">
                            <i data-lucide="inbox" style="width: 64px; height: 64px; color: #dee2e6;"></i>
                            <p class="text-muted mt-3">Nenhuma adoção registrada ainda.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Animal</th>
                                        <th>Adotado por</th>
                                        <th>Data da Adoção</th>
                                        <th>Status</th>
                                        <th>Visitas</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($adocoes as $item): ?>
                                        <?php 
                                        $adocao = $item['adocao'];
                                        $animal = $item['animal'];
                                        $adotante = $item['adotante'];
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if ($animal && $animal->__get('foto')): ?>
                                                        <img src="<?php echo htmlspecialchars($animal->__get('foto')); ?>" 
                                                             alt="<?php echo htmlspecialchars($animal->__get('nome')); ?>" 
                                                             class="rounded-circle me-3" 
                                                             style="width: 45px; height: 45px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3" 
                                                             style="width: 45px; height: 45px;">
                                                            <i data-lucide="paw-print" style="width: 20px; height: 20px; color: white;"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <a href="#" class="fw-bold text-decoration-none text-dark animal-profile-link" 
                                                           data-animal-id="<?php echo $animal ? $animal->__get('id') : ''; ?>">
                                                            <?php echo htmlspecialchars($animal ? $animal->__get('nome') : 'N/A'); ?>
                                                        </a>
                                                        <small class="text-muted d-block">
                                                            <?php echo htmlspecialchars($animal ? $animal->__get('especie') : ''); ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#" class="fw-bold text-decoration-none text-dark adotante-profile-link" 
                                                   data-adotante-id="<?php echo $adotante ? $adotante->__get('id') : ''; ?>">
                                                    <?php echo htmlspecialchars($adotante ? $adotante->__get('nome') : 'N/A'); ?>
                                                </a>
                                                <small class="text-muted d-block">
                                                    <?php echo htmlspecialchars($adotante ? $adotante->__get('telefone_1') : ''); ?>
                                                </small>
                                            </td>
                                            <td>
                                                <?php 
                                                $data = $adocao->__get('solAdc_data');
                                                if ($data) {
                                                    echo date('d/m/Y', strtotime($data));
                                                } else {
                                                    echo 'N/A';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                $status = $adocao->__get('solAdc_status');
                                                $statusMap = [
                                                    'a' => '<span class="badge bg-success">Aprovado</span>',
                                                    'i' => '<span class="badge bg-secondary">Inativo</span>',
                                                    'p' => '<span class="badge bg-warning">Pendente</span>'
                                                ];
                                                echo $statusMap[$status] ?? '<span class="badge bg-secondary">Desconhecido</span>';
                                                ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">0/5</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#visitaModal"
                                                            data-adocao-id="<?php echo $adocao->__get('solAdc_id'); ?>"
                                                            data-animal-nome="<?php echo htmlspecialchars($animal ? $animal->__get('nome') : ''); ?>"
                                                            data-adotante-nome="<?php echo htmlspecialchars($adotante ? $adotante->__get('nome') : ''); ?>">
                                                        <i data-lucide="calendar-plus" style="width: 16px; height: 16px;"></i>
                                                        Registrar Visita
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#visitasModal"
                                                            data-adocao-id="<?php echo $adocao->__get('solAdc_id'); ?>">
                                                        <i data-lucide="list" style="width: 16px; height: 16px;"></i>
                                                        Ver Visitas
                                                    </button>
                                                </div>
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

<!-- Modal para Registrar Visita -->
<div class="modal fade" id="visitaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i data-lucide="calendar-plus" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;"></i>
                    Registrar Visita de Acompanhamento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i data-lucide="info" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;"></i>
                    <strong>Animal:</strong> <span id="modalAnimalNome"></span> | 
                    <strong>Adotante:</strong> <span id="modalAdotanteNome"></span>
                </div>
                
                <form id="visitaForm">
                    <input type="hidden" id="adocaoId" name="adocao_id">
                    
                    <div class="mb-3">
                        <label for="dataVisita" class="form-label fw-bold">Data da Visita *</label>
                        <input type="date" class="form-control" id="dataVisita" name="data_visita" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="estadoAnimal" class="form-label fw-bold">Estado do Animal *</label>
                        <textarea class="form-control" id="estadoAnimal" name="estado_animal" rows="3" 
                                  placeholder="Descreva o estado geral do animal (saúde, comportamento, aparência...)" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="enderecoVisita" class="form-label fw-bold">Endereço da Visita *</label>
                        <input type="text" class="form-control" id="enderecoVisita" name="endereco_visita" 
                               placeholder="Endereço onde a visita foi realizada" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observacoes" class="form-label fw-bold">Observações</label>
                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3" 
                                  placeholder="Informações adicionais sobre a visita"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nota" class="form-label fw-bold">Nota de Acompanhamento *</label>
                        <textarea class="form-control" id="nota" name="nota" rows="4" 
                                  placeholder="Avaliação geral da situação do animal e do adotante. Esta nota ficará vinculada à adoção e ao perfil do usuário." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="salvarVisita()">
                    <i data-lucide="save" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;"></i>
                    Salvar Visita
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver Visitas -->
<div class="modal fade" id="visitasModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i data-lucide="list" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;"></i>
                    Histórico de Visitas
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="visitasList">
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

<!-- Modal para Perfil do Adotante -->
<div class="modal fade" id="adotanteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i data-lucide="user" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;"></i>
                    Perfil do Adotante
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="adotanteModalContent">
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
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') { 
        lucide.createIcons(); 
    }

    // Handle modal open for visit registration
    const visitaModal = document.getElementById('visitaModal');
    visitaModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const adocaoId = button.getAttribute('data-adocao-id');
        const animalNome = button.getAttribute('data-animal-nome');
        const adotanteNome = button.getAttribute('data-adotante-nome');
        
        document.getElementById('adocaoId').value = adocaoId;
        document.getElementById('modalAnimalNome').textContent = animalNome;
        document.getElementById('modalAdotanteNome').textContent = adotanteNome;
        
        // Reset form
        document.getElementById('visitaForm').reset();
    });

    // Handle modal open for visits list
    const visitasModal = document.getElementById('visitasModal');
    visitasModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const adocaoId = button.getAttribute('data-adocao-id');
        
        // Load visits (placeholder for now)
        document.getElementById('visitasList').innerHTML = `
            <div class="text-center py-4 text-muted">
                <i data-lucide="calendar-x" style="width: 48px; height: 48px;"></i>
                <p class="mt-2">Nenhuma visita registrada ainda.</p>
            </div>
        `;
        if (typeof lucide !== 'undefined') { lucide.createIcons(); }
    });

    // Handle adotante profile click
    document.querySelectorAll('.adotante-profile-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const adotanteId = this.getAttribute('data-adotante-id');
            // Load adotante profile (placeholder for now)
            document.getElementById('adotanteModalContent').innerHTML = `
                <div class="alert alert-warning">
                    <i data-lucide="alert-circle" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;"></i>
                    Funcionalidade de perfil do adotante em desenvolvimento.
                </div>
            `;
            if (typeof lucide !== 'undefined') { lucide.createIcons(); }
            new bootstrap.Modal(document.getElementById('adotanteModal')).show();
        });
    });

    // Handle animal profile click
    document.querySelectorAll('.animal-profile-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const animalId = this.getAttribute('data-animal-id');
            // Open animal profile modal (reuse existing modal if available)
            alert('Funcionalidade de perfil do animal em desenvolvimento. ID: ' + animalId);
        });
    });
});

function salvarVisita() {
    const form = document.getElementById('visitaForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const formData = new FormData(form);
    
    // TODO: Implement AJAX call to save visit
    alert('Funcionalidade de salvar visita em desenvolvimento.');
    
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('visitaModal')).hide();
}
</script>
</div>
