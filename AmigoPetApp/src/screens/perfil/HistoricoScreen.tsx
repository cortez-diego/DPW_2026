import React, { useEffect, useState } from 'react';
import { View, FlatList, StyleSheet, ActivityIndicator, RefreshControl } from 'react-native';
import { NativeStackScreenProps } from '@react-navigation/native-stack';
import { PerfilStackParamList } from '../../navigation/stacks/PerfilStack';
import { SolicitacaoCard } from '../../components/domain/SolicitacaoCard';
import { EmptyState } from '../../components/ui/EmptyState';
import { solicitacaoAdocaoService } from '../../api/services/solicitacaoAdocaoService';
import { SolicitacaoAdocao } from '../../types/SolicitacaoAdocao';
import { colors } from '../../theme/colors';
import { spacing } from '../../theme/spacing';

type Props = NativeStackScreenProps<PerfilStackParamList, 'Historico'>;

export function HistoricoScreen(_: Props) {
  const [historico, setHistorico] = useState<SolicitacaoAdocao[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  async function carregar(isRefresh = false) {
    if (isRefresh) setRefreshing(true); else setLoading(true);
    try {
      const todas = await solicitacaoAdocaoService.minhas();
      setHistorico(todas.filter(s => s.status === 'Concluído'));
    } finally {
      if (isRefresh) setRefreshing(false); else setLoading(false);
    }
  }

  useEffect(() => { carregar(); }, []);

  if (loading) {
    return <View style={styles.center}><ActivityIndicator size="large" color={colors.primary} /></View>;
  }

  return (
    <FlatList
      data={historico}
      keyExtractor={s => String(s.id)}
      renderItem={({ item }) => <SolicitacaoCard solicitacao={item} />}
      contentContainerStyle={styles.lista}
      style={styles.root}
      refreshControl={
        <RefreshControl refreshing={refreshing} onRefresh={() => carregar(true)} colors={[colors.primary]} />
      }
      ListEmptyComponent={
        <EmptyState
          icon="🏡"
          title="Nenhuma adoção concluída"
          message="Quando uma adoção for finalizada, ela aparecerá aqui."
        />
      }
    />
  );
}

const styles = StyleSheet.create({
  root: { flex: 1, backgroundColor: colors.bgMuted },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  lista: { padding: spacing.md, flexGrow: 1 },
});
