import type { Vacina, Procedimento, SaudeAnimal, CarteiraIdentificacao, AuditoriaEntry, AtendimentoResumo } from '../../types/Saude';

const hoje = new Date();
const diasDesde = (d: number) => new Date(hoje.getTime() - d * 86400000).toISOString().split('T')[0];
const diasAte = (d: number) => new Date(hoje.getTime() + d * 86400000).toISOString().split('T')[0];

// ── Vacinas ──────────────────────────────────────────────────────────────────
export const vacinasMock: Vacina[] = [
  {
    id: 1,
    nome: 'V8 (Óctupla)',
    data_aplicacao: diasDesde(180),
    data_reforco: diasAte(5),   // reforço em 5 dias — crítico
    veterinario_nome: 'Dr. Lucas Andrade',
    clinica_nome: 'Clínica VetCare',
    fk_animal_id: 1,
    criado_em: diasDesde(180),
    criado_por: 'Patinhas Felizes (ONG)',
  },
  {
    id: 2,
    nome: 'Antirrábica',
    data_aplicacao: diasDesde(90),
    data_reforco: diasAte(275),  // reforço daqui 275 dias — normal
    veterinario_nome: 'Dr. Lucas Andrade',
    clinica_nome: 'Clínica VetCare',
    fk_animal_id: 1,
    criado_em: diasDesde(90),
    criado_por: 'Dr. Lucas Andrade (Vet)',
  },
  {
    id: 3,
    nome: 'Bordetella',
    data_aplicacao: diasDesde(60),
    data_reforco: diasAte(12),   // reforço em 12 dias — alerta
    veterinario_nome: 'Dra. Ana Lima',
    clinica_nome: 'Hospital Animal PetLife',
    fk_animal_id: 2,
    criado_em: diasDesde(60),
    criado_por: 'Dra. Ana Lima (Vet)',
  },
];

// ── Procedimentos ────────────────────────────────────────────────────────────
export const procedimentosMock: Procedimento[] = [
  {
    id: 1,
    nome: 'Castração',
    tipo: 'castracao',
    data: diasDesde(45),
    veterinario_nome: 'Dr. Lucas Andrade',
    observacoes: 'Procedimento realizado sem intercorrências. Recuperação em 10 dias.',
    anexo_url: null,
    fk_animal_id: 1,
    criado_em: diasDesde(45),
    criado_por: 'Dr. Lucas Andrade (Vet)',
  },
  {
    id: 2,
    nome: 'Exame de sangue completo',
    tipo: 'exame',
    data: diasDesde(30),
    veterinario_nome: 'Dr. Lucas Andrade',
    observacoes: 'Resultados dentro da normalidade. Hemograma completo sem alterações.',
    anexo_url: null,
    fk_animal_id: 1,
    criado_em: diasDesde(30),
    criado_por: 'Dr. Lucas Andrade (Vet)',
  },
  {
    id: 3,
    nome: 'Consulta de rotina',
    tipo: 'consulta',
    data: diasDesde(15),
    veterinario_nome: 'Dra. Ana Lima',
    observacoes: 'Animal saudável. Recomendado banho antiparasitário.',
    anexo_url: null,
    fk_animal_id: 2,
    criado_em: diasDesde(15),
    criado_por: 'Dra. Ana Lima (Vet)',
  },
];

// ── Saúde Geral ──────────────────────────────────────────────────────────────
export const saudeMock: SaudeAnimal[] = [
  { fk_animal_id: 1, apto_para_adocao: true, temperamento: 'Dócil, brincalhão, sociável com crianças e outros animais.', necessidades_especiais: null },
  { fk_animal_id: 2, apto_para_adocao: true, temperamento: 'Calmo e independente. Prefere silêncio.', necessidades_especiais: 'Intolerância a ração com grãos.' },
  { fk_animal_id: 3, apto_para_adocao: false, temperamento: 'Em observação', necessidades_especiais: 'Fratura em recuperação na pata dianteira.' },
];

// ── Carteira de Identificação ────────────────────────────────────────────────
export const carteirasMock: CarteiraIdentificacao[] = [
  {
    animal_id: 1,
    nome: 'Thor',
    especie: 'Cachorro',
    raca: 'Labrador',
    data_nascimento: '2022-03-15',
    castrado: true,
    alergias: null,
    foto: null,
    qr_code_url: 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=amigopet://animal/1',
    pdf_url: 'https://example.com/carteira/1.pdf',
  },
  {
    animal_id: 2,
    nome: 'Mia',
    especie: 'Gato',
    raca: 'Siamês',
    data_nascimento: '2023-07-10',
    castrado: false,
    alergias: 'Ração com grãos',
    foto: null,
    qr_code_url: 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=amigopet://animal/2',
    pdf_url: 'https://example.com/carteira/2.pdf',
  },
];

// ── Auditoria ────────────────────────────────────────────────────────────────
export const auditoriaMock: AuditoriaEntry[] = [
  {
    id: 1, tipo: 'registro_inicial', descricao: 'Animal registrado na plataforma.',
    data: diasDesde(180), autor_nome: 'Patinhas Felizes (ONG)', fk_animal_id: 1,
  },
  {
    id: 2, tipo: 'vacinacao', descricao: 'Vacina V8 aplicada.',
    data: diasDesde(180), autor_nome: 'Dr. Lucas Andrade (Vet)', fk_animal_id: 1,
  },
  {
    id: 3, tipo: 'vacinacao', descricao: 'Vacina Antirrábica aplicada.',
    data: diasDesde(90), autor_nome: 'Dr. Lucas Andrade (Vet)', fk_animal_id: 1,
  },
  {
    id: 4, tipo: 'procedimento', descricao: 'Castração realizada com sucesso.',
    data: diasDesde(45), autor_nome: 'Dr. Lucas Andrade (Vet)', fk_animal_id: 1,
  },
  {
    id: 5, tipo: 'procedimento', descricao: 'Exame de sangue completo realizado.',
    data: diasDesde(30), autor_nome: 'Dr. Lucas Andrade (Vet)', fk_animal_id: 1,
  },
  {
    id: 6, tipo: 'registro_inicial', descricao: 'Animal registrado na plataforma.',
    data: diasDesde(60), autor_nome: 'Patinhas Felizes (ONG)', fk_animal_id: 2,
  },
  {
    id: 7, tipo: 'vacinacao', descricao: 'Vacina Bordetella aplicada.',
    data: diasDesde(60), autor_nome: 'Dra. Ana Lima (Vet)', fk_animal_id: 2,
  },
];

// ── Atendimentos do Vet ───────────────────────────────────────────────────────
export const atendimentosVetMock: AtendimentoResumo[] = [
  { animal_id: 1, animal_nome: 'Thor', animal_especie: 'Cachorro', animal_foto: null, ultima_vacina: diasDesde(90), ultimo_procedimento: diasDesde(30) },
  { animal_id: 2, animal_nome: 'Mia', animal_especie: 'Gato', animal_foto: null, ultima_vacina: diasDesde(60), ultimo_procedimento: diasDesde(15) },
  { animal_id: 3, animal_nome: 'Rex', animal_especie: 'Cachorro', animal_foto: null, ultima_vacina: null, ultimo_procedimento: null },
];
