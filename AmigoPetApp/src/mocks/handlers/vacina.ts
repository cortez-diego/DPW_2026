import type { Vacina, AdicionarVacinaRequest } from '../../types/Saude';
import { vacinasMock } from '../data/saude';

let nextId = vacinasMock.length + 1;

export async function listar(animalId: number): Promise<Vacina[]> {
  await new Promise(r => setTimeout(r, 500));
  return vacinasMock.filter(v => v.fk_animal_id === animalId)
    .sort((a, b) => b.data_aplicacao.localeCompare(a.data_aplicacao));
}

export async function adicionar(req: AdicionarVacinaRequest, autorNome: string): Promise<Vacina> {
  await new Promise(r => setTimeout(r, 700));
  const nova: Vacina = {
    id: nextId++,
    nome: req.nome,
    data_aplicacao: req.data_aplicacao,
    data_reforco: req.data_reforco ?? null,
    veterinario_nome: req.veterinario_nome ?? null,
    clinica_nome: req.clinica_nome ?? null,
    fk_animal_id: req.fk_animal_id,
    criado_em: new Date().toISOString(),
    criado_por: autorNome,
  };
  vacinasMock.push(nova);
  return nova;
}
