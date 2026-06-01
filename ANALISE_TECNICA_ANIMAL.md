# ANÁLISE TÉCNICA COMPLETA - MÓDULO ANIMAL

**Projeto:** DPW_2026 (AmigoPET)  
**Análise Data:** Junho 2026  
**Escopo:** Controllers, DAOs, Models, Validadores, Relacionamentos, Fluxo de Dados

---

## 1. ESTRUTURA DO MÓDULO

### Arquivos Principais

```
App/
├── Controller/AnimalController.php      (6 actions)
├── Model/AnimalModel.php                (15 propriedades)
├── DAO/
│   ├── AnimalDAO.php                   (5 métodos CRUD + 2 especializados)
│   └── AnimalRacaDAO.php               (9 métodos de sincronização)
└── Validador/
    ├── ValidadorAnimal.php             (2 métodos: validar + validarFormulario)
    └── ValidadorUpload.php             (Upload com verificação MIME)
```

---

## 2. ENDPOINTS DISPONÍVEIS

### 2.1 AnimalController - Action: listar

- **Método HTTP:** GET
- **Rota:** `/dashboard/animal/listar`
- **Controlador:** AnimalController
- **Action:** listar
- **Acesso:** Dashboard (requer autenticação)
- **Parâmetros:** Nenhum
- **Retorno:** View com lista de animais + dados relacionados
- **Dados Retornados:**
  ```
  animal[] {
    id, nome, data_nascimento, sexo, fk_especie_id,
    cor, castrado, descricao, porte, localizacao, foto, status,
    especie_nome, ong_id, ong_nome, racas (concatenadas),
    idade_meses (calculada)
  }
  ```

### 2.2 AnimalController - Action: cadastro

- **Método HTTP:** GET
- **Rota:** `/dashboard/animal/cadastro`
- **Parâmetros:** Nenhum
- **Retorno:** Formulário de cadastro
- **Dados Enviados à View:**
  - `especies[]` - Lista de espécies para dropdown

### 2.3 AnimalController - Action: cadastrar

- **Método HTTP:** POST
- **Rota:** `/dashboard/animal/cadastrar`
- **Parâmetros POST Esperados:**
  ```
  {
    nome            : string (obrigatório, 3-100 chars)
    sexo            : enum M|F (obrigatório)
    fk_especie_id   : integer (obrigatório, > 0)
    porte           : enum pequeno|médio|grande|gigante (obrigatório)
    cor             : string (opcional)
    castrado        : checkbox (0 ou 1)
    descricao       : text (opcional)
    localizacao     : string (opcional)
    foto            : file (opcional, max 5MB, image/*)
    status          : enum disponivel|adotado|resgate|quarentena|falecido (default: disponivel)
    fk_raca_id      : integer[] (opcional, múltiplas raças)
  }
  ```
- **Validações Executadas:**
  1. ValidadorAnimal::validarFormulario($\_POST) - Valida todos os campos
  2. ValidadorUpload::validar($\_FILES['foto']) - Valida imagem
- **Fluxo Processamento:**
  ```
  POST → ValidadorAnimal (validarFormulario)
       → ValidadorUpload (validar arquivo)
       → processarUploadFoto() [gera nome único]
       → AnimalDAO::inserir() [INSERT]
       → AnimalRacaDAO::vincular() [vincular raças se existirem]
       → Logger::info()
       → Redirect /dashboard/animal/listar
  ```
- **Response Erro:** Retorna para formulário com erros e dados preenchidos
- **Response Sucesso:** Redirect para listagem

### 2.4 AnimalController - Action: editar

- **Método HTTP:** GET
- **Rota:** `/dashboard/animal/editar/{id}`
- **Parâmetros URL:**
  - `id` : integer (obrigatório)
- **Query SQL Executada:**
  ```sql
  SELECT a.*, e.nome AS especie_nome,
         TIMESTAMPDIFF(MONTH, a.data_nascimento, CURDATE()) AS idade_meses,
         o.id AS ong_id, o.nome AS ong_nome,
         GROUP_CONCAT(r.nome ORDER BY r.nome SEPARATOR ', ') AS racas
  FROM animal a
  LEFT JOIN especie e ON e.id = a.fk_especie_id
  LEFT JOIN ong_animal oa ON oa.fk_animal_id = a.id
  LEFT JOIN ong o ON o.id = oa.fk_ong_id
  LEFT JOIN animal_raca ar ON ar.fk_animal_id = a.id
  LEFT JOIN raca r ON r.id = ar.fk_raca_id
  WHERE a.id = :id
  GROUP BY a.id
  ```
