import { client } from '../client';
import { ENDPOINTS } from '../endpoints';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/carteira';
import type { CarteiraIdentificacao } from '../../types/Saude';

export const carteiraService = {
  async obter(animalId: number): Promise<CarteiraIdentificacao> {
    if (USE_MOCKS) return mock.obter(animalId);
    const { data } = await client.get<CarteiraIdentificacao>(ENDPOINTS.animais.carteira(animalId));
    return data;
  },
};
