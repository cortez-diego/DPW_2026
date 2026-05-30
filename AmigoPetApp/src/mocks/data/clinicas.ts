import type { Clinica } from '../../types/Clinica';

export const clinicasMock: Clinica[] = [
  {
    id: 1,
    nome: 'Clínica VetCare',
    cnpj: '12345678000195',
    email: 'contato@vetcare.com.br',
    telefone_1: '11987654321',
    cep: '01310100',
    logradouro: 'Av. Paulista',
    numero: 1000,
    bairro: 'Bela Vista',
    cidade: 'São Paulo',
    estado: 'SP',
  },
  {
    id: 2,
    nome: 'Hospital Animal PetLife',
    cnpj: '98765432000180',
    email: 'atendimento@petlife.com.br',
    telefone_1: '11912345678',
    telefone_2: '1132109876',
    cep: '13010110',
    logradouro: 'Rua Dr. Quirino',
    numero: 500,
    bairro: 'Centro',
    cidade: 'Campinas',
    estado: 'SP',
  },
];
