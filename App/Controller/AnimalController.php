<?php

namespace App\Controller;

use FW\Controller\Action;
use App\DAO\AnimalDAO;
use App\DAO\EspecieDAO;
use App\DAO\RacaDAO;
use App\DAO\AnimalRacaDAO;
use App\Model\AnimalModel;

class AnimalController extends Action
{
    // Pasta de upload relativa à raiz do projeto
    private $uploadDir = 'resources/dashboard/images/animais/';

    private function getProjectRoot(): string
    {
        return realpath(__DIR__ . '/../../') ?: __DIR__ . '/../../';
    }

    private function getUploadDirPath(): string
    {
        return rtrim($this->getProjectRoot(), '/') . '/' . trim($this->uploadDir, '/') . '/';
    }

    private function getUploadDirUrl(): string
    {
        return '/' . trim($this->uploadDir, '/');
    }

    private function getGalleryMetaPath(int $animalId): string
    {
        return $this->getUploadDirPath() . $animalId . '_gallery.json';
    }

    private function decodeBase64Image(string $base64Data): ?array
    {
        if (preg_match('/^data:(image\/(jpeg|jpg|png|webp));base64,(.+)$/', $base64Data, $matches)) {
            return [
                'mime' => $matches[1],
                'data' => base64_decode($matches[3]),
            ];
        }

        $decoded = base64_decode($base64Data, true);
        if ($decoded === false) {
            return null;
        }

        return [
            'mime' => 'image/jpeg',
            'data' => $decoded,
        ];
    }

    private function saveBase64Image(string $base64Data, ?int $animalId, string $animalNome, string $extensao = 'jpg'): string
    {
        $decoded = $this->decodeBase64Image($base64Data);
        if (!$decoded || empty($decoded['data'])) {
            return '';
        }

        if ($decoded['mime'] === 'image/png') {
            $extensao = 'png';
        } elseif ($decoded['mime'] === 'image/webp') {
            $extensao = 'webp';
        } else {
            $extensao = 'jpg';
        }

        $nomeArquivo = $this->gerarNomeArquivo($animalNome, $animalId, $extensao);
        $destinoAbs  = $this->getUploadDirPath() . $nomeArquivo;

        if (!is_dir(dirname($destinoAbs))) {
            mkdir(dirname($destinoAbs), 0755, true);
        }

        if (file_put_contents($destinoAbs, $decoded['data']) === false) {
            return '';
        }

        $this->croparImagemQuadrada($destinoAbs, $extensao);
        return $this->getUploadDirUrl() . '/' . $nomeArquivo;
    }

    public function listar()
    {
        $dao     = new AnimalDAO();
        $animais = $dao->listar();

        $this->getView()->title        = 'Animais';
        $this->getView()->title_pagina = 'Listar Animais';
        $this->getView()->animais      = $animais;

        $this->render('../dashboard/animal_listar', 'dashboard');
    }

    public function cadastro()
    {
        $especieDAO = new EspecieDAO();

        $this->getView()->title        = 'Cadastro de Animal';
        $this->getView()->title_pagina = 'Cadastro de Animal';
        $this->getView()->especies     = $especieDAO->listar();

        $this->render('../dashboard/animal_cadastro', 'dashboard');
    }

    public function cadastrar()
    {
        $foto = $this->processarUploadFoto();

        $model = new AnimalModel();
        $model->__set('nome',            $_POST['nome']            ?? '');
        $model->__set('data_nascimento', $_POST['data_nascimento'] ?? null);
        $model->__set('sexo',            $_POST['sexo']            ?? '');
        $model->__set('fk_especie_id',   $_POST['fk_especie_id']   ?? null);
        $model->__set('cor',             $_POST['cor']             ?? '');
        $model->__set('castrado',        !empty($_POST['castrado']));
        $model->__set('descricao',       $_POST['descricao']       ?? '');
        $model->__set('porte',           $_POST['porte']           ?? '');
        $model->__set('localizacao',     $_POST['localizacao']     ?? '');
        $model->__set('foto',            $foto);
        $model->__set('status',          $_POST['status']          ?? 'disponivel');

        $dao = new AnimalDAO();
        $animalId = $dao->inserir($model);

        // Vincular raça se fornecida
        if (!empty($_POST['fk_raca_id'])) {
            $animalRacaDAO = new AnimalRacaDAO();
            $animalRacaDAO->vincular($animalId, (int) $_POST['fk_raca_id']);
        }

        header('Location: /dashboard/animal/listar');
        die();
    }

