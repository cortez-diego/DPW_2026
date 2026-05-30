import React from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { PerfilScreen } from '../../screens/perfil/PerfilScreen';
import { EditarPerfilScreen } from '../../screens/perfil/EditarPerfilScreen';
import { HistoricoScreen } from '../../screens/perfil/HistoricoScreen';
import { NotificacoesScreen } from '../../screens/notificacoes/NotificacoesScreen';
import { colors } from '../../theme/colors';

export type PerfilStackParamList = {
  Perfil: undefined;
  EditarPerfil: undefined;
  Historico: undefined;
  Notificacoes: undefined;
};

const Stack = createNativeStackNavigator<PerfilStackParamList>();

export function PerfilStack() {
  return (
    <Stack.Navigator screenOptions={{ headerTintColor: colors.primary }}>
      <Stack.Screen name="Perfil" component={PerfilScreen} options={{ title: 'Meu Perfil' }} />
      <Stack.Screen name="EditarPerfil" component={EditarPerfilScreen} options={{ title: 'Editar Perfil' }} />
      <Stack.Screen name="Historico" component={HistoricoScreen} options={{ title: 'Histórico de Adoções' }} />
      <Stack.Screen name="Notificacoes" component={NotificacoesScreen} options={{ title: 'Notificações' }} />
    </Stack.Navigator>
  );
}
