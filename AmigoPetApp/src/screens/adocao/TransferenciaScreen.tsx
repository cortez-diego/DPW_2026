import React, { useCallback, useEffect, useState } from 'react';
import {
  View, Text, ScrollView, StyleSheet, TouchableOpacity, ActivityIndicator, Alert, FlatList,
} from 'react-native';
import { TextInput, Snackbar } from 'react-native-paper';
import { useForm, Controller } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { useNavigation, useRoute } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import type { RouteProp } from '@react-navigation/native';
import { transferenciaService } from '../../api/services/transferenciaService';
import { usuarioService } from '../../api/services/usuarioService';
import { useAuth } from '../../hooks/useAuth';
import { colors } from '../../theme/colors';
import { typography } from '../../theme/typography';
import { spacing } from '../../theme/spacing';
import type { Transferencia } from '../../types/Adocao';
import type { UsuarioBusca } from '../../types/Usuario';
import type { HomeStackParamList } from '../../navigation/stacks/HomeStack';

type Nav = NativeStackNavigationProp<HomeStackParamList, 'Transferencia'>;
type Route = RouteProp<HomeStackParamList, 'Transferencia'>;

const TIPO_LABEL: Record<string, string> = {
  adotante: 'Adotante', ong: 'ONG', veterinario: 'Veterinário',
  administrador: 'Admin', moderador: 'Moderador',
};

const schema = z.object({
  motivo: z.string().optional(),
});

type FormData = z.infer<typeof schema>;

