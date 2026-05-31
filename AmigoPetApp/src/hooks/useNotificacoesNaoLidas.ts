import { useEffect, useState } from 'react';
import { notificacaoService } from '../api/services/notificacaoService';

export function useNotificacoesNaoLidas(): number | undefined {
  const [count, setCount] = useState<number | undefined>(undefined);

  useEffect(() => {
    notificacaoService
      .listar()
      .then(ns => {
        const n = ns.filter(n => !n.lida).length;
        setCount(n > 0 ? n : undefined);
      })
      .catch(() => setCount(undefined));
  }, []);

  return count;
}
