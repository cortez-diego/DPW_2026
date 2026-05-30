export type AvistamentoStatus =
  | 'aguardando_acolhimento'
  | 'em_acolhimento'
  | 'resgatado'
  | 'encerrado';

export type AvistamentoEspecie = 'cachorro' | 'gato' | 'outro' | 'nao_sei';

export interface Avistamento {
  id: number;
  fotos: string[];
  foto: string | null;
  data: string;
  lat: number | null;
  lng: number | null;
  endereco_texto: string | null;
  local_cep?: string;
  local_logradouro?: string;
  local_numero?: string;
  local_bairro?: string;
  local_cidade?: string;
  local_estado?: string;
  condicao: string;
  acoes_tomadas: string;
  especie: AvistamentoEspecie;
  status: AvistamentoStatus;
  usuario: {
    id: number;
    nome: string;
    foto: string | null;
  };
}

export interface PublicarAvistamentoRequest {
  fotos: string[];
  data: string;
  lat?: number;
  lng?: number;
  endereco_texto?: string;
  local_cep?: string;
  local_logradouro?: string;
  local_numero?: string;
  local_bairro?: string;
  local_cidade?: string;
  local_estado?: string;
  condicao: string;
  acoes_tomadas: string;
  especie: AvistamentoEspecie;
}

export interface FiltrosAvistamento {
  status?: AvistamentoStatus;
  especie?: AvistamentoEspecie;
}

export interface RankingItem {
  posicao: number;
  usuario_id: number;
  nome: string;
  foto: string | null;
  total_reports: number;
  pontos: number;
}
