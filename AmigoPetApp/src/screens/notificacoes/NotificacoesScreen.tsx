import React, { useEffect, useState } from 'react';
import { View, FlatList, StyleSheet, ActivityIndicator, RefreshControl } from 'react-native';
import { NativeStackScreenProps } from '@react-navigation/native-stack';
import { PerfilStackParamList } from '../../navigation/stacks/PerfilStack';
import { NotificacaoItem } from '../../components/domain/NotificacaoItem';
import { EmptyState } from '../../components/ui/EmptyState';
import { notificacaoService } from '../../api/services/notificacaoService';
import { Notificacao } from '../../types/Notificacao';
import { colors } from '../../theme/colors';
import { spacing } from '../../theme/spacing';

type Props = NativeStackScreenProps<PerfilStackParamList, 'Notificacoes'>;

export function NotificacoesScreen(_: Props) {
  const [notificacoes, setNotificacoes] = useState<Notificacao[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  async function carregar(isRefresh = false) {
    if (isRefresh) setRefreshing(true); else setLoading(true);
    try { setNotificacoes(await notificacaoService.listar()); }
    finally { if (isRefresh) setRefreshing(false); else setLoading(false); }
  }

  useEffect(() => { carregar(); }, []);

  async function marcarLida(id: number) {
    await notificacaoService.marcarLida(id);
    setNotificacoes(ns => ns.map(n => n.id === id ? { ...n, lida: true } : n));
  }

  if (loading) {
    return <View style={styles.center}><ActivityIndicator size="large" color={colors.primary} /></View>;
  }

  return (
    <FlatList
      data={notificacoes}
      keyExtractor={n => String(n.id)}
      renderItem={({ item }) => (
        <NotificacaoItem
          notificacao={item}
          onPress={() => { if (!item.lida) marcarLida(item.id); }}
        />
      )}
      style={styles.root}
      refreshControl={
        <RefreshControl refreshing={refreshing} onRefresh={() => carregar(true)} colors={[colors.primary]} />
      }
      ListEmptyComponent={
        <EmptyState icon="🔔" title="Sem notificações" message="Você está em dia!" />
      }
    />
  );
}

const styles = StyleSheet.create({
  root: { flex: 1, backgroundColor: colors.bg },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center' },
});
