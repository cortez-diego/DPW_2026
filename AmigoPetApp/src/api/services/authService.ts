import { client } from '../client';
import { ENDPOINTS } from '../endpoints';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/auth';
import type {
  LoginRequest,
  LoginResponse,
  CadastroAdotanteRequest,
  CadastroOngRequest,
  CadastroVeterinarioRequest,
  AlterarSenhaRequest,
} from '../../types/Auth';

export const authService = {
  async login(req: LoginRequest): Promise<LoginResponse> {
    if (USE_MOCKS) return mock.login(req);
    const { data } = await client.post<LoginResponse>(ENDPOINTS.auth.login, req);
    return data;
  },

  async logout(): Promise<void> {
    if (USE_MOCKS) return mock.logout();
    await client.post(ENDPOINTS.auth.logout);
  },

  async cadastrarAdotante(req: CadastroAdotanteRequest): Promise<LoginResponse> {
    if (USE_MOCKS) {
      await new Promise(r => setTimeout(r, 1000));
      return {
        token: 'mock-jwt-novo-usuario',
        usuario: { id: 99, nome: req.nome, email: req.email, tipo_usuario: 'adotante', status: 'a' },
      };
    }
    const { data } = await client.post<LoginResponse>(ENDPOINTS.auth.cadastrarAdotante, req);
    return data;
  },

  async cadastrarOng(req: CadastroOngRequest): Promise<LoginResponse> {
    if (USE_MOCKS) return mock.cadastrarOng(req);
    const { data } = await client.post<LoginResponse>(ENDPOINTS.auth.cadastrarOng, req);
    return data;
  },

  async cadastrarVeterinario(req: CadastroVeterinarioRequest): Promise<LoginResponse> {
    if (USE_MOCKS) return mock.cadastrarVeterinario(req);
    const { data } = await client.post<LoginResponse>(ENDPOINTS.auth.cadastrarVeterinario, req);
    return data;
  },

  async alterarSenha(req: AlterarSenhaRequest): Promise<void> {
    if (USE_MOCKS) { await new Promise(r => setTimeout(r, 600)); return; }
    await client.post(ENDPOINTS.auth.alterarSenha, req);
  },
};