    public function editar($params)
    {
        $root = realpath(__DIR__ . '/../../');
        $logFile = ($root && is_writable($root)) ? $root . '/controller_debug.log' : sys_get_temp_dir() . '/controller_debug.log';
        $timestamp = date('Y-m-d H:i:s');
        
        try {
            file_put_contents($logFile, "[$timestamp] AnimalController::editar called\n", FILE_APPEND);
            file_put_contents($logFile, "[$timestamp] params: " . print_r($params, true) . "\n", FILE_APPEND);
            
            $id = null;
            if (is_array($params)) {
                $id = $params['id'] ?? ($params[0] ?? null);
            } else {
                $id = $params;
            }
            
            file_put_contents($logFile, "[$timestamp] extracted id: " . var_export($id, true) . "\n", FILE_APPEND);

            $id = is_numeric($id) ? (int) $id : null;
            if (!$id) {
                file_put_contents($logFile, "[$timestamp] Invalid ID, redirecting\n", FILE_APPEND);
                header('Location: /dashboard/animal/listar');
                die();
            }

            $animalDAO     = new AnimalDAO();
            $animal        = $animalDAO->buscarPorId($id);
            
            file_put_contents($logFile, "[$timestamp] animal found: " . ($animal ? 'yes' : 'no') . "\n", FILE_APPEND);

            if (!$animal) {
                file_put_contents($logFile, "[$timestamp] Animal not found, redirecting\n", FILE_APPEND);
                header('Location: /dashboard/animal/listar');
                die();
            }

            $especieDAO    = new EspecieDAO();
            $racaDAO       = new RacaDAO();
            $animalRacaDAO = new AnimalRacaDAO();

            $especieId = (int) $animal->__get('fk_especie_id');

            $racasAll = $racaDAO->listar();
            $racas = $especieId
                ? $racaDAO->listarPorEspecie($especieId)
                : $racasAll;

            $animalRacas = $animalRacaDAO->listarPorAnimal($id);
            $racasVinculadas = [];
            foreach ($animalRacas as $ar) {
                $racasVinculadas[] = (int) $ar->fk_raca_id;
            }

            file_put_contents($logFile, "[$timestamp] All data loaded successfully\n", FILE_APPEND);

            $this->getView()->title           = 'Editar Animal';
            $this->getView()->title_pagina    = 'Editar Animal';
            $this->getView()->animal          = $animal;
            $this->getView()->especies        = $especieDAO->listar();
            $this->getView()->racas           = $racas;
            $this->getView()->racasAll        = $racasAll;
            $this->getView()->racasVinculadas = $racasVinculadas;
            $this->getView()->params          = $params;

            file_put_contents($logFile, "[$timestamp] About to render template\n", FILE_APPEND);
            $this->render('../dashboard/animal_editar', 'dashboard');
            file_put_contents($logFile, "[$timestamp] Template rendered successfully\n", FILE_APPEND);
            
        } catch (\Throwable $ex) {
            file_put_contents($logFile, "[$timestamp] Exception: " . $ex->getMessage() . "\n", FILE_APPEND);
            file_put_contents($logFile, "[$timestamp] Stack: " . $ex->getTraceAsString() . "\n", FILE_APPEND);
            throw $ex;
        }
    }

