import { client } from '../client';
import { ENDPOINTS } from '../endpoints';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/avaliacao';
import type { AvaliacaoAdotante, RegistrarAvaliacaoRequest } from '../../types/Adocao';

export const avaliacaoService = {
  async registrar(req: RegistrarAvaliacaoRequest, autorNome: string): Promise<AvaliacaoAdotante> {
    if (USE_MOCKS) return mock.registrar(req, autorNome);
    const { data } = await client.post<AvaliacaoAdotante>(ENDPOINTS.solicitacoes.avaliacao(req.fk_solicitacao_id), req);
    return data;
  },

  async buscarPorSolicitacao(solicitacaoId: number): Promise<AvaliacaoAdotante | null> {
    if (USE_MOCKS) return mock.buscarPorSolicitacao(solicitacaoId);
    try {
      const { data } = await client.get<AvaliacaoAdotante>(ENDPOINTS.solicitacoes.avaliacao(solicitacaoId));
      return data;
    } catch { return null; }
  },
};
