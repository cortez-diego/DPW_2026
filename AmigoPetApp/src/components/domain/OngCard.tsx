import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity } from 'react-native';
import { Ong } from '../../types/Ong';
import { Avatar } from '../ui/Avatar';
import { colors } from '../../theme/colors';
import { typography } from '../../theme/typography';
import { spacing } from '../../theme/spacing';

interface Props {
  ong: Ong;
  onPress: () => void;
}

export function OngCard({ ong, onPress }: Props) {
  return (
    <TouchableOpacity style={styles.card} onPress={onPress} activeOpacity={0.85}>
      <Avatar uri={ong.foto ?? null} nome={ong.nome} size={56} />
      <View style={styles.info}>
        <Text style={styles.nome}>{ong.nome}</Text>
        {ong.cidade && (
          <Text style={styles.detalhe}>
            {ong.cidade}{ong.estado ? `, ${ong.estado}` : ''}
          </Text>
        )}
        {ong.email && <Text style={styles.detalhe}>{ong.email}</Text>}
      </View>
      <Text style={styles.seta}>›</Text>
    </TouchableOpacity>
  );
}

const styles = StyleSheet.create({
  card: {
    flexDirection: 'row',
    alignItems: 'center',
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
  info: { flex: 1, marginLeft: spacing.md },
  nome: {
    fontFamily: typography.fontFamily.titleBold,
    fontSize: typography.fontSize.md,
    color: colors.text,
  },
  detalhe: {
    fontFamily: typography.fontFamily.body,
    fontSize: typography.fontSize.sm,
    color: colors.secondary,
    marginTop: 2,
  },
  seta: {
    fontSize: 22,
    color: colors.secondary,
    marginLeft: spacing.sm,
  },
});
