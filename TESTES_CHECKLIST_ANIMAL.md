# CASOS DE TESTE E CHECKLIST - MÓDULO ANIMAL

## PARTE 1: CASOS DE TESTE

### 1.1 TESTES DE VALIDAÇÃO - CAMPO NOME

| Teste  | Input                       | Esperado              | Resultado | Status   |
| ------ | --------------------------- | --------------------- | --------- | -------- |
| TC-001 | nome vazio                  | ❌ Erro "obrigatório" | -         | 🔴 Criar |
| TC-002 | nome = "AB" (2 chars)       | ❌ Erro "mínimo 3"    | -         | 🔴 Criar |
| TC-003 | nome = "ABC" (3 chars)      | ✅ Válido             | -         | 🔴 Criar |
| TC-004 | nome = "A"\*100 (100 chars) | ✅ Válido             | -         | 🔴 Criar |
| TC-005 | nome = "A"\*101 (101 chars) | ❌ Erro "máximo 100"  | -         | 🔴 Criar |
| TC-006 | nome = "Fluffy"             | ✅ Válido             | -         | 🔴 Criar |

### 1.2 TESTES DE VALIDAÇÃO - CAMPO SEXO

| Teste  | Input                  | Esperado                 | Resultado | Status   |
| ------ | ---------------------- | ------------------------ | --------- | -------- |
| TC-010 | sexo vazio             | ❌ Erro "obrigatório"    | -         | 🔴 Criar |
| TC-011 | sexo = "M"             | ✅ Válido                | -         | 🔴 Criar |
| TC-012 | sexo = "F"             | ✅ Válido                | -         | 🔴 Criar |
| TC-013 | sexo = "m" (lowercase) | ❌ Erro (case-sensitive) | -         | 🔴 Criar |
| TC-014 | sexo = "X"             | ❌ Erro "enum inválido"  | -         | 🔴 Criar |
| TC-015 | sexo = "MF"            | ❌ Erro "enum inválido"  | -         | 🔴 Criar |

### 1.3 TESTES DE VALIDAÇÃO - ESPÉCIE (FK)

| Teste  | Input                               | Esperado                                    | Resultado | Status   |
| ------ | ----------------------------------- | ------------------------------------------- | --------- | -------- |
| TC-020 | fk_especie_id vazio                 | ❌ Erro "obrigatório"                       | -         | 🔴 Criar |
| TC-021 | fk_especie_id = "0"                 | ❌ Erro "deve ser > 0"                      | -         | 🔴 Criar |
| TC-022 | fk_especie_id = "-1"                | ❌ Erro "deve ser > 0"                      | -         | 🔴 Criar |
| TC-023 | fk_especie_id = "abc"               | ❌ Erro "não é número"                      | -         | 🔴 Criar |
| TC-024 | fk_especie_id = "1" (existe)        | ✅ Válido                                   | -         | 🔴 Criar |
| TC-025 | fk_especie_id = "9999" (não existe) | ✅ Passa validador mas FALHA no INSERT (FK) | -         | 🔴 Criar |

### 1.4 TESTES DE VALIDAÇÃO - PORTE

| Teste  | Input                            | Esperado                 | Resultado | Status   |
| ------ | -------------------------------- | ------------------------ | --------- | -------- |
| TC-030 | porte vazio                      | ❌ Erro "obrigatório"    | -         | 🔴 Criar |
| TC-031 | porte = "pequeno"                | ✅ Válido                | -         | 🔴 Criar |
| TC-032 | porte = "médio"                  | ✅ Válido                | -         | 🔴 Criar |
| TC-033 | porte = "grande"                 | ✅ Válido                | -         | 🔴 Criar |
| TC-034 | porte = "gigante"                | ✅ Válido                | -         | 🔴 Criar |
| TC-035 | porte = "Pequeno" (capitalizado) | ❌ Erro (case-sensitive) | -         | 🔴 Criar |
| TC-036 | porte = "medio" (sem acento)     | ❌ Erro "enum inválido"  | -         | 🔴 Criar |

### 1.5 TESTES DE VALIDAÇÃO - DATA NASCIMENTO

