import type { Clinica, CadastrarClinicaRequest } from '../../types/Clinica';
import { clinicasMock } from '../data/clinicas';

let nextId = clinicasMock.length + 1;

export async function listar(): Promise<Clinica[]> {
  await new Promise(r => setTimeout(r, 500));
  return [...clinicasMock];
}

export async function cadastrar(req: CadastrarClinicaRequest): Promise<Clinica> {
  await new Promise(r => setTimeout(r, 800));
  const nova: Clinica = { id: nextId++, ...req };
  clinicasMock.push(nova);
  return nova;
}
