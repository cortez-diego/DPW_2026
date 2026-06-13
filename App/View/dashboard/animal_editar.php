<?php
include_once __DIR__ . '/../includes/dashboard/schema_check.php';

$animal = $this->getView()->animal;
$especies = $this->getView()->especies;
$racas = $this->getView()->racas;
$racasAll = $this->getView()->racasAll ?? [];
$racasVinculadas = $this->getView()->racasVinculadas ?? [];
$imagens = $this->getView()->imagens ?? ['', '', '', '', ''];
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
                                <div class="input-group">
                                    <select class="form-select" id="fk_especie_id" name="fk_especie_id">
                                        <option value="">-- selecione --</option>
                                        <?php foreach ($this->getView()->especies as $esp): ?>
                                            <option value="<?= (int) $esp->__get('id') ?>" <?= (int)$animal->__get('fk_especie_id') === (int)$esp->__get('id') ? 'selected' : '' ?>><?= htmlspecialchars($esp->__get('nome')) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-outline-secondary" type="button" id="btnNewEspecie">+</button>
                                </div>
                                <input class="form-control mt-2 d-none" id="new_especie" placeholder="Nova espécie">
                                <button class="btn btn-sm btn-primary mt-2 d-none" type="button" id="btnAddEspecie">Adicionar</button>
                            </div>
                            <div class="col-md-6">
                                <label for="fk_raca_id" class="form-label">Raça</label>
                                <div class="input-group">
                                    <select class="form-select" id="fk_raca_id" name="fk_raca_id">
                                        <option value="">-- selecione --</option>
                                        <?php foreach ($this->getView()->racasAll as $rc): ?>
                                            <option value="<?= (int) $rc->__get('id') ?>" data-especie="<?= (int) $rc->__get('fk_especie_id') ?>" <?= in_array((int)$rc->__get('id'), $this->getView()->racasVinculadas, true) ? 'selected' : '' ?>><?= htmlspecialchars($rc->__get('nome')) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-outline-secondary" type="button" id="btnNewRaca">+</button>
                                </div>
                                <input class="form-control mt-2 d-none" id="new_raca" placeholder="Nova raça">
                                <select class="form-select mt-2 d-none" id="new_raca_especie_id">
                                    <option value="">-- espécie da raça --</option>
                                    <?php foreach ($this->getView()->especies as $esp): ?>
                                        <option value="<?= (int) $esp->__get('id') ?>"><?= htmlspecialchars($esp->__get('nome')) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn btn-sm btn-primary mt-2 d-none" type="button" id="btnAddRaca">Adicionar</button>
                            </div>

                            <div class="col-md-6">
                                <label for="porte" class="form-label">Porte</label>
                                <div class="input-group">
                                    <select class="form-select" id="porte" name="porte">
                                        <option value="pequeno" <?= $animal->__get('porte') === 'pequeno' ? 'selected' : '' ?>>Pequeno</option>
                                        <option value="medio" <?= $animal->__get('porte') === 'medio' ? 'selected' : '' ?>>Médio</option>
                                        <option value="grande" <?= $animal->__get('porte') === 'grande' ? 'selected' : '' ?>>Grande</option>
                                    </select>
                                    <button class="btn btn-outline-secondary" type="button" id="btnNewPorte">+</button>
                                </div>
                                <input class="form-control mt-2 d-none" id="new_porte" placeholder="Novo porte (opcional)">
                                <button class="btn btn-sm btn-primary mt-2 d-none" type="button" id="btnAddPorte">Adicionar</button>
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

                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label class="form-label">Imagens do animal (5 imagens, selecione a principal)</label>
                        <div class="row g-3">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <?php $previewSrc = !empty($imagens[$i]) ? htmlspecialchars($imagens[$i]) : 'https://via.placeholder.com/300?text=Upload'; ?>
                                <div class="col-6 col-xl-3">
                                    <div class="border rounded p-3 text-center" style="aspect-ratio: 1 / 1; display: flex; flex-direction: column; justify-content: space-between;">
                                        <div>
                                            <small class="text-muted">Imagem <?= $i + 1 ?></small>
                                            <img id="preview-<?= $i ?>" src="<?= $previewSrc ?>" alt="Preview <?= $i + 1 ?>" class="img-fluid rounded mb-2" style="width:100%; height:120px; object-fit:cover;">
                                            <input class="form-control form-control-sm" type="file" id="imagem_<?= $i ?>" name="imagem_<?= $i ?>" accept="image/*" data-preview-target="preview-<?= $i ?>" data-cropped-target="cropped_imagem_<?= $i ?>">
                                            <input type="hidden" id="cropped_imagem_<?= $i ?>" name="cropped_imagem_<?= $i ?>" value="">
                                            <input type="hidden" id="imagem_existente_<?= $i ?>" name="imagem_existente_<?= $i ?>" value="<?= !empty($imagens[$i]) ? htmlspecialchars($imagens[$i]) : '' ?>">
                                        </div>
                                        <div class="form-check mt-2 text-start">
                                            <input class="form-check-input imagem-principal-radio" type="radio" id="imagem_principal_<?= $i ?>" name="imagem_principal" value="<?= $i ?>" <?= $i === 0 ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="imagem_principal_<?= $i ?>">Principal</label>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                        <div class="form-text">Envie até 5 imagens do animal. A primeira é definida como principal por padrão. Selecione outra para alterar a principal em tempo real.</div>
                    </div>
                </div>

                <div class="mb-3">
                    <div id="formDebugStatus" class="small text-muted">Debug de envio: nenhum envio ainda.</div>
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