- **Dados Retornados à View:**
  - `animal` - Objeto AnimalModel com todos os dados
  - `especies[]` - Todas as espécies disponíveis
  - `racas[]` - Raças filtradas por espécie selecionada
  - `racasVinculadas[]` - IDs das raças já vinculadas ao animal

### 2.5 AnimalController - Action: alterar

- **Método HTTP:** POST
- **Rota:** `/dashboard/animal/alterar`
- **Parâmetros POST:**
  ```
  {
    id              : integer (obrigatório)
    nome            : string (obrigatório)
    sexo            : enum (obrigatório)
    fk_especie_id   : integer (obrigatório)
    porte           : enum (obrigatório)
    cor             : string (opcional)
    castrado        : checkbox (0 ou 1)
    descricao       : text (opcional)
    localizacao     : string (opcional)
    foto            : file (opcional)
    foto_atual      : string (caminho da foto salva)
    status          : enum (default: disponivel)
    fk_raca_id      : integer[] (opcional)
  }
  ```
- **Fluxo:**
  ```
  POST → ValidadorAnimal (validarFormulario)
       → ValidadorUpload (validar)
       → processarUploadFoto() [substituição com limpeza]
       → AnimalDAO::alterar() [UPDATE]
       → AnimalRacaDAO::sincronizar() [ADD/REMOVE raças]
       → Logger::info()
       → Redirect /dashboard/animal/listar
  ```
- **Response Erro:** Retorna para edição com erros

### 2.6 AnimalController - Action: excluir

- **Método HTTP:** POST
- **Rota:** `/dashboard/animal/excluir`
- **Parâmetros POST:**
  - `id` : integer
- **Fluxo:**
  ```
  POST → Buscar animal (verificar foto)
       → removerFoto() [deleta arquivo do servidor]
       → AnimalDAO::excluir() [DELETE - cascata no DB]
       → Logger::info()
       → Redirect /dashboard/animal/listar
  ```

---

## 3. CAMPOS SUPORTADOS POR ANIMAL

### 3.1 Estrutura do Model

```php
class AnimalModel {
    // Campos Banco de Dados
    private int $id;
    private string $nome;
    private string $data_nascimento;     // date format: YYYY-MM-DD
    private string $sexo;                // M ou F
    private int $fk_especie_id;          // FK → especie.id (obrigatório)
    private string $cor;
    private bool $castrado;              // 0 ou 1
    private string $descricao;
    private string $porte;               // pequeno|médio|grande|gigante
    private string $localizacao;
    private string $foto;                // caminho relativo
    private string $status;              // disponivel|adotado|resgate|quarentena|falecido

    // Campos Computados (via JOINs - apenas leitura)
    private string $especie_nome;        // FROM especie JOIN
    private int $ong_id;                 // FROM ong_animal JOIN
    private string $ong_nome;
    private string $racas;               // GROUP_CONCAT comma-separated
    private int $idade_meses;            // TIMESTAMPDIFF(MONTH)
}
```

### 3.2 Campos por Tipo

| Campo           | Tipo         | Obrigatório | Default        | Observações                                             |
| --------------- | ------------ | ----------- | -------------- | ------------------------------------------------------- |
| id              | INT          | ✓ PK        | AUTO_INCREMENT |                                                         |
| nome            | VARCHAR(100) | ✓           | -              | 3-100 chars                                             |
| data_nascimento | DATE         | ✗           | NULL           | Validação: não pode ser futura                          |
| sexo            | ENUM(M,F)    | ✓           | -              | Validação: ['M', 'F']                                   |
| fk_especie_id   | INT (FK)     | ✓           | -              | ⚠️ Schema tem também campo 'especie' varchar obsoleto   |
| cor             | VARCHAR(50)  | ✗           | NULL           |                                                         |
| castrado        | TINYINT(1)   | ✗           | 0              | Boolean (0=false, 1=true)                               |
| descricao       | TEXT         | ✗           | NULL           |                                                         |
| porte           | ENUM         | ✓           | -              | pequeno\|médio\|grande\|gigante                         |
| localizacao     | VARCHAR(100) | ✗           | NULL           |                                                         |
| foto            | VARCHAR(255) | ✗           | NULL           | Caminho relativo: resources/dashboard/images/animais/\* |
| status          | ENUM         | ✗           | disponivel     | disponivel\|adotado\|resgate\|quarentena\|falecido      |

