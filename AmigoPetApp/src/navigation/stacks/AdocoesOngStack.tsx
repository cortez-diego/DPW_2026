import React from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { AdocoesOngScreen } from '../../screens/ong/AdocoesOngScreen';
import { AvaliacaoAdotanteScreen } from '../../screens/ong/AvaliacaoAdotanteScreen';
import { colors } from '../../theme/colors';

export type AdocoesOngStackParamList = {
  AdocoesOng: undefined;
  AvaliacaoAdotante: { solicitacaoId: number; adotanteNome: string };
};

const Stack = createNativeStackNavigator<AdocoesOngStackParamList>();

export function AdocoesOngStack() {
  return (
    <Stack.Navigator screenOptions={{ headerTintColor: colors.primary }}>
      <Stack.Screen name="AdocoesOng" component={AdocoesOngScreen} options={{ title: 'Adoções Recebidas' }} />
      <Stack.Screen name="AvaliacaoAdotante" component={AvaliacaoAdotanteScreen} options={{ title: 'Avaliar Adotante', presentation: 'modal' }} />
    </Stack.Navigator>
  );
}
