<?php
$animal = $this->getView()->animal;

$idadeMeses = (int) $animal->__get('idade_meses');
if ($idadeMeses < 1) {
    $idadeTexto = 'Menos de 1 mês';
} elseif ($idadeMeses < 12) {
    $idadeTexto = $idadeMeses . ' ' . ($idadeMeses === 1 ? 'mês' : 'meses');
} else {
    $anos  = intdiv($idadeMeses, 12);
    $meses = $idadeMeses % 12;
    $idadeTexto = $anos . ' ' . ($anos === 1 ? 'ano' : 'anos');
    if ($meses > 0) $idadeTexto .= ' e ' . $meses . ' ' . ($meses === 1 ? 'mês' : 'meses');
}

$sexoLabel    = strtolower($animal->__get('sexo')) === 'm' ? 'Macho' : 'Fêmea';
$castradoLabel = $animal->__get('castrado') ? 'Sim' : 'Não';

$porteMap = ['pequeno' => 'Pequeno', 'medio' => 'Médio', 'grande' => 'Grande'];
$porteLabel = $porteMap[$animal->__get('porte')] ?? $animal->__get('porte') ?? '—';

$foto = $animal->__get('foto');
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Detalhes do Animal</h1>
        <a href="/dashboard/animal/listar" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">

            <!-- Foto -->
            <?php if ($foto): ?>
            <div class="text-center mb-4">
                <img src="<?= htmlspecialchars($foto) ?>"
                     alt="Foto de <?= htmlspecialchars($animal->__get('nome')) ?>"
                     class="rounded"
                     style="max-height: 280px; max-width: 100%; object-fit: cover;">
            </div>
            <?php endif; ?>

            <!-- Identificação -->
            <h5 class="text-primary border-bottom pb-2 mb-3">Identificação</h5>
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

            <!-- Características -->
            <h5 class="text-primary border-bottom pb-2 mb-3">Características</h5>
            <div class="row mb-4">
                <div class="col-sm-4 col-md-2 mb-3">
                    <small class="text-muted d-block">Sexo</small>
                    <span class="fw-semibold"><?= $sexoLabel ?></span>
                </div>
                <div class="col-sm-4 col-md-2 mb-3">
                    <small class="text-muted d-block">Porte</small>
                    <span class="fw-semibold"><?= $porteLabel ?></span>
                </div>
                <div class="col-sm-4 col-md-2 mb-3">
                    <small class="text-muted d-block">Cor</small>
                    <span class="fw-semibold"><?= htmlspecialchars($animal->__get('cor') ?? '—') ?></span>
                </div>
                <div class="col-sm-4 col-md-2 mb-3">
                    <small class="text-muted d-block">Castrado</small>
                    <span class="fw-semibold"><?= $castradoLabel ?></span>
                </div>
                <div class="col-sm-4 col-md-2 mb-3">
                    <small class="text-muted d-block">Nascimento</small>
                    <span class="fw-semibold"><?= htmlspecialchars($animal->__get('data_nascimento') ?? '—') ?></span>
                </div>
                <div class="col-sm-4 col-md-2 mb-3">
                    <small class="text-muted d-block">Idade</small>
                    <span class="fw-semibold"><?= $idadeTexto ?></span>
                </div>
                <div class="col-sm-8 mb-3">
                    <small class="text-muted d-block">Localização</small>
                    <span class="fw-semibold"><?= htmlspecialchars($animal->__get('localizacao') ?? '—') ?></span>
                </div>
            </div>

            <!-- Descrição -->
            <?php if ($animal->__get('descricao')): ?>
            <h5 class="text-primary border-bottom pb-2 mb-3">Descrição</h5>
            <div class="row mb-4">
                <div class="col-12">
                    <p class="mb-0"><?= nl2br(htmlspecialchars($animal->__get('descricao'))) ?></p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Situação -->
            <h5 class="text-primary border-bottom pb-2 mb-3">Situação</h5>
            <div class="row mb-4">
                <div class="col-sm-4 mb-3">
                    <small class="text-muted d-block">Status</small>
                    <span class="fw-semibold"><?= htmlspecialchars($animal->__get('status') ?? '—') ?></span>
                </div>
                <div class="col-sm-8 mb-3">
                    <small class="text-muted d-block">ONG Responsável</small>
                    <span class="fw-semibold"><?= htmlspecialchars($animal->__get('ong_nome') ?? '—') ?></span>
                </div>
            </div>

            <!-- Botões -->
            <div class="d-flex gap-2">
                <a href="/dashboard/adocao/solicitacao/<?= htmlspecialchars($animal->__get('id')) ?>" class="btn btn-success">
                    <i class="fas fa-hand-holding-heart"></i> Solicitar Adoção
                </a>
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
