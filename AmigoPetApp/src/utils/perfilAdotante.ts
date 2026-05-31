import type { Adotante } from '../types/Adotante';

export interface CheckPerfilResult {
  completo: boolean;
  camposFaltando: string[];
}

export function perfilCompletoParaAdotar(adotante: Adotante): CheckPerfilResult {
  const camposFaltando: string[] = [];

  if (!adotante.cpf?.trim()) camposFaltando.push('CPF');
  if (!adotante.nome?.trim()) camposFaltando.push('Nome completo');
  if (!adotante.email?.trim()) camposFaltando.push('E-mail');
  if (!adotante.telefone_1?.trim()) camposFaltando.push('Telefone');
  if (!adotante.data_nascimento) camposFaltando.push('Data de nascimento');
  if (!adotante.logradouro?.trim()) camposFaltando.push('Logradouro');
  if (!adotante.cidade?.trim()) camposFaltando.push('Cidade');
  if (!adotante.estado?.trim()) camposFaltando.push('Estado');
  if (!adotante.cep?.trim()) camposFaltando.push('CEP');

  return { completo: camposFaltando.length === 0, camposFaltando };
}
