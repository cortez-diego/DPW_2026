import { client } from '../client';
import { ENDPOINTS } from '../endpoints';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/adotante';
import type { Adotante, AtualizarAdotanteRequest } from '../../types/Adotante';

export const adotanteService = {
  async perfil(): Promise<Adotante> {
    if (USE_MOCKS) return mock.perfil();
    const { data } = await client.get<Adotante>(ENDPOINTS.adotante.perfil);
    return data;
  },

  async atualizarPerfil(req: AtualizarAdotanteRequest): Promise<Adotante> {
    if (USE_MOCKS) return mock.atualizar(req);
    const { data } = await client.put<Adotante>(ENDPOINTS.adotante.atualizar, req);
    return data;
  },
};