    public function alterar()
    {
        // DEBUG: log $_FILES and $_POST for troubleshooting upload issues
        $logFile = sys_get_temp_dir() . '/animal_alterar_debug.log';
        $timestamp = date('Y-m-d H:i:s');
        error_log("[$timestamp] ALTERAR() CALLED\n", 3, $logFile);
        error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n", 3, $logFile);
        error_log("FILES: " . var_export($_FILES, true) . "\n", 3, $logFile);
        error_log("POST: " . var_export($_POST, true) . "\n", 3, $logFile);

        $id = isset($_POST['id']) ? (int) $_POST['id'] : null;
        $fotoAtual = $_POST['foto_atual'] ?? '';

        $useGalleryCover = isset($_POST['gallery_cover']) && is_numeric($_POST['gallery_cover'])
            ? (int) $_POST['gallery_cover']
            : null;

        if ($useGalleryCover !== null) {
            $foto = $this->processarUploadFoto($fotoAtual, $id, $_POST['nome'] ?? '', 'gallery_images', $useGalleryCover);
        } else {
            $foto = $this->processarUploadFoto($fotoAtual, $id, $_POST['nome'] ?? '');
        }

        $model = new AnimalModel();
        $model->__set('id',              $_POST['id']              ?? null);
        $model->__set('nome',            $_POST['nome']            ?? '');
        $model->__set('data_nascimento', $_POST['data_nascimento'] ?? null);
        $model->__set('sexo',            $_POST['sexo']            ?? '');
        $model->__set('fk_especie_id',   $_POST['fk_especie_id']   ?? null);
        $model->__set('cor',             $_POST['cor']             ?? '');
        $model->__set('castrado',        !empty($_POST['castrado']));
        $model->__set('descricao',       $_POST['descricao']       ?? '');
        $model->__set('porte',           $_POST['porte']           ?? '');
        $model->__set('localizacao',     $_POST['localizacao']     ?? '');
        $model->__set('foto',            $foto);
        $model->__set('status',          $_POST['status']          ?? 'disponivel');

        $dao = new AnimalDAO();
        $dao->alterar($model);

        $racaIds = $_POST['fk_raca_id'] ?? [];
        if (!is_array($racaIds)) {
            $racaIds = [$racaIds];
        }
        $racaIds = array_filter(array_map('intval', $racaIds));

        $animalRacaDAO = new AnimalRacaDAO();
        $animalRacaDAO->sincronizar((int) $model->__get('id'), $racaIds);

        $this->processarUploadGaleria((int) $model->__get('id'), $useGalleryCover);

        header('Location: /dashboard/animal/listar');
        die();
    }

    public function excluir()
    {
        $id = $_POST['id'] ?? null;

        // Remove foto do servidor antes de excluir o registro
        $dao    = new AnimalDAO();
        $animal = $dao->buscarPorId($id);
        if ($animal && $animal->__get('foto')) {
            $this->removerFoto($animal->__get('foto'));
        }

        $dao->excluir($id);

        header('Location: /dashboard/animal/listar');
        die();
    }

    // ------------------------------------------------------------------ //
    //  Upload de foto
    // ------------------------------------------------------------------ //

