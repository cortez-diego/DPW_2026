import React, { createContext, useCallback, useEffect, useState } from 'react';
import * as SecureStore from 'expo-secure-store';
import { authService } from '../api/services/authService';
import { JWT_KEY } from '../api/client';
import type {
  AuthUser,
  LoginRequest,
  CadastroAdotanteRequest,
  CadastroOngRequest,
  CadastroVeterinarioRequest,
} from '../types/Auth';

interface AuthContextData {
  user: AuthUser | null;
  isLoading: boolean;
  login: (req: LoginRequest) => Promise<void>;
  logout: () => Promise<void>;
  cadastrarAdotante: (req: CadastroAdotanteRequest) => Promise<void>;
  cadastrarOng: (req: CadastroOngRequest) => Promise<void>;
  cadastrarVeterinario: (req: CadastroVeterinarioRequest) => Promise<void>;
}

export const AuthContext = createContext<AuthContextData>({} as AuthContextData);

const USER_KEY = 'amigopet_user';

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<AuthUser | null>(null);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    (async () => {
      try {
        const [token, userJson] = await Promise.all([
          SecureStore.getItemAsync(JWT_KEY),
          SecureStore.getItemAsync(USER_KEY),
        ]);
        if (token && userJson) setUser(JSON.parse(userJson) as AuthUser);
      } finally {
        setIsLoading(false);
      }
    })();
  }, []);

  const login = useCallback(async (req: LoginRequest) => {
    const response = await authService.login(req);
    await Promise.all([
      SecureStore.setItemAsync(JWT_KEY, response.token),
      SecureStore.setItemAsync(USER_KEY, JSON.stringify(response.usuario)),
    ]);
    setUser(response.usuario);
  }, []);

  const logout = useCallback(async () => {
    try { await authService.logout(); } catch { /* ignora erros de rede no logout */ }
    await Promise.all([
      SecureStore.deleteItemAsync(JWT_KEY),
      SecureStore.deleteItemAsync(USER_KEY),
    ]);
    setUser(null);
  }, []);

  const cadastrarAdotante = useCallback(async (req: CadastroAdotanteRequest) => {
    const response = await authService.cadastrarAdotante(req);
    await Promise.all([
      SecureStore.setItemAsync(JWT_KEY, response.token),
      SecureStore.setItemAsync(USER_KEY, JSON.stringify(response.usuario)),
    ]);
    setUser(response.usuario);
  }, []);

  const cadastrarOng = useCallback(async (req: CadastroOngRequest) => {
    const response = await authService.cadastrarOng(req);
    await Promise.all([
      SecureStore.setItemAsync(JWT_KEY, response.token),
      SecureStore.setItemAsync(USER_KEY, JSON.stringify(response.usuario)),
    ]);
    setUser(response.usuario);
  }, []);

  const cadastrarVeterinario = useCallback(async (req: CadastroVeterinarioRequest) => {
    const response = await authService.cadastrarVeterinario(req);
    await Promise.all([
      SecureStore.setItemAsync(JWT_KEY, response.token),
      SecureStore.setItemAsync(USER_KEY, JSON.stringify(response.usuario)),
    ]);
    setUser(response.usuario);
  }, []);

  return (
    <AuthContext.Provider value={{ user, isLoading, login, logout, cadastrarAdotante, cadastrarOng, cadastrarVeterinario }}>
      {children}
    </AuthContext.Provider>
  );
}
