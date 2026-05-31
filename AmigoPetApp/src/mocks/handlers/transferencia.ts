import type { Transferencia, TransferirRequest } from '../../types/Adocao';

const transferenciasMock: Transferencia[] = [
  {
    id: 1,
    fk_animal_id: 1,
    de_usuario_id: 0,
    de_usuario_nome: 'Sistema (Resgate Inicial)',
    para_usuario_id: 100,
    para_usuario_nome: 'Patinhas Felizes (ONG)',
    motivo: 'Registro inicial na plataforma.',
    data: '2022-05-01T08:00:00',
  },
];
let nextId = transferenciasMock.length + 1;

export async function historico(animalId: number): Promise<Transferencia[]> {
  await new Promise(r => setTimeout(r, 400));
  return transferenciasMock
    .filter(t => t.fk_animal_id === animalId)
    .sort((a, b) => b.data.localeCompare(a.data));
}

export async function transferir(animalId: number, req: TransferirRequest, deId: number, deNome: string): Promise<Transferencia> {
  await new Promise(r => setTimeout(r, 700));
  const nova: Transferencia = {
    id: nextId++,
    fk_animal_id: animalId,
    de_usuario_id: deId,
    de_usuario_nome: deNome,
    para_usuario_id: req.para_usuario_id,
    para_usuario_nome: req.para_usuario_nome,
    motivo: req.motivo ?? null,
    data: new Date().toISOString(),
  };
  transferenciasMock.push(nova);
  return nova;
}
