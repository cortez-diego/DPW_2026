import { client } from '../client';
import { ENDPOINTS } from '../endpoints';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/vet';
import type { AtendimentoResumo } from '../../types/Saude';
import type { VeterinarioPerfil, AtualizarVeterinarioRequest } from '../../types/Veterinario';
import type { Clinica } from '../../types/Clinica';

export const vetService = {
  async meusAtendimentos(): Promise<AtendimentoResumo[]> {
    if (USE_MOCKS) return mock.meusAtendimentos();
    const { data } = await client.get<AtendimentoResumo[]>(ENDPOINTS.vet.meusAtendimentos);
    return data;
  },

  async perfil(): Promise<VeterinarioPerfil> {
    if (USE_MOCKS) return mock.perfil();
    const { data } = await client.get<VeterinarioPerfil>(ENDPOINTS.vet.perfil);
    return data;
  },

  async atualizarPerfil(req: AtualizarVeterinarioRequest): Promise<VeterinarioPerfil> {
    if (USE_MOCKS) return mock.atualizarPerfil(req);
    const { data } = await client.put<VeterinarioPerfil>(ENDPOINTS.vet.perfil, req);
    return data;
  },

  async minhasClinicas(): Promise<Clinica[]> {
    if (USE_MOCKS) return mock.minhasClinicas();
    const { data } = await client.get<Clinica[]>(ENDPOINTS.vet.clinicas);
    return data;
  },

  async associarClinica(clinicaId: number): Promise<void> {
    if (USE_MOCKS) return mock.associarClinica(clinicaId);
    await client.post(ENDPOINTS.vet.associarClinica(clinicaId));
  },

  async desassociarClinica(clinicaId: number): Promise<void> {
    if (USE_MOCKS) return mock.desassociarClinica(clinicaId);
    await client.delete(ENDPOINTS.vet.desassociarClinica(clinicaId));
  },
};
