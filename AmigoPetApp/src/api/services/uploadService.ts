import { client } from '../client';
import { USE_MOCKS } from '../../config';
import * as mock from '../../mocks/handlers/upload';

export const uploadService = {
  async enviarImagem(uri: string): Promise<string> {
    if (USE_MOCKS) {
      const result = await mock.enviarImagem(uri);
      return result.url;
    }
    const formData = new FormData();
    formData.append('arquivo', { uri, name: 'foto.jpg', type: 'image/jpeg' } as any);
    const { data } = await client.post<{ url: string }>('/upload', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
    return data.url;
  },
};
