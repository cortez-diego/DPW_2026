export interface LoginRequest {
  email: string;
  senha: string;
}

export interface AuthUser {
  id: number;
  nome: string;
  email: string;
  tipo_usuario: 'adotante' | 'ong' | 'veterinario' | 'administrador' | 'moderador';
  status: 'a' | 'i';
}

export interface LoginResponse {
  token: string;
  usuario: AuthUser;
}

export interface CadastroAdotanteRequest {
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
  email: string;
  senha: string;
}

export interface AlterarSenhaRequest {
  senha_atual: string;
  senha_nova: string;
  senha_confirmacao: string;
}

export interface RecuperarSenhaRequest {
  email: string;
}

export interface CadastroOngRequest {
  nome: string;
  cnpj: string;
  email: string;
  senha: string;
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

export interface CadastroVeterinarioRequest {
  nome: string;
  cpf: string;
  crmv: string;
  email: string;
  senha: string;
  data_nascimento: string;
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
