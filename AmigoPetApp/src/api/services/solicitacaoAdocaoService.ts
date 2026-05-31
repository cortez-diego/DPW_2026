import { client } from '../client';
import { ENDPOINTS } from '../endpoints';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/solicitacaoAdocao';
import type { SolicitacaoAdocao, SolicitarAdocaoRequest, AvancarStatusRequest } from '../../types/SolicitacaoAdocao';

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

  async buscarPorId(id: number): Promise<SolicitacaoAdocao> {
    if (USE_MOCKS) return mock.buscarPorId(id);
    const { data } = await client.get<SolicitacaoAdocao>(ENDPOINTS.solicitacoes.buscarPorId(id));
    return data;
  },

  async recebidas(): Promise<SolicitacaoAdocao[]> {
    if (USE_MOCKS) return mock.recebidas();
    const { data } = await client.get<SolicitacaoAdocao[]>(ENDPOINTS.solicitacoes.recebidas);
    return data;
  },

  async avancarStatus(id: number, req: AvancarStatusRequest): Promise<SolicitacaoAdocao> {
    if (USE_MOCKS) return mock.avancarStatus(id, req);
    const { data } = await client.patch<SolicitacaoAdocao>(ENDPOINTS.solicitacoes.avancarStatus(id), req);
    return data;
  },
};
