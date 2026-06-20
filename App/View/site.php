<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($this->view->title) ? $this->view->title : 'AmigoPet'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="/resources/dashboard/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="/resources/dashboard/css/style.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body style="background-color: #f5f5f5;">

<?php
// Conteúdo dinâmico
$page = '';
if (isset($this->view) && isset($this->view->page) && !empty($this->view->page)) {
    $page = $this->view->page;
}

if (!empty($page)) {
    $contentPath = __DIR__ . '/site/' . $page . '.php';
    if (file_exists($contentPath)) {
        include $contentPath;
    } else {
        echo '<div class="alert alert-danger">Página não encontrada</div>';
    }
} else {
    echo '<div class="alert alert-danger">Nenhuma página definida</div>';
}
?>

</body>
</html>
