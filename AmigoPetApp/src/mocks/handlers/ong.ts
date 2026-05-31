import type { Ong } from '../../types/Ong';
import type { AtualizarOngRequest } from '../../types/Veterinario';
import { ongsMock } from '../data/ongs';

export async function listar(): Promise<Ong[]> {
  await new Promise(r => setTimeout(r, 500));
  return ongsMock.filter(o => o.status === 'a');
}

export async function buscarPorId(id: number): Promise<Ong> {
  await new Promise(r => setTimeout(r, 300));
  const ong = ongsMock.find(o => o.id === id);
  if (!ong) {
    const err: any = new Error('ONG não encontrada');
    err.response = { status: 404, data: { erro: 'ONG não encontrada' } };
    throw err;
  }
  return ong;
}

export async function perfil(): Promise<Ong> {
  await new Promise(r => setTimeout(r, 400));
  return { ...ongsMock[0], email: 'ong@teste.com', descricao: 'Organização dedicada ao resgate e adoção de animais desde 2015.' };
}

export async function atualizarPerfil(req: AtualizarOngRequest): Promise<Ong> {
  await new Promise(r => setTimeout(r, 600));
  Object.assign(ongsMock[0], { nome: req.nome, telefone_1: req.telefone_1, telefone_2: req.telefone_2 ?? '', descricao: req.descricao });
  return { ...ongsMock[0], email: req.email };
}
