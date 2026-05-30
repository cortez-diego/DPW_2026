import React from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { AtendimentosScreen } from '../../screens/vet/AtendimentosScreen';
import { AdicionarVacinaScreen } from '../../screens/saude/AdicionarVacinaScreen';
import { AdicionarProcedimentoScreen } from '../../screens/saude/AdicionarProcedimentoScreen';
import { colors } from '../../theme/colors';

export type AtendimentosStackParamList = {
  Atendimentos: undefined;
  AdicionarVacinaVet: { animalId: number };
  AdicionarProcedimentoVet: { animalId: number };
};

const Stack = createNativeStackNavigator<AtendimentosStackParamList>();

export function AtendimentosStack() {
  return (
    <Stack.Navigator screenOptions={{ headerTintColor: colors.primary }}>
      <Stack.Screen name="Atendimentos" component={AtendimentosScreen} options={{ title: 'Atendimentos' }} />
      <Stack.Screen name="AdicionarVacinaVet" component={AdicionarVacinaScreen} options={{ title: 'Adicionar Vacina', presentation: 'modal' }} />
      <Stack.Screen name="AdicionarProcedimentoVet" component={AdicionarProcedimentoScreen} options={{ title: 'Adicionar Procedimento', presentation: 'modal' }} />
    </Stack.Navigator>
  );
}
