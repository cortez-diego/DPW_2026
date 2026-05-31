import React, { useEffect, useState } from 'react';
import { View, FlatList, StyleSheet, ScrollView, RefreshControl, ActivityIndicator, TouchableOpacity } from 'react-native';
import { NativeStackScreenProps } from '@react-navigation/native-stack';
import { AdocaoStackParamList } from '../../navigation/stacks/AdocaoStack';
import { SolicitacaoCard } from '../../components/domain/SolicitacaoCard';
import { EmptyState } from '../../components/ui/EmptyState';
import { Chip } from '../../components/ui/Chip';
import { solicitacaoAdocaoService } from '../../api/services/solicitacaoAdocaoService';
import { SolicitacaoAdocao, StatusSolicitacao } from '../../types/SolicitacaoAdocao';
import { colors } from '../../theme/colors';
import { spacing } from '../../theme/spacing';

type Props = NativeStackScreenProps<AdocaoStackParamList, 'MinhasSolicitacoes'>;

const FILTROS: Array<StatusSolicitacao | 'Todos'> = ['Todos', 'Pendente', 'Em Análise', 'Aprovado', 'Concluído', 'Recusado'];

export function MinhasSolicitacoesScreen({ navigation }: Props) {
  const [solicitacoes, setSolicitacoes] = useState<SolicitacaoAdocao[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [filtro, setFiltro] = useState<StatusSolicitacao | 'Todos'>('Todos');

  async function carregar(isRefresh = false) {
    if (isRefresh) setRefreshing(true); else setLoading(true);
    try {
      setSolicitacoes(await solicitacaoAdocaoService.minhas());
    } finally {
      if (isRefresh) setRefreshing(false); else setLoading(false);
    }
  }

  useEffect(() => { carregar(); }, []);

  const dados = filtro === 'Todos' ? solicitacoes : solicitacoes.filter(s => s.status === filtro);

  if (loading) {
    return <View style={styles.center}><ActivityIndicator size="large" color={colors.primary} /></View>;
  }

  return (
    <View style={styles.root}>
      <ScrollView horizontal showsHorizontalScrollIndicator={false} contentContainerStyle={styles.chips}>
        {FILTROS.map(f => (
          <Chip key={f} label={f} active={filtro === f} onPress={() => setFiltro(f)} />
        ))}
      </ScrollView>

      <FlatList
        data={dados}
        keyExtractor={s => String(s.id)}
        renderItem={({ item }) => (
          <TouchableOpacity onPress={() => navigation.navigate('SolicitacaoDetalhe', { id: item.id })} activeOpacity={0.85}>
            <SolicitacaoCard solicitacao={item} />
          </TouchableOpacity>
        )}
        contentContainerStyle={styles.lista}
        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={() => carregar(true)} colors={[colors.primary]} />}
        ListEmptyComponent={
          <EmptyState
            icon="📋"
            title="Nenhuma solicitação encontrada"
            message={filtro === 'Todos' ? 'Você ainda não fez nenhuma solicitação.' : `Nenhuma solicitação com status "${filtro}".`}
          />
        }
      />
    </View>
  );
}

const styles = StyleSheet.create({
  root: { flex: 1, backgroundColor: colors.bgMuted },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  chips: { paddingHorizontal: spacing.md, paddingVertical: spacing.sm, backgroundColor: colors.bg },
  lista: { padding: spacing.md, flexGrow: 1 },
});
