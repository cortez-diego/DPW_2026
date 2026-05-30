import React from 'react';
import { usePermissions } from '../../permissions/usePermissions';
import type { Capability } from '../../permissions/capabilities';

interface Props {
  capability: Capability;
  children: React.ReactNode;
  /** Renderizado quando o usuário NÃO tem a capability. Padrão: null (não renderiza nada). */
  fallback?: React.ReactNode;
}

export function PermissionGate({ capability, children, fallback = null }: Props) {
  const { has } = usePermissions();
  return has(capability) ? <>{children}</> : <>{fallback}</>;
}
