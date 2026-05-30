import { client } from '../client';
import { ENDPOINTS } from '../endpoints';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/procedimento';
import type { Procedimento, AdicionarProcedimentoRequest } from '../../types/Saude';

export const procedimentoService = {
  async listar(animalId: number): Promise<Procedimento[]> {
    if (USE_MOCKS) return mock.listar(animalId);
    const { data } = await client.get<Procedimento[]>(ENDPOINTS.animais.procedimentos(animalId));
    return data;
  },

  async adicionar(req: AdicionarProcedimentoRequest, autorNome: string): Promise<Procedimento> {
    if (USE_MOCKS) return mock.adicionar(req, autorNome);
    const { data } = await client.post<Procedimento>(ENDPOINTS.animais.procedimentos(req.fk_animal_id), req);
    return data;
  },
};
