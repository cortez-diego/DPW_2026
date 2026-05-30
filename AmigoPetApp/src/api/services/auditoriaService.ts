import { client } from '../client';
import { ENDPOINTS } from '../endpoints';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/auditoria';
import type { AuditoriaEntry } from '../../types/Saude';

export const auditoriaService = {
  async listar(animalId: number): Promise<AuditoriaEntry[]> {
    if (USE_MOCKS) return mock.listar(animalId);
    const { data } = await client.get<AuditoriaEntry[]>(ENDPOINTS.animais.auditoria(animalId));
    return data;
  },
};
