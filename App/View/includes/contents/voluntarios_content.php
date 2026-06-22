<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
                                <h6 class="mb-0">Total Voluntários</h6>
                                <h3 class="mb-0">0</h3>
                            </div>
                            <i data-lucide="users-2" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-0 bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Ativos</h6>
                                <h3 class="mb-0">0</h3>
                            </div>
                            <i data-lucide="user-check" style="width: 48px; height: 48px;"></i>
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
                                <h3 class="mb-0">0</h3>
                            </div>
                            <i data-lucide="user-clock" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-0 bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Equipes</h6>
                                <h3 class="mb-0">0</h3>
                            </div>
                            <i data-lucide="users" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Volunteers List -->
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary">
                                <i data-lucide="users-2" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;"></i>
                                Equipe de Voluntários
                            </h5>
                            <div>
                                <button type="button" class="btn btn-sm btn-success me-2" data-bs-toggle="modal" data-bs-target="#mensagemModal">
                                    <i data-lucide="message-circle" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;"></i>
                                    Enviar Mensagem
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#voluntarioModal">
                                    <i data-lucide="plus" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;"></i>
                                    Novo Voluntário
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-4">
                            <i data-lucide="inbox" style="width: 64px; height: 64px; color: #dee2e6;"></i>
                            <p class="text-muted mt-3">Nenhum voluntário cadastrado ainda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teams Section -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i data-lucide="users" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;"></i>
                                Equipes
                            </h5>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#equipeModal">
                                <i data-lucide="plus" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;"></i>
                                Nova Equipe
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-4">
                            <i data-lucide="inbox" style="width: 64px; height: 64px; color: #dee2e6;"></i>
                            <p class="text-muted mt-3">Nenhuma equipe criada ainda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Novo Voluntário -->
