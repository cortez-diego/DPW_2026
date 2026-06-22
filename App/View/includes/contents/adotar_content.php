<?php
/**
 * AmigoPet - Conteúdo do Formulário de Adoção
 * Localização: ~/App/View/includes/contents/adotar_content.php
 */

// Substitui mock por busca no backend
$petId = isset($_GET['id']) ? intval($_GET['id']) : null;
$selectedPet = null;
if ($petId) {
    $dao = new \App\DAO\AnimalDAO();
    $model = $dao->buscarPorId($petId);
    if ($model) {
        $idade_meses = $model->__get('idade_meses');
        $idade = '';
        if ($idade_meses !== null && $idade_meses !== '') {
            $anos = intdiv((int)$idade_meses, 12);
            $meses = (int)$idade_meses % 12;
            $idade = trim(($anos > 0 ? $anos . ' ano' . ($anos > 1 ? 's' : '') : '') .
                          ($anos > 0 && $meses > 0 ? ' ' : '') .
                          ($meses > 0 ? $meses . ' mês' . ($meses > 1 ? 'es' : '') : ''));
        }

        $selectedPet = [
            'id' => $model->__get('id'),
            'imagem' => $model->__get('foto'),
            'nome' => $model->__get('nome'),
            'especie' => $model->__get('especie_nome'),
            'sexo' => $model->__get('sexo'),
            'descricao' => $model->__get('descricao'),
            'raca' => $model->__get('racas'),
            'idade' => $idade,
            'porte' => $model->__get('porte'),
        ];
    }
}

$formValues = [
    'nome' => '',
    'cpf' => '',
    'data_nascimento' => '',
    'telefone_1' => '',
    'telefone_2' => '',
    'cep' => '',
    'logradouro' => '',
    'numero' => '',
    'complemento' => '',
    'bairro' => '',
    'cidade' => '',
    'estado' => '',
    'status' => 'bom'
];

$submitted = false;
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted = true;
    $formValues = array_merge($formValues, [
        'nome' => trim($_POST['nome'] ?? ''),
        'cpf' => trim($_POST['cpf'] ?? ''),
        'data_nascimento' => trim($_POST['data_nascimento'] ?? ''),
        'telefone_1' => trim($_POST['telefone_1'] ?? ''),
        'telefone_2' => trim($_POST['telefone_2'] ?? ''),
        'cep' => trim($_POST['cep'] ?? ''),
        'logradouro' => trim($_POST['logradouro'] ?? ''),
        'numero' => trim($_POST['numero'] ?? ''),
        'complemento' => trim($_POST['complemento'] ?? ''),
        'bairro' => trim($_POST['bairro'] ?? ''),
        'cidade' => trim($_POST['cidade'] ?? ''),
        'estado' => trim($_POST['estado'] ?? ''),
        'status' => trim($_POST['status'] ?? 'bom'),
    ]);

    if (empty($formValues['nome']) || empty($formValues['cpf']) || empty($selectedPet)) {
        $errorMessage = 'Por favor, preencha seu nome completo, CPF e selecione um animal válido antes de enviar.';
    } else {
        try {
            // Buscar ou criar adotante
            $adotanteDao = new \App\DAO\AdotanteDAO();
            $cpfBusca = $formValues['cpf'];
            $existing = $adotanteDao->buscarPorCpf($cpfBusca);

            if ($existing) {
                $adotanteId = $existing->__get('id');
            } else {
                $ad = new \App\Model\AdotanteModel();
                $ad->__set('nome', $formValues['nome']);
                $ad->__set('cpf', $formValues['cpf']);
                $ad->__set('data_nascimento', $formValues['data_nascimento'] ?: null);
                $ad->__set('telefone_1', $formValues['telefone_1']);
                $ad->__set('telefone_2', $formValues['telefone_2']);
                $ad->__set('cep', $formValues['cep']);
                $ad->__set('logradouro', $formValues['logradouro']);
                $ad->__set('numero', $formValues['numero']);
                $ad->__set('complemento', $formValues['complemento']);
                $ad->__set('bairro', $formValues['bairro']);
                $ad->__set('cidade', $formValues['cidade']);
                $ad->__set('estado', $formValues['estado']);
                $ad->__set('status', $formValues['status'] ?? 'bom');
                $ad->__set('fk_login_id', null);

                $adotanteId = $adotanteDao->inserir($ad);
            }

            // Criar solicitação de adoção
            $solDao = new \App\DAO\SolicitacaoAdocaoDAO();
            $sol = new \App\Model\SolicitacaoAdocaoModel();
            $sol->__set('solAdc_data', date('Y-m-d H:i:s'));
            $sol->__set('solAdc_status', 'p'); // pendente
            $sol->__set('solAdc_motivo', 'Solicitação por formulário público');
            $sol->__set('fk_adotante_id', (int)$adotanteId);
            $sol->__set('fk_animal_id', (int)$selectedPet['id']);

            $solId = $solDao->inserir($sol);

            if ($solId) {
                $successMessage = "Obrigado, {$formValues['nome']}! Sua solicitação para adotar o {$selectedPet['nome']} foi registrada com sucesso (ID: {$solId}). Nosso time entrará em contato em breve.";
            } else {
                $errorMessage = 'Falha ao registrar a solicitação. Por favor, tente novamente mais tarde.';
            }
        } catch (\Exception $e) {
            $errorMessage = 'Erro interno ao processar a solicitação. Tente novamente.';
        }
    }
}
?>