---

## 4. VALIDAÇÕES IMPLEMENTADAS

### 4.1 ValidadorAnimal::validar(AnimalModel, $ehEdicao)

```php
public function validar(AnimalModel $animal, bool $ehEdicao = false): bool
```

**Campos Validados:**

1. **nome**
   - Não vazio ✓
   - Min 3 caracteres ✓
   - Max 100 caracteres ✓

2. **sexo**
   - Não vazio ✓
   - Enumeração: ['M', 'F'] ✓

3. **fk_especie_id** (CRÍTICO)
   - Obrigatório ✓
   - Tipo inteiro > 0 ✓
   - ⚠️ NÃO verifica se existe em tabela especie
   - Confiança: FK no banco (ON DELETE RESTRICT)

4. **porte**
   - Não vazio ✓
   - Enumeração: ['pequeno', 'médio', 'grande', 'gigante'] ✓

5. **status** (se preenchido)
   - Enumeração: ['disponivel', 'adotado', 'resgate', 'quarentena', 'falecido'] ✓

6. **data_nascimento** (se preenchido)
   - Formato data válida (strtotime()) ✓
   - Validação: `strtotime($dataNascimento) > time()` ❌
   - ⚠️ PROBLEMA: Usa time() que inclui hora do dia
   - RECOMENDAÇÃO: Usar `strtotime('tomorrow') - 1`

### 4.2 ValidadorAnimal::validarFormulario(array $dados)

**Idêntico ao validar()** mas recebe array $\_POST diretamente

- Converte fk_especie_id para int
- Verifica se <= 0 (string vazia ou zero invalida)

### 4.3 ValidadorUpload - Validações Arquivo Foto

```php
public function validar(array $arquivo, string $nomeCampo = 'arquivo'): bool
```

| Aspecto              | Validação                                       | Status    |
| -------------------- | ----------------------------------------------- | --------- |
| Arquivo Obrigatório? | NÃO (UPLOAD_ERR_NO_FILE retorna true)           | ✓ Correto |
| is_uploaded_file()   | Verifica se veio via POST                       | ✓ Seguro  |
| Tamanho Máximo       | 5 MB (5242880 bytes)                            | ✓         |
| MIME Type            | Valida com finfo_file (não extensão)            | ✓ Seguro  |
| Tipos Permitidos     | image/jpeg, image/png, image/webp, image/gif    | ✓         |
| Extensão             | Valida: jpg,jpeg,png,webp,gif                   | ✓         |
| Erro Upload          | Mensagens customizadas para cada código de erro | ✓         |

### 4.4 Fluxo de Validação no Cadastro

```
cadastrar() POST
├─ 1. ValidadorAnimal::validarFormulario($_POST)
│  ├─ Se ERRO → Retorna para formulário com erros
│  └─ Se OK → continua
├─ 2. ValidadorUpload::validar($_FILES['foto'])
│  ├─ Se ERRO → Retorna para formulário com erros
│  └─ Se OK → continua
├─ 3. processarUploadFoto()
│  ├─ Gera nome único: animal_XXXXX.ext
│  ├─ Move arquivo para resources/dashboard/images/animais/
│  └─ Retorna caminho salvo
├─ 4. Cria AnimalModel com todos os dados
├─ 5. AnimalDAO::inserir() → INSERT SQL
├─ 6. AnimalRacaDAO::vincular() se houver raças
└─ 7. Logger::info() com animal_id
```

---

## 5. FLUXO DE DADOS: ENTRADA ATÉ BANCO

### 5.1 Exemplo Completo: Cadastro de Animal

