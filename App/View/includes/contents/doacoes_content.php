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
                <div class="card shadow-sm border-0 bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Total Recebido</h6>
                                <h3 class="mb-0">R$ 0,00</h3>
                            </div>
                            <i data-lucide="trending-up" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-0 bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Total Gasto</h6>
                                <h3 class="mb-0">R$ 0,00</h3>
                            </div>
                            <i data-lucide="trending-down" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-0 bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Saldo Atual</h6>
                                <h3 class="mb-0">R$ 0,00</h3>
                            </div>
                            <i data-lucide="wallet" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-0 bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Doações</h6>
                                <h3 class="mb-0">0</h3>
                            </div>
                            <i data-lucide="heart" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Donations Section -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary">
                                <i data-lucide="heart" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;"></i>
                                Doações Recebidas
                            </h5>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#doacaoModal">
                                <i data-lucide="plus" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;"></i>
                                Nova Doação
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-4">
                            <i data-lucide="inbox" style="width: 64px; height: 64px; color: #dee2e6;"></i>
                            <p class="text-muted mt-3">Nenhuma doação registrada ainda.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expenses Section -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-danger">
                                <i data-lucide="receipt" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;"></i>
                                Despesas com Animais
                            </h5>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#despesaModal">
                                <i data-lucide="plus" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;"></i>
                                Nova Despesa
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-4">
                            <i data-lucide="inbox" style="width: 64px; height: 64px; color: #dee2e6;"></i>
                            <p class="text-muted mt-3">Nenhuma despesa registrada ainda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">
                            <i data-lucide="history" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;"></i>
                            Histórico de Transações
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-4">
                            <i data-lucide="inbox" style="width: 64px; height: 64px; color: #dee2e6;"></i>
                            <p class="text-muted mt-3">Nenhuma transação registrada ainda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Nova Doação -->
<div class="modal fade" id="doacaoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i data-lucide="heart" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;"></i>
                    Registrar Nova Doação
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="doacaoForm">
                    <div class="mb-3">
                        <label for="doacaoTipo" class="form-label fw-bold">Tipo de Doação *</label>
                        <select class="form-select" id="doacaoTipo" name="tipo" required>
                            <option value="">Selecione...</option>
                            <option value="dinheiro">Dinheiro</option>
                            <option value="alimentos">Alimentos</option>
                            <option value="medicamentos">Medicamentos</option>
                            <option value="materiais">Materiais (cobertores, camas, etc.)</option>
                            <option value="outros">Outros</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="valorContainer">
                        <label for="doacaoValor" class="form-label fw-bold">Valor (R$) *</label>
                        <input type="number" class="form-control" id="doacaoValor" name="valor" 
                               placeholder="0,00" step="0.01" min="0">
                    </div>
                    
                    <div class="mb-3">
                        <label for="doacaoDescricao" class="form-label fw-bold">Descrição *</label>
                        <textarea class="form-control" id="doacaoDescricao" name="descricao" rows="3" 
                                  placeholder="Descreva a doação (ex: Ração, valor em dinheiro, medicamentos, etc.)" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="doacaoDoador" class="form-label fw-bold">Doador</label>
                        <input type="text" class="form-control" id="doacaoDoador" name="doador" 
                               placeholder="Nome do doador (opcional)">
                    </div>
                    
                    <div class="mb-3">
                        <label for="doacaoData" class="form-label fw-bold">Data da Doação *</label>
                        <input type="date" class="form-control" id="doacaoData" name="data" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="salvarDoacao()">
                    <i data-lucide="save" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;"></i>
                    Salvar Doação
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Nova Despesa -->
<div class="modal fade" id="despesaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i data-lucide="receipt" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle;"></i>
                    Registrar Nova Despesa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="despesaForm">
                    <div class="mb-3">
                        <label for="despesaCategoria" class="form-label fw-bold">Categoria *</label>
                        <select class="form-select" id="despesaCategoria" name="categoria" required>
                            <option value="">Selecione...</option>
                            <option value="veterinario">Veterinário</option>
                            <option value="medicamentos">Medicamentos</option>
                            <option value="alimentacao">Alimentação</option>
                            <option value="higiene">Higiene e Limpeza</option>
                            <option value="transporte">Transporte</option>
                            <option value="instalacoes">Instalações</option>
                            <option value="outros">Outros</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="despesaValor" class="form-label fw-bold">Valor (R$) *</label>
                        <input type="number" class="form-control" id="despesaValor" name="valor" 
                               placeholder="0,00" step="0.01" min="0" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="despesaDescricao" class="form-label fw-bold">Descrição *</label>
                        <textarea class="form-control" id="despesaDescricao" name="descricao" rows="3" 
                                  placeholder="Descreva a despesa (ex: Consulta veterinária, compra de ração, etc.)" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="despesaAnimal" class="form-label fw-bold">Animal Relacionado</label>
                        <select class="form-select" id="despesaAnimal" name="animal_id">
                            <option value="">Selecione um animal (opcional)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="despesaData" class="form-label fw-bold">Data da Despesa *</label>
                        <input type="date" class="form-control" id="despesaData" name="data" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="salvarDespesa()">
                    <i data-lucide="save" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;"></i>
                    Salvar Despesa
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

    // Set default date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('doacaoData').value = today;
    document.getElementById('despesaData').value = today;

    // Show/hide valor field based on tipo
    document.getElementById('doacaoTipo').addEventListener('change', function() {
        const valorContainer = document.getElementById('valorContainer');
        if (this.value === 'dinheiro') {
            valorContainer.style.display = 'block';
            document.getElementById('doacaoValor').required = true;
        } else {
            valorContainer.style.display = 'none';
            document.getElementById('doacaoValor').required = false;
            document.getElementById('doacaoValor').value = '';
        }
    });

    // Load animals for expense form
    // TODO: Load animals from database
    const animalSelect = document.getElementById('despesaAnimal');
    animalSelect.innerHTML = '<option value="">Selecione um animal (opcional)</option>';
});

function salvarDoacao() {
    const form = document.getElementById('doacaoForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // TODO: Implement AJAX call to save donation
    alert('Funcionalidade de salvar doação em desenvolvimento.');
    
    bootstrap.Modal.getInstance(document.getElementById('doacaoModal')).hide();
}

function salvarDespesa() {
    const form = document.getElementById('despesaForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // TODO: Implement AJAX call to save expense
    alert('Funcionalidade de salvar despesa em desenvolvimento.');
    
    bootstrap.Modal.getInstance(document.getElementById('despesaModal')).hide();
}
</script>
