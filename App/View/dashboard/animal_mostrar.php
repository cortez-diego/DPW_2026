<?php
$animal = $this->getView()->animal;

// Helper: converte meses em texto legível
$idadeMeses = (int) $animal->__get('idade_meses');
if ($idadeMeses < 1) {
    $idadeTexto = 'Menos de 1 mês';
} elseif ($idadeMeses < 12) {
    $idadeTexto = $idadeMeses . ' ' . ($idadeMeses === 1 ? 'mês' : 'meses');
} else {
    $anos = intdiv($idadeMeses, 12);
    $meses = $idadeMeses % 12;
    $idadeTexto = $anos . ' ' . ($anos === 1 ? 'ano' : 'anos');
    if ($meses > 0) {
        $idadeTexto .= ' e ' . $meses . ' ' . ($meses === 1 ? 'mês' : 'meses');
    }
}

$castrado = $animal->__get('castrado') ? 'Sim' : 'Não';
$foto     = $animal->__get('foto');
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Detalhes do Animal</h1>
        <a href="/dashboard/animal/listar" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">

            <!-- Foto -->
            <?php if ($foto): ?>
            <div class="text-center mb-4">
                <img src="<?= htmlspecialchars($foto) ?>"
                     alt="Foto de <?= htmlspecialchars($animal->__get('nome')) ?>"
                     class="img-fluid rounded"
                     style="max-height: 300px; object-fit: cover;">
            </div>
            <?php endif; ?>

            <!-- Identificação -->
            <h5 class="mb-3 text-primary">Identificação</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Nome</label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($animal->__get('nome')) ?></p>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Espécie</label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($animal->__get('especie_nome') ?? '—') ?></p>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Raça(s)</label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($animal->__get('racas') ?? '—') ?></p>
                </div>
            </div>

            <!-- Características -->
            <h5 class="mb-3 text-primary">Características</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Sexo</label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($animal->__get('sexo')) ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Porte</label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($animal->__get('porte') ?? '—') ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Cor</label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($animal->__get('cor') ?? '—') ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Castrado</label>
                    <p class="form-control-plaintext"><?= $castrado ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Data de Nascimento</label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($animal->__get('data_nascimento') ?? '—') ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Idade</label>
                    <p class="form-control-plaintext"><?= $idadeTexto ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Localização</label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($animal->__get('localizacao') ?? '—') ?></p>
                </div>
            </div>

            <!-- Descrição -->
            <?php if ($animal->__get('descricao')): ?>
            <h5 class="mb-3 text-primary">Descrição</h5>
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <p class="form-control-plaintext"><?= nl2br(htmlspecialchars($animal->__get('descricao'))) ?></p>
                </div>
            </div>
            <?php endif; ?>

            <!-- ONG e Status -->
            <h5 class="mb-3 text-primary">Situação</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status</label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($animal->__get('status') ?? '—') ?></p>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold">ONG Responsável</label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($animal->__get('ong_nome') ?? '—') ?></p>
                </div>
            </div>

            <!-- Botões -->
            <div class="d-flex gap-2">
                <a href="/dashboard/animal/editar/<?= htmlspecialchars($animal->__get('id')) ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="/dashboard/animal/listar" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Fechar
                </a>
            </div>

        </div>
    </div>
</div>
