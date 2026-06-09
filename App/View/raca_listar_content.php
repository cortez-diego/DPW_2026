<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<style>
    .main-content {
        display: flex;
        justify-content: center;
        padding: 1rem;
    }

    .page-wrapper {
        width: 100%;
    }

    /* Cabeçalho */
    .btn-voltar {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 18px;
        border: none;
        border-radius: 12px;
        background: #6FCF97;
        color: #fff;
        text-decoration: none;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 600;
        transition: .2s;
        box-shadow: 0 3px 12px rgba(111, 207, 151, .35);
    }

    .btn-voltar:hover {
        background: #58b87e;
        color: #fff;
    }

    /* Card */
    .ap-card {
        background: #fff;
        border: 1.5px solid #e8f5ef;
        border-radius: 24px;
        padding: 2rem;
        box-shadow:
            0 4px 32px rgba(111, 207, 151, .13),
            0 1px 6px rgba(0, 0, 0, .05);
    }

    /* Tabela */
    #tabela-racas {
        border-collapse: separate;
        border-spacing: 0 10px;
        margin-top: -5px !important;

    }

    #tabela-racas thead th {
        
        border: none !important;
        background: #6FCF97 !important;
        color: #fff !important;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 600;
        padding: 14px 16px;
    }

    #tabela-racas thead th:first-child {
        border-radius: 12px 0 0 12px;
    }

    #tabela-racas thead th:last-child {
        border-radius: 0 12px 12px 0;
    }

    #tabela-racas tbody tr {
        background: #fff;
        transition: .2s ease;
    }

    #tabela-racas tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(111, 207, 151, .15);
    }

    #tabela-racas tbody td {
        vertical-align: middle;
        border-top: 1px solid #f1f1f1;
        border-bottom: 1px solid #f1f1f1;
        padding: 16px;
        background: #fff !important;
    }

    #tabela-racas tbody td:first-child {
        border-left: 1px solid #f1f1f1;
        border-radius: 14px 0 0 14px;
    }

    #tabela-racas tbody td:last-child {
        border-right: 1px solid #f1f1f1;
        border-radius: 0 14px 14px 0;
    }

    /* Nome */
    .ap-nome {
        font-weight: 700;
        color: #4F4F4F;
        font-family: 'Poppins', sans-serif;
    }

    .ap-nome_especie {
        font-weight: 700;
        color: #4F4F4F;
        font-family: 'Poppins', sans-serif;
    }

    /* Status */
    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        min-width: 95px;
    }

    .status-pessimo {
        background: #EB5757;
        color: #fff;
    }

    .status-regular {
        background: #F2C94C;
        color: #fff;
    }

    .status-bom {
        background: #27AE60;
        color: #fff;
    }

    .status-muito-bom {
        background: #2F80ED;
        color: #fff;
    }

    .status-excelente {
        background: #56CCF2;
        color: #fff;
    }

    /* Botões */
    .btn-editar {
        border: none;
        background: #F2C94C;
        color: #fff;
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: .2s;
    }

    .btn-editar:hover {
        background: #e4bb41;
        color: #fff;
    }

    .btn-excluir {
        border: none;
        background: #EB5757;
        color: #fff;
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 13px;
        font-weight: 600;
        transition: .2s;
    }

    .btn-excluir:hover {
        background: #d94c4c;
    }

    /* DataTables */
    .dataTables_wrapper .dataTables_filter input {
        border: 1.5px solid #e0e0e0;
        border-radius: 10px;
        padding: 7px 12px;
        margin-left: 8px;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #6FCF97;
        outline: none;
        box-shadow: 0 0 0 3px rgba(111, 207, 151, .15);
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #6FCF97 !important;
        border-color: #6FCF97 !important;
        color: #fff !important;
        border-radius: 8px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #58b87e !important;
        border-color: #58b87e !important;
        color: #fff !important;
    }
</style>

<div class="main-content">
    <div class="container-fluid page-wrapper">
        <!-- Cabeçalho -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold" style="color:#4F4F4F;">
                    Listagem de Raças
                </h1>
                <p class="text-muted mb-0">
                    Gerencie as raças cadastradas no sistema
                </p>
            </div>
            <a href="/App/View/raca_cadastro.php" class="btn-voltar">
                + Nova Raça
            </a>
        </div>

        <!-- Card -->
        <div class="ap-card">
            <div class="table-responsive">
                <table id="tabela-racas" class="table table-striped table-hover" width="100% table-hover">
                   
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Raça</th>
                            <th>Espécie</th>
                            <th width="190">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- Raça 1 -->
                        <tr>
                            <td><strong>#1</strong></td>
                            <td>
                                <div class="ap-nome">
                                    Labrador
                                </div>
                            </td>
                            <td>
                                <div class="ap-nome_especie">
                                    Cachorro
                                </div>
                            </td>                       
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn-editar">
                                        Editar
                                    </a>
                                    
                                    <form method="POST"
                                            action="/dashboard/raca/excluir"
                                            style="display:inline-block;"
                                            onsubmit="return confirm('Tem certeza que deseja excluir esta raça?');">

                                            <input type="hidden"
                                                name="id"
                                                value="1">

                                            <button type="submit"
                                                class="btn btn-danger btn-sm">

                                                <i class="fas fa-trash"></i> Excluir

                                            </button>

                                        </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Raça 2 -->
                        <tr>
                            <td><strong>#2</strong></td>
                            <td>
                                <div class="ap-nome">
                                    Siâmes
                                </div>
                            </td>
                            <td>
                                <div class="ap-nome_especie">
                                    Cachorro
                                </div>
                            </td>                       
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn-editar">
                                        Editar
                                    </a>
                                    
                                    <form method="POST"
                                            action="/dashboard/raca/excluir"
                                            style="display:inline-block;"
                                            onsubmit="return confirm('Tem certeza que deseja excluir esta raça?');">

                                            <input type="hidden"
                                                name="id"
                                                value="2">

                                            <button type="submit"
                                                class="btn btn-danger btn-sm">

                                                <i class="fas fa-trash"></i> Excluir

                                            </button>

                                        </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Raça 3 -->
                        <tr>
                            <td><strong>#3</strong></td>
                            <td>
                                <div class="ap-nome">
                                    Persa
                                </div>
                            </td>
                            <td>
                                <div class="ap-nome_especie">
                                    Gato
                                </div>
                            </td>                       
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn-editar">
                                        Editar
                                    </a>
                                    
                                    <form method="POST"
                                            action="/dashboard/raca/excluir"
                                            style="display:inline-block;"
                                            onsubmit="return confirm('Tem certeza que deseja excluir esta raça?');">

                                            <input type="hidden"
                                                name="id"
                                                value="1">

                                            <button type="submit"
                                                class="btn btn-danger btn-sm">

                                                <i class="fas fa-trash"></i> Excluir

                                            </button>

                                        </form>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#tabela-racas').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json' },
            pageLength: 10,
            responsive: true
        });
    });
</script>