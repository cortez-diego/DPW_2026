import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity } from 'react-native';
import { colors } from '../../theme/colors';
import { typography } from '../../theme/typography';
import { spacing } from '../../theme/spacing';

interface Props {
  titulo: string;
  acaoDireita?: React.ReactNode;
  subtitulo?: string;
}

export function Header({ titulo, subtitulo, acaoDireita }: Props) {
  return (
    <View style={styles.container}>
      <View style={styles.textos}>
        <Text style={styles.titulo}>{titulo}</Text>
        {subtitulo && <Text style={styles.subtitulo}>{subtitulo}</Text>}
      </View>
      {acaoDireita && <View>{acaoDireita}</View>}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: spacing.lg,
    paddingVertical: spacing.md,
    backgroundColor: colors.bg,
    borderBottomWidth: 1,
    borderBottomColor: colors.border,
  },
  textos: { flex: 1 },
  titulo: {
    fontFamily: typography.fontFamily.titleBold,
    fontSize: typography.fontSize.xl,
    color: colors.text,
  },
  subtitulo: {
    fontFamily: typography.fontFamily.body,
    fontSize: typography.fontSize.sm,
    color: colors.secondary,
    marginTop: 2,
  },
});
