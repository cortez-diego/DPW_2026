import { client } from '../client';
import { ENDPOINTS } from '../endpoints';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/ranking';
import type { RankingItem } from '../../types/Avistamento';

export const rankingService = {
  async listar(): Promise<RankingItem[]> {
    if (USE_MOCKS) return mock.listar();
    const { data } = await client.get<RankingItem[]>(ENDPOINTS.ranking.rastreadores);
    return data;
  },
};
