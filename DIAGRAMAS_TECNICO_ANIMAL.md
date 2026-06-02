# DIAGRAMAS TÉCNICOS - MÓDULO ANIMAL

## 1. Diagrama Arquitetural

```mermaid
graph TB
    UI["🖥️ Frontend<br/>animal_cadastro.php<br/>animal_editar.php"]

    AC["🎮 AnimalController<br/>6 actions"]

    VA["✔️ ValidadorAnimal<br/>validarFormulario"]
    VU["✔️ ValidadorUpload<br/>validar"]

    DAO["📊 AnimalDAO<br/>CRUD + especializados"]
    ARDAO["🔗 AnimalRacaDAO<br/>Sincronização"]

    Logger["📝 Logger<br/>Auditoria"]

    DB["🗄️ MySQL<br/>animal, animal_raca<br/>especie, ong_animal"]

    FS["💾 FileSystem<br/>resources/dashboard<br/>images/animais/"]

    UI -->|POST| AC
    AC -->|valida| VA
    AC -->|valida| VU
    VA -->|tem erros?| UI
    VU -->|tem erros?| UI
    AC -->|insert/update| DAO
    AC -->|vincular raças| ARDAO
    AC -->|log eventos| Logger
    DAO -->|SQL| DB
    ARDAO -->|SQL| DB
    VU -->|upload| FS
    DAO -->|read| DB
    ARDAO -->|read| DB

    style AC fill:#4a90e2
    style DAO fill:#7cb342
    style ARDAO fill:#7cb342
    style VA fill:#f57c00
    style VU fill:#f57c00
    style DB fill:#c53030
    style FS fill:#8e24aa
```

---

## 2. Fluxo Cadastro de Animal

```mermaid
sequenceDiagram
    participant User as 👤 User
    participant Form as 📋 Form
    participant Controller as 🎮 Controller
    participant Validador as ✔️ Validador
    participant DAO as 📊 DAO
    participant DB as 🗄️ DB
    participant FS as 💾 FileSystem

    User->>Form: Preenche form + seleciona foto
    Form->>Controller: POST /dashboard/animal/cadastrar

    Controller->>Validador: validarFormulario($_POST)
    alt Validação falha
        Validador-->>Controller: array com erros
        Controller-->>Form: Retorna com erros
        Form-->>User: Exibe mensagens erro
    else Validação OK
        Controller->>Validador: validarUpload($_FILES['foto'])
        alt Upload inválido
            Validador-->>Controller: array com erros
            Controller-->>Form: Retorna com erros
        else Upload OK
            Controller->>FS: processarUploadFoto()
            FS->>FS: Gera nome único: animal_XXXXX.jpg
            FS->>FS: Move arquivo para pasta
            FS-->>Controller: Retorna caminho

            Controller->>Controller: Cria AnimalModel
            Controller->>DAO: inserir($model)
            DAO->>DB: INSERT INTO animal
            DB-->>DAO: Retorna id (ex: 42)
            DAO-->>Controller: animal_id = 42

            Controller->>DAO: AnimalRacaDAO::vincular(42, raca_id)
            DAO->>DB: INSERT INTO animal_raca
            DB-->>DAO: OK

            Controller->>DB: Logger::info()
            Controller-->>User: Redirect /dashboard/animal/listar ✓
        end
    end
```

---

## 3. Fluxo Edição + Sincronização Raças

```mermaid
graph TD
    A["GET /dashboard/animal/editar/1"] -->|buscarPorId| B["AnimalDAO"]
    B -->|JOINs| C["SELECT animal + especie<br/>+ raças + ong"]
    C -->|GROUP_CONCAT racas| D["AnimalModel populado"]

    D -->|listarPorEspecie| E["RacaDAO"]
    E -->|Carrega raças<br/>da espécie| F["Populadas na view"]

    D -->|listarPorAnimal| G["AnimalRacaDAO"]
    G -->|Identifica<br/>raças vinculadas| H["Array IDs selecionados"]

    F -->|Renderiza| I["🖥️ animal_editar.php<br/>Form com raças selecionadas"]
    H -->|Marca seleção| I

    I -->|POST dados + raças| J["AnimalController::alterar"]
    J -->|valida| K["ValidadorAnimal"]
    K -->|OK| L["AnimalDAO::alterar"]
    L -->|UPDATE animal| M["🗄️ DB"]

    J -->|sincronizar raças| N["AnimalRacaDAO::sincronizar<br/>animalId, novosRacaIds"]

    N -->|array_diff| O["Determina:<br/>paraRemover = REMOVE<br/>paraAdicionar = ADD"]
    O -->|foreach| P["desvincular()"]
    O -->|foreach| Q["vincular()"]
    P -->|DELETE| M
    Q -->|INSERT| M

    M -->|Sucesso| R["Logger::info()"]
    R -->|Redirect| S["✓ /dashboard/animal/listar"]
```

