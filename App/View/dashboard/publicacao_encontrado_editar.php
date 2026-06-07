<?php
$publicacao = $this->getView()->publicacao;
$animais    = $this->getView()->animais;
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Editar Publicação de Animal Encontrado</h1>
        <a href="/dashboard/publicacao/listar" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">

            <form method="POST" action="/dashboard/publicacao/alterar">

                <input type="hidden"
                       name="id"
                       value="<?= htmlspecialchars($publicacao->__get('id')) ?>">

                <!-- Animal -->
                <h5 class="mb-3 text-primary">Animal Encontrado</h5>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="fk_animal_id" class="form-label">
                            Animal <span class="text-danger">*</span>
                        </label>

                        <select class="form-select"
                                id="fk_animal_id"
                                name="fk_animal_id"
                                required>

                            <option value="">Selecione um animal</option>

                            <?php foreach ($animais as $animal): ?>
                                <option value="<?= $animal->__get('id') ?>"
                                    <?= (int)$publicacao->__get('fk_animal_id') === (int)$animal->__get('id')
                                        ? 'selected'
                                        : '' ?>>
                                    <?= htmlspecialchars($animal->__get('nome')) ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                </div>

                <!-- Informações -->
                <h5 class="mb-3 text-primary">Informações do Encontro</h5>

                <div class="row g-3 mb-4">

                    <div class="col-md-4">
                        <label for="data_encontro" class="form-label">
                            Data de Encontro
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               class="form-control"
                               id="data_encontro"
                               name="data_encontro"
                               required
                               value="<?= htmlspecialchars($publicacao->__get('data_encontro')) ?>">
                    </div>

                    <div class="col-md-12">
                        <label for="condicao_fisica" class="form-label">
                            Condição Física
                            <span class="text-danger">*</span>
                        </label>

                        <textarea class="form-control"
                                  id="condicao_fisica"
                                  name="condicao_fisica"
                                  rows="3"
                                  required><?= htmlspecialchars($publicacao->__get('condicao_fisica')) ?></textarea>
                    </div>

                    <div class="col-md-12">
                        <label for="acoes_realizadas" class="form-label">
                            Ações Realizadas
                        </label>

                        <textarea class="form-control"
                                  id="acoes_realizadas"
                                  name="acoes_realizadas"
                                  rows="3"><?= htmlspecialchars($publicacao->__get('acoes_realizadas')) ?></textarea>
                    </div>

                </div>

                <!-- Status -->
                <h5 class="mb-3 text-primary">Status</h5>

                <div class="row g-3 mb-4">

                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>

                        <select class="form-select"
                                id="status"
                                name="status">

                            <option value="aguardando acolhimento"
                                <?= $publicacao->__get('status') == 'aguardando acolhimento' ? 'selected' : '' ?>>
                                Aguardando Acolhimento
                            </option>

                            <option value="acolhido"
                                <?= $publicacao->__get('status') == 'acolhido' ? 'selected' : '' ?>>
                                Acolhido
                            </option>

                            <option value="em análise"
                                <?= $publicacao->__get('status') == 'em análise' ? 'selected' : '' ?>>
                                Em Análise
                            </option>

                        </select>
                    </div>

                </div>

                <!-- Botões -->
                <div class="d-flex gap-2">

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar
                    </button>

                    <a href="/dashboard/publicacao/listar"
                       class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>

                </div>

            </form>

        </div>
    </div>
</div>