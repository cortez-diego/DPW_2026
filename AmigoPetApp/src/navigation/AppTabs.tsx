import React from 'react';
import { View, StyleSheet } from 'react-native';
import { FAB } from 'react-native-paper';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { useNavigation } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { Ionicons } from '@expo/vector-icons';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { HomeStack } from './stacks/HomeStack';
import { AdocaoStack } from './stacks/AdocaoStack';
import { OngsStack } from './stacks/OngsStack';
import { PerfilStack } from './stacks/PerfilStack';
import { MeusAnimaisStack } from './stacks/MeusAnimaisStack';
import { AdocoesOngStack } from './stacks/AdocoesOngStack';
import { AtendimentosStack } from './stacks/AtendimentosStack';
import { ResgatesStack } from './stacks/ResgatesStack';
import { useAuth } from '../hooks/useAuth';
import { colors } from '../theme/colors';
import type { AppStackParamList } from './RootNavigator';

type IconName = React.ComponentProps<typeof Ionicons>['name'];

// ─────────────────────────────────────────────
// Tabs de Adotante (e Admin/Moderador no app)
// ─────────────────────────────────────────────
type AdotanteTabsParamList = {
  HomeTab: undefined;
  ResgatesTab: undefined;
  AdocaoTab: undefined;
  OngsTab: undefined;
  PerfilTab: undefined;
};
const AdotanteTab = createBottomTabNavigator<AdotanteTabsParamList>();

function AdotanteTabs() {
  const tabIcons: Record<keyof AdotanteTabsParamList, IconName> = {
    HomeTab: 'paw',
    ResgatesTab: 'alert-circle',
    AdocaoTab: 'heart',
    OngsTab: 'business',
    PerfilTab: 'person',
  };
  return (
    <AdotanteTab.Navigator screenOptions={makeScreenOptions(tabIcons)}>
      <AdotanteTab.Screen name="HomeTab" component={HomeStack} options={{ tabBarLabel: 'Animais' }} />
      <AdotanteTab.Screen name="ResgatesTab" component={ResgatesStack} options={{ tabBarLabel: 'Resgates' }} />
      <AdotanteTab.Screen name="AdocaoTab" component={AdocaoStack} options={{ tabBarLabel: 'Adoção' }} />
      <AdotanteTab.Screen name="OngsTab" component={OngsStack} options={{ tabBarLabel: 'ONGs' }} />
      <AdotanteTab.Screen name="PerfilTab" component={PerfilStack} options={{ tabBarLabel: 'Perfil' }} />
    </AdotanteTab.Navigator>
  );
}

// ─────────────────────────────────────────────
// Tabs de ONG
// ─────────────────────────────────────────────
type OngTabsParamList = {
  HomeTab: undefined;
  ResgatesTab: undefined;
  MeusAnimaisTab: undefined;
  AdocoesOngTab: undefined;
  PerfilTab: undefined;
};
const OngTab = createBottomTabNavigator<OngTabsParamList>();

function OngTabs() {
  const tabIcons: Record<keyof OngTabsParamList, IconName> = {
    HomeTab: 'paw',
    ResgatesTab: 'alert-circle',
    MeusAnimaisTab: 'list',
    AdocoesOngTab: 'checkmark-circle',
    PerfilTab: 'person',
  };
  return (
    <OngTab.Navigator screenOptions={makeScreenOptions(tabIcons)}>
      <OngTab.Screen name="HomeTab" component={HomeStack} options={{ tabBarLabel: 'Animais' }} />
      <OngTab.Screen name="ResgatesTab" component={ResgatesStack} options={{ tabBarLabel: 'Resgates' }} />
      <OngTab.Screen name="MeusAnimaisTab" component={MeusAnimaisStack} options={{ tabBarLabel: 'Meus Animais' }} />
      <OngTab.Screen name="AdocoesOngTab" component={AdocoesOngStack} options={{ tabBarLabel: 'Adoções' }} />
      <OngTab.Screen name="PerfilTab" component={PerfilStack} options={{ tabBarLabel: 'Perfil' }} />
    </OngTab.Navigator>
  );
}

// ─────────────────────────────────────────────
// Tabs de Veterinário
// ─────────────────────────────────────────────
type VetTabsParamList = {
  HomeTab: undefined;
  ResgatesTab: undefined;
  AtendimentosTab: undefined;
  PerfilTab: undefined;
};
const VetTab = createBottomTabNavigator<VetTabsParamList>();

function VetTabs() {
  const tabIcons: Record<keyof VetTabsParamList, IconName> = {
    HomeTab: 'paw',
    ResgatesTab: 'alert-circle',
    AtendimentosTab: 'medical',
    PerfilTab: 'person',
  };
  return (
    <VetTab.Navigator screenOptions={makeScreenOptions(tabIcons)}>
      <VetTab.Screen name="HomeTab" component={HomeStack} options={{ tabBarLabel: 'Animais' }} />
      <VetTab.Screen name="ResgatesTab" component={ResgatesStack} options={{ tabBarLabel: 'Resgates' }} />
      <VetTab.Screen name="AtendimentosTab" component={AtendimentosStack} options={{ tabBarLabel: 'Atendimentos' }} />
      <VetTab.Screen name="PerfilTab" component={PerfilStack} options={{ tabBarLabel: 'Perfil' }} />
    </VetTab.Navigator>
  );
}

// ─────────────────────────────────────────────
// Dispatcher principal + FAB universal
// ─────────────────────────────────────────────
export function AppTabs() {
  const { user } = useAuth();
  const insets = useSafeAreaInsets();
  const navigation = useNavigation<NativeStackNavigationProp<AppStackParamList>>();

  const TabsComponent =
    user?.tipo_usuario === 'ong' ? OngTabs :
    user?.tipo_usuario === 'veterinario' ? VetTabs :
    AdotanteTabs;

  return (
    <View style={styles.root}>
      <TabsComponent />
      <FAB
        icon="map-marker-plus"
        label="Reportar"
        onPress={() => navigation.navigate('ReportarAnimal')}
        style={[styles.fab, { bottom: insets.bottom + 60 }]}
        color={colors.white}
        customSize={48}
      />
    </View>
  );
}

// ─────────────────────────────────────────────
// Helper shared screenOptions
// ─────────────────────────────────────────────
function makeScreenOptions(icons: Record<string, IconName>) {
  return ({ route }: { route: { name: string } }) => ({
    headerShown: false,
    tabBarActiveTintColor: colors.primary,
    tabBarInactiveTintColor: '#BDBDBD',
    tabBarStyle: { backgroundColor: colors.bg, borderTopColor: colors.border },
    tabBarIcon: ({ color, size }: { color: string; size: number }) => (
      <Ionicons name={icons[route.name] ?? 'ellipse'} size={size} color={color} />
    ),
  });
}

const styles = StyleSheet.create({
  root: { flex: 1 },
  fab: {
    position: 'absolute',
    right: 16,
    backgroundColor: colors.accent,
  },
});
