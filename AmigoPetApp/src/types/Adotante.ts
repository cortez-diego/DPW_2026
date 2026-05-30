export type StatusAdotante = 'a' | 'i';
export type RankingAdotante = 'pessimo' | 'ruim' | 'bom' | 'muito bom' | 'excelente';

export interface Adotante {
  id: number;
  nome: string;
  cpf: string;
  status: StatusAdotante;
  ranking: RankingAdotante;
  foto: string | null;
  data_nascimento: string | null;
  cep: string;
  numero: number | null;
  bairro: string;
  cidade: string;
  estado: string;
  complemento: string;
  telefone_1: string;
  telefone_2: string;
  logradouro: string;
  fk_login_id: number;
  email?: string;
}

export interface AtualizarAdotanteRequest {
  nome: string;
  cpf: string;
  data_nascimento: string;
  cep: string;
  numero: number;
  bairro: string;
  cidade: string;
  estado: string;
  complemento?: string;
  logradouro: string;
  telefone_1: string;
  telefone_2?: string;
  foto?: string | null;
}
