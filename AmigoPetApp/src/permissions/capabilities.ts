import type { AuthUser } from '../types/Auth';

/** Todas as capacidades funcionais do app. */
export type Capability =
  /** Iniciar solicitação de adoção de um animal. */
  | 'podeAdotar'
  /** Editar nome, foto, endereço e dados pessoais do perfil de adotante. */
  | 'podeEditarPerfilAdotante'
  /** Cadastrar novos animais no sistema (exclusivo ONG). */
  | 'podeCadastrarAnimal'
  /** Aprovar ou recusar solicitações de adoção recebidas (exclusivo ONG). */
  | 'podeAprovarAdocao'
  /** Criar campanhas de adoção (exclusivo ONG). */
  | 'podeCriarCampanha'
  /** Registrar vacinações em animais (exclusivo Veterinário). */
  | 'podeRegistrarVacina'
  /** Registrar procedimentos médicos em animais (exclusivo Veterinário). */
  | 'podeRegistrarProcedimento'
  /** Visualizar agenda e histórico de atendimentos (exclusivo Veterinário). */
  | 'podeVerAtendimentos'
  /**
   * Reportar animal de rua encontrado.
   * Concedido a TODOS os usuários logados — "rastreador" é uma capacidade
   * universal, não um tipo de conta exclusivo.
   */
  | 'podePublicarAnimalDeRua';

type TipoUsuario = AuthUser['tipo_usuario'];

export const CAPABILITIES_BY_ROLE: Record<TipoUsuario, readonly Capability[]> = {
  adotante: [
    'podeAdotar',
    'podeEditarPerfilAdotante',
    'podePublicarAnimalDeRua',
  ],
  ong: [
    'podeCadastrarAnimal',
    'podeAprovarAdocao',
    'podeCriarCampanha',
    'podePublicarAnimalDeRua',
  ],
  veterinario: [
    'podeRegistrarVacina',
    'podeRegistrarProcedimento',
    'podeVerAtendimentos',
    'podePublicarAnimalDeRua',
  ],
  // Admin e Moderador gerenciam via painel web. No app têm capacidades de adotante.
  administrador: [
    'podeAdotar',
    'podeEditarPerfilAdotante',
    'podePublicarAnimalDeRua',
  ],
  moderador: [
    'podeAdotar',
    'podeEditarPerfilAdotante',
    'podePublicarAnimalDeRua',
  ],
};
