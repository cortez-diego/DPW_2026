import type { CarteiraIdentificacao } from '../../types/Saude';
import { carteirasMock } from '../data/saude';
import { animaisMock } from '../data/animais';

export async function obter(animalId: number): Promise<CarteiraIdentificacao> {
  await new Promise(r => setTimeout(r, 600));
  const cached = carteirasMock.find(c => c.animal_id === animalId);
  if (cached) return cached;

  const animal = animaisMock.find(a => a.id === animalId);
  return {
    animal_id: animalId,
    nome: animal?.nome ?? 'Animal',
    especie: animal?.especie ?? null,
    raca: animal?.raca ?? null,
    data_nascimento: animal?.data_nascimento ?? null,
    castrado: animal?.castrado ?? false,
    alergias: animal?.alergias ?? null,
    foto: animal?.foto ?? null,
    qr_code_url: `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=amigopet://animal/${animalId}`,
    pdf_url: `https://example.com/carteira/${animalId}.pdf`,
  };
}
