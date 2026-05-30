import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { colors } from '../../theme/colors';
import { typography } from '../../theme/typography';
import { spacing } from '../../theme/spacing';

export function AtendimentosScreen() {
  return (
    <View style={styles.container}>
      <Text style={styles.icon}>🩺</Text>
      <Text style={styles.titulo}>Atendimentos</Text>
      <Text style={styles.sub}>Em construção — agenda e registro de vacinações e procedimentos.</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: colors.bg, padding: spacing.xl },
  icon: { fontSize: 56, marginBottom: spacing.md },
  titulo: { fontFamily: typography.fontFamily.titleBold, fontSize: typography.fontSize.xl, color: colors.text, textAlign: 'center' },
  sub: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.md, color: colors.secondary, textAlign: 'center', marginTop: spacing.sm },
});
