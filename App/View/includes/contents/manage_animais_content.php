<?php
/**
 * AmigoPet - Conteúdo Gerenciar Animais
 * Localização: ~/App/View/includes/contents/manage_animais_content.php
 * Página única com comportamento adaptado por cargo (role)
 */

if (session_status() === PHP_SESSION_NONE) session_start();
$role = $_SESSION['sim_user_role'] ?? 'usuario';

// Acesso restrito: apenas cargos que não sejam 'usuario' podem acessar
$allowedRoles = ['admin','ong','vet','campo','moderador'];
if (!in_array($role, $allowedRoles)) {
    echo "<div class=\"container p-4\"><div class=\"alert alert-danger\">Acesso negado: recurso disponível apenas para funcionários, ONGs e administradores.</div></div>";
    return;
}

// Carrega DAOs necessários
use App\DAO\AnimalDAO;
use App\DAO\EspecieDAO;
use App\DAO\RacaDAO;
use App\DAO\AnimalRacaDAO;
use App\DAO\SolicitacaoAdocaoDAO;

$animalDao = new AnimalDAO();
$especieDao = new EspecieDAO();
$racaDao = new RacaDAO();
$animalRacaDao = new AnimalRacaDAO();
$solDao = new SolicitacaoAdocaoDAO();

$animalModels = $animalDao->listar();

// Contagem de solicitações por animal
$solCounts = [];
try {
    $allSols = $solDao->listar();
    foreach ($allSols as $s) {
        $aid = $s->__get('fk_animal_id');
        if (!isset($solCounts[$aid])) $solCounts[$aid] = 0;
        $solCounts[$aid]++;
    }
} catch (\Throwable $ex) {
    // ignore
}

// Mapear modelos para arrays que a UI espera (mantendo parte da lógica antiga de vacinas/logs em sessão)
$animals = [];
foreach ($animalModels as $m) {
    $animals[] = [
        'id' => $m->__get('id'),
        'nome' => $m->__get('nome'),
        'especie' => $m->__get('especie_nome'),
        'raca' => $m->__get('racas'),
        'idade' => $m->__get('idade_meses') !== null ? (intdiv((int)$m->__get('idade_meses'),12) . 'a ' . ((int)$m->__get('idade_meses')%12) . 'm') : '',
        'sexo' => $m->__get('sexo'),
        'porte' => $m->__get('porte'),
        'imagem' => $m->__get('foto'),
        'descricao' => $m->__get('descricao'),
        'em_adocao' => ($m->__get('status') === 'disponivel') ? true : false,
        'castrado' => $m->__get('castrado') ? true : false,
        'vacinas' => $_SESSION['vacinas'][$m->__get('id')] ?? []
    ];
}

// Inicializa logs em sessão
if (!isset($_SESSION['action_logs'])) $_SESSION['action_logs'] = [];
$logs =& $_SESSION['action_logs'];

// Helper para gravar log
function pushLog($role, $action, $details = '') {
    // Persistir log em auditoria (DB)
    try {
        $audDao = new \App\DAO\AuditoriaDAO();
        $audDao->inserir($role, $action, $details);
    } catch (\Throwable $ex) {
        // Fallback para session caso DAO falhe
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['action_logs'])) $_SESSION['action_logs'] = [];
        $_SESSION['action_logs'][] = [
            'time' => date('Y-m-d H:i:s'),
            'role' => $role,
            'action' => $action,
            'details' => $details
        ];
    }
}

