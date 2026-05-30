import React from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { AtendimentosScreen } from '../../screens/vet/AtendimentosScreen';
import { colors } from '../../theme/colors';

export type AtendimentosStackParamList = {
  Atendimentos: undefined;
};

const Stack = createNativeStackNavigator<AtendimentosStackParamList>();

export function AtendimentosStack() {
  return (
    <Stack.Navigator screenOptions={{ headerTintColor: colors.primary }}>
      <Stack.Screen name="Atendimentos" component={AtendimentosScreen} options={{ title: 'Atendimentos' }} />
    </Stack.Navigator>
  );
}
