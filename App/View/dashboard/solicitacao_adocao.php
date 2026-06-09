<?php
$animal   = $this->getView()->animal;
//$adotante = $this->getView()->adotante;
?>
 
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Nova Solicitação de Adoção</h1>
        <a href="/dashboard/solicitacao/listar" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
 
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST" action="/dashboard/solicitacao-adocao/cadastrar">
 
                <!-- IDs ocultos -->
                <input type="hidden" name="fk_animal_id"   value="<?= htmlspecialchars($animal->__get('id')) ?>">
                <!--<input type="hidden" name="fk_adotante_id" value="< ?= htmlspecialchars($adotante->__get('adt_id')) ?>">-->
 
                <!-- Animal -->
                <h5 class="text-primary border-bottom pb-2 mb-3">Animal</h5>
                <div class="row mb-4">
                    <div class="col-sm-4 mb-3">
                        <small class="text-muted d-block">Nome</small>
                        <span class="fw-semibold"><?= htmlspecialchars($animal->__get('nome')) ?></span>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <small class="text-muted d-block">Espécie</small>
                        <span class="fw-semibold"><?= htmlspecialchars($animal->__get('especie_nome') ?? '—') ?></span>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <small class="text-muted d-block">Raça(s)</small>
                        <span class="fw-semibold"><?= htmlspecialchars($animal->__get('racas') ?? '—') ?></span>
                    </div>
                </div>
 
                <!-- Adotante -->
                <h5 class="text-primary border-bottom pb-2 mb-3">Adotante</h5>
                <div class="row mb-4">
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted d-block">Nome</small>
                        <!--<span class="fw-semibold">< ?= htmlspecialchars($adotante->__get('adt_nome')) ?></span>-->
                    </div>
                    <div class="col-sm-3 mb-3">
                        <small class="text-muted d-block">CPF</small>
                        <!--<span class="fw-semibold">< ?= htmlspecialchars($adotante->__get('adt_cpf') ?? '—') ?></span>-->
                    </div>
                    <div class="col-sm-3 mb-3">
                        <small class="text-muted d-block">Telefone</small>
                        <!--<span class="fw-semibold">< ?= htmlspecialchars($adotante->__get('adt_tel1') ?? '—') ?></span>-->
                    </div>
                </div>
 
                <!-- Motivo -->
                <h5 class="text-primary border-bottom pb-2 mb-3">Motivo</h5>
                <div class="row mb-4">
                    <div class="col-12">
                        <label for="solAdc_motivo" class="form-label">Motivo da Solicitação</label>
                        <textarea class="form-control" id="motivo" name="motivo" rows="4"
                            placeholder="Descreva o motivo pelo qual deseja adotar este animal..."></textarea>
                        <div class="form-text">Campo opcional. Máximo de 1000 caracteres.</div>
                    </div>
                </div>
 
                <!-- Botões -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-hand-holding-heart"></i> Confirmar Solicitação
                    </button>
                    <a href="/dashboard/animal/mostrar/<?= htmlspecialchars($animal->__get('id')) ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
 
            </form>
        </div>
    </div>
</div>
 
<script>
/*document.getElementById('solAdc_motivo').addEventListener('input', function () {
    var max  = 1000;
    var atual = this.value.length;
    if (atual > max) {
        this.value = this.value.substring(0, max);
        atual = max;
    }
    this.nextElementSibling.textContent = 'Campo opcional. ' + atual + '/' + max + ' caracteres.';
});*/
</script>