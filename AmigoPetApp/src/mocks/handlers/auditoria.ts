import type { AuditoriaEntry } from '../../types/Saude';
import { auditoriaMock } from '../data/saude';

export async function listar(animalId: number): Promise<AuditoriaEntry[]> {
  await new Promise(r => setTimeout(r, 400));
  return auditoriaMock
    .filter(e => e.fk_animal_id === animalId)
    .sort((a, b) => b.data.localeCompare(a.data));
}