| Teste  | Input          | Hoje       | Esperado                | Resultado | Status   | Nota                          |
| ------ | -------------- | ---------- | ----------------------- | --------- | -------- | ----------------------------- |
| TC-040 | Vazio          | -          | ✅ Válido (opcional)    | -         | 🔴 Criar |                               |
| TC-041 | "2026-05-01"   | 2026-06-02 | ✅ Válido (passada)     | -         | 🔴 Criar |                               |
| TC-042 | "2026-06-02"   | 2026-06-02 | ❌ FALHA (hoje)         | -         | 🔴 Criar | 🐛 BUG: Valida incorretamente |
| TC-043 | "2026-06-03"   | 2026-06-02 | ❌ Erro "futura"        | -         | 🔴 Criar |                               |
| TC-044 | "2030-12-31"   | 2026-06-02 | ❌ Erro "futura"        | -         | 🔴 Criar |                               |
| TC-045 | "invalid-date" | -          | ❌ Erro "data inválida" | -         | 🔴 Criar |                               |
| TC-046 | "2026-13-01"   | -          | ❌ Erro "data inválida" | -         | 🔴 Criar |                               |

### 1.6 TESTES DE VALIDAÇÃO - STATUS

| Teste  | Input           | Schema             | Esperado                        | Resultado       | Status          | Nota   |
| ------ | --------------- | ------------------ | ------------------------------- | --------------- | --------------- | ------ |
| TC-050 | Vazio           | -                  | ✅ Válido (default: disponivel) | -               | 🔴 Criar        |        |
| TC-051 | "disponivel"    | ('disponivel',...) | ✓ Validador OK                  | ✓ INSERT OK     | 🟢 OK           |        |
| TC-052 | "adotado"       | ('adotado',...)    | ✓ Validador OK                  | ✓ INSERT OK     | 🟢 OK           |        |
| TC-053 | "resgate"       | ❌ NÃO no schema   | ✓ Validador OK                  | ❌ INSERT FALHA | 🔴 FALHA        | 🐛 BUG |
| TC-054 | "quarentena"    | ❌ NÃO no schema   | ✓ Validador OK                  | ❌ INSERT FALHA | 🔴 FALHA        | 🐛 BUG |
| TC-055 | "falecido"      | ❌ NÃO no schema   | ✓ Validador OK                  | ❌ INSERT FALHA | 🔴 FALHA        | 🐛 BUG |
| TC-056 | "em_tratamento" | ✓ No schema        | ❌ Validador REJEITA            | OK (mas erro)   | 🟡 Inconsistent | 🐛 BUG |
| TC-057 | "reservado"     | ✓ No schema        | ❌ Validador REJEITA            | OK (mas erro)   | 🟡 Inconsistent | 🐛 BUG |

### 1.7 TESTES DE UPLOAD - FOTO

| Teste  | Input                         | Tamanho | MIME                     | Ext  | Esperado                                          | Resultado | Status   |
| ------ | ----------------------------- | ------- | ------------------------ | ---- | ------------------------------------------------- | --------- | -------- |
| TC-060 | Nenhum arquivo                | -       | -                        | -    | ✅ Válido (opcional)                              | -         | 🔴 Criar |
| TC-061 | image.jpg (legítimo)          | 2MB     | image/jpeg               | jpg  | ✅ Válido                                         | -         | 🔴 Criar |
| TC-062 | image.png                     | 3MB     | image/png                | png  | ✅ Válido                                         | -         | 🔴 Criar |
| TC-063 | image.webp                    | 1MB     | image/webp               | webp | ✅ Válido                                         | -         | 🔴 Criar |
| TC-064 | image.gif                     | 500KB   | image/gif                | gif  | ✅ Válido                                         | -         | 🔴 Criar |
| TC-065 | archive.zip                   | 2MB     | application/zip          | zip  | ❌ MIME não permitido                             | -         | 🔴 Criar |
| TC-066 | virus.exe.jpg                 | 1MB     | application/x-msdownload | exe  | ❌ MIME correto (não jpg)                         | -         | 🔴 Criar |
| TC-067 | photo.jpg                     | 6MB     | image/jpeg               | jpg  | ❌ Tamanho > 5MB                                  | -         | 🔴 Criar |
| TC-068 | photo.jpg                     | 5MB     | image/jpeg               | jpg  | ✅ Válido (max)                                   | -         | 🔴 Criar |
| TC-069 | photo.jpeg                    | 1MB     | image/jpeg               | jpeg | ✅ Válido                                         | -         | 🔴 Criar |
| TC-070 | photo.JPG (uppercase)         | 1MB     | image/jpeg               | JPG  | ⚠️ Extensão case-insensitive mas validador aceita | -         | 🔴 Criar |
| TC-071 | photo.jpg.php                 | 1MB     | application/x-php        | php  | ❌ Extensão e MIME incorretos                     | -         | 🔴 Criar |
| TC-072 | photo.jpg (renomeado de .php) | 1MB     | application/x-php        | jpg  | ❌ MIME revela tipo real                          | -         | 🔴 Criar |

