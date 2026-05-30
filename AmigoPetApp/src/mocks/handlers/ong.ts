import type { Ong } from '../../types/Ong';
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
