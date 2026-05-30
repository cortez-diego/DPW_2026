import { useMemo } from 'react';
import { useAuth } from '../hooks/useAuth';
import { Capability, CAPABILITIES_BY_ROLE } from './capabilities';

export function usePermissions() {
  const { user } = useAuth();

  const capSet = useMemo<Set<Capability>>(() => {
    if (!user) return new Set();
    return new Set(CAPABILITIES_BY_ROLE[user.tipo_usuario] ?? []);
  }, [user?.tipo_usuario]);

  return {
    /** Verifica se o usuário logado possui a capability informada. */
    has: (cap: Capability): boolean => capSet.has(cap),
    capabilities: capSet,
  };
}
