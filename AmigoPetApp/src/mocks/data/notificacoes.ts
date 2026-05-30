import type { Notificacao } from '../../types/Notificacao';

export const notificacoesMock: Notificacao[] = [
  {
    id: 1,
    titulo: 'Solicitação em análise',
    mensagem: 'Sua solicitação para adotar a Mia foi recebida e está sendo analisada.',
    data: '2026-05-20T09:00:00',
    lida: false,
    tipo: 'solicitacao',
  },
  {
    id: 2,
    titulo: 'Bem-vindo ao AmigoPet!',
    mensagem: 'Obrigado por se cadastrar. Explore os animais disponíveis para adoção.',
    data: '2026-05-01T08:00:00',
    lida: true,
    tipo: 'sistema',
  },
  {
    id: 3,
    titulo: 'Adoção aprovada! 🎉',
    mensagem: 'Parabéns! Sua adoção do Thor foi concluída com sucesso.',
    data: '2026-04-15T14:30:00',
    lida: true,
    tipo: 'adocao_concluida',
  },
  {
    id: 4,
    titulo: 'Novos animais disponíveis',
    mensagem: '3 novos animais foram cadastrados pela ONG Patinhas Felizes.',
    data: '2026-05-18T10:00:00',
    lida: false,
    tipo: 'sistema',
  },
];
