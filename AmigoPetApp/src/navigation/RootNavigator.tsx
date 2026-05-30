import React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { ActivityIndicator, View } from 'react-native';
import { useAuth } from '../hooks/useAuth';
import { AuthStack } from './AuthStack';
import { AppTabs } from './AppTabs';
import { ReportarAnimalScreen } from '../screens/resgates/ReportarAnimalScreen';
import { colors } from '../theme/colors';

export type AppStackParamList = {
  Tabs: undefined;
  ReportarAnimal: undefined;
};

const AppStack = createNativeStackNavigator<AppStackParamList>();

function AppNavigator() {
  return (
    <AppStack.Navigator screenOptions={{ headerShown: false }}>
      <AppStack.Screen name="Tabs" component={AppTabs} />
      <AppStack.Screen
        name="ReportarAnimal"
        component={ReportarAnimalScreen}
        options={{ presentation: 'modal', headerShown: true, title: 'Reportar Animal', headerTintColor: colors.primary }}
      />
    </AppStack.Navigator>
  );
}

export function RootNavigator() {
  const { user, isLoading } = useAuth();

  if (isLoading) {
    return (
      <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: colors.bg }}>
        <ActivityIndicator size="large" color={colors.primary} />
      </View>
    );
  }

  return (
    <NavigationContainer>
      {user ? <AppNavigator /> : <AuthStack />}
    </NavigationContainer>
  );
}
