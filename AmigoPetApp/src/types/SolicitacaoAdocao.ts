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
}

export interface SolicitarAdocaoRequest {
  fk_animal_id: number;
  motivo: string;
  aceite_termo: boolean;
  timestamp_aceite: string;
}
