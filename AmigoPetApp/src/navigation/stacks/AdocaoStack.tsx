import React from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { MinhasSolicitacoesScreen } from '../../screens/adocao/MinhasSolicitacoesScreen';
import { SolicitacaoDetalheScreen } from '../../screens/adocao/SolicitacaoDetalheScreen';
import { TermoDigitalScreen } from '../../screens/adocao/TermoDigitalScreen';
import { colors } from '../../theme/colors';

export type AdocaoStackParamList = {
  MinhasSolicitacoes: undefined;
  SolicitacaoDetalhe: { id: number };
  TermoDigital: { solicitacaoId: number };
};

const Stack = createNativeStackNavigator<AdocaoStackParamList>();

export function AdocaoStack() {
  return (
    <Stack.Navigator screenOptions={{ headerTintColor: colors.primary }}>
      <Stack.Screen name="MinhasSolicitacoes" component={MinhasSolicitacoesScreen} options={{ title: 'Minhas Solicitações' }} />
      <Stack.Screen name="SolicitacaoDetalhe" component={SolicitacaoDetalheScreen} options={{ title: 'Detalhe da Solicitação' }} />
      <Stack.Screen name="TermoDigital" component={TermoDigitalScreen} options={{ title: 'Termo de Responsabilidade', presentation: 'modal' }} />
    </Stack.Navigator>
  );
}
