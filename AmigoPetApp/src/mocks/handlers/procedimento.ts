import type { Procedimento, AdicionarProcedimentoRequest } from '../../types/Saude';
import { procedimentosMock } from '../data/saude';

let nextId = procedimentosMock.length + 1;

export async function listar(animalId: number): Promise<Procedimento[]> {
  await new Promise(r => setTimeout(r, 500));
  return procedimentosMock.filter(p => p.fk_animal_id === animalId)
    .sort((a, b) => b.data.localeCompare(a.data));
}

export async function adicionar(req: AdicionarProcedimentoRequest, autorNome: string): Promise<Procedimento> {
  await new Promise(r => setTimeout(r, 700));
  const novo: Procedimento = {
    id: nextId++,
    nome: req.nome,
    tipo: req.tipo,
    data: req.data,
    veterinario_nome: req.veterinario_nome ?? null,
    observacoes: req.observacoes ?? null,
    anexo_url: req.anexo_url ?? null,
    fk_animal_id: req.fk_animal_id,
    criado_em: new Date().toISOString(),
    criado_por: autorNome,
  };
  procedimentosMock.push(novo);
  return novo;
}
