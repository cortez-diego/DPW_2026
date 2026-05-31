import type { Termo, AssinarTermoRequest } from '../../types/Adocao';

const TEXTO_TERMO = `TERMO DE RESPONSABILIDADE PARA ADOÇÃO DE ANIMAL

Eu, adotante identificado(a) no sistema AmigoPet, declaro que:

1. COMPROMISSO COM O BEM-ESTAR ANIMAL
Comprometo-me a oferecer ao animal adotado condições dignas de vida, incluindo alimentação adequada, água fresca disponível, abrigo seguro, atendimento veterinário regular e carinho.

2. VEDAÇÃO AO ABANDONO
Estou ciente de que o abandono de animais é crime previsto na Lei Federal nº 9.605/1998 (Lei de Crimes Ambientais), sujeito a pena de detenção de três meses a um ano, e multa.

3. PROIBIÇÃO DE MAUS-TRATOS
Comprometo-me a não praticar nenhum ato de crueldade, violência ou negligência contra o animal, conforme vedado pela mesma lei.

4. DEVOLUÇÃO RESPONSÁVEL
Caso não seja possível manter a adoção, comprometo-me a contatar a ONG responsável antes de qualquer outra medida, para que o animal seja reintegrado ao sistema de adoção com segurança.

5. AUTORIZAÇÃO DE VISITAS
Autorizo a ONG responsável a realizar visitas de acompanhamento ao animal adotado, mediante agendamento prévio.

6. RESPONSABILIDADE CIVIL
Reconheço que sou responsável por quaisquer danos causados pelo animal a terceiros após a adoção.

7. VERACIDADE DAS INFORMAÇÕES
Declaro que todas as informações prestadas no cadastro são verdadeiras, e estou ciente de que informações falsas poderão implicar no cancelamento da adoção.

Ao aceitar este termo no aplicativo AmigoPet, expresso meu pleno entendimento e concordância com todas as cláusulas acima. A assinatura eletrônica inclui data, hora e IP de origem, tendo validade jurídica conforme a Lei nº 14.063/2020.`;

const termosMock: Record<number, Termo> = {
  2: {
    solicitacao_id: 2,
    pdf_url: 'https://example.com/termos/sol-2.pdf',
    pdf_assinado_url: null,
    conteudo_texto: TEXTO_TERMO,
    assinado: false,
    data_assinatura: null,
  },
};

export async function obter(solicitacaoId: number): Promise<Termo> {
  await new Promise(r => setTimeout(r, 500));
  if (termosMock[solicitacaoId]) return termosMock[solicitacaoId];
  const novo: Termo = {
    solicitacao_id: solicitacaoId,
    pdf_url: `https://example.com/termos/sol-${solicitacaoId}.pdf`,
    pdf_assinado_url: null,
    conteudo_texto: TEXTO_TERMO,
    assinado: false,
    data_assinatura: null,
  };
  termosMock[solicitacaoId] = novo;
  return novo;
}

export async function assinar(solicitacaoId: number, req: AssinarTermoRequest): Promise<Termo> {
  await new Promise(r => setTimeout(r, 700));
  const termo = await obter(solicitacaoId);
  if (req.aceite) {
    termo.assinado = true;
    termo.data_assinatura = req.timestamp;
    termo.pdf_assinado_url = `https://example.com/termos/sol-${solicitacaoId}-assinado.pdf`;
  }
  return termo;
}
