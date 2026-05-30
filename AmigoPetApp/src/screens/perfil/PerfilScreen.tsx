import React, { useEffect, useState } from 'react';
import { View, Text, ScrollView, StyleSheet, TouchableOpacity, ActivityIndicator } from 'react-native';
import { NativeStackScreenProps } from '@react-navigation/native-stack';
import { PerfilStackParamList } from '../../navigation/stacks/PerfilStack';
import { Avatar } from '../../components/ui/Avatar';
import { RankingBadge } from '../../components/domain/RankingBadge';
import { adotanteService } from '../../api/services/adotanteService';
import { useAuth } from '../../hooks/useAuth';
import { Adotante } from '../../types/Adotante';
import { colors } from '../../theme/colors';
import { typography } from '../../theme/typography';
import { spacing } from '../../theme/spacing';

type Props = NativeStackScreenProps<PerfilStackParamList, 'Perfil'>;

export function PerfilScreen({ navigation }: Props) {
  const { user, logout } = useAuth();
  const [adotante, setAdotante] = useState<Adotante | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    adotanteService.perfil().then(setAdotante).catch(() => {}).finally(() => setLoading(false));
  }, []);

  const isWebAdmin = user?.tipo_usuario === 'administrador' || user?.tipo_usuario === 'moderador';

  if (loading) {
    return <View style={styles.center}><ActivityIndicator size="large" color={colors.primary} /></View>;
  }

  return (
    <ScrollView style={styles.root} contentContainerStyle={styles.scroll}>
      {isWebAdmin && (
        <View style={styles.adminBanner}>
          <Text style={styles.adminBannerText}>
            ⚙️ Conta {user?.tipo_usuario} — administração completa disponível no painel web.
          </Text>
        </View>
      )}
      <View style={styles.card}>
        <Avatar uri={adotante?.foto ?? null} nome={adotante?.nome ?? user?.nome} size={80} />
        <Text style={styles.nome}>{adotante?.nome ?? user?.nome}</Text>
        <Text style={styles.email}>{user?.email}</Text>
        {adotante?.ranking && (
          <View style={styles.rankingRow}>
            <RankingBadge ranking={adotante.ranking} />
          </View>
        )}
      </View>

      <View style={styles.menu}>
        <MenuItem label="Editar Perfil" icon="✏️" onPress={() => navigation.navigate('EditarPerfil')} />
        <MenuItem label="Histórico de Adoções" icon="📋" onPress={() => navigation.navigate('Historico')} />
        <MenuItem label="Notificações" icon="🔔" onPress={() => navigation.navigate('Notificacoes')} />
      </View>

      <TouchableOpacity style={styles.btnSair} onPress={logout} activeOpacity={0.85}>
        <Text style={styles.btnSairLabel}>Sair da conta</Text>
      </TouchableOpacity>
    </ScrollView>
  );
}

function MenuItem({ label, icon, onPress }: { label: string; icon: string; onPress: () => void }) {
  return (
    <TouchableOpacity style={styles.menuItem} onPress={onPress} activeOpacity={0.8}>
      <Text style={styles.menuIcon}>{icon}</Text>
      <Text style={styles.menuLabel}>{label}</Text>
      <Text style={styles.seta}>›</Text>
    </TouchableOpacity>
  );
}

const styles = StyleSheet.create({
  root: { flex: 1, backgroundColor: colors.bgMuted },
  scroll: { padding: spacing.md },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  card: { alignItems: 'center', backgroundColor: colors.bg, borderRadius: 12, padding: spacing.lg, marginBottom: spacing.md },
  nome: { fontFamily: typography.fontFamily.titleBold, fontSize: typography.fontSize.xl, color: colors.text, marginTop: spacing.sm },
  email: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.sm, color: colors.secondary, marginTop: 2 },
  rankingRow: { marginTop: spacing.sm },
  menu: { backgroundColor: colors.bg, borderRadius: 12, marginBottom: spacing.md, overflow: 'hidden' },
  menuItem: { flexDirection: 'row', alignItems: 'center', padding: spacing.md, borderBottomWidth: 1, borderBottomColor: colors.border },
  menuIcon: { fontSize: 20, marginRight: spacing.md },
  menuLabel: { flex: 1, fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.md, color: colors.text },
  seta: { fontSize: 20, color: colors.secondary },
  btnSair: { backgroundColor: colors.bg, borderRadius: 12, padding: spacing.md, alignItems: 'center', borderWidth: 1, borderColor: colors.error },
  btnSairLabel: { fontFamily: typography.fontFamily.bodyBold, fontSize: typography.fontSize.md, color: colors.error },
  adminBanner: { backgroundColor: '#FFF3CD', borderRadius: 8, padding: spacing.md, marginBottom: spacing.md, borderLeftWidth: 3, borderLeftColor: '#F2994A' },
  adminBannerText: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.sm, color: '#856404' },
});
