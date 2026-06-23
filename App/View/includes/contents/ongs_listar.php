<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$ongs = $this->ongs ?? [];
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-secondary">Gerenciar ONGs</h5>
                        <a href="/ongs-cadastrar" class="btn btn-primary">
                            <i data-lucide="plus" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;"></i>
                            Nova ONG
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_GET['erro'])): ?>
                            <div class="alert alert-danger">
                                <?php if ($_GET['erro'] === 'has_animals'): ?>
                                    Não é possível excluir esta ONG pois possui animais vinculados.
                                <?php else: ?>
                                    Erro ao processar a operação.
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (empty($ongs)): ?>
                            <div class="text-center py-4">
                                <p class="text-muted">Nenhuma ONG cadastrada.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light text-secondary">
                                        <tr style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <th>ID</th>
                                            <th>Nome</th>
                                            <th>CNPJ</th>
                                            <th>Telefone</th>
                                            <th>Cidade/Estado</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ongs as $ong): ?>
                                            <tr>
                                                <td><?php echo $ong['id']; ?></td>
                                                <td><?php echo htmlspecialchars($ong['nome']); ?></td>
                                                <td><?php echo htmlspecialchars($ong['cnpj']); ?></td>
                                                <td><?php echo htmlspecialchars($ong['telefone_1']); ?></td>
                                                <td><?php echo htmlspecialchars($ong['cidade'] . '/' . $ong['estado']); ?></td>
                                                <td>
                                                    <span class="badge <?php echo $ong['status'] === 'a' ? 'bg-success' : 'bg-danger'; ?>">
                                                        <?php echo $ong['status'] === 'a' ? 'Ativo' : 'Inativo'; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="/ongs-editar?id=<?php echo $ong['id']; ?>" class="btn btn-sm btn-outline-primary me-1">
                                                        <i data-lucide="edit" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle;"></i>
                                                    </a>
                                                    <a href="/ongs-excluir?id=<?php echo $ong['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir esta ONG?');">
                                                        <i data-lucide="trash-2" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle;"></i>
                                                    </a>
                                                </td>
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
lucide.createIcons();
</script>
