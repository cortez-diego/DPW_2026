<?php
/**
 * Conteúdo da página de Relatórios
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../Data/animais_mock.php';

// Mock de doações (pode ser substituído por dados reais)
$doacoes = $_SESSION['doacoes'] ?? [
    'labels' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
    'values' => [1200, 900, 1500, 1800, 1600, 2100]
];

// Últimos animais adicionados (ordenar por id desc)
$sorted = $animaisSimulados;
usort($sorted, function($a, $b) { return $b['id'] <=> $a['id']; });
$latestAdded = array_slice($sorted, 0, 5);

$adocoes = $_SESSION['adocoes'] ?? [];

?>
<div class="main-content">
    <div class="container-fluid">
        <style>
            /* Fixar altura dos gráficos para evitar expansão infinita */
            .report-chart { height: 260px; position: relative; }
            .report-chart canvas { height: 100% !important; width: 100% !important; display: block; }
        </style>
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-0 fw-bold">Relatórios</h4>
                <small class="text-muted">Visão geral de doações, animais e adoções</small>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
         * Localização: ~/App/View/includes/contents/relatorios_content.php
                        <h6 class="fw-semibold">Doações (últimos meses)</h6>
                        <div class="report-chart">
                            <canvas id="donationsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-semibold">Distribuição por espécie</h6>
                        <div class="report-chart">
                            <canvas id="chartEspecies"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-semibold">Status de animais (Adotados vs Disponíveis)</h6>
                        <div class="report-chart">
                            <canvas id="chartStatus"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-semibold">Últimos animais adicionados</h6>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Imagem</th>
                                        <th>Nome</th>
                                        <th>Espécie</th>
                                        <th>Raça</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($latestAdded as $a): ?>
                                    <tr>
                                        <td><img src="<?php echo htmlspecialchars($a['imagem']); ?>" style="width:56px;height:40px;object-fit:cover;border-radius:6px;" onerror="this.src='https://via.placeholder.com/56'"/></td>
                                        <td class="fw-bold"><?php echo htmlspecialchars($a['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($a['especie']); ?></td>
                                        <td><?php echo htmlspecialchars($a['raca']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-semibold">Últimas adoções</h6>
                        <?php if (!empty($adocoes)): ?>
                            <div class="table-responsive">
                                <table class="table table-sm align-middle">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Animal</th>
                                            <th>Adotante</th>
                                            <th>ONG</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice(array_reverse($adocoes), 0, 8) as $ado): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($ado['data'] ?? '-'); ?></td>
                                                <td><?php echo htmlspecialchars($ado['animal_nome'] ?? '-'); ?></td>
                                                <td><?php echo htmlspecialchars($ado['adotante'] ?? '-'); ?></td>
                                                <td><?php echo htmlspecialchars($ado['ong'] ?? '-'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted small mb-0">Nenhuma adoção registrada ainda. As adoções aparecerão aqui quando houver dados.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa chart de doações usando dados PHP
    const donationsLabels = <?php echo json_encode($doacoes['labels']); ?>;
    const donationsValues = <?php echo json_encode($doacoes['values']); ?>;

    const elDon = document.getElementById('donationsChart');
    if (elDon) {
        new Chart(elDon.getContext('2d'), {
            type: 'line',
            data: {
                labels: donationsLabels,
                datasets: [{
                    label: 'Valor de Doações (R$)',
                    data: donationsValues,
                    backgroundColor: 'rgba(79, 195, 128, 0.15)',
                    borderColor: '#4FC380',
                    tension: 0.3,
                    fill: true,
                    pointRadius: 3
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // O footer global inicializa chartEspecies e chartStatus quando presentes
});
</script>
