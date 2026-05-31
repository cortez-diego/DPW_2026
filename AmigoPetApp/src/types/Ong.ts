export interface Ong {
  id: number;
  nome: string;
  cnpj: string;
  status: 'a' | 'i';
  quantidade_animais: number;
  cep: string;
  numero: number;
  bairro: string;
  cidade: string;
  estado: string;
  complemento: string;
  logradouro: string;
  telefone_1: string;
  telefone_2: string;
  email?: string;
  foto?: string | null;
  descricao?: string;
}