<!-- Modal de recorte simples -->
<div id="cropModal" class="modal fade" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cropModalLabel">Recorte de imagem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body text-center">
                <p>Arraste/zoom e ajuste a seleção quadrada. Confirme para aplicar o recorte.</p>
                <div class="crop-preview position-relative mx-auto" style="max-width: 700px;">
                    <img id="cropPreviewImage" src="" alt="Preview de recorte" class="img-fluid rounded" style="max-width:100%; display:block; margin:0 auto;">
                </div>
                <div id="cropStatus" class="mt-3 small text-muted">Aguardando imagem...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="cropConfirmBtn" class="btn btn-primary">Confirmar recorte</button>
            </div>
        </div>
    </div>
</div>

<!-- Cropper.js -->
<link rel="stylesheet" href="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.css">
<script src="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var especieSelect = document.getElementById('fk_especie_id');
    var racaSelect = document.getElementById('fk_raca_id');
    var raceOptions = racaSelect ? Array.from(racaSelect.options) : [];

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

    var cropPreviewImage = document.getElementById('cropPreviewImage');
    var currentHiddenInput = null;

    function getCropModal() {
        var cropModalElement = document.getElementById('cropModal');
        if (!cropModalElement || typeof bootstrap === 'undefined' || !bootstrap.Modal) {
            return null;
        }
        return new bootstrap.Modal(cropModalElement, { backdrop: 'static', keyboard: false });
    }

    var cropModal = getCropModal();
    var cropper = null;
    var currentInput = null; // referência ao input de arquivo que acionou o modal
    var currentPreview = null; // elemento <img> que deve ser atualizado após confirmar

    // Handler para os 5 campos de imagem
    document.querySelectorAll('input[name^="imagem_"]').forEach(function(input) {
        input.addEventListener('change', function() {
            if (!this.files || !this.files[0]) {
                return;
            }
            var targetId = this.dataset.previewTarget;
            var preview = targetId ? document.getElementById(targetId) : null;
            if (!preview) {
                return;
            }
            currentInput = this;
            currentPreview = preview;
            var croppedTargetId = this.dataset.croppedTarget;
            currentHiddenInput = croppedTargetId ? document.getElementById(croppedTargetId) : null;
            if (currentHiddenInput) {
                currentHiddenInput.value = '';
            }
            var reader = new FileReader();
            reader.onload = function(e) {
                if (cropPreviewImage) {
                    cropPreviewImage.src = e.target.result;
                }
                if (cropModal) {
                    cropModal.show();
                }
            };
            reader.readAsDataURL(this.files[0]);
        });
    });

    // Inicializa/destrói Cropper ao mostrar/ocultar modal (se existir)
    var cropModalElement = document.getElementById('cropModal');
    if (cropModalElement) {
        cropModalElement.addEventListener('shown.bs.modal', function () {
            if (!cropPreviewImage) return;
            var status = document.getElementById('cropStatus');
            if (status) {
                status.textContent = 'Inicializando recorte...';
            }

            function initCropper() {
                if (typeof Cropper === 'undefined') {
                    if (status) {
                        status.textContent = 'Cropper ainda não carregado. Tentando novamente...';
                    }
                    return false;
                }
                if (cropper) {
                    try { cropper.destroy(); } catch (e) {}
                    cropper = null;
                }
                cropper = new Cropper(cropPreviewImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                    background: false,
                    autoCropArea: 0.9,
                    responsive: true,
                    movable: true,
                    zoomable: true,
                    rotatable: false,
                    scalable: false,
                });
                if (status) {
                    status.textContent = 'Arraste/zoom para ajustar o recorte e clique em Confirmar recorte.';
                }
                return true;
            }

            if (cropPreviewImage.complete && cropPreviewImage.naturalWidth !== 0) {
                if (!initCropper()) {
                    setTimeout(initCropper, 150);
                }
            } else {
                cropPreviewImage.onload = function () {
                    if (!initCropper()) {
                        setTimeout(initCropper, 150);
                    }
                    cropPreviewImage.onload = null;
                };
            }
        });

        cropModalElement.addEventListener('hidden.bs.modal', function () {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            // Se cancelou o recorte (não confirmou), limpar o campo de upload
            if (currentInput && !currentHiddenInput.value) {
                currentInput.value = '';
                if (currentPreview) {
                    // Restaurar preview original se existir
                    var originalSrc = currentPreview.dataset.originalSrc;
                    if (originalSrc) {
                        currentPreview.src = originalSrc;
                    }
                }
            }
            // limpar referência ao input atual para evitar efeitos colaterais
            currentInput = null;
            currentPreview = null;
            currentHiddenInput = null;
        });
    }

    // Salvar preview original antes de alterar
    document.querySelectorAll('input[name^="imagem_"]').forEach(function(input) {
        var targetId = input.dataset.previewTarget;
        var preview = targetId ? document.getElementById(targetId) : null;
        if (preview) {
            preview.dataset.originalSrc = preview.src;
        }
    });

    // Handler para seleção de imagem principal em tempo real
    document.querySelectorAll('.imagem-principal-radio').forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (this.checked) {
                console.log('Imagem principal selecionada: ' + this.value);
            }
        });
    });

    // Botões Adicionar para espécie, raça e porte
    var btnNewEspecie = document.getElementById('btnNewEspecie');
    var newEspecie = document.getElementById('new_especie');
    var btnAddEspecie = document.getElementById('btnAddEspecie');
    var especieSelect = document.getElementById('fk_especie_id');

    if (btnNewEspecie && newEspecie) btnNewEspecie.addEventListener('click', function(){
        newEspecie.classList.toggle('d-none');
        if (btnAddEspecie) btnAddEspecie.classList.toggle('d-none');
    });

    if (btnAddEspecie && newEspecie && especieSelect) btnAddEspecie.addEventListener('click', function(){
        var val = newEspecie.value.trim();
        if (!val) return;
        var opt = document.createElement('option');
        opt.value = val;
        opt.text = val;
        opt.selected = true;
        especieSelect.appendChild(opt);
        newEspecie.value = '';
        newEspecie.classList.add('d-none');
        btnAddEspecie.classList.add('d-none');
    });

    var btnNewRaca = document.getElementById('btnNewRaca');
    var newRaca = document.getElementById('new_raca');
    var newRacaEsp = document.getElementById('new_raca_especie_id');
    var btnAddRaca = document.getElementById('btnAddRaca');
    var raceSelect = document.getElementById('fk_raca_id');

    if (btnNewRaca && newRaca) btnNewRaca.addEventListener('click', function(){
        newRaca.classList.toggle('d-none');
        if (newRacaEsp) newRacaEsp.classList.toggle('d-none');
        if (btnAddRaca) btnAddRaca.classList.toggle('d-none');
        if (newRacaEsp && especieSelect && especieSelect.value) newRacaEsp.value = especieSelect.value;
    });

    if (btnAddRaca && newRaca && newRacaEsp && raceSelect) btnAddRaca.addEventListener('click', function(){
        var val = newRaca.value.trim();
        var espId = newRacaEsp.value;
        if (!val || !espId) return;
        var opt = document.createElement('option');
        opt.value = val;
        opt.text = val;
        opt.setAttribute('data-especie', espId);
        opt.selected = true;
        raceSelect.appendChild(opt);
        newRaca.value = '';
        newRaca.classList.add('d-none');
        newRacaEsp.classList.add('d-none');
        btnAddRaca.classList.add('d-none');
        filterRacas();
    });

    var btnNewPorte = document.getElementById('btnNewPorte');
    var newPorte = document.getElementById('new_porte');
    var btnAddPorte = document.getElementById('btnAddPorte');
    var porteSelect = document.getElementById('porte');

    if (btnNewPorte && newPorte) btnNewPorte.addEventListener('click', function(){
        newPorte.classList.toggle('d-none');
        if (btnAddPorte) btnAddPorte.classList.toggle('d-none');
        if (!newPorte.classList.contains('d-none')) newPorte.focus();
        else newPorte.value = '';
    });

    if (btnAddPorte && newPorte && porteSelect) btnAddPorte.addEventListener('click', function(){
        var val = newPorte.value.trim();
        if (!val) return;
        var opt = document.createElement('option');
        opt.value = val;
        opt.text = val;
        opt.selected = true;
        porteSelect.appendChild(opt);
        newPorte.value = '';
        newPorte.classList.add('d-none');
        btnAddPorte.classList.add('d-none');
    });

    // Confirmar recorte: gerar blob, substituir arquivo do input e atualizar preview
    var cropConfirmBtn = document.getElementById('cropConfirmBtn');
    if (cropConfirmBtn) {
        cropConfirmBtn.addEventListener('click', function () {
            var status = document.getElementById('cropStatus');
            if (!cropper || !currentInput) {
                if (status) {
                    status.textContent = 'Recorte não inicializado. Verifique se o Cropper.js foi carregado corretamente.';
                }
                return;
            }
            // cria canvas quadrado com tamanho máximo de 1000px
            var canvas = cropper.getCroppedCanvas({ width: 800, height: 800, imageSmoothingQuality: 'high' });
            if (!canvas) {
                if (status) {
                    status.textContent = 'Não foi possível gerar o canvas de recorte.';
                }
                return;
            }
            canvas.toBlob(function (blob) {
                if (!blob) {
                    if (status) {
                        status.textContent = 'Falha ao gerar blob de imagem recortada.';
                    }
                    return;
                }
                var filename = (currentInput.name === 'foto') ? ('crop_' + Date.now() + '.jpg') : ('gallery_' + Date.now() + '.jpg');
                try {
                    var file = new File([blob], filename, { type: 'image/jpeg' });
                } catch (e) {
                    // fallback para navegadores antigos
                    var file = blob;
                    file.name = filename;
                }

                // substituir FileList do input via DataTransfer
                var dt = new DataTransfer();
                dt.items.add(file);
                currentInput.files = dt.files;

                // atualizar preview imediato com URL do blob
                var objectUrl = URL.createObjectURL(blob);
                if (currentPreview) {
                    currentPreview.src = objectUrl;
                }

                if (currentHiddenInput) {
                    currentHiddenInput.value = canvas.toDataURL('image/jpeg', 0.9);
                }

                if (status) {
                    status.textContent = 'Imagem recortada e pronta para enviar.';
                }

                // fechar modal (o modal listener destruirá o cropper)
                if (cropModal) cropModal.hide();
            }, 'image/jpeg', 0.9);
        });
    }

    // Depuração do formulário antes do envio
    var animalEditForm = document.querySelector('form[action="/dashboard/animal/alterar"]');
    if (animalEditForm) {
        animalEditForm.addEventListener('submit', function (event) {
            var formDebug = document.getElementById('formDebugStatus');
            var imagemFiles = Array.from(document.querySelectorAll('input[name^="imagem_"]')).reduce(function (sum, input) {
                return sum + (input.files ? input.files.length : 0);
            }, 0);
            var principalSelected = document.querySelector('input[name="imagem_principal"]:checked');
            var principalIndex = principalSelected ? principalSelected.value : '0';
            var debugText = 'Enviando form: imagens=' + imagemFiles + ', principal=' + principalIndex + '.';
            if (formDebug) {
                formDebug.textContent = debugText;
            }
            console.log(debugText);
        });
    }
});
</script>
