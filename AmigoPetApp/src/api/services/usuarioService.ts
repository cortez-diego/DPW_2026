import { client } from '../client';
import { ENDPOINTS } from '../endpoints';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/usuario';
import type { UsuarioBusca } from '../../types/Usuario';

export const usuarioService = {
  async buscar(query: string): Promise<UsuarioBusca[]> {
    if (USE_MOCKS) return mock.buscar(query);
    const { data } = await client.get<UsuarioBusca[]>(ENDPOINTS.usuarios.buscar(query));
    return data;
  },
};
