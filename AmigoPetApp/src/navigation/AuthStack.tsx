import React from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { LoginScreen } from '../screens/auth/LoginScreen';
import { EscolhaCadastroScreen } from '../screens/auth/EscolhaCadastroScreen';
import { CadastroAdotanteScreen } from '../screens/auth/CadastroAdotanteScreen';
import { CadastroOngScreen } from '../screens/auth/CadastroOngScreen';
import { CadastroVeterinarioScreen } from '../screens/auth/CadastroVeterinarioScreen';
import { CadastroClinicaScreen } from '../screens/auth/CadastroClinicaScreen';

export type AuthStackParamList = {
  Login: undefined;
  EscolhaCadastro: undefined;
  CadastroAdotante: undefined;
  CadastroOng: undefined;
  CadastroVeterinario: undefined;
  CadastroClinica: undefined;
};

const Stack = createNativeStackNavigator<AuthStackParamList>();

export function AuthStack() {
  return (
    <Stack.Navigator screenOptions={{ headerShown: false }}>
      <Stack.Screen name="Login" component={LoginScreen} />
      <Stack.Screen name="EscolhaCadastro" component={EscolhaCadastroScreen} />
      <Stack.Screen name="CadastroAdotante" component={CadastroAdotanteScreen} />
      <Stack.Screen name="CadastroOng" component={CadastroOngScreen} />
      <Stack.Screen name="CadastroVeterinario" component={CadastroVeterinarioScreen} />
      <Stack.Screen name="CadastroClinica" component={CadastroClinicaScreen} />
    </Stack.Navigator>
  );
}
