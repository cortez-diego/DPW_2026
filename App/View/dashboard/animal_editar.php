<?php
$animal = $this->getView()->animal;
$especies = $this->getView()->especies;
$racas = $this->getView()->racas;
$racasAll = $this->getView()->racasAll ?? [];
$racasVinculadas = $this->getView()->racasVinculadas ?? [];
?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Editar Animal</h1>
            <p class="text-muted mb-0">Atualize todas as informações do animal e salve.</p>
        </div>
        <a href="/App/View/manage_animais.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="/dashboard/animal/alterar" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars((string) $animal->__get('id')) ?>">
                <input type="hidden" name="foto_atual" value="<?= htmlspecialchars((string) $animal->__get('foto')) ?>">

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Foto atual</label>
                        <div class="border rounded p-2 text-center">
                            <img src="<?= htmlspecialchars((string) $animal->__get('foto')) ?>" alt="Foto do animal" class="img-fluid rounded" style="max-height: 220px; object-fit: cover;">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" required value="<?= htmlspecialchars((string) $animal->__get('nome')) ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="disponivel" <?= $animal->__get('status') === 'disponivel' ? 'selected' : '' ?>>Disponível</option>
                                    <option value="reservado" <?= $animal->__get('status') === 'reservado' ? 'selected' : '' ?>>Reservado</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="fk_especie_id" class="form-label">Espécie</label>
                                <select class="form-select" id="fk_especie_id" name="fk_especie_id">
                                    <option value="">-- selecione --</option>
                                    <?php foreach ($this->getView()->especies as $esp): ?>
                                        <option value="<?= (int) $esp->__get('id') ?>" <?= (int)$animal->__get('fk_especie_id') === (int)$esp->__get('id') ? 'selected' : '' ?>><?= htmlspecialchars($esp->__get('nome')) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="fk_raca_id" class="form-label">Raça</label>
                                <select class="form-select" id="fk_raca_id" name="fk_raca_id">
                                    <option value="">-- selecione --</option>
                                    <?php foreach ($this->getView()->racasAll as $rc): ?>
                                        <option value="<?= (int) $rc->__get('id') ?>" data-especie="<?= (int) $rc->__get('fk_especie_id') ?>" <?= in_array((int)$rc->__get('id'), $this->getView()->racasVinculadas, true) ? 'selected' : '' ?>><?= htmlspecialchars($rc->__get('nome')) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="porte" class="form-label">Porte</label>
                                <select class="form-select" id="porte" name="porte">
                                    <option value="pequeno" <?= $animal->__get('porte') === 'pequeno' ? 'selected' : '' ?>>Pequeno</option>
                                    <option value="medio" <?= $animal->__get('porte') === 'medio' ? 'selected' : '' ?>>Médio</option>
                                    <option value="grande" <?= $animal->__get('porte') === 'grande' ? 'selected' : '' ?>>Grande</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="sexo" class="form-label">Sexo</label>
                                <select class="form-select" id="sexo" name="sexo">
                                    <option value="m" <?= $animal->__get('sexo') === 'm' ? 'selected' : '' ?>>Macho</option>
                                    <option value="f" <?= $animal->__get('sexo') === 'f' ? 'selected' : '' ?>>Fêmea</option>
                                    <option value="n/a" <?= $animal->__get('sexo') === 'n/a' ? 'selected' : '' ?>>N/A</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="<?= htmlspecialchars((string) $animal->__get('data_nascimento')) ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="cor" class="form-label">Cor</label>
                                <input type="text" class="form-control" id="cor" name="cor" value="<?= htmlspecialchars((string) $animal->__get('cor')) ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="localizacao" class="form-label">Localização</label>
                                <input type="text" class="form-control" id="localizacao" name="localizacao" value="<?= htmlspecialchars((string) $animal->__get('localizacao')) ?>">
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="castrado" name="castrado" value="1" <?= $animal->__get('castrado') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="castrado">Castrado</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="3"><?= htmlspecialchars((string) $animal->__get('descricao')) ?></textarea>
                            </div>

                            <div class="col-md-6">
                                <label for="foto" class="form-label">Nova imagem</label>
                                <input class="form-control" type="file" id="foto" name="foto" accept="image/*">
                                <small class="form-text text-muted">Envie uma nova foto para substituir a atual, ou deixe em branco para manter.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar alterações
                    </button>
                    <a href="/App/View/manage_animais.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function(){
    var especieSelect = document.getElementById('fk_especie_id');
    var racaSelect = document.getElementById('fk_raca_id');
    var raceOptions = Array.from(racaSelect.options);

    function filterRacas() {
        var selectedEspecie = especieSelect.value;
        raceOptions.forEach(function(opt){
            if (!opt.value) { opt.style.display = ''; return; }
            var optionEspecie = opt.dataset.especie || '';
            if (!selectedEspecie || String(optionEspecie) === String(selectedEspecie)) {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
            }
        });
        if (racaSelect.value) {
            var current = racaSelect.selectedOptions[0];
            if (current && current.style.display === 'none') {
                racaSelect.value = '';
            }
        }
    }

    if (especieSelect) {
        especieSelect.addEventListener('change', filterRacas);
        filterRacas();
    }
})();
</script>
