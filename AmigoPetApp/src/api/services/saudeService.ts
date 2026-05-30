import { client } from '../client';
import { ENDPOINTS } from '../endpoints';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/saudeAnimal';
import type { SaudeAnimal } from '../../types/Saude';

export const saudeService = {
  async obter(animalId: number): Promise<SaudeAnimal> {
    if (USE_MOCKS) return mock.obter(animalId);
    const { data } = await client.get<SaudeAnimal>(ENDPOINTS.animais.saude(animalId));
    return data;
  },

  async atualizar(animalId: number, dados: Partial<Omit<SaudeAnimal, 'fk_animal_id'>>): Promise<SaudeAnimal> {
    if (USE_MOCKS) return mock.atualizar(animalId, dados);
    const { data } = await client.put<SaudeAnimal>(ENDPOINTS.animais.saude(animalId), dados);
    return data;
  },
};
