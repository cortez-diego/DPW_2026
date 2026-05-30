export async function enviarImagem(_uri: string): Promise<{ url: string }> {
  await new Promise(r => setTimeout(r, 1200));
  return { url: 'https://placedog.net/500/500?random=' + Date.now() };
}

export async function enviarArquivo(_uri: string, _nome: string): Promise<{ url: string }> {
  await new Promise(r => setTimeout(r, 1500));
  return { url: 'https://example.com/uploads/mock-documento-' + Date.now() + '.pdf' };
}
