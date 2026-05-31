export interface VeterinarioPerfil {
  id: number;
  nome: string;
  cpf: string;
  crmv: string;
  email: string;
  data_nascimento: string | null;
  telefone_1: string;
  telefone_2?: string;
  cep: string;
  logradouro: string;
  numero: number;
  bairro: string;
  cidade: string;
  estado: string;
  complemento?: string;
  foto?: string | null;
}

export interface AtualizarVeterinarioRequest {
  nome: string;
  telefone_1: string;
  telefone_2?: string;
  cep: string;
  logradouro: string;
  numero: number;
  bairro: string;
  cidade: string;
  estado: string;
  complemento?: string;
}

export interface AtualizarOngRequest {
  nome: string;
  email: string;
  telefone_1: string;
  telefone_2?: string;
  cep: string;
  logradouro: string;
  numero: number;
  bairro: string;
  cidade: string;
  estado: string;
  complemento?: string;
  descricao?: string;
}
