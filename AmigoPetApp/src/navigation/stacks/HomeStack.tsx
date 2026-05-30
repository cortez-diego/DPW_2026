import React from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { FeedAnimaisScreen } from '../../screens/home/FeedAnimaisScreen';
import { AnimalDetalheScreen } from '../../screens/home/AnimalDetalheScreen';
import { SolicitarAdocaoScreen } from '../../screens/adocao/SolicitarAdocaoScreen';
import { TermoResponsabilidadeScreen } from '../../screens/adocao/TermoResponsabilidadeScreen';
import { colors } from '../../theme/colors';

export type HomeStackParamList = {
  FeedAnimais: undefined;
  AnimalDetalhe: { id: number };
  SolicitarAdocao: { animalId: number };
  TermoResponsabilidade: { animalId: number; motivo: string };
};

const Stack = createNativeStackNavigator<HomeStackParamList>();

export function HomeStack() {
  return (
    <Stack.Navigator screenOptions={{ headerTintColor: colors.primary }}>
      <Stack.Screen name="FeedAnimais" component={FeedAnimaisScreen} options={{ title: 'Animais para Adoção' }} />
      <Stack.Screen name="AnimalDetalhe" component={AnimalDetalheScreen} options={{ title: 'Detalhes' }} />
      <Stack.Screen name="SolicitarAdocao" component={SolicitarAdocaoScreen} options={{ title: 'Solicitar Adoção' }} />
      <Stack.Screen name="TermoResponsabilidade" component={TermoResponsabilidadeScreen} options={{ title: 'Termo de Responsabilidade' }} />
    </Stack.Navigator>
  );
}