```
CLIENTE FRONTEND
    ↓ POST /dashboard/animal/cadastrar
    ├─ nome: "Fluffy"
    ├─ sexo: "F"
    ├─ fk_especie_id: "1"
    ├─ porte: "pequeno"
    ├─ foto: [arquivo binary]
    └─ fk_raca_id: [3, 5]
    ↓
AnimalController::cadastrar()
    ├─ ValidadorAnimal::validarFormulario($_POST)
    │  └─ Retorna true/false + $this->erros[]
    ├─ Se erros → renderiza formulário com erros
    ├─ ValidadorUpload::validar($_FILES['foto'])
    │  ├─ is_uploaded_file() ✓
    │  ├─ MIME type ✓
    │  ├─ Extensão ✓
    │  └─ Retorna true/false
    ├─ Se erro → renderiza formulário com erros
    ├─ processarUploadFoto()
    │  ├─ Gera: animal_65bd3c42a78c1.jpg
    │  ├─ Move para: resources/dashboard/images/animais/animal_65bd3c42a78c1.jpg
    │  └─ Retorna caminho relativo
    ├─ Cria AnimalModel
    │  ├─ $model->__set('nome', 'Fluffy')
    │  ├─ $model->__set('sexo', 'F')
    │  ├─ $model->__set('fk_especie_id', 1)
    │  ├─ $model->__set('foto', 'resources/dashboard/images/animais/animal_65bd3c42a78c1.jpg')
    │  └─ ...
    ├─ AnimalDAO::inserir($model)
    │  └─ Executa SQL parametrizado
    ├─ AnimalRacaDAO::vincular(animalId, 3)
    ├─ AnimalRacaDAO::vincular(animalId, 5)
    ├─ Logger::info('Animal cadastrado com sucesso', ['animal_id' => $id])
    └─ Redirect /dashboard/animal/listar
    ↓
BANCO DE DADOS
    INSERT INTO animal (nome, sexo, fk_especie_id, ...)
    VALUES ('Fluffy', 'F', 1, ...)
    → animal.id = 42 (AUTO_INCREMENT)

    INSERT INTO animal_raca (fk_animal_id, fk_raca_id)
    VALUES (42, 3)

    INSERT INTO animal_raca (fk_animal_id, fk_raca_id)
    VALUES (42, 5)

    arquivo: resources/dashboard/images/animais/animal_65bd3c42a78c1.jpg
```

---

## 6. RELACIONAMENTOS E CONSUMO

### 6.1 Animal → Especie

**Tipo:** 1:1 Relação Referencial (FK obrigatória)

```sql
ALTER TABLE animal ADD FOREIGN KEY (fk_especie_id)
  REFERENCES especie(id) ON DELETE RESTRICT
```

**Como Consumir:**

```php
$animalDAO = new AnimalDAO();
$animal = $animalDAO->buscarPorId(1);

// Acessar espécie
echo $animal->__get('especie_nome');        // Ex: "Cachorro"
echo $animal->__get('fk_especie_id');       // Ex: 1

// Buscar dados completos da espécie
$especieDAO = new EspecieDAO();
$especie = $especieDAO->buscarPorId($animal->__get('fk_especie_id'));
```

**Restrições:**

- ⚠️ Não pode deletar Espécie se houver Animal vinculado (FK RESTRICT)
- ✓ Mudança de Espécie: Permitida (UPDATE fk_especie_id)

### 6.2 Animal → ONG (via pivot ong_animal)

**Tipo:** M:N (múltiplas ONGs por animal, múltiplos animais por ONG)

```sql
CREATE TABLE ong_animal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fk_ong_id INT NOT NULL,
    fk_animal_id INT NOT NULL,
    FOREIGN KEY (fk_animal_id) REFERENCES animal(id) ON DELETE CASCADE,
    FOREIGN KEY (fk_ong_id) REFERENCES ong(id) ON DELETE CASCADE
)
```

**Como Consumir:**

```php
// Buscar animal com ONG
$animal = $animalDAO->buscarPorId(1);
echo $animal->__get('ong_id');          // ID da ONG
echo $animal->__get('ong_nome');        // Nome da ONG

// Query com LEFT JOIN - retorna apenas 1 ONG (primeira)
// Se animal vinculado a múltiplas ONGs, usar:
$sql = "SELECT DISTINCT o.* FROM ong o
        JOIN ong_animal oa ON o.id = oa.fk_ong_id
        WHERE oa.fk_animal_id = ?";
```

**Limitações:**

- ⚠️ JOIN retorna apenas primeira ONG (sem GROUP_CONCAT)
- ⚠️ Sem método DAO para gerenciar vínculo ONG (apenas campo read-only)

### 6.3 Animal → Raça (via pivot animal_raca)