// Sincroniza alterações via POST (simulado)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ma_action'])) {
    $act = $_POST['ma_action'];

    if ($act === 'create' && in_array($role, ['admin','ong'])) {
        // Persistir no banco com suporte a criação de espécie/raça e upload de imagem
        try {
            $uploadError = false;
            $nome = trim($_POST['nome'] ?? 'Sem nome');
            $descricao = trim($_POST['descricao'] ?? '');
            $sexo = trim($_POST['sexo'] ?? 'n/a');
            $porte = trim($_POST['porte'] ?? 'medio');
            $data_nascimento = trim($_POST['data_nascimento'] ?? '') ?: null;
            $cor = trim($_POST['cor'] ?? '');
            $localizacao = trim($_POST['localizacao'] ?? '');
            $castrado = !empty($_POST['castrado']) ? 1 : 0;

            // Especie: usa fk_especie_id ou cria nova espécie se informado
            $fk_especie_id = null;
            if (!empty($_POST['fk_especie_id'])) {
                $fk_especie_id = (int) $_POST['fk_especie_id'];
            } elseif (!empty($_POST['new_especie'])) {
                $e = new \App\Model\EspecieModel();
                $e->__set('nome', trim($_POST['new_especie']));
                $fk_especie_id = $especieDao->inserir($e);
            }

            // Raça: may provide existing fk_raca_id or new_raca name
            $fk_raca_id = null;
            if (!empty($_POST['fk_raca_id'])) {
                $fk_raca_id = (int) $_POST['fk_raca_id'];
            } elseif (!empty($_POST['new_raca'])) {
                $newRacaName = trim($_POST['new_raca']);
                $targetEspecieId = null;
                if (!empty($_POST['new_raca_especie_id'])) $targetEspecieId = (int)$_POST['new_raca_especie_id'];
                elseif (!empty($fk_especie_id)) $targetEspecieId = $fk_especie_id;

                // Try to find existing race with same name for the target species
                $existing = false;
                if ($targetEspecieId) {
                    $existing = $racaDao->buscarPorNomeEEspecie($newRacaName, $targetEspecieId);
                }

                if ($existing) {
                    $fk_raca_id = (int) $existing->__get('id');
                } else {
                    $r = new \App\Model\RacaModel();
                    $r->__set('nome', $newRacaName);
                    $r->__set('fk_especie_id', $targetEspecieId ?: null);
                    $fk_raca_id = $racaDao->inserir($r);
                }
            }

            // Se o usuário escolheu uma raça existente mas não escolheu espécie,
            // infere a espécie a partir da raça selecionada.
            if (empty($fk_especie_id) && !empty($fk_raca_id)) {
                $raceModel = $racaDao->buscarPorId($fk_raca_id);
                if ($raceModel) {
                    $fk_especie_id = (int) $raceModel->__get('fk_especie_id');
                }
            }

            // Handle image upload
            $fotoUrl = '';
            if (!empty($_FILES['imagem_file']) && is_uploaded_file($_FILES['imagem_file']['tmp_name'])) {
                $uploadDir = __DIR__ . '/../../../resources/dashboard/images/animais/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $ext = pathinfo($_FILES['imagem_file']['name'], PATHINFO_EXTENSION);
                $filename = bin2hex(random_bytes(8)) . '.' . ($ext ?: 'jpg');
                $target = $uploadDir . $filename;
                if (move_uploaded_file($_FILES['imagem_file']['tmp_name'], $target)) {
                    $fotoUrl = '/resources/dashboard/images/animais/' . $filename;
                } else {
                    if (session_status() === PHP_SESSION_NONE) session_start();
                    $_SESSION['flash_message'] = ['type'=>'danger','text'=>'Upload falhou. Verifique permissões de pasta.'];
                }
            } else {
                $fotoUrl = trim($_POST['imagem'] ?? '');
            }

            $model = new \App\Model\AnimalModel();
            $model->__set('nome', $nome);
            $model->__set('descricao', $descricao);
            $model->__set('sexo', $sexo);
            $model->__set('porte', $porte);
            $model->__set('foto', $fotoUrl);
            $model->__set('fk_especie_id', $fk_especie_id);
            $model->__set('cor', $cor);
            $model->__set('localizacao', $localizacao);
            $model->__set('data_nascimento', $data_nascimento);
            $model->__set('castrado', $castrado);
            $model->__set('status', isset($_POST['em_adocao']) ? 'reservado' : 'disponivel');

            $newId = $animalDao->inserir($model);

            // Vincular raça se foi selecionada/ criada
            if ($newId && $fk_raca_id) {
                $animalRacaDao->vincular((int)$newId, (int)$fk_raca_id);
            }

            pushLog($role, 'create_animal', json_encode(['id' => $newId, 'nome' => $model->__get('nome')]));
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (!isset($_SESSION['flash_message']) || $_SESSION['flash_message']['type'] !== 'danger') {
                $_SESSION['flash_message'] = ['type'=>'success','text'=>'Animal cadastrado com sucesso.'];
            }
        } catch (\Throwable $ex) {
            $_SESSION['flash_message'] = ['type'=>'danger','text'=>'Falha ao cadastrar animal.'];
        }
    }

    if ($act === 'toggle_adocao' && in_array($role, ['admin','ong','moderador'])) {
        $id = $_POST['pet_id'] ?? null;
        $model = $animalDao->buscarPorId((int)$id);
        if ($model) {
            $current = $model->__get('status');
            $newStatus = ($current === 'disponivel') ? 'reservado' : 'disponivel';
            $model->__set('status', $newStatus);
            $animalDao->alterar($model);
            pushLog($role, 'toggle_adocao', json_encode(['id'=>$id,'status'=>$newStatus]));
            // Mensagem flash para o usuário
            if (session_status() === PHP_SESSION_NONE) session_start();
            $petName = $model->__get('nome') ?: 'Animal';
            if ($newStatus === 'reservado') {
                $_SESSION['flash_message'] = ['type' => 'success', 'text' => "{$petName} foi marcado para adoção."];
            } else {
                $_SESSION['flash_message'] = ['type' => 'info', 'text' => "{$petName} foi removido da adoção."];
            }
        }
    }

    if ($act === 'add_vaccine' && $role === 'vet') {
        $id = $_POST['pet_id'] ?? null;
        $vac = trim($_POST['vacina'] ?? '');
        if ($id) {
            // Persistir em historico_animal
            $hist = new \App\Model\HistoricoAnimalModel();
            $hist->__set('hist_descr', $vac);
            $hist->__set('hist_data', date('Y-m-d H:i:s'));
            $hist->__set('hist_tipo', 'vacinacao');
            $hist->__set('fk_animal_id', (int)$id);
            $histDao = new \App\DAO\HistoricoAnimalDAO();
            $histDao->inserir($hist);
            pushLog($role, 'add_vaccine', json_encode(['id'=>$id,'vacina'=>$vac]));
        }
    }

    if ($act === 'castrar' && in_array($role, ['admin','vet','ong'])) {
        $id = $_POST['pet_id'] ?? null;
        $model = $animalDao->buscarPorId((int)$id);
        if ($model) {
            $model->__set('castrado', 1);
            $animalDao->alterar($model);
            pushLog($role, 'castrar', json_encode(['id'=>$id]));
        }
    }

    if ($act === 'approve_adoption' && in_array($role, ['admin','ong'])) {
        $id = $_POST['pet_id'] ?? null;
        $reason = trim($_POST['reason'] ?? '');
        pushLog($role, 'approve_adoption', json_encode(['id'=>$id,'reason'=>$reason]));
    }

    if ($act === 'deny_adoption' && in_array($role, ['admin','ong'])) {
        $id = $_POST['pet_id'] ?? null;
        $reason = trim($_POST['reason'] ?? '');
        pushLog($role, 'deny_adoption', json_encode(['id'=>$id,'reason'=>$reason]));
    }

    if ($act === 'report_found' && $role === 'campo') {
        $id = $_POST['pet_id'] ?? null;
        $loc = trim($_POST['local'] ?? '');
        $desc = trim($_POST['descricao'] ?? '');
        pushLog($role, 'report_found', json_encode(['id'=>$id,'local'=>$loc,'descricao'=>$desc]));
    }

    // After POST actions, request a client-side reload — set flag and continue rendering
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['should_reload_manage_animais'] = true;
}

