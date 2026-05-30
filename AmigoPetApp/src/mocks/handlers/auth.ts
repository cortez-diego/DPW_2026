import type { LoginRequest, LoginResponse, CadastroOngRequest, CadastroVeterinarioRequest } from '../../types/Auth';
import { usuariosMock } from '../data/auth';

export async function login(req: LoginRequest): Promise<LoginResponse> {
  await new Promise(r => setTimeout(r, 800));

  const usuario = usuariosMock.find(
    u => u.email === req.email && u.senha === req.senha,
  );

  if (!usuario) {
    const err: any = new Error('Email ou senha inválidos.');
    err.response = { status: 401, data: { erro: 'Email ou senha inválidos.' } };
    throw err;
  }

  const { senha: _omit, ...usuarioSemSenha } = usuario;
  return { token: `mock-jwt-amigopet-${usuario.id}`, usuario: usuarioSemSenha };
}

export async function logout(): Promise<void> {
  await new Promise(r => setTimeout(r, 200));
}

export async function cadastrarOng(req: CadastroOngRequest): Promise<LoginResponse> {
  await new Promise(r => setTimeout(r, 1000));
  return {
    token: 'mock-jwt-nova-ong',
    usuario: { id: 100, nome: req.nome, email: req.email, tipo_usuario: 'ong', status: 'a' },
  };
}

export async function cadastrarVeterinario(req: CadastroVeterinarioRequest): Promise<LoginResponse> {
  await new Promise(r => setTimeout(r, 1000));
  return {
    token: 'mock-jwt-novo-vet',
    usuario: { id: 101, nome: req.nome, email: req.email, tipo_usuario: 'veterinario', status: 'a' },
  };
}
