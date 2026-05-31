export type TipoMoradia = 'casa_com_quintal' | 'casa_sem_quintal' | 'apartamento' | 'outro';
export type ResultadoAvaliacao = 'aprovado' | 'reprovado' | 'pendente_informacoes';

export interface AvaliacaoAdotante {
  id: number;
  fk_solicitacao_id: number;
  tipo_moradia: TipoMoradia;
  experiencia_previa: boolean;
  tem_criancas: boolean;
  tem_outros_animais: boolean;
  parecer: string;
  resultado: ResultadoAvaliacao;
  criado_em: string;
  criado_por: string;
}

export interface RegistrarAvaliacaoRequest {
  fk_solicitacao_id: number;
  tipo_moradia: TipoMoradia;
  experiencia_previa: boolean;
  tem_criancas: boolean;
  tem_outros_animais: boolean;
  parecer: string;
  resultado: ResultadoAvaliacao;
}

export interface Termo {
  solicitacao_id: number;
  pdf_url: string;
  pdf_assinado_url: string | null;
  conteudo_texto: string;
  assinado: boolean;
  data_assinatura: string | null;
}

export interface AssinarTermoRequest {
  aceite: boolean;
  timestamp: string;
}

export interface Transferencia {
  id: number;
  fk_animal_id: number;
  de_usuario_id: number;
  de_usuario_nome: string;
  para_usuario_id: number;
  para_usuario_nome: string;
  motivo: string | null;
  data: string;
}

export interface TransferirRequest {
  para_usuario_id: number;
  para_usuario_nome: string;
  motivo?: string;
}
