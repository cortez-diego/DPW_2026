import { client } from '../client';
import { ENDPOINTS } from '../endpoints';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/avistamento';
import type { Avistamento, PublicarAvistamentoRequest, FiltrosAvistamento } from '../../types/Avistamento';

export const avistamentoService = {
  async listar(filtros?: FiltrosAvistamento): Promise<Avistamento[]> {
    if (USE_MOCKS) return mock.listar(filtros);
    const { data } = await client.get<Avistamento[]>(ENDPOINTS.avistamentos.listar, { params: filtros });
    return data;
  },

  async buscarPorId(id: number): Promise<Avistamento> {
    if (USE_MOCKS) return mock.buscarPorId(id);
    const { data } = await client.get<Avistamento>(ENDPOINTS.avistamentos.buscarPorId(id));
    return data;
  },

  async publicar(req: PublicarAvistamentoRequest, usuarioId: number, usuarioNome: string): Promise<Avistamento> {
    if (USE_MOCKS) return mock.publicar(req, usuarioId, usuarioNome);
    const { data } = await client.post<Avistamento>(ENDPOINTS.avistamentos.publicar, req);
    return data;
  },

  async atualizarStatus(id: number, status: Avistamento['status']): Promise<Avistamento> {
    if (USE_MOCKS) return mock.atualizarStatus(id, status);
    const { data } = await client.patch<Avistamento>(ENDPOINTS.avistamentos.atualizarStatus(id), { status });
    return data;
  },
};
