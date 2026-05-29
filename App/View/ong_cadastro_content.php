<?php
/**
 * AmigoPet - Cadastro de ONG
 * Localização: ~/App/View/dashboard/ong_cadastro_content.php
 */
?>

<style>
    /*  Estilos base do projeto  */
    .main-content {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    
    .pet-card-inner, .table tr { 
        cursor: pointer; transition: z-index 0.3s; 
    }
    
    .pet-card-inner:hover { 
        z-index: 50; position: relative; 
    }
    
    .pet-card-inner img, .table img {
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); transform-origin: center center; 
    }
    
    .pet-card-inner:hover .ratio { 
        overflow: visible !important; 
    }
    
    .pet-card-inner:hover img { 
        object-fit: contain !important; 
        position: absolute; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%; 
        transform: scale(1.25); 
        background-color: #ffffff; 
        z-index: 100; 
        box-shadow: 0 15px 35px rgba(0,0,0,0.2); 
        border-radius: 12px; 
    }
    tr:hover .img-thumbnail { 
        object-fit: contain !important; 
        transform: scale(2.2); 
        z-index: 100; 
        position: relative; 
        background-color: #ffffff; 
        box-shadow: 0 8px 20px rgba(0,0,0,0.15); 
        max-height: 200px; 
    }
    .gender-icon { 
        width: 14px; 
        height: 14px; 
        vertical-align: middle; 
        margin-right: 4px; 
    }
    .gender-macho { 
        color: #56CCF2; 
    } .gender-femea { 
        color: #F2994A; 
    }
    
    .carousel-control-prev, .carousel-control-next { width: 45px; height: 45px; background-color: var(--primary-green); border-radius: 50%; top: 50%; transform: translateY(-50%); opacity: 1; border: 3px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); z-index: 60; position: absolute; }
    
    .carousel-control-prev { left: -30px; } .carousel-control-next { right: -30px; }
    
    .carousel-control-prev:hover, .carousel-control-next:hover { background-color: var(--secondary-orange); color: white; }
    
    .carousel-control-prev-icon, .carousel-control-next-icon { width: 22px; height: 22px; }

    /*  Botão Voltar  */
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
        border-color: #6FCF97; color: #6FCF97; 
    }

    /*  Card  */
    .ap-form-card {
        background: #fff;
        border: 1.5px solid #e8f5ef;
        border-radius: 24px;
        padding: 2.5rem 2rem;
        box-shadow: 0 4px 32px rgba(111,207,151,0.13), 0 1px 6px rgba(0,0,0,0.05);
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

    .ap-section { margin-bottom: 2rem; }

    /*  Labels  */
    .form-label {
        display: block;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: #4F4F4F;
        margin-bottom: 5px;
    }

    .form-label .req { 
        color: #F2994A; margin-left: 2px; 
    }
    .form-label .opt { 
        font-weight: 400; color: #aaa; font-size: 12px; margin-left: 4px; 
    }

    /*  Inputs e select  */
    .form-control, .ap-select {
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
        box-sizing: border-box;
    }

    .form-control:focus,
    .ap-select:focus {
        border-color: #6FCF97;
        box-shadow: 0 0 0 3px rgba(111,207,151,0.15);
        background: #fff;
    }

    .form-control[] {
        background: #f0f2f0;
        color: #aaa;
        cursor: not-allowed;
    }

    .form-control.is-invalid {
        border-color: #F2994A;
        box-shadow: 0 0 0 3px rgba(242,153,74,0.12);
    }

    .ap-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23b0b0b0' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 32px;
    }

    /*  Toggle de status  */
    .ap-toggle-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        border: 1.5px solid #e0e0e0;
        border-radius: 10px;
        background: #FAF9F6;
        max-width: 260px;
        cursor: pointer;
        user-select: none;
        transition: border-color 0.2s;
    }

    .ap-toggle-wrap:hover { 
        border-color: #6FCF97; 
    }

    .ap-toggle {
        position: relative;
        width: 40px;
        height: 22px;
        flex-shrink: 0;
    }

    .ap-toggle input { 
        opacity: 0; width: 0; height: 0; 
    }

    .ap-toggle-slider {
        position: absolute;
        inset: 0;
        background: #e0e0e0;
        border-radius: 22px;
        transition: background 0.2s;
    }

    .ap-toggle-slider::before {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        left: 3px;
        top: 3px;
        background: #fff;
        border-radius: 50%;
        transition: transform 0.2s;
        box-shadow: 0 1px 4px rgba(0,0,0,0.15);
    }

    .ap-toggle input:checked + .ap-toggle-slider { 
        background: #6FCF97; 
    }
    .ap-toggle input:checked + .ap-toggle-slider::before { 
        transform: translateX(18px); 
    }

    .ap-toggle-label {
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 600;
        color: #4F4F4F;
    }

    .ap-toggle-label .status-text { 
        color: #6FCF97; 
    }
    .ap-toggle-label .status-text.inativa { 
        color: #aaa; 
    }

    /*  Alerta de validação  */
    .ap-alert {
        display: none;
        align-items: center;
        gap: 10px;
        background: #fff8f0;
        border: 1.5px solid #f5c98a;
        color: #a05f00;
        border-radius: 10px;
        padding: 11px 14px;
        margin-bottom: 1.5rem;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 500;
    }

    .ap-alert.show { display: flex; }
    .ap-alert svg { flex-shrink: 0; width: 18px; height: 18px; stroke: currentColor; }

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
        box-shadow: 0 3px 12px rgba(111,207,151,0.35);
        transition: background 0.2s;
        text-decoration: none;
    }

    .ap-btn-primary:hover { background: #58b87e; color: #fff; }

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

    .ap-btn-secondary:hover { border-color: #6FCF97; color: #6FCF97; }

    /*  Grid  */
    .ap-grid { display: grid; gap: 1rem; }
    .ap-grid-2 { grid-template-columns: repeat(2, 1fr); }
    .ap-grid-3 { grid-template-columns: repeat(3, 1fr); }
    .ap-grid-4 { grid-template-columns: repeat(4, 1fr); }
    .ap-col-2 { grid-column: span 2; }
    .ap-col-3 { grid-column: span 3; }

    @media (max-width: 768px) {
        .ap-grid-2, .ap-grid-3, .ap-grid-4 { grid-template-columns: 1fr; }
        .ap-col-2, .ap-col-3 { grid-column: span 1; }
    }

    @media (min-width: 769px) and (max-width: 992px) {
        .ap-grid-4 { grid-template-columns: repeat(2, 1fr); }
        .ap-grid-3 { grid-template-columns: repeat(2, 1fr); }
        .ap-col-3 { grid-column: span 2; }
    }
</style>

<div class="main-content">
    <div class="container-fluid">

        <!-- Cabeçalho -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold" style="color:#4F4F4F;">Cadastro de ONG</h1>
                <p class="text-muted mb-0">Preencha os dados para solicitar o cadastro da ONG</p>
            </div>
            <a href="ong_listar.php" class="btn-voltar">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Voltar
            </a>
        </div>

        <!-- Alerta de validação -->
        <div class="ap-alert" id="apAlert">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
            <span id="apAlertMsg">Preencha todos os campos obrigatórios antes de continuar.</span>
        </div>

        <!-- Card do formulário -->
        <div class="ap-form-card">
            <form method="POST" action="/dashboard/ong/cadastrar">

                <!-- Dados da ONG -->
                <div class="ap-section">
                    <div class="ap-section-title">Dados da ONG</div>
                    <div class="ap-grid ap-grid-4">
                        <div class="ap-col-2">
                            <label class="form-label" for="nome">Nome da ONG <span class="req">*</span></label>
                            <input type="text" class="form-control" id="nome" name="nome" maxlength="50" required>
                        </div>
                        <div>
                            <label class="form-label" for="cnpj">CNPJ <span class="req">*</span></label>
                            <input type="text" class="form-control" id="cnpj" name="cnpj" maxlength="18" placeholder="00.000.000/0000-00" required>
                        </div>
                        <div>
                            <label class="form-label" for="telefone">Telefone de contato <span class="req">*</span></label>
                            <input type="text" class="form-control" id="telefone" name="telefone" maxlength="20" placeholder="(00) 00000-0000" required>
                        </div>
                        <div class="ap-col-2">
                            <label class="form-label" for="email">E-mail institucional <span class="req">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" maxlength="255" placeholder="contato@ong.org.br" required>
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="ap-section">
                    <div class="ap-section-title">Endereço</div>
                    <div class="ap-grid ap-grid-4">
                        <div>
                            <label class="form-label" for="cep">CEP</label>
                            <input type="text" class="form-control" id="cep" name="cep" maxlength="9" placeholder="00000-000">
                        </div>
                        <div class="ap-col-2">
                            <label class="form-label" for="logradouro">Logradouro</label>
                            <input type="text" class="form-control" id="logradouro" name="logradouro" >
                        </div>
                        <div>
                            <label class="form-label" for="numero">Número</label>
                            <input type="text" class="form-control" id="numero" name="numero">
                        </div>
                        <div>
                            <label class="form-label" for="complemento">Complemento</label>
                            <input type="text" class="form-control" id="complemento" name="complemento">
                        </div>
                        <div>
                            <label class="form-label" for="bairro">Bairro</label>
                            <input type="text" class="form-control" id="bairro" name="bairro" >
                        </div>
                        <div>
                            <label class="form-label" for="cidade">Cidade</label>
                            <input type="text" class="form-control" id="cidade" name="cidade" >
                        </div>
                        <div>
                            <label class="form-label" for="estado">UF</label>
                            <input type="text" class="form-control" id="estado" name="estado"  maxlength="2">
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="ap-section">
                    <div class="ap-section-title">Status</div>
                    <label class="form-label" for="statusToggle">Status da ONG</label>
                    <label class="ap-toggle-wrap" for="statusToggle">
                        <div class="ap-toggle">
                            <input type="checkbox" id="statusToggle" name="status" value="1" checked>
                            <span class="ap-toggle-slider"></span>
                        </div>
                        <span class="ap-toggle-label">
                            <span class="status-text" id="statusText">Ativa</span>
                        </span>
                    </label>
                    <!-- Campo hidden para garantir envio correto do boolean -->
                    <input type="hidden" name="status_value" id="statusValue" value="1">
                </div>

                <!-- Botões -->
                <div style="display:flex; gap: 10px; padding-top: 0.5rem;">
                    <button type="submit" class="ap-btn-primary">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V7l-4-4z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 3v4H7V3M12 12v5m-2-2h4"/>
                        </svg>
                        Cadastrar
                    </button>
                    <a href="ong_listar.php" class="ap-btn-secondary">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancelar
                    </a>
                </div>

            </form>
        </div>

    </div>
</div>