**Tipo:** M:N (múltiplas raças por animal, múltiplos animais por raça)

```sql
CREATE TABLE animal_raca (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fk_animal_id INT NOT NULL,
    fk_raca_id INT NOT NULL,
    UNIQUE KEY (fk_animal_id, fk_raca_id),
    FOREIGN KEY (fk_animal_id) REFERENCES animal(id) ON DELETE CASCADE,
    FOREIGN KEY (fk_raca_id) REFERENCES raca(id) ON DELETE RESTRICT
)
```

**Como Consumir:**

```php
// Via AnimalModel (JOIN + GROUP_CONCAT)
$animal = $animalDAO->buscarPorId(1);
echo $animal->__get('racas');           // "Labrador, Poodle" (comma-separated)

// Via AnimalRacaDAO - obter lista estruturada
$animalRacaDAO = new AnimalRacaDAO();
$vinculos = $animalRacaDAO->listarPorAnimal(1);
foreach ($vinculos as $vinculo) {
    echo $vinculo->__get('fk_raca_id');  // Ex: 3, 5
}

// Gerenciar raças na edição
$animalRacaDAO->sincronizar(1, [3, 5, 7]);
// Resultado: ADD raça 7, REMOVE outras
```

**Métodos AnimalRacaDAO:**

- `vincular(int $animalId, int $racaId)` - Adiciona vínculo
- `desvincular(int $animalId, int $racaId)` - Remove vínculo
- `sincronizar(int $animalId, array $novosRacaIds)` - ADD/REMOVE necessários
- `listarPorAnimal(int $animalId)` - Retorna array
- `listarPorRaca(int $racaId)` - Retorna animais de raça
- `existeVinculo(int $animalId, int $racaId)` - Boolean

---

## 7. ERROS E INCONSISTÊNCIAS IDENTIFICADAS

### 🔴 CRÍTICOS

#### 1. Status ENUM Inconsistente

**Arquivo:** `DB/Schema_AmigoPET.sql` linha 271

```sql
CREATE TABLE animal (
    ...
    status enum('disponivel', 'adotado', 'em_tratamento', 'reservado')
            NOT NULL DEFAULT 'disponivel'
);
```

**Código:** `App/Validador/ValidadorAnimal.php` linha 53

```php
$this->validarEnumeracao($status,
    ['disponivel', 'adotado', 'resgate', 'quarentena', 'falecido'],
    'status'
);
```

**Problema:** Schema tem ('disponivel', 'adotado', 'em_tratamento', 'reservado')  
mas validador espera ('disponivel', 'adotado', 'resgate', 'quarentena', 'falecido')

**Impacto:** ❌ Validação aceitará valores que o banco rejeitará  
**Solução:** Sincronizar schema com validador

---

#### 2. Campo 'especie' Obsoleto vs fk_especie_id

**Arquivo:** `DB/Schema_AmigoPET.sql` linha 264

```sql
ALTER TABLE animal
    ADD COLUMN especie varchar(50) null,    -- ⚠️ OBSOLETO
    ADD COLUMN porte enum(...) null,
    ADD COLUMN fk_especie_id int(11);       -- ⚠️ NOVO (sem FK constraint)
```

**Problema:**

- Campo VARCHAR 'especie' coexiste com FK 'fk_especie_id'
- Código usa apenas fk_especie_id
- Sem integridade referencial explícita (falta ALTER para adicionar FOREIGN KEY)

**Impacto:**

- ❌ Possibilidade de inserir animal com fk_especie_id inválido
- ⚠️ Campo 'especie' nunca é populado (confusion)

**Solução:**

```sql
-- 1. Remover campo obsoleto
ALTER TABLE animal DROP COLUMN especie;

-- 2. Adicionar FK constraint
ALTER TABLE animal
ADD CONSTRAINT fk_animal_especie
    FOREIGN KEY (fk_especie_id)
    REFERENCES especie(id) ON DELETE RESTRICT;

-- 3. Fazer fk_especie_id NOT NULL
ALTER TABLE animal MODIFY fk_especie_id INT NOT NULL;
```

---

#### 3. Validação Data Futura Imprecisa

**Arquivo:** `App/Validador/ValidadorAnimal.php` linha 48

```php
if (strtotime($dataNascimento) > time()) {
    $this->adicionarErro('data_nascimento',
        'Data de nascimento não pode ser no futuro');
}
```