// UI
?>

<div class="main-content">
    <div class="container-fluid p-4">
        <?php if (isset($_SESSION['flash_message'])): $f = $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
            <div id="flash-message" class="alert <?php echo ($f['type']==='success')? 'alert-success':'alert-info'; ?>" role="alert" style="position:fixed;top:16px;left:50%;transform:translateX(-50%);z-index:2000;min-width:300px;max-width:80%;text-align:center;box-shadow:0 8px 20px rgba(0,0,0,0.08);">
                <?php echo htmlspecialchars($f['text']); ?>
            </div>
            <script>
                (function(){
                    var el = document.getElementById('flash-message');
                    if (!el) return;
                    setTimeout(function(){
                        el.style.transition = 'opacity 400ms ease';
                        el.style.opacity = '0';
                        setTimeout(function(){ el.remove(); }, 500);
                    }, 3500);
                })();
            </script>
        <?php endif; ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gerenciar Animais</h2>
            <div>Perfil atual: <strong><?php echo htmlspecialchars(strtoupper($role)); ?></strong></div>
        </div>

        <?php if (in_array($role, ['admin','ong'])): ?>
        <div class="mb-3">
            <button class="btn btn-lg btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#newAnimalCollapse" aria-expanded="false" aria-controls="newAnimalCollapse">
                Cadastrar novo animal
            </button>
        </div>

        <div class="collapse mb-4" id="newAnimalCollapse">
            <div class="card card-body">
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="ma_action" value="create">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Nome</label>
                            <input class="form-control" name="nome" placeholder="Nome" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Espécie</label>
                            <div class="input-group">
                                <select class="form-select" name="fk_especie_id" id="fk_especie_id">
                                    <option value="">-- selecione --</option>
                                    <?php try { $esps = $especieDao->listar(); foreach($esps as $esp): ?>
                                        <option value="<?php echo (int)$esp->__get('id'); ?>"><?php echo htmlspecialchars($esp->__get('nome')); ?></option>
                                    <?php endforeach; } catch(Throwable $e) {} ?>
                                </select>
                                <button class="btn btn-outline-secondary" type="button" id="btnNewEspecie">+</button>
                            </div>
                            <input class="form-control mt-2 d-none" name="new_especie" id="new_especie" placeholder="Nova espécie">
                            <div class="mt-1 small text-muted">Sugestões: <a href="#" class="quick-especie">Cachorro</a>, <a href="#" class="quick-especie">Gato</a></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Raça</label>
                            <div class="input-group">
                                <select class="form-select" name="fk_raca_id" id="fk_raca_id">
                                    <option value="">-- selecione --</option>
                                    <?php try { $racas = $racaDao->listar(); foreach($racas as $rc): ?>
                                        <option value="<?php echo (int)$rc->__get('id'); ?>" data-especie="<?php echo (int)$rc->__get('fk_especie_id'); ?>"><?php echo htmlspecialchars($rc->__get('nome')); ?></option>
                                    <?php endforeach; } catch(Throwable $e) {} ?>
                                </select>
                                <button class="btn btn-outline-secondary" type="button" id="btnNewRaca">+</button>
                            </div>
                            <input class="form-control mt-2 d-none" name="new_raca" id="new_raca" placeholder="Nova raça">
                            <select class="form-select mt-2 d-none" name="new_raca_especie_id" id="new_raca_especie_id">
                                <option value="">-- espécie da raça --</option>
                                <?php try { $esps2 = $especieDao->listar(); foreach($esps2 as $esp2): ?>
                                    <option value="<?php echo (int)$esp2->__get('id'); ?>"><?php echo htmlspecialchars($esp2->__get('nome')); ?></option>
                                <?php endforeach; } catch(Throwable $e) {} ?>
                            </select>
                            <div class="form-text">Clique no + para adicionar uma nova raça e selecione a espécie associada.</div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Porte</label>
                            <div class="input-group">
                                <select class="form-select" name="porte" id="porte_select">
                                    <option value="pequeno">Pequeno</option>
                                    <option value="medio" selected>Médio</option>
                                    <option value="grande">Grande</option>
                                </select>
                                <button class="btn btn-outline-secondary" type="button" id="btnNewPorte">+</button>
                            </div>
                            <input class="form-control mt-2 d-none" id="new_porte" placeholder="Novo porte (opcional)">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Sexo</label>
                            <select class="form-select" name="sexo">
                                <option value="m">Macho</option>
                                <option value="f">Fêmea</option>
                                <option value="n/a">N/A</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Data de Nascimento</label>
                            <input type="date" class="form-control" name="data_nascimento">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Cor</label>
                            <input class="form-control" name="cor" placeholder="Cor">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Localização</label>
                            <input class="form-control" name="localizacao" placeholder="Cidade / Bairro">
                        </div>

                        <div class="col-md-3 d-flex align-items-center">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" value="1" id="castrado_chk" name="castrado">
                                <label class="form-check-label" for="castrado_chk">Castrado</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Imagem</label>
                            <div class="d-flex gap-2">
                                <input type="file" name="imagem_file" accept="image/*" class="form-control">
                                <input type="text" name="imagem" class="form-control" placeholder="ou URL da imagem">
                            </div>
                            <div class="form-text">O upload salva a imagem e grava a URL. O recorte de paisagem ainda não está implementado.</div>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Descrição</label>
                            <input class="form-control" name="descricao" placeholder="Descrição curta">
                        </div>

                        <div class="col-12 text-end mt-2"><button class="btn btn-primary">Cadastrar</button></div>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <div class="card-amigopet p-3 mb-3">
                    <h5 class="mb-3">Lista de Animais</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead><tr><th>Foto</th><th>Nome</th><th>Espécie</th><th>Sexo</th><th>Status</th><th>Ações</th></tr></thead>
                            <tbody>
                                <?php foreach ($animals as $a): ?>
                                    <tr>
                                        <td style="width:80px;"><img src="<?php echo htmlspecialchars($a['imagem']); ?>" style="width:70px;height:50px;object-fit:cover;border-radius:6px;"></td>
                                        <td><?php echo htmlspecialchars($a['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($a['especie']); ?></td>
                                        <td><?php echo ($a['sexo']==='m')? 'Macho' : (($a['sexo']==='f')? 'Fêmea' : 'N/A'); ?></td>
                                        <td>
                                            <?php echo !empty($a['em_adocao']) ? '<span class="badge bg-success">Em Adoção</span>' : '<span class="badge bg-secondary">Não em adoção</span>'; ?>
                                            <?php if (!empty($a['castrado'])): ?> <small class="text-muted"> • Castrado</small><?php endif; ?>
                                        </td>
                                        <td>
                                            <!-- Ações por role -->
                                            <?php if (in_array($role, ['admin','ong','vet','moderador'])): ?>
                                                <a href="/dashboard/animal/editar/<?php echo htmlspecialchars($a['id']); ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
                                            <?php endif; ?>

                                            <?php if (in_array($role, ['admin','ong','moderador'])): ?>
                                                <form style="display:inline-block" method="post">
                                                    <input type="hidden" name="ma_action" value="toggle_adocao">
                                                    <input type="hidden" name="pet_id" value="<?php echo htmlspecialchars($a['id']); ?>">
                                                    <button class="btn btn-sm btn-outline-primary" type="submit"><?php echo empty($a['em_adocao']) ? 'Colocar em adoção' : 'Remover da adoção'; ?></button>
                                                </form>
                                            <?php endif; ?>

                                            <?php if ($role === 'vet'): ?>
                                                <button class="btn btn-sm btn-outline-success" onclick="document.getElementById('vacina-<?php echo $a['id']; ?>').classList.toggle('d-none')">Vacinar</button>
                                                <div id="vacina-<?php echo $a['id']; ?>" class="d-none mt-2">
                                                    <form method="post">
                                                        <input type="hidden" name="ma_action" value="add_vaccine">
                                                        <input type="hidden" name="pet_id" value="<?php echo htmlspecialchars($a['id']); ?>">
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" name="vacina" class="form-control" placeholder="Nome da vacina">
                                                            <button class="btn btn-sm btn-primary" type="submit">Adicionar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (in_array($role, ['admin','vet','ong'])): ?>
                                                <form style="display:inline-block" method="post">
                                                    <input type="hidden" name="ma_action" value="castrar">
                                                    <input type="hidden" name="pet_id" value="<?php echo htmlspecialchars($a['id']); ?>">
                                                    <button class="btn btn-sm btn-outline-warning" type="submit">Marcar castrado</button>
                                                </form>
                                            <?php endif; ?>

                                            <?php if (in_array($role, ['admin','ong'])): ?>
                                                <?php $solCount = $solCounts[$a['id']] ?? 0; ?>
                                                <button class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('adopt-forms-<?php echo $a['id']; ?>').classList.toggle('d-none')">Solicitações <span class="badge bg-secondary ms-1"><?php echo $solCount; ?></span></button>
                                                <div id="adopt-forms-<?php echo $a['id']; ?>" class="d-none mt-2">
                                                    <form method="post" style="margin-bottom:6px;">
                                                        <input type="hidden" name="ma_action" value="approve_adoption">
                                                        <input type="hidden" name="pet_id" value="<?php echo htmlspecialchars($a['id']); ?>">
                                                        <input type="text" name="reason" class="form-control form-control-sm" placeholder="Motivo (opcional)">
                                                        <div class="mt-2 text-end"><button class="btn btn-sm btn-success">Aprovar</button></div>
                                                    </form>
                                                    <form method="post">
                                                        <input type="hidden" name="ma_action" value="deny_adoption">
                                                        <input type="hidden" name="pet_id" value="<?php echo htmlspecialchars($a['id']); ?>">
                                                        <input type="text" name="reason" class="form-control form-control-sm" placeholder="Motivo da recusa">
                                                        <div class="mt-2 text-end"><button class="btn btn-sm btn-danger">Negar</button></div>
                                                    </form>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($role === 'campo'): ?>
                                                <button class="btn btn-sm btn-outline-info" onclick="document.getElementById('found-<?php echo $a['id']; ?>').classList.toggle('d-none')">Reportar encontrado</button>
                                                <div id="found-<?php echo $a['id']; ?>" class="d-none mt-2">
                                                    <form method="post">
                                                        <input type="hidden" name="ma_action" value="report_found">
                                                        <input type="hidden" name="pet_id" value="<?php echo htmlspecialchars($a['id']); ?>">
                                                        <input type="text" name="local" class="form-control form-control-sm" placeholder="Local encontrado">
                                                        <textarea name="descricao" class="form-control form-control-sm mt-1" placeholder="Descrição"></textarea>
                                                        <div class="mt-2 text-end"><button class="btn btn-sm btn-primary">Enviar relatório</button></div>
                                                    </form>
                                                </div>
                                            <?php endif; ?>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Formulário de cadastro movido para o topo (collapse) -->

            </div>

            <!-- Logs agora centralizados na aba de Auditoria (Configurações do Admin) -->
        </div>

    </div>
</div>

<style>
.card-amigopet { background:white; border-radius:10px; padding:12px; border:1px solid #f0f0f0; }
</style>

<?php if (isset($_SESSION['should_reload_manage_animais']) && $_SESSION['should_reload_manage_animais']): ?>
    <script>
        // Remove flag on next request
        try { fetch('/?clear_reload=1', { method: 'POST', credentials: 'same-origin' }); } catch(e){}
        // Reload the page after the full layout is rendered
        setTimeout(function(){ window.location.href = window.location.pathname; }, 100);
    </script>
    <?php unset($_SESSION['should_reload_manage_animais']); endif; ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
    var btnNewEspecie = document.getElementById('btnNewEspecie');
    var newEspecie = document.getElementById('new_especie');
    if (btnNewEspecie && newEspecie) btnNewEspecie.addEventListener('click', function(){ newEspecie.classList.toggle('d-none'); });

    var btnNewRaca = document.getElementById('btnNewRaca');
    var newRaca = document.getElementById('new_raca');
    var newRacaEsp = document.getElementById('new_raca_especie_id');
    var especieSelect = document.getElementById('fk_especie_id');
    if (btnNewRaca && newRaca) btnNewRaca.addEventListener('click', function(){
        newRaca.classList.toggle('d-none');
        if (newRacaEsp) newRacaEsp.classList.toggle('d-none');
        // prefill species for new race with currently selected species
        if (newRacaEsp && especieSelect && especieSelect.value) newRacaEsp.value = especieSelect.value;
    });

    var btnNewPorte = document.getElementById('btnNewPorte');
    var newPorte = document.getElementById('new_porte');
    var porteSelect = document.getElementById('porte_select');
    if (btnNewPorte && newPorte && porteSelect) btnNewPorte.addEventListener('click', function(){
        newPorte.classList.toggle('d-none');
        if (!newPorte.classList.contains('d-none')) newPorte.focus();
        else newPorte.value = '';
    });
    if (newPorte) newPorte.addEventListener('change', function(){
        var val = this.value.trim(); if (!val) return;
        // add new option to select
        var opt = document.createElement('option'); opt.value = val; opt.text = val; opt.selected = true;
        porteSelect.appendChild(opt);
    });

    var quicks = document.querySelectorAll('.quick-especie');
    quicks.forEach(function(el){ el.addEventListener('click', function(e){ e.preventDefault(); var v = this.textContent.trim(); var input = document.getElementById('new_especie'); if (input){ input.classList.remove('d-none'); input.value = v; } }); });

    // Filter races by selected species
    function filterRacasByEspecie() {
        var especieVal = especieSelect ? especieSelect.value : '';
        var raceSelect = document.getElementById('fk_raca_id');
        if (!raceSelect) return;
        var options = raceSelect.querySelectorAll('option[data-especie]');
        options.forEach(function(opt){
            var e = opt.getAttribute('data-especie') || '';
            if (!especieVal) { opt.style.display = ''; } else {
                if (String(e) === String(especieVal)) opt.style.display = '';
                else opt.style.display = 'none';
            }
        });
        // If currently selected option is hidden, clear selection
        if (raceSelect.value) {
            var cur = raceSelect.selectedOptions[0];
            if (cur && cur.style.display === 'none') raceSelect.value = '';
        }
    }

    if (especieSelect) especieSelect.addEventListener('change', function(){
        // when species changes, prefill new_raca_especie_id and filter races
        if (newRacaEsp && especieSelect.value) newRacaEsp.value = especieSelect.value;
        filterRacasByEspecie();
    });

    // initial filter on load
    filterRacasByEspecie();
});
</script>
