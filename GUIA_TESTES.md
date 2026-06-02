# Guia de Testes - Validadores e Logger

## 1. Testes Manuais nos Formulários

### 1.1 Teste: Cadastro de Animal

#### Caso 1: Validação de Nome Vazio

1. Acesse `/dashboard/animal/cadastro`
2. Deixe o campo **Nome** vazio
3. Preencha os demais campos corretamente
4. Clique em **Salvar**
5. **Esperado**: Mensagem de erro "nome não pode estar vazio"

#### Caso 2: Validação de Nome Muito Curto

1. Campo **Nome**: escreva apenas "AB" (2 caracteres)
2. Preencha demais campos
3. Clique em **Salvar**
4. **Esperado**: Erro "nome deve ter no mínimo 3 caracteres"

#### Caso 3: Validação de Sexo

1. Não selecione sexo (deixe em branco)
2. Preencha outros campos
3. **Esperado**: Erro "sexo não pode estar vazio" ou "sexo contém valor inválido"

#### Caso 4: Validação de Espécie (Obrigatório)

1. Deixe **Espécie** sem seleção
2. Preencha nome, sexo, porte
3. **Esperado**: Erro "Espécie deve ser selecionada"

#### Caso 5: Validação de Porte

1. Se houver dropdown, tente selecionar valor inválido
2. **Esperado**: Erro "porte contém valor inválido"
3. Valores válidos: `pequeno`, `médio`, `grande`, `gigante`

#### Caso 6: Validação de Data Futura

1. Campo **Data de Nascimento**: insira uma data futura (ex: 2027-06-01)
2. Preencha outros campos
3. **Esperado**: Erro "Data de nascimento não pode ser no futuro"

#### Caso 7: Validação de Data Inválida

1. Digite: `01/06/2026` (formato brasileiro) ou `2026-13-01` (mês inválido)
2. **Esperado**: Erro "data_nascimento deve estar no formato YYYY-MM-DD"

#### Caso 8: Upload Vazio (Opcional)

1. Não selecione foto
2. Preencha dados obrigatórios
3. **Esperado**: Funciona normalmente (foto é opcional)

#### Caso 9: Upload de Arquivo Grande (>5MB)

1. Selecione imagem > 5MB
2. **Esperado**: Erro "Tamanho do arquivo não pode exceder 5 MB"

#### Caso 10: Upload de Tipo Inválido

1. Tente fazer upload de arquivo `.txt`, `.pdf`, `.exe`
2. **Esperado**: Erro "Tipo de arquivo não permitido"

#### Caso 11: Upload de Imagem Válida

1. Selecione imagem `.jpg`, `.png`, `.webp` ou `.gif` < 5MB
2. Preencha dados corretamente
3. **Esperado**: Upload bem-sucedido, animal cadastrado

### 1.2 Teste: Cadastro de Espécie

#### Caso 1: Nome Vazio

1. Acesse `/dashboard/especie/cadastro`
2. Deixe **Nome** vazio
3. **Esperado**: Erro "nome não pode estar vazio"

#### Caso 2: Nome Válido

1. Digite nome com 5+ caracteres (ex: "Felinos")
2. **Esperado**: Espécie cadastrada com sucesso

### 1.3 Teste: Cadastro de Raça

#### Caso 1: Raça sem Espécie

1. Acesse `/dashboard/raca/cadastro`
2. Deixe **Espécie** sem seleção
3. Preencha Nome
4. **Esperado**: Erro "Espécie deve ser selecionada"

#### Caso 2: Raça Válida

1. Selecione uma espécie
2. Digite nome (ex: "Siamês")
3. **Esperado**: Raça cadastrada com sucesso

## 2. Testes via Curl/Postman

### Teste de Validação de Animal

```bash
# Teste: Nome vazio (deve falhar)
curl -X POST http://localhost:8000/dashboard/animal/cadastrar \
  -d "nome=&sexo=M&fk_especie_id=1&porte=pequeno&status=disponivel"

# Teste: Dados válidos (deve suceder)
curl -X POST http://localhost:8000/dashboard/animal/cadastrar \
  -d "nome=Rex&sexo=M&fk_especie_id=1&porte=pequeno&status=disponivel&castrado=1"
```

## 3. Verificar Logs

### Localização

```
storage/logs/2026-06-01.log
```

### Ver últimos logs

```bash
# Terminal (Windows PowerShell)
tail -f storage\logs\2026-06-01.log

# Linux/Mac
tail -f storage/logs/2026-06-01.log
```

### Conteúdo Esperado

#### Sucesso

```
[2026-06-01 10:30:45] [INFO] Animal cadastrado com sucesso | Contexto: {"animal_id":5} | Usuário: 1 | IP: 127.0.0.1
```

#### Erro de Validação

```
[2026-06-01 10:31:12] [WARNING] Validação falhou ao cadastrar animal | Contexto: {"erros":{"nome":["nome não pode estar vazio"]}} | Usuário: 1 | IP: 127.0.0.1
```

## 4. Testes de Upload de Imagem

### Caso 1: Arquivo Muito Grande

