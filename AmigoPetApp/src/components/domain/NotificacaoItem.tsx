import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity } from 'react-native';
import { Notificacao } from '../../types/Notificacao';
import { colors } from '../../theme/colors';
import { typography } from '../../theme/typography';
import { spacing } from '../../theme/spacing';

interface Props {
  notificacao: Notificacao;
  onPress: () => void;
}

const TIPO_ICON: Record<string, string> = {
  solicitacao: '📋',
  adocao_concluida: '🎉',
  sistema: 'ℹ️',
};

export function NotificacaoItem({ notificacao, onPress }: Props) {
  return (
    <TouchableOpacity
      style={[styles.item, !notificacao.lida && styles.naoLida]}
      onPress={onPress}
      activeOpacity={0.8}
    >
      <Text style={styles.icon}>{TIPO_ICON[notificacao.tipo] ?? 'ℹ️'}</Text>
      <View style={styles.content}>
        <Text style={styles.titulo}>{notificacao.titulo}</Text>
        <Text style={styles.mensagem} numberOfLines={2}>{notificacao.mensagem}</Text>
        <Text style={styles.data}>
          {new Date(notificacao.data).toLocaleDateString('pt-BR')}
        </Text>
      </View>
      {!notificacao.lida && <View style={styles.dot} />}
    </TouchableOpacity>
  );
}

const styles = StyleSheet.create({
  item: {
    flexDirection: 'row',
    alignItems: 'flex-start',
    backgroundColor: colors.bg,
    paddingHorizontal: spacing.md,
    paddingVertical: spacing.sm,
    borderBottomWidth: 1,
    borderBottomColor: colors.border,
  },
  naoLida: { backgroundColor: '#F0FAF4' },
  icon: { fontSize: 28, marginRight: spacing.md, marginTop: 2 },
  content: { flex: 1 },
  titulo: {
    fontFamily: typography.fontFamily.bodyBold,
    fontSize: typography.fontSize.md,
    color: colors.text,
  },
  mensagem: {
    fontFamily: typography.fontFamily.body,
    fontSize: typography.fontSize.sm,
    color: colors.secondary,
    marginTop: 2,
  },
  data: {
    fontFamily: typography.fontFamily.body,
    fontSize: typography.fontSize.xs,
    color: colors.secondary,
    marginTop: 4,
  },
  dot: {
    width: 10,
    height: 10,
    borderRadius: 5,
    backgroundColor: colors.primary,
    marginTop: 6,
    marginLeft: spacing.sm,
  },
});
