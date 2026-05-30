import { useState, useCallback } from 'react';

interface ApiState<T> {
  data: T | null;
  isLoading: boolean;
  error: string | null;
}

export function useApi<T>(fn: (...args: any[]) => Promise<T>) {
  const [state, setState] = useState<ApiState<T>>({
    data: null,
    isLoading: false,
    error: null,
  });

  const execute = useCallback(
    async (...args: any[]): Promise<T | undefined> => {
      setState(s => ({ ...s, isLoading: true, error: null }));
      try {
        const data = await fn(...args);
        setState({ data, isLoading: false, error: null });
        return data;
      } catch (err: any) {
        const msg =
          err?.response?.data?.erro ?? err?.message ?? 'Erro inesperado. Tente novamente.';
        setState(s => ({ ...s, isLoading: false, error: msg }));
        throw err;
      }
    },
    [fn],
  );

  const reset = useCallback(() => {
    setState({ data: null, isLoading: false, error: null });
  }, []);

  return { ...state, execute, reset };
}
