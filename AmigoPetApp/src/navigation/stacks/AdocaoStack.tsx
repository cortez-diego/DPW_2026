import React from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { MinhasSolicitacoesScreen } from '../../screens/adocao/MinhasSolicitacoesScreen';
import { colors } from '../../theme/colors';

export type AdocaoStackParamList = {
  MinhasSolicitacoes: undefined;
};

const Stack = createNativeStackNavigator<AdocaoStackParamList>();

export function AdocaoStack() {
  return (
    <Stack.Navigator screenOptions={{ headerTintColor: colors.primary }}>
      <Stack.Screen
        name="MinhasSolicitacoes"
        component={MinhasSolicitacoesScreen}
        options={{ title: 'Minhas Solicitações' }}
      />
    </Stack.Navigator>
  );
}
