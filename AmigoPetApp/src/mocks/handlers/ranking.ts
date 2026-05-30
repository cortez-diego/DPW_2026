import type { RankingItem } from '../../types/Avistamento';
import { rankingMock } from '../data/ranking';

export async function listar(): Promise<RankingItem[]> {
  await new Promise(r => setTimeout(r, 500));
  return [...rankingMock];
}