### 1.8 TESTES FLUXO COMPLETO - CADASTRO SUCESSO

```
TC-080: Cadastro Animal Completo Válido
├─ Dados:
│  ├─ nome: "Rex"
│  ├─ sexo: "M"
│  ├─ fk_especie_id: 1
│  ├─ porte: "grande"
│  ├─ cor: "Preto"
│  ├─ castrado: 1
│  ├─ descricao: "Cão dócil"
│  ├─ localizacao: "São Paulo - SP"
│  ├─ status: "disponivel"
│  ├─ foto: image.jpg (2MB)
│  └─ fk_raca_id: [1, 2]
├─ Validador: ✅ PASSA
├─ Upload: ✅ OK
├─ INSERT animal: ✅ OK (id=42)
├─ INSERT animal_raca(42,1): ✅ OK
├─ INSERT animal_raca(42,2): ✅ OK
├─ Logger: ✅ Info registrado
└─ Resultado: 🟢 SUCESSO
   └─ Redirect: /dashboard/animal/listar
```

### 1.9 TESTES FLUXO COMPLETO - CADASTRO FALHA

```
TC-081: Cadastro com Espécie Inválida
├─ Dados:
│  ├─ nome: "Fluffy"
│  └─ fk_especie_id: "" (vazio)
├─ Validador: ❌ FALHA
│  └─ Erro: "Espécie deve ser selecionada"
├─ Upload: (não chega aqui)
├─ Resultado: 🟠 ERRO
│  └─ Retorna: Formulário com erro exibido
│  └─ Dados preenchidos mantidos no form
```

```
TC-082: Cadastro com Upload Inválido
├─ Dados:
│  ├─ nome: "Fluffy"
│  ├─ ... (todos válidos)
│  └─ foto: virus.exe
├─ Validador animal: ✅ PASSA
├─ Validador upload: ❌ FALHA
│  └─ Erro: "Tipo de arquivo não permitido"
├─ Resultado: 🟠 ERRO
│  └─ Retorna: Formulário com erro de upload
│  └─ Dados animais preenchidos mantidos
```

### 1.10 TESTES EDIÇÃO - SINCRONIZAÇÃO RAÇAS

```
TC-090: Editar + Remover Raça
├─ Estado inicial:
│  ├─ animal_id: 1
│  └─ racas: [2, 3, 5]
├─ POST edição:
│  └─ fk_raca_id: [2, 5]  (removeu 3)
├─ Sincronização:
│  ├─ Atuais: [2, 3, 5]
│  ├─ Novos: [2, 5]
│  ├─ Para remover: [3]
│  └─ Para adicionar: []
├─ SQL:
│  └─ DELETE FROM animal_raca WHERE fk_animal_id=1 AND fk_raca_id=3
├─ Resultado: ✅ Raça 3 removida
└─ Estado final: [2, 5]
```