    /**
     * Processa o upload da foto do animal.
     * Se nenhum arquivo for enviado, retorna a foto atual.
     *
     * @param  string $fotoAtual  Caminho da foto já salva (edição)
     * @return string             Caminho relativo salvo no banco
     */
    private function processarUploadFoto(string $fotoAtual = '', ?int $animalId = null, string $animalNome = '', string $fileField = 'foto', ?int $fileIndex = null): string
    {
        $uploadedPath = $this->getUploadDirPath();
        $publicDir    = $this->getUploadDirUrl();

        $fileName = '';
        $fileTmp  = '';
        $fileErr  = UPLOAD_ERR_NO_FILE;

        $field = $_FILES[$fileField] ?? null;
        if ($field) {
            if ($fileIndex === null) {
                $fileName = $field['name'] ?? '';
                $fileTmp  = $field['tmp_name'] ?? '';
                $fileErr  = $field['error'] ?? UPLOAD_ERR_NO_FILE;
            } else {
                if (!isset($field['name'][$fileIndex])) {
                    $fileName = '';
                    $fileErr  = UPLOAD_ERR_NO_FILE;
                } else {
                    $fileName = $field['name'][$fileIndex];
                    $fileTmp  = $field['tmp_name'][$fileIndex] ?? '';
                    $fileErr  = $field['error'][$fileIndex] ?? UPLOAD_ERR_NO_FILE;
                }
            }
        }

        if (empty($fileName) || $fileErr !== UPLOAD_ERR_OK || !is_uploaded_file($fileTmp)) {
            // Tenta dados base64 de crop se o upload não estiver disponível
            if ($fileField === 'foto' && !empty($_POST['cropped_foto'])) {
                $result = $this->saveBase64Image($_POST['cropped_foto'], $animalId, $animalNome);
                if ($result) {
                    if ($fotoAtual && $fotoAtual !== $result) {
                        $this->removerFoto($fotoAtual);
                    }
                    return $result;
                }
            }

            if ($fileField === 'gallery_images' && $fileIndex !== null) {
                $croppedKey = 'cropped_gallery_' . $fileIndex;
                if (!empty($_POST[$croppedKey])) {
                    $result = $this->saveBase64Image($_POST[$croppedKey], $animalId, $animalNome);
                    if ($result) {
                        return $result;
                    }
                }
            }

            return $fotoAtual;
        }

        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $extensao = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (!in_array($extensao, $extensoesPermitidas, true)) {
            return $fotoAtual;
        }

        $nomeArquivo = $this->gerarNomeArquivo($animalNome, $animalId, $extensao);
        $destinoAbs  = $uploadedPath . $nomeArquivo;

        if (!is_dir(dirname($destinoAbs))) {
            mkdir(dirname($destinoAbs), 0755, true);
        }

        if (!move_uploaded_file($fileTmp, $destinoAbs)) {
            return $fotoAtual;
        }

        $this->croparImagemQuadrada($destinoAbs, $extensao);
        $publicPath = rtrim($publicDir, '/') . '/' . $nomeArquivo;

        if ($fotoAtual && $fotoAtual !== $publicPath) {
            $this->removerFoto($fotoAtual);
        }

        return $publicPath;
    }

