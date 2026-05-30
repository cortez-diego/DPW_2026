import React, { useEffect, useState } from 'react';
import {
  View,
  Text,
  ScrollView,
  StyleSheet,
  ActivityIndicator,
  TouchableOpacity,
} from 'react-native';
import { NativeStackScreenProps } from '@react-navigation/native-stack';
import { HomeStackParamList } from '../../navigation/stacks/HomeStack';
import { Avatar } from '../../components/ui/Avatar';
import { Section } from '../../components/ui/Section';
import { animalService } from '../../api/services/animalService';
import { Animal, HistoricoAnimal } from '../../types/Animal';
import { colors } from '../../theme/colors';
import { typography } from '../../theme/typography';
import { spacing } from '../../theme/spacing';

type Props = NativeStackScreenProps<HomeStackParamList, 'AnimalDetalhe'>;

const PORTE_LABEL: Record<string, string> = { pequeno: 'Pequeno', medio: 'Médio', grande: 'Grande' };
const SEXO_LABEL: Record<string, string> = { m: 'Macho', f: 'Fêmea' };
const STATUS_LABEL: Record<string, string> = {
  disponivel: 'Disponível', adotado: 'Adotado', em_tratamento: 'Em Tratamento', reservado: 'Reservado',
};
const HISTORICO_ICON: Record<string, string> = {
  vacinacao: '💉', procedimento: '🩺', ocorrencia: '⚠️',
};

export function AnimalDetalheScreen({ route, navigation }: Props) {
  const { id } = route.params;
  const [animal, setAnimal] = useState<Animal | null>(null);
  const [historico, setHistorico] = useState<HistoricoAnimal[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    (async () => {
      try {
        const [a, h] = await Promise.all([
          animalService.buscarPorId(id),
          animalService.historico(id),
        ]);
        setAnimal(a);
        setHistorico(h);
      } finally {
        setLoading(false);
      }
    })();
  }, [id]);

  if (loading) {
    return <View style={styles.center}><ActivityIndicator size="large" color={colors.primary} /></View>;
  }

  if (!animal) {
    return <View style={styles.center}><Text style={styles.erroText}>Animal não encontrado.</Text></View>;
  }

  const disponivel = animal.status === 'disponivel';

  return (
    <View style={styles.root}>
      <ScrollView contentContainerStyle={styles.scroll}>
        <View style={styles.hero}>
          <Avatar uri={animal.foto} nome={animal.nome} size={100} />
          <Text style={styles.nome}>{animal.nome}</Text>
          {animal.raca && <Text style={styles.raca}>{animal.raca}</Text>}
          {animal.especie && !animal.raca && <Text style={styles.raca}>{animal.especie}</Text>}
          <View style={[styles.statusBadge, { backgroundColor: disponivel ? colors.primary : colors.border }]}>
            <Text style={[styles.statusLabel, { color: disponivel ? colors.white : colors.secondary }]}>
              {STATUS_LABEL[animal.status] ?? animal.status}
            </Text>
          </View>
        </View>

        <Section titulo="Informações">
          <View style={styles.infoGrid}>
            <InfoItem label="Espécie" value={animal.especie ?? '—'} />
            <InfoItem label="Porte" value={animal.porte ? PORTE_LABEL[animal.porte] ?? animal.porte : '—'} />
            <InfoItem label="Sexo" value={animal.sexo ? SEXO_LABEL[animal.sexo] ?? animal.sexo : '—'} />
            <InfoItem label="Localização" value={animal.localizacao ?? '—'} />
            {animal.ong && <InfoItem label="ONG" value={animal.ong.nome} />}
          </View>
        </Section>

        {historico.length > 0 && (
          <Section titulo="Histórico">
            {historico.map(h => (
              <View key={h.id} style={styles.historicoItem}>
                <Text style={styles.historicoIcon}>{HISTORICO_ICON[h.tipo] ?? '📋'}</Text>
                <View style={styles.historicoTexto}>
                  <Text style={styles.historicoDesc}>{h.descricao}</Text>
                  <Text style={styles.historicoData}>{new Date(h.data).toLocaleDateString('pt-BR')}</Text>
                </View>
              </View>
            ))}
          </Section>
        )}
      </ScrollView>

      <View style={styles.footer}>
        <TouchableOpacity
          style={[styles.btnSolicitar, !disponivel && styles.btnDisabled]}
          disabled={!disponivel}
          onPress={() => navigation.navigate('SolicitarAdocao', { animalId: id })}
          activeOpacity={0.85}
        >
          <Text style={styles.btnLabel}>
            {disponivel ? 'Solicitar Adoção' : 'Indisponível para adoção'}
          </Text>
        </TouchableOpacity>
      </View>
    </View>
  );
}

function InfoItem({ label, value }: { label: string; value: string }) {
  return (
    <View style={styles.infoItem}>
      <Text style={styles.infoLabel}>{label}</Text>
      <Text style={styles.infoValue}>{value}</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  root: { flex: 1, backgroundColor: colors.bgMuted },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  erroText: { fontFamily: typography.fontFamily.body, color: colors.secondary, fontSize: typography.fontSize.md },
  scroll: { padding: spacing.md },
  hero: { alignItems: 'center', paddingVertical: spacing.lg, backgroundColor: colors.bg, borderRadius: 12, marginBottom: spacing.md },
  nome: { fontFamily: typography.fontFamily.titleBold, fontSize: typography.fontSize.xl, color: colors.text, marginTop: spacing.sm },
  raca: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.md, color: colors.secondary, marginTop: 2 },
  statusBadge: { marginTop: spacing.sm, paddingHorizontal: spacing.md, paddingVertical: 4, borderRadius: 12 },
  statusLabel: { fontFamily: typography.fontFamily.bodyBold, fontSize: typography.fontSize.sm },
  infoGrid: { gap: spacing.sm },
  infoItem: { flexDirection: 'row', justifyContent: 'space-between', paddingVertical: spacing.xs, borderBottomWidth: 1, borderBottomColor: colors.border },
  infoLabel: { fontFamily: typography.fontFamily.bodyBold, fontSize: typography.fontSize.sm, color: colors.secondary },
  infoValue: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.sm, color: colors.text },
  historicoItem: { flexDirection: 'row', alignItems: 'flex-start', marginBottom: spacing.sm },
  historicoIcon: { fontSize: 22, marginRight: spacing.sm },
  historicoTexto: { flex: 1 },
  historicoDesc: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.sm, color: colors.text },
  historicoData: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.xs, color: colors.secondary, marginTop: 2 },
  footer: { padding: spacing.md, backgroundColor: colors.bg, borderTopWidth: 1, borderTopColor: colors.border },
  btnSolicitar: { backgroundColor: colors.primary, borderRadius: 10, paddingVertical: spacing.md, alignItems: 'center' },
  btnDisabled: { backgroundColor: colors.border },
  btnLabel: { fontFamily: typography.fontFamily.bodyBold, fontSize: typography.fontSize.md, color: colors.white },
});
