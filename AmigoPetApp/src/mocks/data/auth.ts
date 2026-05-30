import type { AuthUser } from '../../types/Auth';

export const MOCK_SENHA = '12345678';

export const usuariosMock: Array<AuthUser & { senha: string }> = [
  {
    id: 1,
    nome: 'João Gabriel',
    email: 'joao@exemplo.com',
    tipo_usuario: 'adotante',
    status: 'a',
    senha: MOCK_SENHA,
  },
  {
    id: 2,
    nome: 'Adotante Teste',
    email: 'adotante@teste.com',
    tipo_usuario: 'adotante',
    status: 'a',
    senha: MOCK_SENHA,
  },
  {
    id: 3,
    nome: 'Patinhas Felizes ONG',
    email: 'ong@teste.com',
    tipo_usuario: 'ong',
    status: 'a',
    senha: MOCK_SENHA,
  },
  {
    id: 4,
    nome: 'Dr. Veterinário Teste',
    email: 'vet@teste.com',
    tipo_usuario: 'veterinario',
    status: 'a',
    senha: MOCK_SENHA,
  },
];

/** Mantido para retrocompatibilidade com código que já importa usuarioMock diretamente. */
export const usuarioMock = usuariosMock[0];
export const MOCK_EMAIL = usuariosMock[0].email;
