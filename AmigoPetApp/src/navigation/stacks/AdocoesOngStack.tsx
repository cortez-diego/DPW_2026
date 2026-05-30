import React from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { AdocoesOngScreen } from '../../screens/ong/AdocoesOngScreen';
import { colors } from '../../theme/colors';

export type AdocoesOngStackParamList = {
  AdocoesOng: undefined;
};

const Stack = createNativeStackNavigator<AdocoesOngStackParamList>();

export function AdocoesOngStack() {
  return (
    <Stack.Navigator screenOptions={{ headerTintColor: colors.primary }}>
      <Stack.Screen name="AdocoesOng" component={AdocoesOngScreen} options={{ title: 'Adoções Recebidas' }} />
    </Stack.Navigator>
  );
}
