import React from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { MeusAnimaisScreen } from '../../screens/ong/MeusAnimaisScreen';
import { CadastrarAnimalScreen } from '../../screens/ong/CadastrarAnimalScreen';
import { colors } from '../../theme/colors';

export type MeusAnimaisStackParamList = {
  MeusAnimais: undefined;
  CadastrarAnimal: undefined;
};

const Stack = createNativeStackNavigator<MeusAnimaisStackParamList>();

export function MeusAnimaisStack() {
  return (
    <Stack.Navigator screenOptions={{ headerTintColor: colors.primary }}>
      <Stack.Screen name="MeusAnimais" component={MeusAnimaisScreen} options={{ title: 'Meus Animais' }} />
      <Stack.Screen name="CadastrarAnimal" component={CadastrarAnimalScreen} options={{ title: 'Cadastrar Animal' }} />
    </Stack.Navigator>
  );
}
