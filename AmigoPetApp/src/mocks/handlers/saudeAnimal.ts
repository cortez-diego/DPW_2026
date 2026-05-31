import type { SaudeAnimal } from '../../types/Saude';
import { saudeMock } from '../data/saude';

export async function obter(animalId: number): Promise<SaudeAnimal> {
  await new Promise(r => setTimeout(r, 300));
  return saudeMock.find(s => s.fk_animal_id === animalId)
    ?? { fk_animal_id: animalId, apto_para_adocao: true, temperamento: null, necessidades_especiais: null, condicao_geral: null };
}

export async function atualizar(animalId: number, dados: Partial<Omit<SaudeAnimal, 'fk_animal_id'>>): Promise<SaudeAnimal> {
  await new Promise(r => setTimeout(r, 500));
  const idx = saudeMock.findIndex(s => s.fk_animal_id === animalId);
  if (idx >= 0) {
    Object.assign(saudeMock[idx], dados);
    return saudeMock[idx];
  }
  const nova: SaudeAnimal = {
    fk_animal_id: animalId,
    apto_para_adocao: dados.apto_para_adocao ?? true,
    temperamento: dados.temperamento ?? null,
    necessidades_especiais: dados.necessidades_especiais ?? null,
    condicao_geral: dados.condicao_geral ?? null,
  };
  saudeMock.push(nova);
  return nova;
}
