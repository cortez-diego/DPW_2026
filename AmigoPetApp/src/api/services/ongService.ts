import { client } from '../client';
import { ENDPOINTS } from '../endpoints';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/ong';
import type { Ong } from '../../types/Ong';
import type { Animal } from '../../types/Animal';

export const ongService = {
  async listar(): Promise<Ong[]> {
    if (USE_MOCKS) return mock.listar();
    const { data } = await client.get<Ong[]>(ENDPOINTS.ongs.listar);
    return data;
  },

  async buscarPorId(id: number): Promise<Ong> {
    if (USE_MOCKS) return mock.buscarPorId(id);
    const { data } = await client.get<Ong>(ENDPOINTS.ongs.buscarPorId(id));
    return data;
  },

  async animais(id: number): Promise<Animal[]> {
    if (USE_MOCKS) {
      const { animaisMock } = await import('../../mocks/data/animais');
      await new Promise(r => setTimeout(r, 400));
      return animaisMock.filter(a => a.ong?.id === id);
    }
    const { data } = await client.get<Animal[]>(ENDPOINTS.ongs.animais(id));
    return data;
  },
};
