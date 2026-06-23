<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get historico from controller
$historico = $this->historico ?? [];
$searchTerm = $_GET['search'] ?? '';
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 text-secondary">Histórico de Avaliações Veterinárias</h5>
                    </div>
                    <div class="card-body">
                        <!-- Search bar -->
                        <form method="GET" action="/historico-medico" class="mb-4">
                            <div class="row">
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="search" name="search" 
                                           placeholder="Buscar por animal, espécie, raça, diagnóstico, tratamento ou ONG..." 
                                           value="<?php echo htmlspecialchars($searchTerm); ?>">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i data-lucide="search" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;"></i>
                                        Buscar
                                    </button>
                                </div>
                            </div>
                        </form>

                        <?php if (empty($historico)): ?>
                            <div class="text-center py-4">
                                <p class="text-muted">
                                    <?php if ($searchTerm): ?>
                                        Nenhuma avaliação encontrada para a busca "<?php echo htmlspecialchars($searchTerm); ?>".
                                    <?php else: ?>
                                        Nenhuma avaliação registrada ainda.
                                    <?php endif; ?>
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light text-secondary">
                                        <tr style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <th>Data Avaliação</th>
                                            <th>Animal</th>
                                            <th>ONG</th>
                                            <th>Diagnóstico</th>
                                            <th>Tratamento</th>
                                            <th>Status</th>
                                            <th>Prioridade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($historico as $caso): ?>
                                            <?php
                                                $prioridadeClass = $caso['prioridade'] === 'urgente' ? 'bg-danger' : ($caso['prioridade'] === 'alta' ? 'bg-warning' : ($caso['prioridade'] === 'media' ? 'bg-info' : 'bg-secondary'));
                                                $statusClass = $caso['status'] === 'concluido' ? 'bg-success' : ($caso['status'] === 'em_avaliacao' ? 'bg-info' : 'bg-warning');
                                            ?>
                                            <tr>
                                                <td><?php echo date('d/m/Y H:i', strtotime($caso['data_avaliacao'])); ?></td>
                                                <td>
                                                    <?php echo htmlspecialchars($caso['animal_nome'] ?? 'N/A'); ?>
                                                    <br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($caso['especie'] ?? '') . ' (' . htmlspecialchars($caso['raca'] ?? '') . ')'; ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($caso['ong_nome'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($caso['diagnostico'] ? substr($caso['diagnostico'], 0, 50) . '...' : 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($caso['tratamento'] ? substr($caso['tratamento'], 0, 50) . '...' : 'N/A'); ?></td>
                                                <td><span class="badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($caso['status']); ?></span></td>
                                                <td><span class="badge <?php echo $prioridadeClass; ?>"><?php echo htmlspecialchars($caso['prioridade']); ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize Lucide icons
lucide.createIcons();
</script>
