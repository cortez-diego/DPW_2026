import React from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { FeedAnimaisScreen } from '../../screens/home/FeedAnimaisScreen';
import { AnimalDetalheScreen } from '../../screens/home/AnimalDetalheScreen';
import { SolicitarAdocaoScreen } from '../../screens/adocao/SolicitarAdocaoScreen';
import { TermoResponsabilidadeScreen } from '../../screens/adocao/TermoResponsabilidadeScreen';
import { AdicionarVacinaScreen } from '../../screens/saude/AdicionarVacinaScreen';
import { AdicionarProcedimentoScreen } from '../../screens/saude/AdicionarProcedimentoScreen';
import { CarteiraIdentificacaoScreen } from '../../screens/saude/CarteiraIdentificacaoScreen';
import { TransferenciaScreen } from '../../screens/adocao/TransferenciaScreen';
import { EditarSaudeAnimalScreen } from '../../screens/saude/EditarSaudeAnimalScreen';
import { colors } from '../../theme/colors';

export type HomeStackParamList = {
  FeedAnimais: undefined;
  AnimalDetalhe: { id: number };
  SolicitarAdocao: { animalId: number };
  TermoResponsabilidade: { animalId: number; motivo: string };
  AdicionarVacina: { animalId: number };
  AdicionarProcedimento: { animalId: number };
  CarteiraIdentificacao: { animalId: number };
  Transferencia: { animalId: number };
  EditarSaudeAnimal: { animalId: number };
};

const Stack = createNativeStackNavigator<HomeStackParamList>();

export function HomeStack() {
  return (
    <Stack.Navigator screenOptions={{ headerTintColor: colors.primary }}>
      <Stack.Screen name="FeedAnimais" component={FeedAnimaisScreen} options={{ title: 'Animais para Adoção' }} />
      <Stack.Screen name="AnimalDetalhe" component={AnimalDetalheScreen} options={{ title: 'Detalhes' }} />
      <Stack.Screen name="SolicitarAdocao" component={SolicitarAdocaoScreen} options={{ title: 'Solicitar Adoção' }} />
      <Stack.Screen name="TermoResponsabilidade" component={TermoResponsabilidadeScreen} options={{ title: 'Termo de Responsabilidade' }} />
      <Stack.Screen name="AdicionarVacina" component={AdicionarVacinaScreen} options={{ title: 'Adicionar Vacina', presentation: 'modal' }} />
      <Stack.Screen name="AdicionarProcedimento" component={AdicionarProcedimentoScreen} options={{ title: 'Adicionar Procedimento', presentation: 'modal' }} />
      <Stack.Screen name="CarteiraIdentificacao" component={CarteiraIdentificacaoScreen} options={{ title: 'Carteira de Identificação' }} />
      <Stack.Screen name="Transferencia" component={TransferenciaScreen} options={{ title: 'Transferir Responsabilidade', presentation: 'modal' }} />
      <Stack.Screen name="EditarSaudeAnimal" component={EditarSaudeAnimalScreen} options={{ title: 'Editar Condição de Saúde', presentation: 'modal' }} />
    </Stack.Navigator>
  );
}
