# REFERÊNCIA RÁPIDA - MÓDULO ANIMAL

## 🚀 QUICK START: Endpoints Disponíveis

| Método   | Endpoint                        | Ação                    | Status |
| -------- | ------------------------------- | ----------------------- | ------ |
| **GET**  | `/dashboard/animal/listar`      | Listar todos os animais | ✅     |
| **GET**  | `/dashboard/animal/cadastro`    | Formulário cadastro     | ✅     |
| **POST** | `/dashboard/animal/cadastrar`   | Inserir novo animal     | ✅     |
| **GET**  | `/dashboard/animal/editar/{id}` | Formulário edição       | ✅     |
| **POST** | `/dashboard/animal/alterar`     | Atualizar animal        | ✅     |
| **POST** | `/dashboard/animal/excluir`     | Deletar animal          | ✅     |

---

## 📋 Campos do Animal

### Obrigatórios (✓ MUST HAVE)

- `nome` - string [3-100]
- `sexo` - enum [M | F]
- `fk_especie_id` - int > 0
- `porte` - enum [pequeno | médio | grande | gigante]

### Opcionais (○ NICE-TO-HAVE)

- `data_nascimento` - date (não pode ser futura)
- `cor` - string
- `castrado` - boolean
- `descricao` - text
- `localizacao` - string
- `foto` - file (max 5MB, image/\*)
- `status` - enum (default: disponivel)
- `fk_raca_id` - int[] (múltiplas raças)

---

## ✅ Validações

### Automaticamente Executadas

```
Nome
  ├─ Obrigatório
  ├─ Min: 3 caracteres
  └─ Max: 100 caracteres

Sexo
  ├─ Obrigatório
  └─ Enum: M ou F

Espécie (fk_especie_id)
  ├─ Obrigatório
  ├─ > 0
  └─ FK validada no banco

Porte
  ├─ Obrigatório
  └─ Enum: pequeno | médio | grande | gigante

Data Nascimento (se preenchida)
  ├─ Formato válido
  └─ ⚠️ BUG: Pode aceitar data de hoje

Status (se preenchida)
  └─ Enum: ✓ disponivel | adotado | resgate | quarentena | falecido

Upload Foto (se preenchido)
  ├─ is_uploaded_file()
  ├─ Tamanho: ≤ 5 MB
  ├─ MIME: image/jpeg | image/png | image/webp | image/gif
  └─ Extensão: jpg | jpeg | png | webp | gif
```

---

## 🔗 Relacionamentos

```
animal (1) ──FK──→ (1) especie

animal (M) ←pivot→ (N) raca
         ↓
     animal_raca
   (fk_animal_id, fk_raca_id)

animal (M) ←pivot→ (N) ong
         ↓
      ong_animal
   (fk_animal_id, fk_ong_id)
```

### Acessar Dados

```php
// Espécie
$animal->__get('especie_nome')      // Ex: "Cachorro"
$animal->__get('fk_especie_id')     // Ex: 1

// Raças
$animal->__get('racas')              // "Labrador, Poodle" (comma-sep)

// ONG
$animal->__get('ong_nome')           // Ex: "ONG AmigoPET"
$animal->__get('ong_id')             // Ex: 5

// Idade Calculada
$animal->__get('idade_meses')        // Ex: 24
```

---

## 💾 Exemplos de Uso

### Listar Todos

```php
$animalDAO = new AnimalDAO();
$animais = $animalDAO->listar();

foreach ($animais as $animal) {
    echo $animal->__get('nome');
    echo $animal->__get('especie_nome');
}
```

### Buscar por ID

```php
$animal = $animalDAO->buscarPorId(1);
if ($animal) {
    echo $animal->__get('nome');
}
```

### Listar Disponíveis (PÚBLICO)

```php
$animaisDisponiveis = $animalDAO->listarDisponiveis();
// Retorna apenas status = 'disponivel'
```

### Filtrar Avançado

```php
$filtros = [
    'porte' => 'pequeno',
    'fk_especie_id' => 1,
    'castrado' => 0,
    'idade_min' => 12,
    'idade_max' => 60,
    'status' => 'disponivel'
];

$animais = $animalDAO->buscarComFiltros($filtros);
// ⚠️ Nenhuma rota/endpoint para isso (implementar se necessário)
```

### Gerenciar Raças

```php
$animalRacaDAO = new AnimalRacaDAO();

// Adicionar raça
$animalRacaDAO->vincular($animalId, $racaId);

// Remover raça
$animalRacaDAO->desvincular($animalId, $racaId);

// Sincronizar (ADD/REMOVE conforme necessário)
$animalRacaDAO->sincronizar($animalId, [3, 5, 7]);
// Resultado: Remove raças antigas, adiciona apenas as novas
```

---

## 🐛 Bugs Conhecidos

### 🔴 CRÍTICOS

