<?php
/**
 * AmigoPet - Listagem de Adotantes
 * Localização: ~/App/View/adotante_listar_content.php
 */

// Simulação de base de dados de utilizadores
$adotantes = [

    (object) [
        'id' => 1,
        'nome' => 'Mariana Oliveira',
        'cpf' => '123.456.789-00',
        'tel1' => '(19) 99876-1122',
        'cidade' => 'São João da Boa Vista',
        'estado' => 'SP',
        'status' => 'Excelente'
    ],

    (object) [
        'id' => 2,
        'nome' => 'Carlos Henrique Souza',
        'cpf' => '987.654.321-10',
        'tel1' => '(19) 99123-4455',
        'cidade' => 'Águas da Prata',
        'estado' => 'SP',
        'status' => 'Bom'
    ],

    (object) [
        'id' => 3,
        'nome' => 'Fernanda Lima Costa',
        'cpf' => '741.852.963-20',
        'tel1' => '(19) 99777-8899',
        'cidade' => 'Poços de Caldas',
        'estado' => 'MG',
        'status' => 'Regular'
    ]
];
?>

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
    #tabela-adotantes {
        border-collapse: separate;
        border-spacing: 0 10px;
        margin-top: -10px !important;
    }

    #tabela-adotantes thead th {
        border: none !important;
        background: #6FCF97 !important;
        color: #fff !important;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 600;
        padding: 14px 16px;
    }

    #tabela-adotantes thead th:first-child {
        border-radius: 12px 0 0 12px;
    }

    #tabela-adotantes thead th:last-child {
        border-radius: 0 12px 12px 0;
    }

    #tabela-adotantes tbody tr {
        background: #fff;
        transition: .2s ease;
    }

    #tabela-adotantes tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(111, 207, 151, .15);
    }

    #tabela-adotantes tbody td {
        vertical-align: middle;
        border-top: 1px solid #f1f1f1;
        border-bottom: 1px solid #f1f1f1;
        padding: 16px;
        background: #fff !important;
    }

    #tabela-adotantes tbody td:first-child {
        border-left: 1px solid #f1f1f1;
        border-radius: 14px 0 0 14px;
    }

    #tabela-adotantes tbody td:last-child {
        border-right: 1px solid #f1f1f1;
        border-radius: 0 14px 14px 0;
    }

    /* Nome */
    .ap-nome {
        font-weight: 700;
        color: #4F4F4F;
        font-family: 'Poppins', sans-serif;
    }

    .ap-cidade {
        color: #828282;
        font-size: 13px;
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
                    Listagem de Adotantes
                </h1>
                <p class="text-muted mb-0">
                    Gerencie os adotantes cadastrados no sistema
                </p>
            </div>
            <a href="/dashboard/adotante/cadastro" class="btn-voltar">
                + Novo Adotante
            </a>
        </div>

        <!-- Card -->
        <div class="ap-card">
            <div class="table-responsive">
                <table id="tabela-adotantes" class="table align-middle w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Adotante</th>
                            <th>CPF</th>
                            <th>Telefone</th>
                            <th>Localização</th>
                            <th>Status</th>
                            <th width="190">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td><strong>#1</strong></td>
                            <td>
                                <div class="ap-nome">
                                    Mariana Oliveira
                                </div>
                            </td>
                            <td>
                                123.456.789-00
                            </td>
                            <td>
                                (19) 99876-1122
                            </td>
                            <td>
                                <div class="ap-cidade">
                                    São João da Boa Vista - SP
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-excelente">
                                    Excelente
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn-editar">
                                        Editar
                                    </a>
                                    <button class="btn-excluir">
                                        Excluir
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>#2</strong></td>
                            <td>
                                <div class="ap-nome">
                                    Carlos Henrique Souza
                                </div>
                            </td>
                            <td>
                                987.654.321-10
                            </td>
                            <td>
                                (19) 99123-4455
                            </td>
                            <td>
                                <div class="ap-cidade">
                                    Águas da Prata - SP
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-bom">
                                    Bom
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn-editar">
                                        Editar
                                    </a>

                                    <button class="btn-excluir">
                                        Excluir
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>#3</strong></td>
                            <td>
                                <div class="ap-nome">
                                    Fernanda Lima Costa
                                </div>
                            </td>
                            <td>
                                741.852.963-20
                            </td>
                            <td>
                                (19) 99777-8899
                            </td>
                            <td>
                                <div class="ap-cidade">
                                    Poços de Caldas - MG
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-regular">
                                    Regular
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn-editar">
                                        Editar
                                    </a>
                                    <button class="btn-excluir">
                                        Excluir
                                    </button>
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
        $('#tabela-adotantes').DataTable({
            "language": { "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json" },
            "pageLength": 10,
            "responsive": true
        });
    });
</script>