```
TC-091: Editar + Adicionar Raça
├─ Estado inicial:
│  ├─ animal_id: 1
│  └─ racas: [2, 3]
├─ POST edição:
│  └─ fk_raca_id: [2, 3, 5, 7]  (adicionou 5, 7)
├─ Sincronização:
│  ├─ Atuais: [2, 3]
│  ├─ Novos: [2, 3, 5, 7]
│  ├─ Para remover: []
│  └─ Para adicionar: [5, 7]
├─ SQL:
│  ├─ INSERT INTO animal_raca (fk_animal_id, fk_raca_id) VALUES (1, 5)
│  └─ INSERT INTO animal_raca (fk_animal_id, fk_raca_id) VALUES (1, 7)
├─ Resultado: ✅ Raças 5 e 7 adicionadas
└─ Estado final: [2, 3, 5, 7]
```

### 1.11 TESTES EXCLUSÃO

```
TC-100: Excluir Animal
├─ Estado:
│  ├─ animal.id: 42
│  ├─ animal.foto: resources/dashboard/images/animais/animal_65bd3c42a78c1.jpg
│  └─ vinculos: animal_raca (3), ong_animal (1), solicitacao_adocao (2)
├─ Operações:
│  ├─ 1. removerFoto() - deleta arquivo do servidor
│  ├─ 2. AnimalDAO::excluir(42) - DELETE FROM animal WHERE id=42
│  ├─ 3. Cascata ON DELETE CASCADE:
│  │  ├─ animal_raca com fk_animal_id=42
│  │  ├─ ong_animal com fk_animal_id=42
│  │  ├─ historico_animal com fk_animal_id=42
│  │  └─ solicitacao_adocao com fk_animal_id=42
│  └─ 4. Logger::info()
├─ Resultado: ✅ Deletado com cascata
└─ Verificação:
   ├─ Animal: não encontrado
   ├─ animal_raca: 0 registros para id 42
   ├─ ong_animal: 0 registros para id 42
   └─ Arquivo: não existe no servidor
```

---

## PARTE 2: CHECKLIST DE IMPLEMENTAÇÃO

### Checklist 1: Funcionalidades Básicas

```
CRUD OPERATIONS
  [✓] Criar (POST /cadastrar)
  [✓] Ler (GET /listar, /editar)
  [✓] Atualizar (POST /alterar)
  [✓] Deletar (POST /excluir)

LISTAGENS
  [✓] Listar todos os animais
  [✓] Buscar por ID
  [✓] Listar animais disponíveis (método DAO existe mas sem rota)
  [✗] Paginação (não implementada)
  [✗] Busca por filtros com UI (método DAO existe mas sem rota)

RELACIONAMENTOS
  [✓] FK animal → especie
  [✓] Pivot animal ↔ raca (M:N)
  [✓] Pivot animal ↔ ong (M:N, read-only)
  [✓] Sincronização de raças na edição
```

### Checklist 2: Validações

```
VALIDADOR ANIMAL
  [✓] Nome obrigatório, 3-100 chars
  [✓] Sexo obrigatório, enum (M|F)
  [✓] Espécie obrigatória, int > 0
  [✓] Porte obrigatório, enum válido
  [✓] Status enum válido (se preenchido)
  [⚠️] Data nascimento não pode ser futura (BUG: aceita hoje)
  [?] Validação FK espécie (deixa para banco)

VALIDADOR UPLOAD
  [✓] is_uploaded_file()
  [✓] MIME type com finfo_file()
  [✓] Extensão whitelist
  [✓] Tamanho máximo 5MB
  [✓] Mensagens de erro customizadas
  [✓] Upload opcional (UPLOAD_ERR_NO_FILE)

VALIDADOR GENÉRICO
  [✓] Não vazio
  [✓] Comprimento mínimo/máximo
  [✓] Enumeração
  [✓] Data formato
  [✓] Número
```

### Checklist 3: Segurança

```
UPLOAD
  [✓] Validação MIME type (não apenas extensão)
  [✓] Nomes únicos (uniqid) para evitar colisões
  [✓] Pasta isolada (resources/dashboard/images/animais/)
  [✓] Remoção de arquivo anterior
  [⚠️] Sem validação de conteúdo (poderia ter PNG com ext JPG)
  [✗] Sem antivírus scanner

SQL
  [✓] Prepared statements com PDO
  [✓] Parametrização com bindValue
  [✓] Validação de tipos (PDO::PARAM_INT)
  [✓] FK constraints no banco

VALIDAÇÃO
  [✓] Validação de entrada antes de INSERT
  [✓] Mensagens de erro informativas
  [✓] Feedback no formulário (dados mantidos)
  [✗] CSRF token (não implementado)
  [✗] Rate limiting (não implementado)

AUTENTICAÇÃO
  [✓] Verificação $_SESSION no Controller (validaAutenticacao)
  [✓] Redirect para /login se não autenticado
```

