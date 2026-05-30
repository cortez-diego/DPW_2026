import React from 'react';
import { View, Image, Text, StyleSheet } from 'react-native';
import { colors } from '../../theme/colors';
import { typography } from '../../theme/typography';

interface Props {
  uri?: string | null;
  nome?: string;
  size?: number;
}

export function Avatar({ uri, nome, size = 48 }: Props) {
  const inicial = nome ? nome.charAt(0).toUpperCase() : '?';
  const radius = size / 2;

  if (uri) {
    return (
      <Image
        source={{ uri }}
        style={{ width: size, height: size, borderRadius: radius }}
        resizeMode="cover"
      />
    );
  }

  return (
    <View style={[styles.fallback, { width: size, height: size, borderRadius: radius }]}>
      <Text style={[styles.inicial, { fontSize: size * 0.38 }]}>{inicial}</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  fallback: {
    backgroundColor: colors.primary,
    justifyContent: 'center',
    alignItems: 'center',
  },
  inicial: {
    color: colors.white,
    fontFamily: typography.fontFamily.titleBold,
  },
});