1. **Status ENUM Inconsistente**
   - Schema: `('disponivel', 'adotado', 'em_tratamento', 'reservado')`
   - Código: `['disponivel', 'adotado', 'resgate', 'quarentena', 'falecido']`
   - **Resultado:** Validação passa mas banco rejeita
   - **Fix:** UPDATE schema

2. **Campo 'especie' Obsoleto**
   - Coexiste com `fk_especie_id`
   - Nunca é populado
   - **Fix:** DROP campo obsoleto

3. **FK Constraint Faltando**
   - `fk_especie_id` sem FOREIGN KEY
   - Possível inserir animal com espécie inválida
   - **Fix:** ADD FOREIGN KEY constraint

4. **Validação Data Futura Imprecisa**
   - Usa `time()` (hora do dia)
   - Pode aceitar data de hoje
   - **Fix:** Usar `strtotime('today')`

### 🟠 IMPORTANTES

5. **Métodos Sem Rotas**
   - `listarDisponiveis()` e `buscarComFiltros()` não têm endpoint
   - Funcionalidade "orphaned"

6. **Sem Logging em AnimalDAO**
   - Operações não são auditadas
   - Apenas AnimalRacaDAO tem Logger

7. **Upload Concorrência**
   - `uniqid()` pode gerar colisões em alta concorrência
   - Usar UUID v4 em produção

---

## 🔐 Segurança

✅ **BOM**

- MIME type validation (finfo_file)
- Extensão whitelist
- Tamanho máximo (5MB)
- is_uploaded_file() check
- Nomes únicos para evitar sobrescrita
- PDO parameterized queries

⚠️ **MELHORAR**

- Adicionar CSRF token no formulário
- Validar X-Requested-With header
- Implementar rate limiting
- Adicionar antivírus scanner opcional

---

## 📊 Performance

### Queries

- `listar()`: 1 query com 5 LEFT JOINs + GROUP_CONCAT (aceitável)
- `buscarPorId()`: 1 query com 5 LEFT JOINs (OK)
- `buscarComFiltros()`: 1 query dinâmica com HAVING (OK)

### Índices Recomendados

```sql
CREATE INDEX idx_animal_especie ON animal(fk_especie_id);
CREATE INDEX idx_animal_status ON animal(status);
CREATE INDEX idx_animal_raca ON animal_raca(fk_animal_id, fk_raca_id);
CREATE INDEX idx_ong_animal ON ong_animal(fk_animal_id);
```

---

## 📝 POST Parameters

### cadastrar()

```
nome=Fluffy
sexo=F
fk_especie_id=1
porte=médio
cor=Marrom&castrado=1
descricao=Doce e carinhosa
localizacao=São Paulo - SP
status=disponivel
fk_raca_id[]=3&fk_raca_id[]=5
foto=<binary>
```

### alterar()

```
id=1
nome=Fluffy
sexo=F
fk_especie_id=1
... (identico a cadastrar)
foto_atual=resources/dashboard/images/animais/animal_123.jpg
```

### excluir()

```
id=1
```

---

## 🧪 Teste Rápido

### CREATE

```bash
curl -X POST http://localhost/dashboard/animal/cadastrar \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "nome=TestDog&sexo=M&fk_especie_id=1&porte=grande&status=disponivel" \
  -d "foto=@/path/to/image.jpg"
```

### READ

```bash
curl http://localhost/dashboard/animal/listar
```

### UPDATE

```bash
curl -X POST http://localhost/dashboard/animal/alterar \
  -d "id=1&nome=NewName&sexo=F&fk_especie_id=1&porte=pequeno"
```

### DELETE

```bash
curl -X POST http://localhost/dashboard/animal/excluir \
  -d "id=1"
```

---

## 📚 Arquivos Relacionados

- [AnimalController](App/Controller/AnimalController.php)
- [AnimalModel](App/Model/AnimalModel.php)
- [AnimalDAO](App/DAO/AnimalDAO.php)
- [AnimalRacaDAO](App/DAO/AnimalRacaDAO.php)
- [ValidadorAnimal](App/Validador/ValidadorAnimal.php)
- [ValidadorUpload](App/Validador/ValidadorUpload.php)
- [Logger](App/Logger/Logger.php)
- [Schema BD](DB/Schema_AmigoPET.sql)

---

## 📞 Suporte

**Problemas Comuns:**

❓ Animal não é criado  
→ Verifique validação de espécie (fk_especie_id > 0)

❓ Upload falha  
→ Verifique tamanho (≤ 5MB) e tipo (image/\*)

❓ Status não funciona  
→ Schema vs código inconsistente (veja Bugs)

❓ Raças não sincronizam  
→ Verifique AnimalRacaDAO::sincronizar() é chamado

❓ Foto antiga não remove  
→ Veja se removerFoto() tem permissão (777)

---

**Última Atualização:** Junho 2026  
**Versão Schema:** Sprint 2 (parcial)