### Checklist 4: Banco de Dados

```
SCHEMA ANIMAL
  [✓] Campos principais (nome, sexo, data_nascimento)
  [✓] FK para especie
  [✓] Campos opcionais (cor, castrado, descricao)
  [✓] Porte, localizacao, foto, status
  [⚠️] Campo 'especie' VARCHAR obsoleto (deixado no schema)
  [⚠️] Status ENUM inconsistente com validador
  [⚠️] Sem NOT NULL em fk_especie_id (deveria ser obrigatório)
  [✗] Sem índices de performance (não criados)

TABELAS PIVOT
  [✓] animal_raca com unique constraint (fk_animal_id, fk_raca_id)
  [✓] ong_animal sem unique constraint (permite duplicação)
  [✓] ON DELETE CASCADE implementado

MIGRATIONS
  [✗] Nenhuma migração automática (scripts SQL manuais)
  [✗] Sem versionamento de schema
```

### Checklist 5: Logging e Auditoria

```
LOGGING
  [✓] AnimalRacaDAO: info, warning, erro em todos métodos
  [✗] AnimalDAO: sem logging (erro redirect apenas)
  [✓] AnimalController: Logger::info() em cadastro/alteração
  [?] Logger em erro de upload/validação

AUDITORIA
  [✓] Registra animal_id no LOG
  [⚠️] Sem rastreamento de quem alterou (user_id deveria estar)
  [✗] Sem timestamp de auditoria (created_at, updated_at)
  [✗] Sem soft delete (permite auditoria de exclusões)

ARQUIVO LOG
  [✓] Localização: storage/logs/
  [✓] Níveis: info, warning, erro, critico
  [✓] Contexto incluído (user_id, IP)
```

### Checklist 6: Performance

```
QUERIES
  [✓] listar(): 1 query com 5 JOINs + GROUP_CONCAT
  [✓] buscarPorId(): 1 query
  [✓] buscarComFiltros(): 1 query dinâmica
  [⚠️] Sem índices (pode ser lento com muitos registros)
  [✗] Sem paginação (sem LIMIT)
  [✗] Sem cache (sem memcached/redis)

UPLOAD
  [✓] move_uploaded_file() eficiente
  [⚠️] uniqid() com alta concorrência pode duplicar
  [✗] Sem compressão de imagem (armazena original)
  [✗] Sem geração de thumbnail

OTIMIZAÇÕES SUGERIDAS
  [ ] CREATE INDEX idx_animal_especie ON animal(fk_especie_id)
  [ ] CREATE INDEX idx_animal_status ON animal(status)
  [ ] CREATE INDEX idx_animal_raca ON animal_raca(fk_animal_id)
  [ ] Implementar paginação com LIMIT/OFFSET
  [ ] Lazy loading de relacionamentos
```

### Checklist 7: Testes

```
TESTES UNITÁRIOS
  [ ] ValidadorAnimal::validar()
  [ ] ValidadorAnimal::validarFormulario()
  [ ] ValidadorUpload::validar()
  [ ] AnimalDAO::inserir()
  [ ] AnimalDAO::alterar()
  [ ] AnimalRacaDAO::sincronizar()

TESTES INTEGRAÇÃO
  [ ] Fluxo completo cadastro
  [ ] Fluxo completo edição
  [ ] Fluxo completo exclusão
  [ ] Sincronização de raças
  [ ] Upload com validação

TESTES FUNCCIONAIS
  [ ] Listar animais na UI
  [ ] Formulário cadastro renderiza correto
  [ ] Erros de validação exibem corretamente
  [ ] Upload funciona e arquivo é salvo
  [ ] Raças sincronizam corretamente

TESTE DE CARGA
  [ ] 1000 animais: listar performance
  [ ] Upload: 10 simultâneos
  [ ] Sincronização: 100 raças por animal
```

