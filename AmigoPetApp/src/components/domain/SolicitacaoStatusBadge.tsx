import React from 'react';
import { StyleSheet } from 'react-native';
import { Chip } from 'react-native-paper';
import type { StatusSolicitacao } from '../../types/SolicitacaoAdocao';
import { colors } from '../../theme/colors';

const STATUS_CONFIG: Record<StatusSolicitacao, { bg: string; text: string }> = {
  Pendente:     { bg: '#FFF3CD', text: '#856404' },
  'Em Análise': { bg: '#CCE5FF', text: '#004085' },
  Aprovado:     { bg: '#D4EDDA', text: '#155724' },
  Concluído:    { bg: '#D1ECF1', text: '#0C5460' },
  Recusado:     { bg: '#F8D7DA', text: '#721C24' },
};

interface Props {
  status: StatusSolicitacao;
}

export function SolicitacaoStatusBadge({ status }: Props) {
  const cfg = STATUS_CONFIG[status] ?? { bg: colors.bgMuted, text: colors.text };
  return (
    <Chip
      style={[styles.chip, { backgroundColor: cfg.bg }]}
      textStyle={{ color: cfg.text, fontSize: 12 }}
      compact
    >
      {status}
    </Chip>
  );
}

const styles = StyleSheet.create({
  chip: { alignSelf: 'flex-start', borderRadius: 12 },
});
