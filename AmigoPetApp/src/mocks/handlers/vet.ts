import type { AtendimentoResumo } from '../../types/Saude';
import { atendimentosVetMock } from '../data/saude';

export async function meusAtendimentos(): Promise<AtendimentoResumo[]> {
  await new Promise(r => setTimeout(r, 600));
  return [...atendimentosVetMock];
}
