import React, { useEffect, useState } from 'react';
import {
  View,
  Text,
  ScrollView,
  StyleSheet,
  TextInput,
  TouchableOpacity,
  ActivityIndicator,
} from 'react-native';
import { Snackbar } from 'react-native-paper';
import { NativeStackScreenProps } from '@react-navigation/native-stack';
import { HomeStackParamList } from '../../navigation/stacks/HomeStack';
import { animalService } from '../../api/services/animalService';
import { solicitacaoAdocaoService } from '../../api/services/solicitacaoAdocaoService';
import { Animal } from '../../types/Animal';
import { colors } from '../../theme/colors';
import { typography } from '../../theme/typography';
import { spacing } from '../../theme/spacing';

type Props = NativeStackScreenProps<HomeStackParamList, 'SolicitarAdocao'>;

const MOTIVO_MIN = 20;

export function SolicitarAdocaoScreen({ route, navigation }: Props) {
  const { animalId } = route.params;
  const [animal, setAnimal] = useState<Animal | null>(null);
  const [motivo, setMotivo] = useState('');
  const [aceite, setAceite] = useState(false);
  const [enviando, setEnviando] = useState(false);
  const [snack, setSnack] = useState<{ visible: boolean; msg: string; err: boolean }>({
    visible: false, msg: '', err: false,
  });

  useEffect(() => {
    animalService.buscarPorId(animalId).then(setAnimal).catch(() => {});
  }, [animalId]);

  const motivoValido = motivo.trim().length >= MOTIVO_MIN;
  const podeEnviar = motivoValido && aceite && !enviando;

  async function enviar() {
    if (!podeEnviar) return;
    setEnviando(true);
    try {
      await solicitacaoAdocaoService.criar({
        fk_animal_id: animalId,
        motivo: motivo.trim(),
        aceite_termo: true,
        timestamp_aceite: new Date().toISOString(),
      });
      setSnack({ visible: true, msg: 'Solicitação enviada com sucesso!', err: false });
      setTimeout(() => navigation.popToTop(), 1500);
    } catch {
      setSnack({ visible: true, msg: 'Erro ao enviar. Tente novamente.', err: true });
    } finally {
      setEnviando(false);
    }
  }

  return (
    <View style={styles.root}>
      <ScrollView contentContainerStyle={styles.scroll} keyboardShouldPersistTaps="handled">
        {animal && (
          <View style={styles.animalInfo}>
            <Text style={styles.animalLabel}>Animal</Text>
            <Text style={styles.animalNome}>{animal.nome}</Text>
          </View>
        )}

        <Text style={styles.fieldLabel}>Motivo da solicitação *</Text>
        <TextInput
          style={styles.textarea}
          multiline
          numberOfLines={5}
          placeholder={`Descreva por que deseja adotar este animal (mín. ${MOTIVO_MIN} caracteres)...`}
          placeholderTextColor={colors.secondary}
          value={motivo}
          onChangeText={setMotivo}
          textAlignVertical="top"
        />
        <Text style={[styles.contador, !motivoValido && motivo.length > 0 && styles.contadorErro]}>
          {motivo.length}/{MOTIVO_MIN} caracteres mínimos
        </Text>

        <TouchableOpacity
          style={styles.termoLink}
          onPress={() => navigation.navigate('TermoResponsabilidade', { animalId, motivo })}
          activeOpacity={0.7}
        >
          <Text style={styles.termoLinkText}>Ler Termo de Responsabilidade</Text>
        </TouchableOpacity>

        <TouchableOpacity style={styles.checkRow} onPress={() => setAceite(v => !v)} activeOpacity={0.8}>
          <View style={[styles.checkbox, aceite && styles.checkboxAtivo]}>
            {aceite && <Text style={styles.checkmark}>✓</Text>}
          </View>
          <Text style={styles.checkLabel}>Li e aceito o Termo de Responsabilidade</Text>
        </TouchableOpacity>
      </ScrollView>

      <View style={styles.footer}>
        <TouchableOpacity
          style={[styles.btnEnviar, !podeEnviar && styles.btnDisabled]}
          onPress={enviar}
          disabled={!podeEnviar}
          activeOpacity={0.85}
        >
          {enviando
            ? <ActivityIndicator color={colors.white} />
            : <Text style={styles.btnLabel}>Enviar Solicitação</Text>}
        </TouchableOpacity>
      </View>

      <Snackbar
        visible={snack.visible}
        onDismiss={() => setSnack(s => ({ ...s, visible: false }))}
        duration={3000}
        style={{ backgroundColor: snack.err ? colors.error : colors.success }}
      >
        {snack.msg}
      </Snackbar>
    </View>
  );
}

const styles = StyleSheet.create({
  root: { flex: 1, backgroundColor: colors.bgMuted },
  scroll: { padding: spacing.md },
  animalInfo: {
    backgroundColor: colors.bg,
    borderRadius: 10,
    padding: spacing.md,
    marginBottom: spacing.md,
    borderLeftWidth: 3,
    borderLeftColor: colors.primary,
  },
  animalLabel: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.xs, color: colors.secondary },
  animalNome: { fontFamily: typography.fontFamily.titleBold, fontSize: typography.fontSize.lg, color: colors.text, marginTop: 2 },
  fieldLabel: { fontFamily: typography.fontFamily.bodyBold, fontSize: typography.fontSize.sm, color: colors.text, marginBottom: spacing.xs },
  textarea: {
    backgroundColor: colors.bg,
    borderRadius: 10,
    padding: spacing.md,
    borderWidth: 1,
    borderColor: colors.border,
    fontFamily: typography.fontFamily.body,
    fontSize: typography.fontSize.md,
    color: colors.text,
    minHeight: 120,
  },
  contador: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.xs, color: colors.secondary, marginTop: 4, marginBottom: spacing.md },
  contadorErro: { color: colors.error },
  termoLink: { marginBottom: spacing.md },
  termoLinkText: { fontFamily: typography.fontFamily.bodyBold, fontSize: typography.fontSize.sm, color: colors.primary, textDecorationLine: 'underline' },
  checkRow: { flexDirection: 'row', alignItems: 'center', marginBottom: spacing.md },
  checkbox: { width: 22, height: 22, borderRadius: 4, borderWidth: 2, borderColor: colors.border, marginRight: spacing.sm, justifyContent: 'center', alignItems: 'center' },
  checkboxAtivo: { backgroundColor: colors.primary, borderColor: colors.primary },
  checkmark: { color: colors.white, fontSize: 14, fontFamily: typography.fontFamily.bodyBold },
  checkLabel: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.sm, color: colors.text, flex: 1 },
  footer: { padding: spacing.md, backgroundColor: colors.bg, borderTopWidth: 1, borderTopColor: colors.border },
  btnEnviar: { backgroundColor: colors.primary, borderRadius: 10, paddingVertical: spacing.md, alignItems: 'center' },
  btnDisabled: { backgroundColor: colors.border },
  btnLabel: { fontFamily: typography.fontFamily.bodyBold, fontSize: typography.fontSize.md, color: colors.white },
});