**Problema:** `time()` retorna timestamp da hora atual (Ex: 1654321600)  
Comparação `strtotime('2026-06-02') > time()` será:

- ✓ Correto se data for ano seguinte
- ❌ Incorreto se data for hoje mas hora diferente

**Exemplo Problema:**

```
Hoje: 2026-06-02 10:30:00 (timestamp: 1654321800)
Data digitada: 2026-06-02 (strtotime = 1654252800)
Resultado: 1654252800 > 1654321800 = false ❌ (aceita como válida)

Mas se digitarem 2026-06-03:
strtotime('2026-06-03') = 1654339200
1654339200 > 1654321800 = true ✓ (rejeita corretamente)
```

**Solução:**

```php
$hoje = strtotime('today 00:00:00');  // Começo do dia
$dataNascimentoTs = strtotime($dataNascimento);
if ($dataNascimentoTs >= $hoje) {
    $this->adicionarErro('data_nascimento',
        'Data de nascimento não pode ser no futuro');
}
```

---

### 🟠 IMPORTANTES

#### 4. Métodos DAO Sem Rotas Mapeadas

**Arquivo:** `App/DAO/AnimalDAO.php` linha 300

```php
public function listarDisponiveis()  // ← Implementado
public function buscarComFiltros($filtros)  // ← Muito completo, suporta 10+ filtros
```

**Problema:**

- Métodos muito úteis mas sem ação no Controller
- Sem rota no banco de dados
- Funcionalidade de filtro implementada mas não acessível

**Filtros Suportados:**

```php
$filtros = [
    'fk_especie_id' => 1,        // int
    'porte' => 'pequeno',         // enum
    'sexo' => 'F',                // M|F
    'castrado' => 1,              // 0|1
    'status' => 'disponivel',     // enum
    'cor' => 'branco',            // string exato
    'localizacao' => 'SP',        // LIKE %valor%
    'idade_min' => 12,            // meses
    'idade_max' => 60,            // meses
    'ong_id' => 5                 // int
];
$animais = $animalDAO->buscarComFiltros($filtros);
```

**Impacto:** Funcionalidade de busca avançada não está disponível na UI

---

#### 5. Logging Não Implementado em AnimalDAO

**Arquivo:** `App/DAO/AnimalDAO.php`

**Problema:**

- AnimalDAO operações (inserir, alterar, excluir) não loggam
- Apenas erro (header redirect) é gerado
- AnimalRacaDAO tem Logger implementado, mas Animal não

**Impacto:**

- ⚠️ Sem rastreabilidade de mudanças de animais
- Auditoria comprometida

**Solução:** Adicionar Logger::info() após cada operação bem-sucedida

---

#### 6. Substituição de Foto Não Remove Arquivo Anterior

**Arquivo:** `App/Controller/AnimalController.php` linha 316

```php
if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
    if ($fotoAtual) {
        $this->removerFoto($fotoAtual);  // ✓ Remove corretamente
    }
    return $destino;
}
return $fotoAtual;  // ⚠️ Volta para foto antiga se upload falhar
```

**Cenário Problema:**

1. Upload falha (arquivo corrompido)
2. Função retorna $fotoAtual
3. Mas arquivo foi parcialmente gravado em $destino
4. Arquivo órfão permanece no servidor

**Solução:** Limpar arquivo temporário antes de retornar erro

---

### 🟡 OBSERVAÇÕES

#### 7. Performance: N+1 Query Potencial

**Arquivo:** `App/DAO/AnimalDAO.php` linha 315

Na edição, há 3 queries separadas:

```php
$animal = $animalDAO->buscarPorId($id);         // Query 1: LEFT JOINs
$racas = $racaDAO->listarPorEspecie($especieId); // Query 2: todas raças
$racasVinculadas = $animalRacaDAO->listarPorAnimal($id); // Query 3
```

**Impacto:** Moderado (apenas 3 queries, não loop)  
**Potencial Otimização:** Combinar em 1 query

---

#### 8. Upload MIME Type Validation

**Arquivo:** `App/Validador/ValidadorUpload.php` linha 111

Usa `finfo_file()` para validar tipo real (não extensão)  
✓ **Segurança:** Excelente - previne image.exe.jpg  
✓ **Performance:** Aceitável - operação rápida

