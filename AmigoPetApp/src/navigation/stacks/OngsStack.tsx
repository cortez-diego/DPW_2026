import React from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { ListaOngsScreen } from '../../screens/ongs/ListaOngsScreen';
import { OngDetalheScreen } from '../../screens/ongs/OngDetalheScreen';
import { colors } from '../../theme/colors';

export type OngsStackParamList = {
  ListaOngs: undefined;
  OngDetalhe: { id: number };
};

const Stack = createNativeStackNavigator<OngsStackParamList>();

export function OngsStack() {
  return (
    <Stack.Navigator screenOptions={{ headerTintColor: colors.primary }}>
      <Stack.Screen name="ListaOngs" component={ListaOngsScreen} options={{ title: 'ONGs Parceiras' }} />
      <Stack.Screen name="OngDetalhe" component={OngDetalheScreen} options={{ title: 'Sobre a ONG' }} />
    </Stack.Navigator>
  );
}
