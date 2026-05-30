import { client } from '../client';
import { ENDPOINTS } from '../endpoints';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/clinica';
import type { Clinica, CadastrarClinicaRequest } from '../../types/Clinica';

export const clinicaService = {
  async listar(): Promise<Clinica[]> {
    if (USE_MOCKS) return mock.listar();
    const { data } = await client.get<Clinica[]>(ENDPOINTS.clinicas.listar);
    return data;
  },

  async cadastrar(req: CadastrarClinicaRequest): Promise<Clinica> {
    if (USE_MOCKS) return mock.cadastrar(req);
    const { data } = await client.post<Clinica>(ENDPOINTS.clinicas.cadastrar, req);
    return data;
  },
};
