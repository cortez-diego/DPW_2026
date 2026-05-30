import { client } from '../client';
import { ENDPOINTS } from '../endpoints';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/solicitacaoAdocao';
import type { SolicitacaoAdocao, SolicitarAdocaoRequest } from '../../types/SolicitacaoAdocao';

export const solicitacaoAdocaoService = {
  async minhas(): Promise<SolicitacaoAdocao[]> {
    if (USE_MOCKS) return mock.minhas();
    const { data } = await client.get<SolicitacaoAdocao[]>(ENDPOINTS.solicitacoes.minhas);
    return data;
  },

  async criar(req: SolicitarAdocaoRequest): Promise<SolicitacaoAdocao> {
    if (USE_MOCKS) return mock.criar(req);
    const { data } = await client.post<SolicitacaoAdocao>(ENDPOINTS.solicitacoes.criar, req);
    return data;
  },
};
