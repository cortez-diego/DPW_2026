import { client } from '../client';
import { ENDPOINTS } from '../endpoints';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/transferencia';
import type { Transferencia, TransferirRequest } from '../../types/Adocao';

export const transferenciaService = {
  async historico(animalId: number): Promise<Transferencia[]> {
    if (USE_MOCKS) return mock.historico(animalId);
    const { data } = await client.get<Transferencia[]>(ENDPOINTS.animais.transferencias(animalId));
    return data;
  },

  async transferir(animalId: number, req: TransferirRequest, deId: number, deNome: string): Promise<Transferencia> {
    if (USE_MOCKS) return mock.transferir(animalId, req, deId, deNome);
    const { data } = await client.post<Transferencia>(ENDPOINTS.animais.transferencias(animalId), req);
    return data;
  },
};