export function TransferenciaScreen() {
  const { user } = useAuth();
  const navigation = useNavigation<Nav>();
  const route = useRoute<Route>();
  const animalId = route.params.animalId;

  const [historico, setHistorico] = useState<Transferencia[]>([]);
  const [carregandoHist, setCarregandoHist] = useState(true);
  const [enviando, setEnviando] = useState(false);
  const [snack, setSnack] = useState({ visible: false, msg: '' });

  const [query, setQuery] = useState('');
  const [buscando, setBuscando] = useState(false);
  const [resultados, setResultados] = useState<UsuarioBusca[]>([]);
  const [destinatario, setDestinatario] = useState<UsuarioBusca | null>(null);

  const { control, handleSubmit, formState: { errors } } = useForm<FormData>({
    resolver: zodResolver(schema),
    defaultValues: { motivo: '' },
  });

  const carregarHistorico = useCallback(async () => {
    setCarregandoHist(true);
    try { setHistorico(await transferenciaService.historico(animalId)); }
    catch { /* histórico é secundário */ }
    finally { setCarregandoHist(false); }
  }, [animalId]);

  useEffect(() => { carregarHistorico(); }, [carregarHistorico]);

  useEffect(() => {
    if (!query.trim()) { setResultados([]); return; }
    const timeout = setTimeout(async () => {
      setBuscando(true);
      try { setResultados(await usuarioService.buscar(query)); }
      catch { setResultados([]); }
      finally { setBuscando(false); }
    }, 400);
    return () => clearTimeout(timeout);
  }, [query]);

  function selecionarDestinatario(u: UsuarioBusca) {
    setDestinatario(u);
    setQuery('');
    setResultados([]);
  }

  async function onSubmit(data: FormData) {
    if (!destinatario) {
      setSnack({ visible: true, msg: 'Selecione um destinatário.' });
      return;
    }
    Alert.alert(
      'Confirmar transferência',
      `Transferir este animal para "${destinatario.nome}"? Esta ação é registrada de forma permanente.`,
      [
        { text: 'Cancelar', style: 'cancel' },
        {
          text: 'Confirmar',
          onPress: async () => {
            setEnviando(true);
            try {
              await transferenciaService.transferir(
                animalId,
                { para_usuario_id: destinatario.id, para_usuario_nome: destinatario.nome, motivo: data.motivo },
                user!.id,
                user!.nome,
              );
              await carregarHistorico();
              navigation.goBack();
            } catch (err: any) {
              setSnack({ visible: true, msg: err?.message ?? 'Erro ao transferir.' });
            } finally { setEnviando(false); }
          },
        },
      ],
    );
  }

  return (
    <View style={styles.root}>
      <ScrollView contentContainerStyle={styles.scroll} keyboardShouldPersistTaps="handled">
        <Text style={styles.heading}>Transferir Responsabilidade</Text>
        <Text style={styles.sub}>Transfira a guarda deste animal para outro usuário ou organização.</Text>

        <View style={styles.aviso}>
          <Text style={styles.avisoTexto}>🔒 A transferência é registrada de forma imutável e permanente.</Text>
        </View>

        {/* Busca de destinatário */}
        <View style={styles.campo}>
          <Text style={styles.campoLabel}>Destinatário *</Text>
          {destinatario ? (
            <View style={styles.destinatarioSelecionado}>
              <View style={styles.destinatarioInfo}>
                <Text style={styles.destinatarioNome}>{destinatario.nome}</Text>
                <Text style={styles.destinatarioTipo}>{TIPO_LABEL[destinatario.tipo_usuario] ?? destinatario.tipo_usuario} · {destinatario.email}</Text>
              </View>
              <TouchableOpacity onPress={() => setDestinatario(null)} style={styles.btnLimpar}>
                <Text style={styles.btnLimparLabel}>Trocar</Text>
              </TouchableOpacity>
            </View>
          ) : (
            <View>
              <TextInput
                mode="outlined"
                value={query}
                onChangeText={setQuery}
                placeholder="Buscar por nome ou e-mail..."
                outlineColor={colors.border}
                activeOutlineColor={colors.primary}
                style={styles.input}
                contentStyle={styles.inputContent}
                right={buscando ? <TextInput.Icon icon="loading" /> : undefined}
              />
              {resultados.length > 0 && (
                <View style={styles.lista}>
                  {resultados.map(u => (
                    <TouchableOpacity
                      key={u.id}
                      style={styles.listaItem}
                      onPress={() => selecionarDestinatario(u)}
                      activeOpacity={0.8}
                    >
                      <Text style={styles.listaItemNome}>{u.nome}</Text>
                      <Text style={styles.listaItemSub}>{TIPO_LABEL[u.tipo_usuario] ?? u.tipo_usuario} · {u.email}</Text>
                    </TouchableOpacity>
                  ))}
                </View>
              )}
              {query.length > 0 && !buscando && resultados.length === 0 && (
                <Text style={styles.semResultados}>Nenhum usuário ou ONG encontrado.</Text>
              )}
            </View>
          )}
        </View>

        <View style={styles.campo}>
          <Text style={styles.campoLabel}>Motivo da transferência (opcional)</Text>
          <Controller control={control} name="motivo"
            render={({ field: { onChange, onBlur, value } }) => (
              <TextInput mode="outlined" value={value ?? ''} onChangeText={onChange} onBlur={onBlur}
                multiline numberOfLines={3} placeholder="Ex: Adoção concluída, resgate encaminhado..."
                outlineColor={colors.border} activeOutlineColor={colors.primary}
                style={styles.inputMultiline} contentStyle={styles.inputContent} />
            )} />
        </View>

        <TouchableOpacity
          style={[styles.btnTransferir, enviando && styles.btnDisabled]}
          onPress={handleSubmit(onSubmit)}
          disabled={enviando}
          activeOpacity={0.85}>
          {enviando
            ? <ActivityIndicator color={colors.white} />
            : <Text style={styles.btnLabel}>Transferir responsabilidade</Text>}
        </TouchableOpacity>

        {/* Histórico de tutores */}
        <Text style={styles.histTitulo}>Histórico de tutores (imutável)</Text>
        {carregandoHist ? (
          <ActivityIndicator color={colors.primary} style={{ marginTop: spacing.sm }} />
        ) : historico.length === 0 ? (
          <Text style={styles.semDados}>Nenhuma transferência registrada.</Text>
        ) : (
          historico.map(t => (
            <View key={t.id} style={styles.histItem}>
              <View style={styles.histLinha} />
              <View style={styles.histConteudo}>
                <Text style={styles.histDe}>De: {t.de_usuario_nome}</Text>
                <Text style={styles.histPara}>Para: {t.para_usuario_nome}</Text>
                {t.motivo && <Text style={styles.histMotivo}>{t.motivo}</Text>}
                <Text style={styles.histData}>{new Date(t.data).toLocaleString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</Text>
              </View>
            </View>
          ))
        )}
      </ScrollView>

      <Snackbar visible={snack.visible} onDismiss={() => setSnack(s => ({ ...s, visible: false }))}
        duration={4000} style={{ backgroundColor: colors.error }}>
        {snack.msg}
      </Snackbar>
    </View>
  );
}

const styles = StyleSheet.create({
  root: { flex: 1, backgroundColor: colors.bgMuted },
  scroll: { padding: spacing.md, paddingBottom: spacing.xxl },
  heading: { fontFamily: typography.fontFamily.titleBold, fontSize: typography.fontSize.xl, color: colors.text, marginBottom: 2 },
  sub: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.md, color: colors.secondary, marginBottom: spacing.md },
  aviso: { backgroundColor: '#FFF8E7', borderRadius: 8, padding: spacing.sm, marginBottom: spacing.md, borderLeftWidth: 3, borderLeftColor: colors.accent },
  avisoTexto: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.sm, color: colors.text },
  campo: { marginBottom: spacing.sm },
  campoLabel: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.sm, color: colors.secondary, marginBottom: 4 },
  input: { backgroundColor: colors.bg, height: 48 },
  inputMultiline: { backgroundColor: colors.bg },
  inputContent: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.md },
  lista: { backgroundColor: colors.bg, borderRadius: 8, borderWidth: 1, borderColor: colors.border, marginTop: 4, overflow: 'hidden' },
  listaItem: { padding: spacing.sm, borderBottomWidth: 1, borderBottomColor: colors.border },
  listaItemNome: { fontFamily: typography.fontFamily.bodyBold, fontSize: typography.fontSize.sm, color: colors.text },
  listaItemSub: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.xs, color: colors.secondary },
  semResultados: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.sm, color: colors.secondary, fontStyle: 'italic', marginTop: 4 },
  destinatarioSelecionado: { flexDirection: 'row', alignItems: 'center', backgroundColor: '#E8F5E9', borderRadius: 8, borderWidth: 1, borderColor: colors.success, padding: spacing.sm },
  destinatarioInfo: { flex: 1 },
  destinatarioNome: { fontFamily: typography.fontFamily.bodyBold, fontSize: typography.fontSize.sm, color: colors.text },
  destinatarioTipo: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.xs, color: colors.secondary },
  btnLimpar: { paddingHorizontal: spacing.sm },
  btnLimparLabel: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.sm, color: colors.primary },
  btnTransferir: { backgroundColor: colors.primary, borderRadius: 10, paddingVertical: spacing.md, alignItems: 'center', marginTop: spacing.sm, marginBottom: spacing.lg },
  btnDisabled: { backgroundColor: colors.border },
  btnLabel: { fontFamily: typography.fontFamily.bodyBold, fontSize: typography.fontSize.md, color: colors.white },
  histTitulo: { fontFamily: typography.fontFamily.titleBold, fontSize: typography.fontSize.md, color: colors.text, marginBottom: spacing.sm, borderLeftWidth: 3, borderLeftColor: colors.primary, paddingLeft: spacing.sm },
  histItem: { flexDirection: 'row', marginBottom: spacing.sm },
  histLinha: { width: 2, backgroundColor: colors.border, marginRight: spacing.sm, borderRadius: 1 },
  histConteudo: { flex: 1 },
  histDe: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.sm, color: colors.secondary },
  histPara: { fontFamily: typography.fontFamily.bodyBold, fontSize: typography.fontSize.sm, color: colors.text },
  histMotivo: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.xs, color: colors.secondary, fontStyle: 'italic' },
  histData: { fontFamily: typography.fontFamily.body, fontSize: 10, color: colors.secondary, marginTop: 2 },
  semDados: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.sm, color: colors.secondary, fontStyle: 'italic' },
});
