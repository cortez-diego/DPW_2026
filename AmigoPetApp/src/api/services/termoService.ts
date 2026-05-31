import { client } from '../client';
import { ENDPOINTS } from '../endpoints';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/termo';
import type { Termo, AssinarTermoRequest } from '../../types/Adocao';

export const termoService = {
  async obter(solicitacaoId: number): Promise<Termo> {
    if (USE_MOCKS) return mock.obter(solicitacaoId);
    const { data } = await client.get<Termo>(ENDPOINTS.solicitacoes.termo(solicitacaoId));
    return data;
  },

  async assinar(solicitacaoId: number, req: AssinarTermoRequest): Promise<Termo> {
    if (USE_MOCKS) return mock.assinar(solicitacaoId, req);
    const { data } = await client.post<Termo>(ENDPOINTS.solicitacoes.assinarTermo(solicitacaoId), req);
    return data;
  },
};
