export const ENDPOINTS = {
  auth: {
    login: '/auth/login',
    logout: '/auth/logout',
    cadastrarAdotante: '/auth/cadastrar/adotante',
    cadastrarOng: '/auth/cadastrar/ong',
    cadastrarVeterinario: '/auth/cadastrar/veterinario',
    alterarSenha: '/auth/alterar-senha',
    recuperarSenha: '/auth/recuperar-senha',
  },
  animais: {
    listar: '/animais',
    meus: '/animais/meus',
    buscarPorId: (id: number) => `/animais/${id}`,
    cadastrar: '/animais',
    atualizar: (id: number) => `/animais/${id}`,
    historico: (id: number) => `/animais/${id}/historico`,
    vacinas: (id: number) => `/animais/${id}/vacinas`,
    procedimentos: (id: number) => `/animais/${id}/procedimentos`,
    saude: (id: number) => `/animais/${id}/saude`,
    carteira: (id: number) => `/animais/${id}/carteira`,
    auditoria: (id: number) => `/animais/${id}/auditoria`,
    transferencias: (id: number) => `/animais/${id}/transferencias`,
  },
  vet: {
    meusAtendimentos: '/vet/atendimentos',
    perfil: '/veterinario/perfil',
    clinicas: '/veterinario/clinicas',
    associarClinica: (id: number) => `/veterinario/clinicas/${id}`,
    desassociarClinica: (id: number) => `/veterinario/clinicas/${id}`,
  },
  ong: {
    perfil: '/ong/perfil',
  },
  usuarios: {
    buscar: (q: string) => `/usuarios/busca?q=${encodeURIComponent(q)}`,
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
    recebidas: '/solicitacoes/recebidas',
    buscarPorId: (id: number) => `/solicitacoes/${id}`,
    avancarStatus: (id: number) => `/solicitacoes/${id}/status`,
    avaliacao: (id: number) => `/solicitacoes/${id}/avaliacao`,
    termo: (id: number) => `/solicitacoes/${id}/termo`,
    assinarTermo: (id: number) => `/solicitacoes/${id}/termo/assinar`,
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