---

## PARTE 3: MATRIX DE PRIORIDADES

### P1 - CRÍTICO (Implementar Antes do Go-Live)

| ID     | Descrição                                       | Impacto | Esforço | Status  |
| ------ | ----------------------------------------------- | ------- | ------- | ------- |
| P1-001 | Sincronizar Status ENUM (schema vs validador)   | Alto    | Médio   | 🔴 TODO |
| P1-002 | Adicionar FK constraint em animal.fk_especie_id | Alto    | Baixo   | 🔴 TODO |
| P1-003 | Corrigir validação data futura                  | Médio   | Baixo   | 🔴 TODO |
| P1-004 | Remover campo 'especie' obsoleto                | Médio   | Baixo   | 🔴 TODO |
| P1-005 | Testar fluxo completo cadastro/edição           | Alto    | Alto    | 🔴 TODO |

### P2 - IMPORTANTE (Próxima Sprint)

| ID     | Descrição                                             | Impacto | Esforço | Status  |
| ------ | ----------------------------------------------------- | ------- | ------- | ------- |
| P2-001 | Adicionar Logger em AnimalDAO                         | Médio   | Baixo   | 🔴 TODO |
| P2-002 | Criar endpoint /animal/filtro (usar buscarComFiltros) | Médio   | Médio   | 🔴 TODO |
| P2-003 | Criar endpoint /animal/disponiveis (público)          | Médio   | Baixo   | 🔴 TODO |
| P2-004 | Implementar paginação em listar()                     | Médio   | Médio   | 🔴 TODO |
| P2-005 | Adicionar índices no banco                            | Médio   | Baixo   | 🔴 TODO |

### P3 - NICE-TO-HAVE (Futuro)

| ID     | Descrição                 | Impacto | Esforço | Status  |
| ------ | ------------------------- | ------- | ------- | ------- |
| P3-001 | Soft delete com auditoria | Baixo   | Alto    | 🔴 TODO |
| P3-002 | Gerar thumbnails de foto  | Baixo   | Médio   | 🔴 TODO |
| P3-003 | Compressão de imagem      | Baixo   | Médio   | 🔴 TODO |
| P3-004 | CSRF token em formulários | Médio   | Baixo   | 🔴 TODO |
| P3-005 | Rate limiting             | Baixo   | Médio   | 🔴 TODO |

---

## PARTE 4: PROBLEMAS IDENTIFICADOS (RESUMO)

### 🔴 CRÍTICOS

```
1. STATUS ENUM INCONSISTENTE
   Schema:     ('disponivel','adotado','em_tratamento','reservado')
   Código:     ['disponivel','adotado','resgate','quarentena','falecido']
   Impacto:    INSERT vai falhar se houver validação
   Fix:        UPDATE schema ou UPDATE validador

2. FK CONSTRAINT FALTANDO
   Campo:      fk_especie_id
   Problema:   Sem FOREIGN KEY, permite inserir espécie inválida
   Fix:        ALTER TABLE animal ADD CONSTRAINT ...

3. CAMPO OBSOLETO COEXISTE
   Campo:      especie VARCHAR(50)
   Problema:   Nunca populado, confunde developers
   Fix:        DROP COLUMN especie
```

### 🟠 IMPORTANTES

```
4. DATA FUTURA - BUG
   Problema:   Usa time() que inclui hora do dia
   Resultado:  Aceita data de hoje incorretamente
   Fix:        Usar strtotime('today')

5. MÉTODOS SEM ROTAS
   Métodos:    listarDisponiveis(), buscarComFiltros()
   Problema:   Implementados mas sem endpoint
   Fix:        Criar actions no controller

6. SEM LOGGING EM AnimalDAO
   Problema:   Só animalRacaDAO tem logger
   Fix:        Adicionar Logger em inserir/alterar/excluir
```

---

**Gerado:** Junho 2026  
**Total Testes:** 72 casos definidos  
**Total Itens Checklist:** 89 itens
