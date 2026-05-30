export type TipoNotificacao = 'solicitacao' | 'sistema' | 'adocao_concluida';

export interface Notificacao {
  id: number;
  titulo: string;
  mensagem: string;
  data: string;
  lida: boolean;
  tipo: TipoNotificacao;
}
