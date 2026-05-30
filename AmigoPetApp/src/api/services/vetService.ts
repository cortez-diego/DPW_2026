import { client } from '../client';
import { ENDPOINTS } from '../endpoints';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/vet';
import type { AtendimentoResumo } from '../../types/Saude';

export const vetService = {
  async meusAtendimentos(): Promise<AtendimentoResumo[]> {
    if (USE_MOCKS) return mock.meusAtendimentos();
    const { data } = await client.get<AtendimentoResumo[]>(ENDPOINTS.vet.meusAtendimentos);
    return data;
  },
};
