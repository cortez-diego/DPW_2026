<?php
// Buscar animal no backend (DAO) ao invés do mock
$petId = isset($_GET['id']) ? intval($_GET['id']) : null;
$selected = null;
if ($petId) {
    $dao = new \App\DAO\AnimalDAO();
    $model = $dao->buscarPorId($petId);
    if ($model) {
        // Mapear para o mesmo formato que a view espera (compatibilidade com o mock)
        $idade_meses = $model->__get('idade_meses');
        $idade = '';
        if ($idade_meses !== null && $idade_meses !== '') {
            $anos = intdiv((int)$idade_meses, 12);
            $meses = (int)$idade_meses % 12;
            $idade = trim(($anos > 0 ? $anos . ' ano' . ($anos > 1 ? 's' : '') : '') .
                          ($anos > 0 && $meses > 0 ? ' ' : '') .
                          ($meses > 0 ? $meses . ' mês' . ($meses > 1 ? 'es' : '') : ''));
        }

        $selected = [
            'id' => $model->__get('id'),
            'imagem' => $model->__get('foto'),
            'nome' => $model->__get('nome'),
            'sexo' => $model->__get('sexo'),
            'porte' => $model->__get('porte'),
            'idade' => $idade,
            'especie' => $model->__get('especie_nome'),
            'raca' => $model->__get('racas'),
            'cor' => $model->__get('cor'),
            'nascimento' => $model->__get('data_nascimento'),
            'historico' => '',
            'status' => $model->__get('status'),
            'ong' => $model->__get('ong_nome'),
            'alergias' => '',
            'castrado' => $model->__get('castrado') ? 'Sim' : 'Não',
            'data_castracao' => '',
            'observacoes_veterinarias' => '',
            'vacinas' => [],
            'descricao' => $model->__get('descricao'),
        ];

        function obterGaleriaAnimal(int $id): array {
            $metaFile = __DIR__ . '/../../../resources/dashboard/images/animais/' . $id . '_gallery.json';
            if (!file_exists($metaFile)) {
                return [];
            }
            $json = file_get_contents($metaFile);
            $data = json_decode($json, true);
            return is_array($data['images'] ?? null) ? $data['images'] : [];
        }

        $galleryImages = obterGaleriaAnimal($selected['id']);
    }
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
                <img id="img-main" class="img-thumbnail m-4" src="<?php echo htmlspecialchars($selected['imagem'] ?? ''); ?>">
                <div class="d-flex px-4 gap-2 flex-wrap">
                    <?php
                        $galleryPreview = !empty($galleryImages) ? array_slice($galleryImages, 0, 4) : [];
                        while (count($galleryPreview) < 4) {
                            $galleryPreview[] = $selected['imagem'];
                        }
                    ?>
                    <?php foreach ($galleryPreview as $image): ?>
                        <img class="img-carousel" src="<?php echo htmlspecialchars($image); ?>" onclick="trocarImg(this.src)" style="width: calc(25% - 8px); min-width: 80px;">
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-md-8 col-sm-12 p-5">
                <h1><?php echo htmlspecialchars($selected['nome'] ?? ''); ?></h1>
                <p><?php echo htmlspecialchars($selected['sexo'] ?? ''); ?> | <?php echo htmlspecialchars($selected['porte'] ?? 'Médio'); ?> | <?php echo htmlspecialchars($selected['idade'] ?? ''); ?></p>
                <div>
                    <div id="div-share" class="w-100">
                        <button id="btn-share" class="btn btn-primary w-100" data-bs-toggle="collapse" data-bs-target="#share-options">Compartilhar</button>
                        <br>
                        <div id="share-options" class="collapse">
                            <a
                                href="#"
                                class="share-option"
                                onclick="share()"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#4F4F4F" class="bi bi-share" viewBox="0 0 16 16"><path d="M13.5 1a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3M11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.5 2.5 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5m-8.5 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3m11 5.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3"/></svg></a>
                            <a
                                href='whatsapp://send?text=AQUI VEM O LINK DA PÁGINA'
                                class="share-option"
                                onclick="shareWhatsapp()"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#4F4F4F" class="bi bi-whatsapp" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/></svg></a>
                            <a
                                href="#"
                                class="share-option"
                                onclick="shareInstagram()"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#4F4F4F" class="bi bi-instagram" viewBox="0 0 16 16"><path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/></svg></a>
                            <a
                                href="#"
                                class="share-option"
                                onclick="shareFacebook()"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#4F4F4F" class="bi bi-facebook" viewBox="0 0 16 16"><path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/></svg></a>
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
                        <li class="list-group-item border-0">Nome: <?php echo htmlspecialchars($selected['nome'] ?? ''); ?></li>
                        <li class="list-group-item border-0">Espécie: <?php echo htmlspecialchars($selected['especie'] ?? ''); ?></li>
                        <li class="list-group-item border-0">Raça: <?php echo htmlspecialchars($selected['raca'] ?? ''); ?></li>
                        <li class="list-group-item border-0">Cor: <?php echo htmlspecialchars($selected['cor'] ?? ''); ?></li>
                        <li class="list-group-item border-0">Sexo: <?php echo htmlspecialchars($selected['sexo'] ?? ''); ?></li>
                        <li class="list-group-item border-0">Nascimento: <?php echo htmlspecialchars($selected['nascimento'] ?? ''); ?></li>
                        <li class="list-group-item border-0">Idade: <?php echo htmlspecialchars($selected['idade'] ?? ''); ?></li>
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