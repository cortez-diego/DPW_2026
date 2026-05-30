import type { Avistamento, PublicarAvistamentoRequest, FiltrosAvistamento } from '../../types/Avistamento';
import { avistamentosMock } from '../data/avistamentos';

let nextId = avistamentosMock.length + 1;

export async function listar(filtros?: FiltrosAvistamento): Promise<Avistamento[]> {
  await new Promise(r => setTimeout(r, 600));
  let resultado = [...avistamentosMock];
  if (filtros?.status) resultado = resultado.filter(a => a.status === filtros.status);
  if (filtros?.especie) resultado = resultado.filter(a => a.especie === filtros.especie);
  return resultado.sort((a, b) => b.data.localeCompare(a.data));
}

export async function buscarPorId(id: number): Promise<Avistamento> {
  await new Promise(r => setTimeout(r, 400));
  const item = avistamentosMock.find(a => a.id === id);
  if (!item) {
    const err: any = new Error('Avistamento não encontrado');
    err.response = { status: 404, data: { erro: 'Avistamento não encontrado' } };
    throw err;
  }
  return item;
}

export async function publicar(req: PublicarAvistamentoRequest, usuarioId: number, usuarioNome: string): Promise<Avistamento> {
  await new Promise(r => setTimeout(r, 900));
  const novo: Avistamento = {
    id: nextId++,
    fotos: req.fotos,
    foto: req.fotos[0] ?? null,
    data: req.data,
    lat: req.lat ?? null,
    lng: req.lng ?? null,
    endereco_texto: req.endereco_texto ?? null,
    local_cep: req.local_cep,
    local_logradouro: req.local_logradouro,
    local_numero: req.local_numero,
    local_bairro: req.local_bairro,
    local_cidade: req.local_cidade,
    local_estado: req.local_estado,
    condicao: req.condicao,
    acoes_tomadas: req.acoes_tomadas,
    especie: req.especie,
    status: 'aguardando_acolhimento',
    usuario: { id: usuarioId, nome: usuarioNome, foto: null },
  };
  avistamentosMock.unshift(novo);
  return novo;
}

export async function atualizarStatus(id: number, status: Avistamento['status']): Promise<Avistamento> {
  await new Promise(r => setTimeout(r, 500));
  const item = avistamentosMock.find(a => a.id === id);
  if (!item) {
    const err: any = new Error('Avistamento não encontrado');
    err.response = { status: 404, data: { erro: 'Avistamento não encontrado' } };
    throw err;
  }
  item.status = status;
  return item;
}