    private function processarUploadGaleria(int $animalId, ?int $coverIndex = null): array
    {
        $gallery = $this->obterGaleriaAnimal($animalId);
        $field = $_FILES['gallery_images'] ?? null;
        $uploadedPath = $this->getUploadDirPath();
        $publicDir = $this->getUploadDirUrl();

        if (!$field || !is_array($field['name'])) {
            return $gallery;
        }

        $maxFiles = 4;
        for ($index = 0; $index < $maxFiles; $index++) {
            if ($coverIndex !== null && $index === $coverIndex) {
                continue;
            }

            $croppedKey = 'cropped_gallery_' . $index;
            if (empty($field['name'][$index]) || $field['error'][$index] !== UPLOAD_ERR_OK) {
                if (!empty($_POST[$croppedKey])) {
                    $savedPath = $this->saveBase64Image($_POST[$croppedKey], $animalId, (string) $_POST['nome'], 'jpg');
                    if ($savedPath) {
                        $gallery[] = $savedPath;
                    }
                }
                continue;
            }

            $extensao = strtolower(pathinfo($field['name'][$index], PATHINFO_EXTENSION));
            if (!in_array($extensao, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                continue;
            }

            $nomeArquivo = $this->gerarNomeArquivo((string) $_POST['nome'], $animalId, $extensao);
            $destinoAbs  = $uploadedPath . $nomeArquivo;

            if (!is_dir(dirname($destinoAbs))) {
                mkdir(dirname($destinoAbs), 0755, true);
            }

            if (!move_uploaded_file($field['tmp_name'][$index], $destinoAbs)) {
                if (!empty($_POST[$croppedKey])) {
                    $savedPath = $this->saveBase64Image($_POST[$croppedKey], $animalId, (string) $_POST['nome'], $extensao);
                    if ($savedPath) {
                        $gallery[] = $savedPath;
                    }
                }
                continue;
            }

            $this->croparImagemQuadrada($destinoAbs, $extensao);
            $gallery[] = rtrim($publicDir, '/') . '/' . $nomeArquivo;
        }

        $this->salvarGaleriaAnimal($animalId, $gallery);
        return $gallery;
    }

    private function obterGaleriaAnimal(int $animalId): array
    {
        $metaFile = $this->getGalleryMetaPath($animalId);
        if (!file_exists($metaFile)) {
            return [];
        }

        $json = file_get_contents($metaFile);
        $data = json_decode($json, true);
        return is_array($data['images'] ?? null) ? $data['images'] : [];
    }

    private function salvarGaleriaAnimal(int $animalId, array $gallery): bool
    {
        $metaFile = $this->getGalleryMetaPath($animalId);
        if (!is_dir(dirname($metaFile))) {
            mkdir(dirname($metaFile), 0755, true);
        }

        $data = ['images' => array_values($gallery)];
        return file_put_contents($metaFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) !== false;
    }

    private function gerarNomeArquivo(string $animalNome, ?int $animalId, string $extensao): string
    {
        $slug = $this->slugify($animalNome ?: 'animal');
        $date = date('Ymd');
        $random = substr(bin2hex(random_bytes(3)), 0, 6);
        $idPart = $animalId ? $animalId . '_' : '';

        return sprintf('animal_%s%s_%s.%s', $idPart, $slug, $date . '_' . $random, $extensao);
    }

    private function slugify(string $value): string
    {
        $value = trim($value);
        $value = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
        $value = preg_replace('/[^a-zA-Z0-9]+/', '_', $value);
        $value = preg_replace('/_+/', '_', $value);
        $value = trim($value, '_');
        $value = strtolower($value);

        return $value !== '' ? $value : 'animal';
    }

    private function croparImagemQuadrada(string $caminho, string $extensao): void
    {
        if (!function_exists('getimagesize')) {
            return;
        }

        $info = getimagesize($caminho);
        if (!$info) {
            return;
        }

        [$width, $height] = $info;
        $size = min($width, $height);
        if ($size <= 0) {
            return;
        }

        switch ($info[2]) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($caminho);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($caminho);
                break;
            case IMAGETYPE_WEBP:
                $source = imagecreatefromwebp($caminho);
                break;
            default:
                return;
        }

        if (!$source) {
            return;
        }

        $crop = imagecreatetruecolor($size, $size);
        if ($info[2] === IMAGETYPE_PNG || $info[2] === IMAGETYPE_WEBP) {
            imagealphablending($crop, false);
            imagesavealpha($crop, true);
            $transparent = imagecolorallocatealpha($crop, 0, 0, 0, 127);
            imagefill($crop, 0, 0, $transparent);
        }

        $srcX = (int) floor(($width - $size) / 2);
        $srcY = (int) floor(($height - $size) / 2);
        imagecopyresampled($crop, $source, 0, 0, $srcX, $srcY, $size, $size, $size, $size);

        switch ($info[2]) {
            case IMAGETYPE_JPEG:
                imagejpeg($crop, $caminho, 90);
                break;
            case IMAGETYPE_PNG:
                imagepng($crop, $caminho, 8);
                break;
            case IMAGETYPE_WEBP:
                imagewebp($crop, $caminho, 90);
                break;
        }

        imagedestroy($source);
        imagedestroy($crop);
    }

    /**
     * Remove o arquivo de foto do servidor.
     */
    private function removerFoto(string $caminho): void
    {
        if (!$caminho) {
            return;
        }

        $relativePath = ltrim($caminho, '/');
        $path = $this->getProjectRoot() . '/' . $relativePath;
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function validaAutenticacao()
    {
        if (
            !isset($_SESSION['id'])   || $_SESSION['id']   == '' ||
            !isset($_SESSION['nome']) || $_SESSION['nome'] == ''
        ) {
            header('Location: /login');
            die();
        }
    }
}