---

## 4. Modelo de Dados - Relacionamentos

```mermaid
erDiagram
    ANIMAL ||--o{ ANIMAL_RACA : "tem"
    ANIMAL ||--o{ ONG_ANIMAL : "tem"
    ANIMAL ||--o{ SOLICITACAO_ADOCAO : "tem"
    ANIMAL ||--o{ HISTORICO_ANIMAL : "tem"

    ANIMAL }o--|| ESPECIE : "pertence"

    ANIMAL_RACA }o--|| RACA : "referencia"
    RACA }o--|| ESPECIE : "pertence"

    ONG_ANIMAL }o--|| ONG : "referencia"

    SOLICITACAO_ADOCAO }o--|| ADOTANTE : "referencia"
    HISTORICO_ANIMAL }o--|| VETERINARIO : "referencia"

    ANIMAL {
        int id PK
        string nome
        date data_nascimento
        enum sexo
        int fk_especie_id FK
        string cor
        boolean castrado
        text descricao
        enum porte
        string localizacao
        string foto
        enum status
    }

    ESPECIE {
        int id PK
        string nome
    }

    RACA {
        int id PK
        string nome
        int fk_especie_id FK
    }

    ANIMAL_RACA {
        int id PK
        int fk_animal_id FK
        int fk_raca_id FK
        unique "fk_animal_id, fk_raca_id"
    }

    ONG {
        int id PK
        string nome
    }

    ONG_ANIMAL {
        int id PK
        int fk_animal_id FK
        int fk_ong_id FK
    }
```

---

## 5. Estrutura Pastas Upload

```
resources/
└── dashboard/
    └── images/
        └── animais/
            ├── animal_65bd3c42a78c1.jpg  (2.3 MB)
            ├── animal_65bd3d50b2e4f.png  (1.8 MB)
            ├── animal_65bd3e99c1234.gif  (0.5 MB)
            └── ... (mais arquivos)

Nomenclatura: animal_{uniqid}.{extensão}
Exemplo:      animal_65bd3c42a78c1.jpg
```

---

## 6. Validação em Cascata

```mermaid
graph TD
    A["POST /dashboard/animal/cadastrar"] -->|Recebe| B["array POST<br/>array FILES"]

    B -->|Etapa 1| C["ValidadorAnimal<br/>validarFormulario"]
    C -->|Valida| D["✓ nome, sexo, especie,<br/>porte, status,<br/>data_nascimento"]

    D -->|Erro?| E["❌ Retorna lista erros"]
    E -->|Renderiza| F["📋 Formulário com erros<br/>+ dados preenchidos"]

    D -->|OK| G["Etapa 2:<br/>ValidadorUpload"]
    G -->|Verifica| H["✓ is_uploaded_file<br/>✓ MIME type finfo<br/>✓ Extensão<br/>✓ Tamanho"]

    H -->|Erro?| I["❌ Erro upload"]
    I -->|Renderiza| F

    H -->|OK| J["Etapa 3:<br/>processarUploadFoto"]
    J -->|Operações| K["✓ Gera nome único<br/>✓ Move arquivo<br/>✓ Retorna caminho"]

    K -->|Etapa 4| L["AnimalDAO::inserir"]
    L -->|Parametrizado| M["INSERT INTO animal<br/>values :nome, :sexo, ..."]

    M -->|Etapa 5| N["AnimalRacaDAO::vincular<br/>se houver raças"]
    N -->|INSERT INTO| O["animal_raca"]

    O -->|Etapa 6| P["Logger::info"]
    P -->|Sucesso!| Q["🎉 Redirect listar"]

    style C fill:#ff9800
    style D fill:#4caf50
    style E fill:#f44336
    style F fill:#f44336
    style L fill:#2196f3
    style M fill:#2196f3
    style Q fill:#4caf50
```

---

## 7. Ciclo de Vida do Upload de Foto

