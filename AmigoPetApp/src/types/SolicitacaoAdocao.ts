import type { Animal } from './Animal';

export type StatusSolicitacao =
  | 'Pendente'
  | 'Em Análise'
  | 'Aprovado'
  | 'Concluído'
  | 'Recusado';

export interface SolicitacaoAdocao {
  id: number;
  data: string;
  status: StatusSolicitacao;
  motivo: string;
  fk_adotante_id: number;
  fk_animal_id: number;
  animal?: Animal;
  adotante_nome?: string;
  adotante_email?: string;
  termo_assinado?: boolean;
  pdf_termo_url?: string | null;
}

export interface AvancarStatusRequest {
  status: StatusSolicitacao;
  motivo_recusa?: string;
}

export interface SolicitarAdocaoRequest {
  fk_animal_id: number;
  motivo: string;
  aceite_termo: boolean;
  timestamp_aceite: string;
}
