import type { Notificacao } from '../../types/Notificacao';
import { notificacoesMock } from '../data/notificacoes';

let notificacoes: Notificacao[] = [...notificacoesMock];

export async function listar(): Promise<Notificacao[]> {
  await new Promise(r => setTimeout(r, 400));
  return [...notificacoes];
}

export async function marcarLida(id: number): Promise<void> {
  await new Promise(r => setTimeout(r, 200));
  notificacoes = notificacoes.map(n => n.id === id ? { ...n, lida: true } : n);
}