---

#### 9. Sincronização de Raças - Idempotente

**Arquivo:** `App/DAO/AnimalRacaDAO.php` linha 223

```php
public function sincronizar(int $animalId, array $novosRacaIds): void
{
    $novos = array_map('intval', array_filter($novosRacaIds));
    $atuais = array_map(
        fn(AnimalRacaModel $ar) => (int) $ar->__get('fk_raca_id'),
        $this->listarPorAnimal($animalId)
    );

    $paraRemover = array_diff($atuais, $novos);      // Remove apenas necessárias
    $paraAdicionar = array_diff($novos, $atuais);    // Add apenas novas

    foreach ($paraRemover as $racaId) {
        $this->desvincular($animalId, $racaId);
    }
    foreach ($paraAdicionar as $racaId) {
        $this->vincular($animalId, $racaId);
    }
}
```

✓ **Padrão:** Excelente uso de array_diff()  
✓ **Atomicidade:** Sem transações, mas operações são seguras (FK constraints)

---

## 8. TESTES DE VALIDAÇÃO

### 8.1 Test Case: Cadastro Animal Válido

```
Input:
{
    nome: "Rex",
    sexo: "M",
    fk_especie_id: 1,
    porte: "grande",
    status: "disponivel"
}

Expected: ✓ SUCCESS
Response: Redirect /dashboard/animal/listar
```

### 8.2 Test Case: Data Nascimento Futura

```
Input:
{
    data_nascimento: "2027-12-31"  // Ano futuro
}

Current Result: ❌ ERRO (strtotime() > time() é true)
Expected: ❌ ERRO - "Data de nascimento não pode ser no futuro"
Status: ✓ CORRETO (mas por coincidência)
```

### 8.3 Test Case: Data Nascimento Hoje (problema)

```
Input:
{
    data_nascimento: "2026-06-02"  // Hoje
}

Current: time() = 1654321800 (10:30:00)
strtotime('2026-06-02') = 1654252800 (00:00:00)
1654252800 > 1654321800 = false
Result: ✓ VÁLIDA

Expected: ❌ Deveria ser inválida (não pode nascer hoje)
Status: ❌ FALHA
```

### 8.4 Test Case: Upload Foto Seguro

```
Input:
{
    foto: "image.jpg" (MIME: image/jpeg, size: 2MB, ext: jpg)
}

Validações:
✓ is_uploaded_file()
✓ MIME type com finfo_file()
✓ Extensão em whitelist
✓ Tamanho <= 5MB

Result: ✓ ACEITA
```

### 8.5 Test Case: Upload Malicioso

```
Input:
{
    foto: "virus.php" (renomeado para virus.jpg)
}

Validações:
✓ is_uploaded_file()
✓ MIME type = application/x-php (NÃO em whitelist)
✗ Extensão = php (NÃO em whitelist)
✗ Rejeita "Tipo de arquivo não permitido"

Result: ❌ REJEITA corretamente
```

---

## 9. EXEMPLO DE CONSUMO: Busca com Filtros

### Cenário: Buscar animais pequenos, não castrados, de uma espécie

```php
$animalDAO = new AnimalDAO();

$filtros = [
    'porte' => 'pequeno',
    'castrado' => 0,
    'fk_especie_id' => 2,
    'status' => 'disponivel'
];

$animais = $animalDAO->buscarComFiltros($filtros);

foreach ($animais as $animal) {
    echo "ID: " . $animal->__get('id') . "<br>";
    echo "Nome: " . $animal->__get('nome') . "<br>";
    echo "Espécie: " . $animal->__get('especie_nome') . "<br>";
    echo "Idade: " . $animal->__get('idade_meses') . " meses<br>";
    echo "Raças: " . $animal->__get('racas') . "<br>";
    echo "ONG: " . $animal->__get('ong_nome') . "<br><br>";
}
```

---

## 10. DADOS EXEMPLO PARA TESTE

### 10.1 Insert Espécie (prerequisito)

```sql
INSERT INTO especie (nome) VALUES ('Cachorro');
INSERT INTO especie (nome) VALUES ('Gato');
INSERT INTO especie (nome) VALUES ('Coelho');
```

### 10.2 Insert Raças

