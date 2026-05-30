import { client } from '../client';
import { ENDPOINTS } from '../endpoints';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/vacina';
import type { Vacina, AdicionarVacinaRequest } from '../../types/Saude';

export const vacinaService = {
  async listar(animalId: number): Promise<Vacina[]> {
    if (USE_MOCKS) return mock.listar(animalId);
    const { data } = await client.get<Vacina[]>(ENDPOINTS.animais.vacinas(animalId));
    return data;
  },

  async adicionar(req: AdicionarVacinaRequest, autorNome: string): Promise<Vacina> {
    if (USE_MOCKS) return mock.adicionar(req, autorNome);
    const { data } = await client.post<Vacina>(ENDPOINTS.animais.vacinas(req.fk_animal_id), req);
    return data;
  },
};
