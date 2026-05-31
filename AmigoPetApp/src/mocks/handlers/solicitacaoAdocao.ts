import type { SolicitacaoAdocao, SolicitarAdocaoRequest, AvancarStatusRequest } from '../../types/SolicitacaoAdocao';
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
    adotante_nome: 'João Silva',
    adotante_email: 'joao@exemplo.com',
    termo_assinado: false,
    pdf_termo_url: null,
  },
  {
    id: 2,
    data: '2026-05-05T14:30:00',
    status: 'Aprovado',
    motivo: 'Sempre quis ter um labrador, tenho casa com quintal.',
    fk_adotante_id: 1,
    fk_animal_id: 1,
    animal: animaisMock.find(a => a.id === 1),
    adotante_nome: 'João Silva',
    adotante_email: 'joao@exemplo.com',
    termo_assinado: false,
    pdf_termo_url: null,
  },
  {
    id: 10,
    data: '2026-05-10T09:00:00',
    status: 'Pendente',
    motivo: 'Quero adotar para minha filha.',
    fk_adotante_id: 5,
    fk_animal_id: 1,
    animal: animaisMock.find(a => a.id === 1),
    adotante_nome: 'Ana Beatriz',
    adotante_email: 'ana@email.com',
    termo_assinado: false,
    pdf_termo_url: null,
  },
  {
    id: 11,
    data: '2026-05-12T11:00:00',
    status: 'Em Análise',
    motivo: 'Tenho experiência com gatos há 5 anos.',
    fk_adotante_id: 7,
    fk_animal_id: 2,
    animal: animaisMock.find(a => a.id === 2),
    adotante_nome: 'Pedro Costa',
    adotante_email: 'pedro@email.com',
    termo_assinado: false,
    pdf_termo_url: null,
  },
];

export async function minhas(): Promise<SolicitacaoAdocao[]> {
  await new Promise(r => setTimeout(r, 600));
  return solicitacoesMock.filter(s => s.fk_adotante_id === 1);
}

export async function criar(req: SolicitarAdocaoRequest): Promise<SolicitacaoAdocao> {
  await new Promise(r => setTimeout(r, 800));
  const nova: SolicitacaoAdocao = {
    id: solicitacoesMock.length + 100,
    data: new Date().toISOString(),
    status: 'Pendente',
    motivo: req.motivo,
    fk_adotante_id: 1,
    fk_animal_id: req.fk_animal_id,
    animal: animaisMock.find(a => a.id === req.fk_animal_id),
    adotante_nome: 'João Silva',
    adotante_email: 'joao@exemplo.com',
    termo_assinado: false,
    pdf_termo_url: null,
  };
  solicitacoesMock.push(nova);
  return nova;
}

export async function buscarPorId(id: number): Promise<SolicitacaoAdocao> {
  await new Promise(r => setTimeout(r, 400));
  const s = solicitacoesMock.find(x => x.id === id);
  if (!s) {
    const err: any = new Error('Solicitação não encontrada');
    err.response = { status: 404, data: { erro: 'Solicitação não encontrada' } };
    throw err;
  }
  return s;
}

export async function recebidas(): Promise<SolicitacaoAdocao[]> {
  await new Promise(r => setTimeout(r, 600));
  return solicitacoesMock.filter(s => s.fk_adotante_id !== 1);
}

export async function avancarStatus(id: number, req: AvancarStatusRequest): Promise<SolicitacaoAdocao> {
  await new Promise(r => setTimeout(r, 600));
  const s = solicitacoesMock.find(x => x.id === id);
  if (!s) throw new Error('Solicitação não encontrada');
  s.status = req.status;
  if (req.status === 'Concluído') {
    const animal = animaisMock.find(a => a.id === s.fk_animal_id);
    if (animal) animal.status = 'adotado';
  }
  return s;
}