```mermaid
stateDiagram-v2
    [*] --> UsuarioSeleciona: input type=file
    UsuarioSeleciona --> FormularioEnviado: Click "Enviar"

    FormularioEnviado --> ValidadorUpload: $_FILES['foto'] passado
    ValidadorUpload --> VerificaExistencia: Arquivo foi enviado?

    VerificaExistencia -->|NÃO (UPLOAD_ERR_NO_FILE)| RetornoOptional: Retorna true<br/>Upload é opcional
    VerificaExistencia -->|SIM| VerificaUploadado: is_uploaded_file()?

    VerificaUploadado -->|NÃO| ErroSeguranca: ❌ Erro: Não veio via POST
    VerificaUploadado -->|SIM| VerificaTamanho: Tamanho ≤ 5MB?

    VerificaTamanho -->|NÃO| ErroTamanho: ❌ Arquivo muito grande
    VerificaTamanho -->|SIM| VerificaMIME: MIME correto?

    VerificaMIME -->|NÃO| ErroMIME: ❌ Tipo não permitido
    VerificaMIME -->|SIM| VerificaExtensao: Extensão OK?

    VerificaExtensao -->|NÃO| ErroExtensao: ❌ Extensão não permitida
    VerificaExtensao -->|SIM| RetornoValido: ✅ Válido

    ErroSeguranca --> FormularioRetorno: Redirecion com erros
    ErroTamanho --> FormularioRetorno
    ErroMIME --> FormularioRetorno
    ErroExtensao --> FormularioRetorno

    FormularioRetorno --> [*]
    RetornoOptional --> ContinuarProcessamento: Continua cadastro
    RetornoValido --> MoveArquivo: move_uploaded_file()

    MoveArquivo --> GeraNomeUnico: uniqid('animal_', true)
    GeraNomeUnico --> GeraCaminho: resources/dashboard/images/animais/animal_XXXXX.jpg
    GeraCaminho --> RemoveFotoAnterior: Se for edição, remove foto antiga
    RemoveFotoAnterior --> RetornaDestino: Retorna caminho para DB

    RetornaDestino --> ContinuarProcessamento
    ContinuarProcessamento --> InserirDB: INSERT animal com foto
    InserirDB --> [*]
```

---

## 8. Árvore de Validação - Espécie Obrigatória

```
fk_especie_id Obrigatório?
│
├─ SIM, $_POST['fk_especie_id'] vazio
│  └─ ❌ Erro: "Espécie deve ser selecionada"
│
├─ SIM, $_POST['fk_especie_id'] = "0"
│  └─ (int) "0" = 0
│  └─ ❌ Erro: especie <= 0 é invalido
│
├─ SIM, $_POST['fk_especie_id'] = "abc"
│  └─ (int) "abc" = 0
│  └─ ❌ Erro: não é número válido
│
└─ SIM, $_POST['fk_especie_id'] = "1"
   └─ (int) "1" = 1
   └─ ✓ Número válido
   └─ ⚠️ NOTA: Banco ainda pode rejeitar se id não existe
      (FK RESTRICT no banco, mas validador não checa)
```

---

## 9. Query SELECT com JOINs Completa

```mermaid
graph LR
    subgraph "SELEÇÃO"
        A["SELECT<br/>a.*, e.nome especie_nome,<br/>TIMESTAMPDIFF idade_meses,<br/>o.id ong_id, o.nome ong_nome,<br/>GROUP_CONCAT racas"]
    end

    subgraph "JOINs"
        B["FROM animal a"]
        C["LEFT JOIN especie e<br/>ON e.id = a.fk_especie_id"]
        D["LEFT JOIN ong_animal oa<br/>ON oa.fk_animal_id = a.id"]
        E["LEFT JOIN ong o<br/>ON o.id = oa.fk_ong_id"]
        F["LEFT JOIN animal_raca ar<br/>ON ar.fk_animal_id = a.id"]
        G["LEFT JOIN raca r<br/>ON r.id = ar.fk_raca_id"]
    end

    subgraph "AGRUPAMENTO"
        H["GROUP BY a.id"]
    end

    A --> B
    B --> C
    C --> D
    D --> E
    E --> F
    F --> G
    G --> H

    style B fill:#e8f5e9
    style C fill:#fff3e0
    style D fill:#fff3e0
    style E fill:#fff3e0
    style F fill:#fff3e0
    style G fill:#fff3e0
    style H fill:#f3e5f5
```

---

## 10. Sincronização Raças - Array Diff

