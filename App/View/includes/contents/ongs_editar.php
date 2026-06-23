<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$ong = $this->ong ?? null;
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 text-secondary">Editar ONG</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_GET['erro'])): ?>
                            <div class="alert alert-danger">
                                Erro ao atualizar ONG. Tente novamente.
                            </div>
                        <?php endif; ?>

                        <?php if (!$ong): ?>
                            <div class="alert alert-danger">
                                ONG não encontrada.
                            </div>
                            <a href="/ongs" class="btn btn-secondary">Voltar</a>
                        <?php else: ?>
                            <form method="POST" action="/ongs-atualizar">
                                <input type="hidden" name="id" value="<?php echo $ong['id']; ?>">
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nome" class="form-label">Nome da ONG *</label>
                                        <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($ong['nome']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cnpj" class="form-label">CNPJ *</label>
                                        <input type="text" class="form-control" id="cnpj" name="cnpj" value="<?php echo htmlspecialchars($ong['cnpj']); ?>" maxlength="18" placeholder="00.000.000/0000-00" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="cep" class="form-label">CEP *</label>
                                        <input type="text" class="form-control" id="cep" name="cep" value="<?php echo htmlspecialchars($ong['cep']); ?>" maxlength="9" placeholder="00000-000" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="telefone_1" class="form-label">Telefone Principal *</label>
                                        <input type="text" class="form-control" id="telefone_1" name="telefone_1" value="<?php echo htmlspecialchars($ong['telefone_1']); ?>" maxlength="15" placeholder="(00) 00000-0000" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="telefone_2" class="form-label">Telefone Secundário</label>
                                        <input type="text" class="form-control" id="telefone_2" name="telefone_2" value="<?php echo htmlspecialchars($ong['telefone_2'] ?? ''); ?>" maxlength="15" placeholder="(00) 00000-0000">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="logradouro" class="form-label">Logradouro *</label>
                                        <input type="text" class="form-control" id="logradouro" name="logradouro" value="<?php echo htmlspecialchars($ong['logradouro']); ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="bairro" class="form-label">Bairro *</label>
                                        <input type="text" class="form-control" id="bairro" name="bairro" value="<?php echo htmlspecialchars($ong['bairro']); ?>" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="cidade" class="form-label">Cidade *</label>
                                        <input type="text" class="form-control" id="cidade" name="cidade" value="<?php echo htmlspecialchars($ong['cidade']); ?>" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="estado" class="form-label">Estado *</label>
                                        <input type="text" class="form-control" id="estado" name="estado" value="<?php echo htmlspecialchars($ong['estado']); ?>" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="complemento" class="form-label">Complemento</label>
                                    <textarea class="form-control" id="complemento" name="complemento" rows="3"><?php echo htmlspecialchars($ong['complemento'] ?? ''); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="a" <?php echo $ong['status'] === 'a' ? 'selected' : ''; ?>>Ativo</option>
                                        <option value="i" <?php echo $ong['status'] === 'i' ? 'selected' : ''; ?>>Inativo</option>
                                    </select>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="/ongs" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara CNPJ: 00.000.000/0000-00
    var cnpjInput = document.getElementById('cnpj');
    if (cnpjInput) {
        cnpjInput.addEventListener('input', function(e) {
            var v = e.target.value.replace(/\D/g, '');
            v = v.substring(0, 14);
            if (v.length > 12) {
                v = v.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{0,2})$/, '$1.$2.$3/$4-$5');
            } else if (v.length > 8) {
                v = v.replace(/^(\d{2})(\d{3})(\d{3})(\d{0,4})$/, '$1.$2.$3/$4');
            } else if (v.length > 5) {
                v = v.replace(/^(\d{2})(\d{3})(\d{0,3})$/, '$1.$2.$3');
            } else if (v.length > 2) {
                v = v.replace(/^(\d{2})(\d{0,3})$/, '$1.$2');
            }
            e.target.value = v;
        });
    }

    // Máscara CEP: 00000-000
    var cepInput = document.getElementById('cep');
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            var v = e.target.value.replace(/\D/g, '');
            v = v.substring(0, 8);
            if (v.length > 5) {
                v = v.replace(/^(\d{5})(\d{0,3})/, '$1-$2');
            }
            e.target.value = v;
        });

        // ViaCEP
        cepInput.addEventListener('blur', function() {
            var cep = this.value.replace(/\D/g, '');
            if (cep.length !== 8) return;

            fetch('https://viacep.com.br/ws/' + cep + '/json/')
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (data.erro) return;
                    var logradouro = document.getElementById('logradouro');
                    var bairro = document.getElementById('bairro');
                    var cidade = document.getElementById('cidade');
                    var estado = document.getElementById('estado');
                    
                    if (logradouro) logradouro.value = data.logradouro || '';
                    if (bairro) bairro.value = data.bairro || '';
                    if (cidade) cidade.value = data.localidade || '';
                    if (estado) estado.value = data.uf || '';
                })
                .catch(function(err) {
                    console.error('Erro ao buscar CEP:', err);
                });
        });
    }

    // Máscara Telefone: (00) 00000-0000
    function maskPhone(value) {
        value = value.replace(/\D/g, '');
        value = value.substring(0, 11);
        if (value.length > 10) {
            value = value.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
        } else if (value.length > 6) {
            value = value.replace(/^(\d{2})(\d{4})(\d{0,4})$/, '($1) $2-$3');
        } else if (value.length > 2) {
            value = value.replace(/^(\d{2})(\d{0,5})$/, '($1) $2');
        } else if (value.length > 0) {
            value = value.replace(/^(\d{0,2})$/, '($1');
        }
        return value;
    }

    var telefone1 = document.getElementById('telefone_1');
    if (telefone1) {
        telefone1.addEventListener('input', function(e) {
            e.target.value = maskPhone(e.target.value);
        });
    }

    var telefone2 = document.getElementById('telefone_2');
    if (telefone2) {
        telefone2.addEventListener('input', function(e) {
            e.target.value = maskPhone(e.target.value);
        });
    }
});
</script>
