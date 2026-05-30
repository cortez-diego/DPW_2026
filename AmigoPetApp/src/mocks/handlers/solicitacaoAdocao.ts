import type { SolicitacaoAdocao, SolicitarAdocaoRequest } from '../../types/SolicitacaoAdocao';
import { animaisMock } from '../data/animais';

const solicitacoesMock: SolicitacaoAdocao[] = [
  {
    id: 1,
    data: '2026-05-01T10:00:00',
    status: 'Em Análise',
    motivo: 'Tenho espaço e muito carinho para oferecer.',
    fk_adotante_id: 1,
    fk_animal_id: 2,
    animal: animaisMock.find(a => a.id === 2),
  },
];

export async function minhas(): Promise<SolicitacaoAdocao[]> {
  await new Promise(r => setTimeout(r, 600));
  return [...solicitacoesMock];
}

export async function criar(req: SolicitarAdocaoRequest): Promise<SolicitacaoAdocao> {
  await new Promise(r => setTimeout(r, 800));
  const nova: SolicitacaoAdocao = {
    id: solicitacoesMock.length + 1,
    data: new Date().toISOString(),
    status: 'Pendente',
    motivo: req.motivo,
    fk_adotante_id: 1,
    fk_animal_id: req.fk_animal_id,
    animal: animaisMock.find(a => a.id === req.fk_animal_id),
  };
  solicitacoesMock.push(nova);
  return nova;
}