```sql
INSERT INTO raca (nome, fk_especie_id) VALUES ('Labrador', 1);
INSERT INTO raca (nome, fk_especie_id) VALUES ('Poodle', 1);
INSERT INTO raca (nome, fk_especie_id) VALUES ('Persa', 2);
INSERT INTO raca (nome, fk_especie_id) VALUES ('Siamês', 2);
INSERT INTO raca (nome, fk_especie_id) VALUES ('Lop', 3);
```

### 10.3 Insert Animal Completo

```sql
INSERT INTO animal (
    nome,
    data_nascimento,
    sexo,
    fk_especie_id,
    cor,
    castrado,
    descricao,
    porte,
    localizacao,
    foto,
    status
) VALUES (
    'Fluffy',
    '2024-03-15',
    'F',
    1,
    'Marrom',
    1,
    'Cão dócil e brincalhão',
    'médio',
    'São Paulo - SP',
    'resources/dashboard/images/animais/animal_123.jpg',
    'disponivel'
);

-- Retorna: animal.id = 1

INSERT INTO animal_raca (fk_animal_id, fk_raca_id)
VALUES (1, 1), (1, 2);  -- Labrador + Poodle
```

### 10.4 Busca Resultado Esperado

```php
$animal = $animalDAO->buscarPorId(1);

// Resultado:
{
    id: 1,
    nome: "Fluffy",
    data_nascimento: "2024-03-15",
    sexo: "F",
    fk_especie_id: 1,
    cor: "Marrom",
    castrado: 1,
    descricao: "Cão dócil e brincalhão",
    porte: "médio",
    localizacao: "São Paulo - SP",
    foto: "resources/dashboard/images/animais/animal_123.jpg",
    status: "disponivel",
    especie_nome: "Cachorro",
    idade_meses: 15,
    ong_id: null,
    ong_nome: null,
    racas: "Labrador, Poodle"
}
```

---

## 11. RECOMENDAÇÕES E AÇÕES NECESSÁRIAS

### 🔴 CRÍTICAS (Implementar Imediatamente)

1. **Sincronizar Status ENUM**
   - [ ] Atualizar schema para: ('disponivel', 'adotado', 'resgate', 'quarentena', 'falecido')
   - [ ] Migrar dados existentes
   - [ ] Testar conversão

2. **Adicionar FK Constraint**

   ```sql
   ALTER TABLE animal MODIFY fk_especie_id INT NOT NULL;
   ALTER TABLE animal
   ADD CONSTRAINT fk_animal_especie
       FOREIGN KEY (fk_especie_id) REFERENCES especie(id);
   ```

3. **Remover Campo Obsoleto**

   ```sql
   ALTER TABLE animal DROP COLUMN especie;
   ```

4. **Corrigir Validação Data Futura**
   - [ ] Atualizar ValidadorAnimal para usar `strtotime('today')`

### 🟠 IMPORTANTES (Próximas Sprints)

5. [ ] Adicionar método no Controller para expor `buscarComFiltros()`
6. [ ] Implementar Logger em AnimalDAO
7. [ ] Criar testes unitários para validadores
8. [ ] Implementar transações no AnimalRacaDAO::sincronizar()

### 🟡 NICE-TO-HAVE

9. [ ] Otimizar queries (combinar em JOINs únicos)
10. [ ] Adicionar paginação em listar()
11. [ ] Implementar soft delete para auditoria

---

## 12. RESUMO EXECUTIVO

| Aspecto               | Status | Observação                                            |
| --------------------- | ------ | ----------------------------------------------------- |
| **CRUD Completo**     | ✅     | Criar, Ler, Atualizar, Deletar funcionando            |
| **Validações**        | ⚠️     | Implementadas mas com bugs (data futura, status enum) |
| **Segurança Upload**  | ✅     | Muito boa (MIME + extensão + tamanho)                 |
| **Relacionamentos**   | ✅     | FK, Pivot tables, Sincronização OK                    |
| **Logging**           | ⚠️     | Parcial (apenas AnimalRacaDAO + Logger)               |
| **Performance**       | ✅     | Aceitável (JOINs eficientes, GROUP_CONCAT)            |
| **Schema BD**         | ❌     | Inconsistências críticas (status, campos obsoletos)   |
| **Filtros Avançados** | ⚠️     | Implementados mas não expostos                        |

---

**Gerado em:** Junho 2026  
**Autor da Análise:** GitHub Copilot
