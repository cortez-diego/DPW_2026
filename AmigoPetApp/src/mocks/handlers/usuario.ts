import type { UsuarioBusca } from '../../types/Usuario';

const usuariosBusca: UsuarioBusca[] = [
  { id: 1, nome: 'Patinhas Felizes', tipo_usuario: 'ong', email: 'ong@teste.com' },
  { id: 2, nome: 'João Adotante', tipo_usuario: 'adotante', email: 'joao@exemplo.com' },
  { id: 3, nome: 'Maria Silva', tipo_usuario: 'adotante', email: 'maria@exemplo.com' },
  { id: 4, nome: 'ONG Bicho Bom', tipo_usuario: 'ong', email: 'bicho@ong.com' },
  { id: 5, nome: 'Instituto Animal', tipo_usuario: 'ong', email: 'instituto@ong.com' },
  { id: 6, nome: 'Dr. Lucas Andrade', tipo_usuario: 'veterinario', email: 'vet@teste.com' },
];

export async function buscar(query: string): Promise<UsuarioBusca[]> {
  await new Promise(r => setTimeout(r, 400));
  if (!query.trim()) return [];
  const q = query.toLowerCase();
  return usuariosBusca.filter(
    u => u.nome.toLowerCase().includes(q) || u.email.toLowerCase().includes(q),
  );
}