```bash
# Criar arquivo de teste 10MB
dd if=/dev/zero of=test_10mb.jpg bs=1M count=10

# Tentar upload (deve falhar)
curl -F "foto=@test_10mb.jpg" http://localhost:8000/dashboard/animal/cadastrar
```

### Caso 2: Tipo MIME Inválido

```bash
# Criar arquivo .txt e renomear para .jpg
echo "Este é um arquivo falso" > fake.jpg

# Tentar upload (deve falhar se o validador usa fileinfo)
curl -F "foto=@fake.jpg" http://localhost:8000/dashboard/animal/cadastrar
```

### Caso 3: Imagem Válida

```bash
# Usar imagem real
curl -F "foto=@animal.jpg" \
     -F "nome=Meu_Animal" \
     -F "sexo=M" \
     -F "fk_especie_id=1" \
     -F "porte=pequeno" \
     http://localhost:8000/dashboard/animal/cadastrar
```

## 5. Teste de Sincronização de Raças

### Editando Animal com Múltiplas Raças

1. Acesse edição de animal existente
2. Se a espécie permitir múltiplas raças, selecione várias
3. Salve
4. **Esperado**:
   - Raças anteriores são removidas
   - Novas raças são adicionadas
   - Log registra: "Vínculos animal-raça sincronizados"

## 6. Checklist de Testes Automáticos

Para criar testes unitários em PHPUnit:

```php
<?php

namespace Tests\Validador;

use PHPUnit\Framework\TestCase;
use App\Validador\ValidadorAnimal;
use App\Model\AnimalModel;

class ValidadorAnimalTest extends TestCase
{
    private $validador;

    protected function setUp(): void
    {
        $this->validador = new ValidadorAnimal();
    }

    public function testNomeVazio()
    {
        $dados = [
            'nome' => '',
            'sexo' => 'M',
            'fk_especie_id' => 1,
            'porte' => 'pequeno'
        ];

        $this->assertFalse($this->validador->validarFormulario($dados));
        $this->assertArrayHasKey('nome', $this->validador->obterErros());
    }

    public function testNomeValido()
    {
        $dados = [
            'nome' => 'Rex',
            'sexo' => 'M',
            'fk_especie_id' => 1,
            'porte' => 'pequeno'
        ];

        $this->assertTrue($this->validador->validarFormulario($dados));
    }

    public function testDataFutura()
    {
        $amanha = date('Y-m-d', strtotime('+1 day'));

        $dados = [
            'nome' => 'Miau',
            'sexo' => 'F',
            'fk_especie_id' => 1,
            'porte' => 'pequeno',
            'data_nascimento' => $amanha
        ];

        $this->assertFalse($this->validador->validarFormulario($dados));
    }

    public function testUploadArquivoGrande()
    {
        $validador = new ValidadorUpload();

        $arquivo = [
            'name' => 'grande.jpg',
            'tmp_name' => '/tmp/grande.jpg',
            'error' => UPLOAD_ERR_OK,
            'size' => 10485760 // 10MB, maior que 5MB
        ];

        $this->assertFalse($validador->validar($arquivo));
    }
}
```

## 7. Pontos Críticos para Testar

| Item                     | Como Testar               | Esperado          |
| ------------------------ | ------------------------- | ----------------- |
| **Nome vazio**           | Campo vazio → Salvar      | ❌ Erro           |
| **Nome muito curto**     | "AB" → Salvar             | ❌ Erro           |
| **Sexo inválido**        | Deixar em branco          | ❌ Erro           |
| **Espécie obrigatória**  | Sem seleção → Salvar      | ❌ Erro           |
| **Data futura**          | 2027-06-01 → Salvar       | ❌ Erro           |
| **Data inválida**        | 2026-13-01 → Salvar       | ❌ Erro           |
| **Upload > 5MB**         | Arquivo > 5MB             | ❌ Erro           |
| **Upload tipo inválido** | .txt ou .pdf              | ❌ Erro           |
| **Upload válido**        | .jpg/.png < 5MB           | ✅ Sucesso        |
| **Sincronização raças**  | Editar múltiplas raças    | ✅ Sincroniza     |
| **Logger criado**        | Verificar `storage/logs/` | ✅ Arquivo criado |

## 8. Cenários de Integração

### Fluxo Completo: Animal com Raça

1. ✅ Cadastrar Espécie "Caninos"
2. ✅ Cadastrar Raça "Labrador" (espécie: Caninos)
3. ✅ Cadastrar Animal "Rex" (espécie: Caninos, raça: Labrador)
4. ✅ Editar Animal e mudar raça para "Poodle"
5. ✅ Verificar log de sincronização
6. ✅ Confirmar raça foi trocada no banco

### Fluxo de Erro: Validação em Cascata

1. ❌ Tentar cadastrar Animal com dados vazios → Retorna view com erros
2. ❌ Tentar enviar upload inválido → Retorna view com erros
3. ✅ Verificar que dados anteriores aparecem pré-preenchidos na view

---

**Dicas:**

- Sempre verificar `storage/logs/` após operações
- Testar com dados válidos E inválidos
- Limpar uploads de teste após cada teste
- Acompanhar mudanças no banco com query direto ou SQLiteStudio
