export const ENDPOINTS = {
  auth: {
    login: '/auth/login',
    logout: '/auth/logout',
    cadastrarAdotante: '/auth/cadastrar/adotante',
    cadastrarOng: '/auth/cadastrar/ong',
    cadastrarVeterinario: '/auth/cadastrar/veterinario',
    alterarSenha: '/auth/alterar-senha',
  },
  animais: {
    listar: '/animais',
    meus: '/animais/meus',
    buscarPorId: (id: number) => `/animais/${id}`,
    cadastrar: '/animais',
    atualizar: (id: number) => `/animais/${id}`,
    historico: (id: number) => `/animais/${id}/historico`,
  },
  adotante: {
    perfil: '/adotante/perfil',
    atualizar: '/adotante/perfil',
  },
  ongs: {
    listar: '/ongs',
    buscarPorId: (id: number) => `/ongs/${id}`,
    animais: (id: number) => `/ongs/${id}/animais`,
  },
  clinicas: {
    listar: '/clinicas',
    cadastrar: '/clinicas',
    buscarPorId: (id: number) => `/clinicas/${id}`,
  },
  solicitacoes: {
    criar: '/solicitacoes',
    minhas: '/solicitacoes/minhas',
    buscarPorId: (id: number) => `/solicitacoes/${id}`,
  },
  especies: {
    listar: '/especies',
  },
  racas: {
    listar: '/racas',
    porEspecie: (especieId: number) => `/racas?especie_id=${especieId}`,
  },
  avistamentos: {
    listar: '/avistamentos',
    publicar: '/avistamentos',
    buscarPorId: (id: number) => `/avistamentos/${id}`,
    atualizarStatus: (id: number) => `/avistamentos/${id}/status`,
  },
  ranking: {
    rastreadores: '/ranking/rastreadores',
  },
} as const;