```
Raças Atuais no DB:    [2, 3, 5]
Raças Novas Enviadas:  [2, 5, 7]

         array_diff(atuais, novos)  = [3]     → REMOVER
         array_diff(novos, atuais)  = [7]     → ADICIONAR

Ações:
  DELETE FROM animal_raca WHERE fk_animal_id=42 AND fk_raca_id=3
  INSERT INTO animal_raca (fk_animal_id, fk_raca_id) VALUES (42, 7)

Resultado Final:  [2, 5, 7]  ✓
```

---

## 11. Validação de Data Futura (com BUG)

```
Input: data_nascimento = "2026-06-02"
Hoje: 2026-06-02 (10:30:00)

time() = 1654321800  (timestamp atual com hora)

strtotime("2026-06-02") = 1654252800  (começo do dia)

Comparação:
  1654252800 > 1654321800  = FALSE

Resultado: Data ACEITA ✓ (mas deveria ser rejeitada!)

PROBLEMA: strtotime retorna 00:00 do dia, time() retorna hora atual.
Se hora atual > 00:00, comparação falha.

SOLUÇÃO:
  $hoje = strtotime('today 00:00:00') = 1654252800
  strtotime($data) >= $hoje  = REJEITAR
```

---

## 12. Estado das Rotas Mapeadas vs Implementadas

```
✅ = Mapeado no banco (routes table)
❌ = Não mapeado
⚠️  = Implementado no DAO/Controller mas sem rota

AnimalController
├─ ✅ listar()
├─ ✅ cadastro()
├─ ✅ cadastrar()
├─ ✅ editar()
├─ ✅ alterar()
├─ ✅ excluir()
├─ ❌ listarDisponiveis()           (Implementado em DAO, sem ação)
└─ ❌ buscarComFiltros()            (Implementado em DAO, sem ação)

AnimalDAO - Métodos "orphaned"
├─ listarDisponiveis()              (Muito útil, não exposto)
└─ buscarComFiltros(filtros)        (10+ filtros, não exposto)
```

---

## 13. Estados Possíveis de um Animal

```mermaid
stateDiagram-v2
    [*] --> disponivel: Novo animal cadastrado

    disponivel --> adotado: Adotado com sucesso
    disponivel --> resgate: Resgatado
    disponivel --> quarentena: Problema de saúde detectado

    resgate --> quarentena: Requer tratamento
    resgate --> disponivel: Recuperado

    quarentena --> disponivel: Saúde recuperada
    quarentena --> falecido: Faleceu

    adotado --> falecido: Animal faleceu no lar

    disponivel --> falecido: Faleceu na ONG
    resgate --> falecido

    falecido --> [*]
    adotado --> [*]
```

---

## 14. Camadas de Abstração

```
┌─────────────────────────────────┐
│   Frontend / View               │
│ (animal_cadastro.php)           │
├─────────────────────────────────┤
│   Controller Layer              │
│ AnimalController::cadastrar()   │ ← Orquestra o fluxo
├─────────────────────────────────┤
│   Validação Layer               │
│ ValidadorAnimal                 │ ← Regras de negócio
│ ValidadorUpload                 │ ← Segurança
├─────────────────────────────────┤
│   Persistência Layer            │
│ AnimalDAO::inserir()            │ ← CRUD
│ AnimalRacaDAO::vincular()       │ ← Relacionamentos
├─────────────────────────────────┤
│   Auditoria Layer               │
│ Logger::info()                  │ ← Rastreabilidade
├─────────────────────────────────┤
│   Dados Layer                   │
│ MySQL (animal, animal_raca...)  │ ← Persistência
├─────────────────────────────────┤
│   Filesystem Layer              │
│ resources/dashboard/images/     │ ← Armazenamento arquivos
└─────────────────────────────────┘
```

---

## 15. Comparação: Query vs Validador (Status ENUM)

```
┌──────────────┬──────────────────┬──────────────────────┐
│ Valor        │ Validador Aceita │ Banco Aceita          │
├──────────────┼──────────────────┼──────────────────────┤
│disponivel    │ ✓                │ ✓                    │
│adotado       │ ✓                │ ✓                    │
│resgate       │ ✓                │ ❌ (schema: removed) │
│quarentena    │ ✓                │ ❌ (schema: removed) │
│falecido      │ ✓                │ ❌ (schema: removed) │
│em_tratamento │ ❌               │ ✓ (schema: exists)   │
│reservado     │ ❌               │ ✓ (schema: exists)   │
└──────────────┴──────────────────┴──────────────────────┘

RESULTADO: Validação passa mas INSERT falha ❌
```

---

**Gerado em:** Junho 2026  
**Formato:** Diagramas Mermaid Markdown
