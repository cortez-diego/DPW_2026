export type TipoNotificacao =
  | 'solicitacao'
  | 'sistema'
  | 'adocao_concluida'
  | 'status_adocao'
  | 'avistamento_atualizado'
  | 'alerta_vacina'
  | 'promocao_papel';

export interface NotificacaoDestino {
  tab: string;
  tela: string;
  params?: Record<string, unknown>;
}

export interface Notificacao {
  id: number;
  titulo: string;
  mensagem: string;
  data: string;
  lida: boolean;
  tipo: TipoNotificacao;
  destino?: NotificacaoDestino;
}
