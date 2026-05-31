import type { AvaliacaoAdotante, RegistrarAvaliacaoRequest } from '../../types/Adocao';

const avaliacoesMock: AvaliacaoAdotante[] = [];
let nextId = 1;

export async function registrar(req: RegistrarAvaliacaoRequest, autorNome: string): Promise<AvaliacaoAdotante> {
  await new Promise(r => setTimeout(r, 700));
  const nova: AvaliacaoAdotante = {
    id: nextId++,
    fk_solicitacao_id: req.fk_solicitacao_id,
    tipo_moradia: req.tipo_moradia,
    experiencia_previa: req.experiencia_previa,
    tem_criancas: req.tem_criancas,
    tem_outros_animais: req.tem_outros_animais,
    parecer: req.parecer,
    resultado: req.resultado,
    criado_em: new Date().toISOString(),
    criado_por: autorNome,
  };
  avaliacoesMock.push(nova);
  return nova;
}

export async function buscarPorSolicitacao(solicitacaoId: number): Promise<AvaliacaoAdotante | null> {
  await new Promise(r => setTimeout(r, 300));
  return avaliacoesMock.find(a => a.fk_solicitacao_id === solicitacaoId) ?? null;
}
