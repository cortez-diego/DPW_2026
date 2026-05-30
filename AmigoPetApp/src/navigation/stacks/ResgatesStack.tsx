import React from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { FeedResgatesScreen } from '../../screens/resgates/FeedResgatesScreen';
import { AvistamentoDetalheScreen } from '../../screens/resgates/AvistamentoDetalheScreen';
import { RankingScreen } from '../../screens/resgates/RankingScreen';
import { colors } from '../../theme/colors';

export type ResgatesStackParamList = {
  FeedResgates: undefined;
  AvistamentoDetalhe: { id: number };
  Ranking: undefined;
};

const Stack = createNativeStackNavigator<ResgatesStackParamList>();

export function ResgatesStack() {
  return (
    <Stack.Navigator screenOptions={{ headerTintColor: colors.primary }}>
      <Stack.Screen name="FeedResgates" component={FeedResgatesScreen} options={{ title: 'Resgates' }} />
      <Stack.Screen name="AvistamentoDetalhe" component={AvistamentoDetalheScreen} options={{ title: 'Detalhes do Avistamento' }} />
      <Stack.Screen name="Ranking" component={RankingScreen} options={{ title: 'Ranking de Rastreadores' }} />
    </Stack.Navigator>
  );
}
