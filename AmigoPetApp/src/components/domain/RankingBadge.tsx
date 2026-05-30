import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { RankingAdotante } from '../../types/Adotante';
import { typography } from '../../theme/typography';

interface Props {
  ranking: RankingAdotante;
}

const RANKING_CONFIG: Record<RankingAdotante, { label: string; bg: string; text: string; stars: number }> = {
  pessimo:    { label: 'Péssimo',    bg: '#F8D7DA', text: '#721C24', stars: 1 },
  ruim:       { label: 'Ruim',       bg: '#FFE8CC', text: '#7C4700', stars: 2 },
  bom:        { label: 'Bom',        bg: '#D4EDDA', text: '#155724', stars: 3 },
  'muito bom':{ label: 'Muito Bom', bg: '#CCE5FF', text: '#004085', stars: 4 },
  excelente:  { label: 'Excelente', bg: '#D1ECF1', text: '#0C5460', stars: 5 },
};

export function RankingBadge({ ranking }: Props) {
  const cfg = RANKING_CONFIG[ranking] ?? RANKING_CONFIG.bom;
  const stars = '★'.repeat(cfg.stars) + '☆'.repeat(5 - cfg.stars);

  return (
    <View style={[styles.badge, { backgroundColor: cfg.bg }]}>
      <Text style={[styles.label, { color: cfg.text }]}>{cfg.label}</Text>
      <Text style={[styles.stars, { color: cfg.text }]}>{stars}</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  badge: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 8,
    alignSelf: 'flex-start',
    gap: 6,
  },
  label: {
    fontFamily: typography.fontFamily.bodyBold,
    fontSize: 12,
  },
  stars: {
    fontFamily: typography.fontFamily.body,
    fontSize: 12,
    letterSpacing: 1,
  },
});
