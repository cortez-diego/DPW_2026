import type { AtendimentoResumo } from '../../types/Saude';
import type { VeterinarioPerfil, AtualizarVeterinarioRequest } from '../../types/Veterinario';
import type { Clinica } from '../../types/Clinica';
import { atendimentosVetMock } from '../data/saude';
import { clinicasMock } from '../data/clinicas';

const vetPerfilMock: VeterinarioPerfil = {
  id: 101,
  nome: 'Dr. Lucas Andrade',
  cpf: '98765432100',
  crmv: 'SP-12345',
  email: 'vet@teste.com',
  data_nascimento: '1985-06-20',
  telefone_1: '11987654321',
  cep: '01310100',
  logradouro: 'Av. Paulista',
  numero: 500,
  bairro: 'Bela Vista',
  cidade: 'São Paulo',
  estado: 'SP',
};

let clinicasAssociadas: number[] = [1];

export async function meusAtendimentos(): Promise<AtendimentoResumo[]> {
  await new Promise(r => setTimeout(r, 600));
  return [...atendimentosVetMock];
}

export async function perfil(): Promise<VeterinarioPerfil> {
  await new Promise(r => setTimeout(r, 400));
  return { ...vetPerfilMock };
}

export async function atualizarPerfil(req: AtualizarVeterinarioRequest): Promise<VeterinarioPerfil> {
  await new Promise(r => setTimeout(r, 600));
  Object.assign(vetPerfilMock, req);
  return { ...vetPerfilMock };
}

export async function minhasClinicas(): Promise<Clinica[]> {
  await new Promise(r => setTimeout(r, 400));
  return clinicasMock.filter(c => clinicasAssociadas.includes(c.id));
}

export async function associarClinica(clinicaId: number): Promise<void> {
  await new Promise(r => setTimeout(r, 400));
  if (!clinicasAssociadas.includes(clinicaId)) clinicasAssociadas.push(clinicaId);
}

export async function desassociarClinica(clinicaId: number): Promise<void> {
  await new Promise(r => setTimeout(r, 400));
  clinicasAssociadas = clinicasAssociadas.filter(id => id !== clinicaId);
}
