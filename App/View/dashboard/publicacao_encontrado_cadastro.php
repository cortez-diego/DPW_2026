<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Cadastro de Publicação de Animais Encontrados</h1>
        <a href="/dashboard/publicacao_encontrado/listar" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="/dashboard/publicacao/cadastrar">
                <h5 class="mb-3 text-primary">Informações Sobre o Animal encontrado</h5>    
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="data_encontro" class="form-label">Data de Encontro <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="dataEncontro" name="dataEncontro" required>                        
                    </div>
                    <div class="col-md-3">
                        <label for="condicao_fisica" class="form-label">Condição Física - Descreva a condição em que o animal foi encontrado!<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="condicaoFisica" name="condicaoFisica" required>                        
                    </div>
                    <div class="col-md-3">
                        <label for="acoes_realizadas" class="form-label">Ações Realizadas</label>
                        <input type="text" class="form-control" id="acoesRealizadas" name="acoesRealizadas">
    
                    </div>
                </div>

                <h5 class="mb-3 text-primary">Status</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status da Publicacao</label>
                        <select class="form-select" id="status" name="status">
                            <option value="pessimo"></option>
                            <option value="regular"></option>
                            <option value="bom" selected></option>
                            <option value="muito bom"></option>
                            <option value="excelente"></option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>