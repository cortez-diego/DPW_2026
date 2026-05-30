import { client } from '../client';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/notificacao';
import type { Notificacao } from '../../types/Notificacao';

export const notificacaoService = {
  async listar(): Promise<Notificacao[]> {
    if (USE_MOCKS) return mock.listar();
    const { data } = await client.get<Notificacao[]>('/notificacoes');
    return data;
  },

  async marcarLida(id: number): Promise<void> {
    if (USE_MOCKS) return mock.marcarLida(id);
    await client.patch(`/notificacoes/${id}/marcar-lida`);
  },
};
