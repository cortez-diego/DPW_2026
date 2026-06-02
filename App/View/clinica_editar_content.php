<style>
    /*  Estilos base do projeto  */
    .main-content {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    /*  Cabeçalho  */
    .btn-voltar {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border: 1.5px solid #e0e0e0;
        border-radius: 10px;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: #4F4F4F;
        text-decoration: none;
        background: #fff;
        transition: border-color 0.2s, color 0.2s;
    }

    .btn-voltar:hover {
        border-color: #6FCF97;
        color: #6FCF97;
    }

    .page-title {
        font-family: 'Poppins', sans-serif;
        font-size: 20px;
        font-weight: 700;
        color: #4F4F4F;
        margin: 0 0 2px;
    }

    .page-subtitle {
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        color: #888;
        margin: 0;
    }

    /*  Card  */
    .ap-form-card {
        background: #fff;
        border: 1.5px solid #e8f5ef;
        border-radius: 24px;
        padding: 2.5rem 2rem;
        box-shadow: 0 4px 32px rgba(111, 207, 151, 0.13),
                    0 1px 6px rgba(0, 0, 0, 0.05);
    }

    /*  Seções  */
    .ap-section-title {
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 700;
        color: #6FCF97;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding-bottom: 8px;
        border-bottom: 1.5px solid #e8f5ef;
        margin-bottom: 1.25rem;
    }

    .ap-section {
        margin-bottom: 2rem;
    }

    /*  Labels  */
    .ap-label {
        display: block;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: #4F4F4F;
        margin-bottom: 5px;
    }

    .ap-label .req {
        color: #F2994A;
        margin-left: 2px;
    }

    /*  Inputs e select  */
    .ap-input,
    .ap-select {
        width: 100%;
        padding: 10px 12px;
        border: 1.5px solid #e0e0e0;
        border-radius: 10px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        color: #4F4F4F;
        background: #FAF9F6;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        appearance: none;
    }

    .ap-input:focus,
    .ap-select:focus {
        border-color: #6FCF97;
        box-shadow: 0 0 0 3px rgba(111, 207, 151, 0.15);
        background: #fff;
    }

    .ap-input[readonly] {
        background: #f0f2f0;
        color: #aaa;
        cursor: not-allowed;
    }

    .ap-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23b0b0b0' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 32px;
    }

    /*  Botões  */
    .ap-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 11px 22px;
        background: #6FCF97;
        color: #fff;
        border: none;
        border-radius: 12px;
        font-family: 'Poppins', sans-serif;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 3px 12px rgba(111, 207, 151, 0.35);
        transition: background 0.2s;
        text-decoration: none;
    }

    .ap-btn-primary:hover {
        background: #58b87e;
        color: #fff;
    }

    .ap-btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 11px 22px;
        background: transparent;
        color: #4F4F4F;
        border: 1.5px solid #e0e0e0;
        border-radius: 12px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: border-color 0.2s, color 0.2s;
        text-decoration: none;
    }

    .ap-btn-secondary:hover {
        border-color: #6FCF97;
        color: #6FCF97;
    }

    /*  Grid  */
    .ap-grid {
        display: grid;
        gap: 1rem;
    }

    .ap-grid-4 {
        grid-template-columns: repeat(4, 1fr);
    }

    .ap-col-2 {
        grid-column: span 2;
    }

    @media (max-width: 768px) {
        .ap-grid-4 {
            grid-template-columns: 1fr;
        }

        .ap-col-2 {
            grid-column: span 1;
        }
    }

    @media (min-width: 769px) and (max-width: 992px) {
        .ap-grid-4 {
            grid-template-columns: repeat(2, 1fr);
        }

        .ap-col-2 {
            grid-column: span 2;
        }
    }
</style>