<div class="modal fade" id="voluntarioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i data-lucide="user-plus" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;"></i>
                    Cadastrar Novo Voluntário
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="voluntarioForm">
                    <div class="mb-3">
                        <label for="voluntarioNome" class="form-label fw-bold">Nome Completo *</label>
                        <input type="text" class="form-control" id="voluntarioNome" name="nome" 
                               placeholder="Nome do voluntário" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="voluntarioEmail" class="form-label fw-bold">E-mail *</label>
                        <input type="email" class="form-control" id="voluntarioEmail" name="email" 
                               placeholder="exemplo@email.com" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="voluntarioTelefone" class="form-label fw-bold">WhatsApp *</label>
                        <input type="tel" class="form-control" id="voluntarioTelefone" name="telefone" 
                               placeholder="(00) 00000-0000" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="voluntarioEquipe" class="form-label fw-bold">Equipe</label>
                        <select class="form-select" id="voluntarioEquipe" name="equipe_id">
                            <option value="">Selecione uma equipe (opcional)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="voluntarioDisponibilidade" class="form-label fw-bold">Disponibilidade</label>
                        <select class="form-select" id="voluntarioDisponibilidade" name="disponibilidade">
                            <option value="manha">Manhã</option>
                            <option value="tarde">Tarde</option>
                            <option value="noite">Noite</option>
                            <option value="fim_semana">Fim de Semana</option>
                            <option value="flexivel">Flexível</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="voluntarioHabilidades" class="form-label fw-bold">Habilidades</label>
                        <textarea class="form-control" id="voluntarioHabilidades" name="habilidades" rows="3" 
                                  placeholder="Descreva as habilidades do voluntário (ex: cuidado com animais, transporte, etc.)"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="voluntarioStatus" class="form-label fw-bold">Status</label>
                        <select class="form-select" id="voluntarioStatus" name="status">
                            <option value="ativo">Ativo</option>
                            <option value="pendente">Pendente</option>
                            <option value="inativo">Inativo</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="salvarVoluntario()">
                    <i data-lucide="save" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;"></i>
                    Salvar Voluntário
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Nova Equipe -->
<div class="modal fade" id="equipeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i data-lucide="users" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;"></i>
                    Criar Nova Equipe
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="equipeForm">
                    <div class="mb-3">
                        <label for="equipeNome" class="form-label fw-bold">Nome da Equipe *</label>
                        <input type="text" class="form-control" id="equipeNome" name="nome" 
                               placeholder="Ex: Equipe de Resgate, Equipe de Adoção, etc." required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="equipeDescricao" class="form-label fw-bold">Descrição</label>
                        <textarea class="form-control" id="equipeDescricao" name="descricao" rows="3" 
                                  placeholder="Descreva o objetivo da equipe"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="equipeResponsavel" class="form-label fw-bold">Responsável</label>
                        <select class="form-select" id="equipeResponsavel" name="responsavel_id">
                            <option value="">Selecione um responsável (opcional)</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="salvarEquipe()">
                    <i data-lucide="save" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;"></i>
                    Salvar Equipe
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Enviar Mensagem -->
<div class="modal fade" id="mensagemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i data-lucide="message-circle" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;"></i>
                    Enviar Mensagem via WhatsApp
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="mensagemForm">
                    <div class="mb-3">
                        <label for="mensagemDestinatarios" class="form-label fw-bold">Destinatários *</label>
                        <select class="form-select" id="mensagemDestinatarios" name="destinatarios" multiple required>
                            <option value="">Selecione os voluntários...</option>
                        </select>
                        <small class="text-muted">Segure Ctrl para selecionar múltiplos voluntários</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="mensagemTipo" class="form-label fw-bold">Tipo de Mensagem</label>
                        <select class="form-select" id="mensagemTipo" name="tipo">
                            <option value="alerta">⚠️ Alerta</option>
                            <option value="convite">📅 Convite</option>
                            <option value="informacao">ℹ️ Informação</option>
                            <option value="agradecimento">🙏 Agradecimento</option>
                            <option value="personalizada">✏️ Personalizada</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="mensagemTexto" class="form-label fw-bold">Mensagem *</label>
                        <textarea class="form-control" id="mensagemTexto" name="mensagem" rows="5" 
                                  placeholder="Digite sua mensagem aqui..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="enviarMensagem()">
                    <i data-lucide="send" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;"></i>
                    Enviar via WhatsApp
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') { 
        lucide.createIcons(); 
    }

    // Load teams for volunteer form
    // TODO: Load teams from database
    const equipeSelect = document.getElementById('voluntarioEquipe');
    equipeSelect.innerHTML = '<option value="">Selecione uma equipe (opcional)</option>';

    // Load volunteers for message form
    // TODO: Load volunteers from database
    const destinatariosSelect = document.getElementById('mensagemDestinatarios');
    destinatariosSelect.innerHTML = '<option value="">Selecione os voluntários...</option>';

    // Load volunteers for team responsible
    // TODO: Load volunteers from database
    const responsavelSelect = document.getElementById('equipeResponsavel');
    responsavelSelect.innerHTML = '<option value="">Selecione um responsável (opcional)</option>';
});

function salvarVoluntario() {
    const form = document.getElementById('voluntarioForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // TODO: Implement AJAX call to save volunteer
    alert('Funcionalidade de salvar voluntário em desenvolvimento.');
    
    bootstrap.Modal.getInstance(document.getElementById('voluntarioModal')).hide();
}

function salvarEquipe() {
    const form = document.getElementById('equipeForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // TODO: Implement AJAX call to save team
    alert('Funcionalidade de salvar equipe em desenvolvimento.');
    
    bootstrap.Modal.getInstance(document.getElementById('equipeModal')).hide();
}

function enviarMensagem() {
    const form = document.getElementById('mensagemForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const destinatarios = document.getElementById('mensagemDestinatarios');
    const selectedOptions = Array.from(destinatarios.selectedOptions);
    
    if (selectedOptions.length === 0) {
        alert('Selecione pelo menos um destinatário.');
        return;
    }

    const mensagem = document.getElementById('mensagemTexto').value;
    
    // Open WhatsApp for each selected volunteer
    selectedOptions.forEach(option => {
        const telefone = option.getAttribute('data-telefone');
        if (telefone) {
            const whatsappUrl = `https://wa.me/${telefone}?text=${encodeURIComponent(mensagem)}`;
            window.open(whatsappUrl, '_blank');
        }
    });
    
    bootstrap.Modal.getInstance(document.getElementById('mensagemModal')).hide();
}
</script>
