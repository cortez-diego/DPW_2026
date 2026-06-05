<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Cadastro de Publicação de Animais Encontrados</h1>
        <a href="/dashboard/publicacao/listar" class="btn btn-secondary">
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
                        <input type="date" class="form-control" id="data_encontro" name="data_encontro" required>                        
                    </div>
                    <div class="col-md-3">
                        <label for="condicao_fisica" class="form-label">Condição Física - Descreva a condição em que o animal foi encontrado!<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="condicao_fisica" name="condicao_fisica" required>                        
                    </div>
                    <div class="col-md-3">
                        <label for="acoes_realizadas" class="form-label">Ações Realizadas</label>
                        <input type="text" class="form-control" id="acoes_realizadas" name="acoes_realizadas">
    
                    </div>
                </div>

                <h5 class="mb-3 text-primary">Status do Animal Publicado</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="aguardando acolhimento">Aguardando Acolhimento</option>
                            <option value="acolhido">Acolhido</option>
                            <option value="em análise">Em Análise</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar
                    </button>
                    <a href="/dashboard/publicacao/listar" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>