<div class="main-content">
    <div class="container-fluid">

        <!-- Cabeçalho -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title">
                    Editar Clinica
                </h1>

                <p class="page-subtitle">
                    Atualize as informações da clinica
                </p>
            </div>

            <a href="/dashboard/clinica/listar" class="btn-voltar">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Voltar
            </a>
        </div>

        <!-- Card -->
        <div class="ap-form-card">

            <form method="POST" action="/dashboard/clinica/alterar">

                <input type="hidden" name="id" value="1">

                <!-- Dados da Clinica -->
                <div class="ap-section">
                    <div class="ap-section-title">
                        Dados da Clinica
                    </div>

                    <div class="ap-grid ap-grid-4">

                        <div class="ap-col-2">
                            <label class="ap-label" for="nome">
                                Nome <span class="req">*</span>
                            </label>

                            <input type="text"
                                    class="form-control "
                                    id="cln_nome"
                                    name="cln_nome"
                                    required
                                    value="Clinica Animal">
                        </div>

                        <div>
                            <label class="ap-label" for="cln_cnpj">
                                CNPJ
                            </label>

                            <input type="text"
                                    class="form-control ap-input"
                                    id="cln_cnpj"
                                    name="cln_cnpj"
                                    value="00.394.460/0058-87">
                        </div>
                        
                    </div>
                </div>

                <!-- Endereço -->
                <div class="ap-section">

                    <div class="ap-section-title">
                        Endereço
                    </div>

                    <div class="ap-grid ap-grid-4">

                        <div>
                            <label for="cln_cep" class="form-label ap-label">
                                CEP
                            </label>

                            <input type="text"
                                class="form-control ap-input"
                                id="cln_cep"
                                name="cln_cep"
                                value="45810-000">
                        </div>

                        <div class="ap-col-2">
                             <label for="cln_estado" class="form-label ap-label">
                                Estado
                            </label>

                            <input type="text"
                                class="form-control ap-input"
                                id="cln_estado"
                                name="cln_estado"
                                value="SP">

                        </div>

                        <div>
                            
                            <label for="cln_cidade" class="form-label ap-label">
                            Cidade
                            </label>

                            <input type="text"
                                class="form-control ap-input"
                                id="cln_cidade"
                                name="cln_cidade"
                                value="São João da Boa Vista">
                            </div>

                        <div>
                            <label class="ap-label" for="cln_bairro">
                                Bairro
                            </label>

                            <input type="text"
                                class="form-control ap-input"
                                id="cln_bairro"
                                name="cln_bairro"
                                value="Jardim Recanto">

                        </div>

                        <div>
                            <label class="ap-label" for="cln_logradouro">
                                Logradouro
                            </label>

                            <input type="text"
                                class="form-control ap-input"
                                id="cln_logradouro"
                                name="cln_logradouro"
                                value="Av. Paulista">
                        </div>

                        <div>
                            <label class="ap-label" for="cln_numero">
                                Número
                            </label>

                            <input type="text"
                                class="form-control ap-input"
                                id="cln_numero"
                                name="cln_numero"
                                value="257">
                        </div>

                        <div>
                            <label class="ap-label" for="cln_complemento">
                                Complemento
                            </label>

                            <input type="text"
                                class="form-control ap-input"
                                id="cln_complemento"
                                name="cln_complemento"
                                value=" ">
                        </div>

                    </div>
                </div>

                <div class="ap-section">
                    <div class="ap-section-title">
                        Contato
                    </div>

                    <div class="ap-grid ap-grid-4">
                        <div>
                            <label class="ap-label" for="cln_complemento">
                                Telefone 1
                            </label>

                            <input  type="text"
                                    class="form-control ap-input"
                                    id="cln_tel1"
                                    name="cln_tel1"
                                    value="(19) 99876-1122">
                        </div>

                        <div>
                            <label class="ap-label" for="cln_complemento">
                                Telefone 2
                            </label>

                            <input  type="text"
                                     class="form-control ap-input"
                                    id="cln_tel2"
                                    name="cln_tel2"
                                    value="(19) 99111-2233">
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div style="display:flex; gap: 10px; padding-top: 0.5rem;">

                    <button type="submit" class="ap-btn-primary">
                        Salvar
                    </button>

                    <a href="/dashboard/adotante/listar" class="ap-btn-secondary">
                        Cancelar
                    </a>

                </div>

            </form>

        </div>
    </div>
</div>