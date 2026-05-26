<?php
require_once __DIR__ . '/../../../Data/animais_mock.php';

$petId = isset($_GET['id']) ? intval($_GET['id']) : null;
$selected = null;
foreach ($animaisSimulados as $p) {
    if ($p['id'] == $petId) { $selected = $p; break; }
}

if (!$selected) {
    echo '<div class="container"><div class="alert alert-warning">Animal não encontrado.</div></div>';
    return;
}
?>

<div class="main-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-4 col-sm-12">
                <img id="img-main" class="img-thumbnail m-4" src="<?php echo htmlspecialchars($selected['imagem']); ?>">
                <div class="d-flex px-4">
                    <!-- imagens adicionais, usando mesma imagem como fallback -->
                    <img class="img-carousel" src="<?php echo htmlspecialchars($selected['imagem']); ?>" onclick="trocarImg(this.src)">
                    <img class="img-carousel" src="<?php echo htmlspecialchars($selected['imagem']); ?>" onclick="trocarImg(this.src)">
                    <img class="img-carousel" src="<?php echo htmlspecialchars($selected['imagem']); ?>" onclick="trocarImg(this.src)">
                    <img class="img-carousel" src="<?php echo htmlspecialchars($selected['imagem']); ?>" onclick="trocarImg(this.src)">
                </div>
            </div>
            <div class="col-md-8 col-sm-12 p-5">
                <h1><?php echo htmlspecialchars($selected['nome']); ?></h1>
                <p><?php echo htmlspecialchars($selected['sexo']); ?> | <?php echo htmlspecialchars($selected['porte'] ?? 'Médio'); ?> | <?php echo htmlspecialchars($selected['idade']); ?></p>
                <div>
                    <div id="div-share" class="w-100">
                        <button id="btn-share" class="btn btn-primary w-100" data-bs-toggle="collapse" data-bs-target="#share-options">Compartilhar</button>
                        <br>
                        <div id="share-options" class="collapse">
                            <a href="#" class="share-option" onclick="share()">Copiar link</a>
                            <a href='whatsapp://send?text=' class="share-option" onclick="shareWhatsapp()">WhatsApp</a>
                            <a href="#" class="share-option" onclick="shareInstagram()">Instagram</a>
                            <a href="#" class="share-option" onclick="shareFacebook()">Facebook</a>
                        </div>
                        <br>
                    </div>
                    <a href="adotar.php?id=<?php echo $selected['id']; ?>" class="btn btn-primary">Adotar Animal</a>
                    <button class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#infos">Mais informações</button>
                </div>
            </div>
        </div>
        <div id="infos" class="collapse container">
            <div class="row">
                <div class="col order-last">
                    <h2>Informações</h2>
                    <ul class="list-group list-unstyled">
                        <li class="list-group-item border-0">Nome: <?php echo htmlspecialchars($selected['nome']); ?></li>
                        <li class="list-group-item border-0">Espécie: <?php echo htmlspecialchars($selected['especie']); ?></li>
                        <li class="list-group-item border-0">Raça: <?php echo htmlspecialchars($selected['raca']); ?></li>
                        <li class="list-group-item border-0">Cor: <?php echo htmlspecialchars($selected['cor'] ?? ''); ?></li>
                        <li class="list-group-item border-0">Sexo: <?php echo htmlspecialchars($selected['sexo']); ?></li>
                        <li class="list-group-item border-0">Nascimento: <?php echo htmlspecialchars($selected['nascimento'] ?? ''); ?></li>
                        <li class="list-group-item border-0">Idade: <?php echo htmlspecialchars($selected['idade']); ?></li>
                        <li class="list-group-item border-0">Histórico de Resgate: <?php echo htmlspecialchars($selected['historico'] ?? ''); ?></li>
                        <li class="list-group-item border-0">Status: <?php echo htmlspecialchars($selected['status'] ?? ''); ?></li>
                        <li class="list-group-item border-0">Porte: <?php echo htmlspecialchars($selected['porte'] ?? 'Médio'); ?></li>
                        <li class="list-group-item border-0">Responsável atual: <?php echo htmlspecialchars($selected['ong'] ?? ''); ?></li>
                    </ul>
                    <br>
                </div>
                <div class="col">
                    <h2>Saúde</h2>
                    <ul class="list-group list-unstyled">
                        <li class="list-group-item border-0">Alergias: <?php echo htmlspecialchars($selected['alergias'] ?? ''); ?></li>
                        <li class="list-group-item border-0">Castração: <?php echo htmlspecialchars($selected['castrado'] ?? ''); ?></li>
                        <li class="list-group-item border-0">Data da Castração: <?php echo htmlspecialchars($selected['data_castracao'] ?? ''); ?></li>
                        <li class="list-group-item border-0">Observações veterinárias: <?php echo htmlspecialchars($selected['observacoes_veterinarias'] ?? ''); ?></li>
                    </ul>
                    <br>
                </div>
            </div>
            <div class="col order-first">
                <h2>Vacinação</h2>
                <!-- Para cada vacina -->
                <ul class="list-group list-unstyled">
                    <?php if (!empty($selected['vacinas']) && is_array($selected['vacinas'])): ?>
                        <?php foreach ($selected['vacinas'] as $vac): ?>
                            <li class="list-group-item border-0"><?php echo htmlspecialchars($vac['nome'] ?? ''); ?> — <?php echo htmlspecialchars($vac['data'] ?? ''); ?></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item border-0">Nenhuma vacina registrada.</li>
                    <?php endif; ?>
                </ul>
                <br>
            </div>
        </div>

    </div>
</div>

<style>
    #div-share { max-width: 315px; }
    #btn-share { background-color: #56CCF2; border-color: #56CCF2; }
    #btn-share:hover { background-color: #49ADCE; border-color: #49ADCE; }
    #btn-share:active { background-color: #44A2BF; border-color: #44A2BF; }
    #share-options.show { display:flex; justify-content: space-between; margin-right: 20px; margin-left: 20px; margin-top: 20px; }
    #share-options.collapsing { transition: none !important; }
    .share-option svg:hover { fill: #F2994A; }
    .share-option svg:active { fill: #6FCF97; }
    .btn { min-width: 150px; }
    .img-carousel { width: 60px; padding: 2px; }
    .img-carousel:hover { filter: brightness(120%); }
    #img-main { max-width: 300px; }
    #img-main, .img-carousel { cursor: pointer; object-fit: cover; aspect-ratio: 1 / 1; object-position: center center; }
</style>

<script>
    function trocarImg(img) { document.getElementById("img-main").src = img; }
    function share() { navigator.clipboard.writeText(window.location.href); }
    function shareWhatsapp() { const link = encodeURIComponent(window.location.href); window.open(`https://wa.me/?text=${link}`, "_blank"); }
    function shareInstagram() { navigator.clipboard.writeText(window.location.href); alert("Link copiado para colar no Instagram!"); window.open("https://www.instagram.com/", "_blank"); }
    function shareFacebook() { const link = encodeURIComponent(window.location.href); window.open(`https://www.facebook.com/sharer/sharer.php?u=${link}`, "_blank"); }
</script>