<style>
    /*  Estilos base do projeto  */
    .main-content {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .pet-card-inner, .table tr {
        cursor: pointer;
        transition: z-index 0.3s;
    }

    .pet-card-inner:hover {
        z-index: 50;
        position: relative;
    }

    .pet-card-inner img,
    .table img {
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        transform-origin: center center;
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
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        border-radius: 12px;
    }

    tr:hover .img-thumbnail {
        object-fit: contain !important;
        transform: scale(2.2);
        z-index: 100;
        position: relative;
        background-color: #ffffff;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
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
    }

    .gender-femea {
        color: #F2994A;
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 45px;
        height: 45px;
        background-color: var(--primary-green);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        opacity: 1;
        border: 3px solid #fff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        z-index: 60;
        position: absolute;
    }

    .carousel-control-prev {
        left: -30px;
    }

    .carousel-control-next {
        right: -30px;
    }

    .carousel-control-prev:hover, .carousel-control-next:hover {
        background-color: var(--secondary-orange);
        color: white;
    }

    .carousel-control-prev-icon, .carousel-control-next-icon {
        width: 22px;
        height: 22px;
    }

    /* Página */
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

    /*  Card  */
    .ap-form-card {
        background: #fff;
        border: 1.5px solid #e8f5ef;
        border-radius: 24px;
        padding: 2.5rem 2rem;
        box-shadow: 0 4px 32px rgba(111, 207, 151, 0.13), 0 1px 6px rgba(0, 0, 0, 0.05);
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
    .form-label {
        display: block;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: #4F4F4F;
        margin-bottom: 5px;
    }

    .form-label .req {
        color: #F2994A;
        margin-left: 2px;
    }

    .form-label .opt {
        font-weight: 400;
        color: #aaa;
        font-size: 12px;
        margin-left: 4px;
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

    .ap-grid-2 {
        grid-template-columns: repeat(2, 1fr);
    }

    .ap-grid-3 {
        grid-template-columns: repeat(3, 1fr);
    }

    .ap-grid-4 {
        grid-template-columns: repeat(4, 1fr);
    }

    .ap-col-2 {
        grid-column: span 2;
    }
</style>

<div class="main-content">
    <div class="container-fluid">

        <!-- Cabeçalho -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold" style="color:#4F4F4F;">
                    Cadastro de usuário
                </h1>
            </div>

            <a href="/" class="btn-voltar">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Voltar
            </a>
        </div>

        <!-- Card do formulário -->
        <div class="ap-form-card">
            <?php if (isset($_GET['erro'])): ?>
                <?php if ($_GET['erro'] == 1): ?>
                    <div class="alert alert-danger" role="alert">As senhas não conferem.</div>
                <?php elseif ($_GET['erro'] == 2): ?>
                    <div class="alert alert-danger" role="alert">A senha deve ter pelo menos 8 caracteres.</div>
                <?php elseif ($_GET['erro'] == 3): ?>
                    <div class="alert alert-danger" role="alert">E-mail já cadastrado no sistema.</div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (isset($_GET['sucesso'])): ?>
                <div class="alert alert-success" role="alert">Cadastro realizado com sucesso! Faça login para continuar.</div>
            <?php endif; ?>

            <form method="POST" action="/adotante/cadastrarPublico">

                <!-- Dados Pessoais -->
                <div class="ap-section">
                    <div class="ap-section-title">Dados Pessoais</div>
                    <div class="ap-grid ap-grid-4">
                        <div class="ap-col-2">
                             <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div>
                            <label for="cpf" class="form-label">CPF</label>
                            <input type="text" class="form-control" id="cpf" name="cpf" maxlength="14"
                                placeholder="000.000.000-00">
                        </div>
                        <div>
                            <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                            <input type="date" class="form-control" id="data_nascimento" name="data_nascimento">
                        </div>
                        <div>
                            <label for="telefone_1" class="form-label">Telefone 1</label>
                            <input type="text" class="form-control" id="telefone_1" name="telefone_1"  placeholder="(00) 00000-0000">
                        </div>
                        <div>
                            <label  for="telefone_2" class="form-label">Telefone 2 <span
                                    class="opt">(opcional)</span></label>
                            <input type="text" class="form-control" id="telefone_2" name="telefone_2" placeholder="(00) 00000-0000">
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="ap-section">
                    <div class="ap-section-title">Endereço</div>
                    <div class="ap-grid ap-grid-4">
                        <div>
                            <label for="cep" class="form-label">CEP</label>
                            <input type="text" class="form-control" id="cep" name="cep" maxlength="9" placeholder="00000-000">
                        </div>
                        <div class="ap-col-2">
                            <label for="logradouro" class="form-label">Logradouro</label>
                            <input type="text" class="form-control" id="logradouro" name="logradouro" readonly>
                        </div>
                        <div>
                            <label for="numero" class="form-label">Número</label>
                            <input type="text" class="form-control" id="numero" name="numero">
                        </div>
                        <div>
                            <label for="complemento" class="form-label">Complemento</label>
                            <input type="text" class="form-control" id="complemento" name="complemento">
                        </div>
                        <div>
                            <label for="bairro" class="form-label">Bairro</label>
                            <input type="text" class="form-control" id="bairro" name="bairro" readonly>
                        </div>
                        <div>
                            <label for="cidade" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="cidade" name="cidade" readonly>
                        </div>
                        <div>
                            <label for="estado" class="form-label">UF</label>
                            <input type="text" class="form-control" id="estado" name="estado" readonly maxlength="2">
                        </div>
                    </div>
                </div>

                <!-- Dados de Acesso -->
                <div class="ap-section">
                    <div class="ap-section-title">Dados de Acesso</div>
                    <div class="ap-grid ap-grid-4">
                        <div class="ap-col-2">
                            <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div>
                            <label for="senha" class="form-label">Senha <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="senha" name="senha" required minlength="8">
                        </div>
                        <div>
                            <label for="confirmar_senha" class="form-label">Confirmar Senha <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" required minlength="8">
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div style="display:flex; gap: 10px; padding-top: 0.5rem;">
                    <button type="submit" class="ap-btn-primary">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V7l-4-4z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 3v4H7V3M12 12v5m-2-2h4" />
                        </svg>
                        Salvar
                    </button>
                    <a href="/" class="ap-btn-secondary">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancelar
                    </a>
                </div>

            </form>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara CPF: 000.000.000-00
    var cpfInput = document.getElementById('cpf');
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
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

    // Máscara CEP: 00000-000
    var cepInput = document.getElementById('cep');
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            var v = e.target.value.replace(/\D/g, '');
            v = v.substring(0, 8);
            if (v.length > 5) {
                v = v.replace(/^(\d{5})(\d{0,3})/, '$1-$2');
            }
            e.target.value = v;
        });

        // ViaCEP
        cepInput.addEventListener('blur', function() {
            var cep = this.value.replace(/\D/g, '');
            if (cep.length !== 8) return;

            fetch('https://viacep.com.br/ws/' + cep + '/json/')
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (data.erro) return;
                    var logradouro = document.getElementById('logradouro');
                    var bairro = document.getElementById('bairro');
                    var cidade = document.getElementById('cidade');
                    var estado = document.getElementById('estado');
                    var numero = document.getElementById('numero');
                    
                    if (logradouro) logradouro.value = data.logradouro || '';
                    if (bairro) bairro.value = data.bairro || '';
                    if (cidade) cidade.value = data.localidade || '';
                    if (estado) estado.value = data.uf || '';
                    if (numero) numero.focus();
                })
                .catch(function(err) {
                    console.error('Erro ao buscar CEP:', err);
                });
        });
    }

    // Máscara Telefone
    var telefone1 = document.getElementById('telefone_1');
    var telefone2 = document.getElementById('telefone_2');
    
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
    
    mascaraTelefone(telefone1);
    mascaraTelefone(telefone2);
});
</script>
