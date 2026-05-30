<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Nova Solicitação de Adoção</h1>
        <a href="/dashboard/solicitacao/listar" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
 
    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="/dashboard/solicitacao/cadastrar">
 
                <!-- Dados da Solicitação -->
                <h5 class="mb-3 text-primary">Dados da Solicitação</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="solAdc_data" class="form-label">Data da Solicitação <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="solAdc_data" name="solAdc_data" required>
                    </div>
                    <div class="col-md-4">
                        <label for="solAdc_status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="solAdc_status" name="solAdc_status" required>
                            <option value="" disabled selected>Selecione um status</option>
                            <option value="pendente">Pendente</option>
                            <option value="em_analise">Em Análise</option>
                            <option value="aprovado">Aprovado</option>
                            <option value="reprovado">Reprovado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                </div>
 
                <!-- Adotante e Animal -->
                <h5 class="mb-3 text-primary">Adotante e Animal</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="fk_adotante_id" class="form-label">Adotante <span class="text-danger">*</span></label>
                        <select class="form-select" id="fk_adotante_id" name="fk_adotante_id" required>
                            <option value="" disabled selected>Selecione um adotante</option>
                            <?php foreach ($adotantes as $adotante): ?>
                                <option value="<?= htmlspecialchars($adotante->adotante_id) ?>">
                                    <?= htmlspecialchars($adotante->nome) ?> — <?= htmlspecialchars($adotante->cpf ?? 'CPF não informado') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="fk_animal_id" class="form-label">Animal <span class="text-danger">*</span></label>
                        <select class="form-select" id="fk_animal_id" name="fk_animal_id" required>
                            <option value="" disabled selected>Selecione um animal</option>
                            <?php foreach ($animais as $animal): ?>
                                <option value="<?= htmlspecialchars($animal->animal_id) ?>">
                                    <?= htmlspecialchars($animal->nome) ?> — <?= htmlspecialchars($animal->especie ?? '') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
 
                <!-- Motivo -->
                <h5 class="mb-3 text-primary">Motivo</h5>
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label for="solAdc_motivo" class="form-label">Motivo da Solicitação</label>
                        <textarea class="form-control" id="solAdc_motivo" name="solAdc_motivo" rows="4"
                            placeholder="Descreva o motivo pelo qual deseja adotar este animal..."></textarea>
                        <div class="form-text">Campo opcional. Máximo de 1000 caracteres.</div>
                    </div>
                </div>
 
                <!-- Botões -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar
                    </button>
                    <a href="/dashboard/solicitacao/listar" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
 
            </form>
        </div>
    </div>
</div>
 
<script>
// Preenche a data de hoje como padrão
document.addEventListener('DOMContentLoaded', function () {
    var hoje = new Date().toISOString().split('T')[0];
    var campoData = document.getElementById('solAdc_data');
    if (campoData && !campoData.value) {
        campoData.value = hoje;
    }
});
 
// Contador de caracteres para o motivo
document.getElementById('solAdc_motivo').addEventListener('input', function () {
    var max = 1000;
    var atual = this.value.length;
    var texto = this.nextElementSibling;
    if (atual > max) {
        this.value = this.value.substring(0, max);
        atual = max;
    }
    texto.textContent = 'Campo opcional. ' + atual + '/' + max + ' caracteres.';
});
</script>