<style>
    .adoption-page-header {
        background: #E9F7EF;
        border-left: 5px solid #6FCF97;
        padding: 18px 22px;
        border-radius: 14px;
        margin-bottom: 24px;
    }
    .adoption-card {
        border-radius: 24px;
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid #edeff2;
    }
    .adoption-card img {
        border-top-left-radius: 24px;
        border-top-right-radius: 24px;
        object-fit: cover;
        height: 320px;
        width: 100%;
    }
    .label-secondary {
        color: #6B7280;
    }
    .form-section-title {
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 16px;
    }
    .badge-detail {
        background: rgba(111, 207, 151, 0.12);
        color: #219653;
        font-weight: 700;
        border-radius: 999px;
        padding: 6px 12px;
        font-size: 0.8rem;
        display: inline-block;
    }
</style>

<div class="main-content">
    <div class="container-fluid">
        <div class="adoption-page-header">
            <h1 class="h3 mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Formulário de Adoção</h1>
            <p class="text-muted mb-0">Preencha seus dados para que possamos iniciar o processo de adoção do seu novo amigo.</p>
        </div>

        <?php if (!$selectedPet): ?>
            <div class="alert alert-warning">
                <strong>Pet não encontrado.</strong> Volte à página de adoção e selecione um animal novamente.
                <a href="/adocao" class="alert-link">Voltar para adoção</a>
            </div>
        <?php else: ?>
            <?php if ($submitted): ?>
                <?php if ($successMessage): ?>
                    <div class="alert alert-success"><?php echo $successMessage; ?></div>
                <?php elseif ($errorMessage): ?>
                    <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-xl-4">
                    <div class="card adoption-card overflow-hidden">
                        <img src="<?php echo htmlspecialchars($selectedPet['imagem']); ?>" alt="<?php echo htmlspecialchars($selectedPet['nome']); ?>">
                        <div class="card-body">
                            <h4 class="mb-2" style="font-weight: 700;"><?php echo htmlspecialchars($selectedPet['nome']); ?></h4>
                            <div class="mb-3">
                                <span class="badge-detail"><?php echo htmlspecialchars($selectedPet['especie']); ?></span>
                                <span class="badge-detail" style="margin-left: 8px;"><?php echo htmlspecialchars($selectedPet['sexo']); ?></span>
                            </div>
                            <p class="text-secondary mb-3"><?php echo htmlspecialchars($selectedPet['descricao']); ?></p>
                            <ul class="list-unstyled mb-0">
                                <li><strong>Raça:</strong> <?php echo htmlspecialchars($selectedPet['raca']); ?></li>
                                <li><strong>Idade:</strong> <?php echo htmlspecialchars($selectedPet['idade']); ?></li>
                                <li><strong>Porte:</strong> <?php echo htmlspecialchars($selectedPet['porte'] ?? 'Médio'); ?></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form method="POST" action="/adotar?id=<?php echo $selectedPet['id']; ?>">
                                <input type="hidden" name="pet_id" value="<?php echo $selectedPet['id']; ?>">

                                <div class="form-section-title">Dados Pessoais</div>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="nome" class="form-label">Nome completo <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nome" name="nome" required value="<?php echo htmlspecialchars($formValues['nome']); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="cpf" name="cpf" maxlength="14" placeholder="000.000.000-00" value="<?php echo htmlspecialchars($formValues['cpf']); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="data_nascimento" class="form-label">Data de nascimento</label>
                                        <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($formValues['data_nascimento']); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="telefone_1" class="form-label">Telefone principal</label>
                                        <input type="text" class="form-control" id="telefone_1" name="telefone_1" placeholder="(00) 00000-0000" value="<?php echo htmlspecialchars($formValues['telefone_1']); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="telefone_2" class="form-label">Telefone alternativo</label>
                                        <input type="text" class="form-control" id="telefone_2" name="telefone_2" placeholder="(00) 00000-0000" value="<?php echo htmlspecialchars($formValues['telefone_2']); ?>">
                                    </div>
                                </div>

                                <div class="form-section-title">Endereço</div>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-3">
                                        <label for="cep" class="form-label">CEP</label>
                                        <input type="text" class="form-control" id="cep" name="cep" maxlength="9" placeholder="00000-000" value="<?php echo htmlspecialchars($formValues['cep']); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="logradouro" class="form-label">Logradouro</label>
                                        <input type="text" class="form-control" id="logradouro" name="logradouro" readonly value="<?php echo htmlspecialchars($formValues['logradouro']); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="numero" class="form-label">Número</label>
                                        <input type="text" class="form-control" id="numero" name="numero" value="<?php echo htmlspecialchars($formValues['numero']); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="complemento" class="form-label">Complemento</label>
                                        <input type="text" class="form-control" id="complemento" name="complemento" value="<?php echo htmlspecialchars($formValues['complemento']); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="bairro" class="form-label">Bairro</label>
                                        <input type="text" class="form-control" id="bairro" name="bairro" readonly value="<?php echo htmlspecialchars($formValues['bairro']); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="cidade" class="form-label">Cidade</label>
                                        <input type="text" class="form-control" id="cidade" name="cidade" readonly value="<?php echo htmlspecialchars($formValues['cidade']); ?>">
                                    </div>
                                    <div class="col-md-1">
                                        <label for="estado" class="form-label">UF</label>
                                        <input type="text" class="form-control" id="estado" name="estado" readonly maxlength="2" value="<?php echo htmlspecialchars($formValues['estado']); ?>">
                                    </div>
                                </div>

                                <div class="form-section-title">Status da adoção</div>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label for="status" class="form-label">Como você descreve seu perfil?</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="pessimo"<?php echo ($formValues['status'] === 'pessimo') ? ' selected' : ''; ?>>Péssimo</option>
                                            <option value="regular"<?php echo ($formValues['status'] === 'regular') ? ' selected' : ''; ?>>Regular</option>
                                            <option value="bom"<?php echo ($formValues['status'] === 'bom') ? ' selected' : ''; ?>>Bom</option>
                                            <option value="muito bom"<?php echo ($formValues['status'] === 'muito bom') ? ' selected' : ''; ?>>Muito Bom</option>
                                            <option value="excelente"<?php echo ($formValues['status'] === 'excelente') ? ' selected' : ''; ?>>Excelente</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i data-lucide="heart" class="me-1"></i> Enviar solicitação
                                    </button>
                                    <a href="/adocao" class="btn btn-secondary">
                                        <i data-lucide="arrow-left" class="me-1"></i> Voltar à lista
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function mascaraTelefone(campo) {
    if (!campo) return;
    campo.addEventListener('input', function(e) {
        var v = e.target.value.replace(/\D/g, '');
        v = v.substring(0, 11);
        if (v.length > 10) {
            v = v.replace(/^(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        } else if (v.length > 6) {
            v = v.replace(/^(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
        } else if (v.length > 2) {
            v = v.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
        } else if (v.length > 0) {
            v = v.replace(/^(\d{0,2})/, '($1');
        }
        e.target.value = v;
    });
}

function mascaraCPF(campo) {
    campo.addEventListener('input', function(e) {
        var v = e.target.value.replace(/\D/g, '');
        v = v.substring(0, 11);
        if (v.length > 9) {
            v = v.replace(/^(\d{3})(\d{3})(\d{3})(\d{0,2})/, '$1.$2.$3-$4');
        } else if (v.length > 6) {
            v = v.replace(/^(\d{3})(\d{3})(\d{0,3})/, '$1.$2.$3');
        } else if (v.length > 3) {
            v = v.replace(/^(\d{3})(\d{0,3})/, '$1.$2');
        }
        e.target.value = v;
    });
}

var telefone1 = document.getElementById('telefone_1');
var telefone2 = document.getElementById('telefone_2');
var cpf = document.getElementById('cpf');
var cep = document.getElementById('cep');
var logradouro = document.getElementById('logradouro');
var bairro = document.getElementById('bairro');
var cidade = document.getElementById('cidade');
var estado = document.getElementById('estado');

mascaraTelefone(telefone1);
mascaraTelefone(telefone2);
mascaraCPF(cpf);

if (cep) {
    cep.addEventListener('blur', function() {
        var valor = this.value.replace(/\D/g, '');
        if (valor.length !== 8) return;

        fetch('https://viacep.com.br/ws/' + valor + '/json/')
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.erro) return;
                if (logradouro) logradouro.value = data.logradouro || '';
                if (bairro) bairro.value = data.bairro || '';
                if (cidade) cidade.value = data.localidade || '';
                if (estado) estado.value = data.uf || '';
                if (document.getElementById('numero')) {
                    document.getElementById('numero').focus();
                }
            })
            .catch(function() {});
    });
}

if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>