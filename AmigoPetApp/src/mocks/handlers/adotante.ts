import type { Adotante, AtualizarAdotanteRequest } from '../../types/Adotante';
import { adotanteMock } from '../data/adotante';

let adotanteAtual: Adotante = { ...adotanteMock };

export async function perfil(): Promise<Adotante> {
  await new Promise(r => setTimeout(r, 400));
  return { ...adotanteAtual };
}

export async function atualizar(req: AtualizarAdotanteRequest): Promise<Adotante> {
  await new Promise(r => setTimeout(r, 600));
  adotanteAtual = { ...adotanteAtual, ...req };
  return { ...adotanteAtual };
}
