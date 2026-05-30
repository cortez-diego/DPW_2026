import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { SolicitacaoAdocao, StatusSolicitacao } from '../../types/SolicitacaoAdocao';
import { colors } from '../../theme/colors';
import { typography } from '../../theme/typography';
import { spacing } from '../../theme/spacing';

interface Props {
  solicitacao: SolicitacaoAdocao;
}

const STATUS_CONFIG: Record<StatusSolicitacao, { label: string; bg: string; text: string }> = {
  Pendente:     { label: 'Pendente',     bg: '#FFF3CD', text: '#856404' },
  'Em Análise': { label: 'Em Análise',  bg: '#CCE5FF', text: '#004085' },
  Aprovado:     { label: 'Aprovado',    bg: '#D4EDDA', text: '#155724' },
  Concluído:    { label: 'Concluído',   bg: '#D1ECF1', text: '#0C5460' },
  Recusado:     { label: 'Recusado',    bg: '#F8D7DA', text: '#721C24' },
};

export function SolicitacaoCard({ solicitacao }: Props) {
  const cfg = STATUS_CONFIG[solicitacao.status] ?? STATUS_CONFIG.Pendente;

  return (
    <View style={styles.card}>
      <View style={styles.header}>
        <Text style={styles.animal}>{solicitacao.animal?.nome ?? `Animal #${solicitacao.fk_animal_id}`}</Text>
        <View style={[styles.badge, { backgroundColor: cfg.bg }]}>
          <Text style={[styles.badgeLabel, { color: cfg.text }]}>{cfg.label}</Text>
        </View>
      </View>
      {solicitacao.motivo ? (
        <Text style={styles.motivo} numberOfLines={2}>{solicitacao.motivo}</Text>
      ) : null}
      <Text style={styles.data}>
        {new Date(solicitacao.data).toLocaleDateString('pt-BR')}
      </Text>
    </View>
  );
}

const styles = StyleSheet.create({
  card: {
    backgroundColor: colors.bg,
    borderRadius: 12,
    padding: spacing.md,
    marginBottom: spacing.sm,
    shadowColor: '#000',
    shadowOpacity: 0.06,
    shadowRadius: 6,
    shadowOffset: { width: 0, height: 2 },
    elevation: 3,
  },
  header: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' },
  animal: {
    fontFamily: typography.fontFamily.titleBold,
    fontSize: typography.fontSize.md,
    color: colors.text,
    flex: 1,
    marginRight: spacing.sm,
  },
  badge: {
    paddingHorizontal: spacing.sm,
    paddingVertical: 3,
    borderRadius: 6,
  },
  badgeLabel: {
    fontFamily: typography.fontFamily.bodyBold,
    fontSize: typography.fontSize.xs,
  },
  motivo: {
    fontFamily: typography.fontFamily.body,
    fontSize: typography.fontSize.sm,
    color: colors.secondary,
    marginTop: spacing.xs,
  },
  data: {
    fontFamily: typography.fontFamily.body,
    fontSize: typography.fontSize.xs,
    color: colors.secondary,
    marginTop: spacing.xs,
  },
});
