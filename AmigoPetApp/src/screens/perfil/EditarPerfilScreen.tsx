import React, { useEffect, useState } from 'react';
import {
  View,
  Text,
  ScrollView,
  StyleSheet,
  TouchableOpacity,
  ActivityIndicator,
  Alert,
} from 'react-native';
import { TextInput, Snackbar } from 'react-native-paper';
import MaskInput from 'react-native-mask-input';
import * as ImagePicker from 'expo-image-picker';
import { useForm, Controller } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { NativeStackScreenProps } from '@react-navigation/native-stack';
import { PerfilStackParamList } from '../../navigation/stacks/PerfilStack';
import { Avatar } from '../../components/ui/Avatar';
import { adotanteService } from '../../api/services/adotanteService';
import { uploadService } from '../../api/services/uploadService';
import { Adotante } from '../../types/Adotante';
import { colors } from '../../theme/colors';
import { typography } from '../../theme/typography';
import { spacing } from '../../theme/spacing';

type Props = NativeStackScreenProps<PerfilStackParamList, 'EditarPerfil'>;

const CPF_MASK = [/\d/, /\d/, /\d/, '.', /\d/, /\d/, /\d/, '.', /\d/, /\d/, /\d/, '-', /\d/, /\d/];
const PHONE_MASK = ['(', /\d/, /\d/, ')', ' ', /\d/, /\d/, /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\d/];
const DATE_MASK = [/\d/, /\d/, '/', /\d/, /\d/, '/', /\d/, /\d/, /\d/, /\d/];
const CEP_MASK = [/\d/, /\d/, /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/];

const schema = z.object({
  nome: z.string().min(3, 'Nome deve ter ao menos 3 caracteres'),
  cpf: z.string().refine(v => v.replace(/\D/g, '').length === 11, 'CPF inválido'),
  data_nascimento: z.string().min(10, 'Data inválida'),
  telefone_1: z.string().refine(v => v.replace(/\D/g, '').length >= 10, 'Telefone inválido'),
  telefone_2: z.string().optional(),
  cep: z.string().refine(v => v.replace(/\D/g, '').length === 8, 'CEP inválido'),
  logradouro: z.string().min(1, 'Obrigatório'),
  numero: z.string().min(1, 'Obrigatório'),
  bairro: z.string().min(1, 'Obrigatório'),
  complemento: z.string().optional(),
  cidade: z.string().min(1, 'Obrigatório'),
  estado: z.string().min(2, 'Obrigatório'),
});

type FormData = z.infer<typeof schema>;

function formatarDataParaBR(iso: string | null) {
  if (!iso) return '';
  const [ano, mes, dia] = iso.split('T')[0].split('-');
  return `${dia}/${mes}/${ano}`;
}

function parseDateToISO(br: string) {
  const [dia, mes, ano] = br.split('/');
  return `${ano}-${mes}-${dia}`;
}

export function EditarPerfilScreen({ navigation }: Props) {
  const [adotante, setAdotante] = useState<Adotante | null>(null);
  const [fotoUri, setFotoUri] = useState<string | null>(null);
  const [carregando, setCarregando] = useState(true);
  const [salvando, setSalvando] = useState(false);
  const [snack, setSnack] = useState<{ visible: boolean; msg: string; err: boolean }>({ visible: false, msg: '', err: false });

  const { control, handleSubmit, setValue, formState: { errors } } = useForm<FormData>({
    resolver: zodResolver(schema),
    defaultValues: {
      nome: '', cpf: '', data_nascimento: '', telefone_1: '', telefone_2: '',
      cep: '', logradouro: '', numero: '', bairro: '', complemento: '', cidade: '', estado: '',
    },
  });

  useEffect(() => {
    adotanteService.perfil().then(a => {
      setAdotante(a);
      setFotoUri(a.foto);
      setValue('nome', a.nome);
      setValue('cpf', a.cpf);
      setValue('data_nascimento', formatarDataParaBR(a.data_nascimento));
      setValue('telefone_1', a.telefone_1);
      setValue('telefone_2', a.telefone_2 ?? '');
      setValue('cep', a.cep);
      setValue('logradouro', a.logradouro);
      setValue('numero', String(a.numero ?? ''));
      setValue('bairro', a.bairro);
      setValue('complemento', a.complemento ?? '');
      setValue('cidade', a.cidade);
      setValue('estado', a.estado);
    }).catch(() => {}).finally(() => setCarregando(false));
  }, [setValue]);

  async function escolherFoto() {
    const perm = await ImagePicker.requestMediaLibraryPermissionsAsync();
    if (!perm.granted) {
      Alert.alert('Permissão necessária', 'Permita o acesso à galeria para trocar a foto.');
      return;
    }
    const result = await ImagePicker.launchImageLibraryAsync({
      mediaTypes: ['images'],
      allowsEditing: true,
      aspect: [1, 1],
      quality: 0.8,
    });
    if (!result.canceled && result.assets[0]) {
      setFotoUri(result.assets[0].uri);
    }
  }

  async function onSubmit(data: FormData) {
    setSalvando(true);
    try {
      let fotoUrl = adotante?.foto ?? null;
      if (fotoUri && fotoUri !== adotante?.foto) {
        fotoUrl = await uploadService.enviarImagem(fotoUri);
      }
      await adotanteService.atualizarPerfil({
        nome: data.nome,
        cpf: data.cpf.replace(/\D/g, ''),
        data_nascimento: parseDateToISO(data.data_nascimento),
        telefone_1: data.telefone_1.replace(/\D/g, ''),
        telefone_2: data.telefone_2?.replace(/\D/g, ''),
        cep: data.cep.replace(/\D/g, ''),
        logradouro: data.logradouro,
        numero: Number(data.numero),
        bairro: data.bairro,
        complemento: data.complemento,
        cidade: data.cidade,
        estado: data.estado,
        foto: fotoUrl,
      });
      setSnack({ visible: true, msg: 'Perfil atualizado com sucesso!', err: false });
      setTimeout(() => navigation.goBack(), 1500);
    } catch {
      setSnack({ visible: true, msg: 'Erro ao salvar. Tente novamente.', err: true });
    } finally {
      setSalvando(false);
    }
  }

  if (carregando) {
    return <View style={styles.center}><ActivityIndicator size="large" color={colors.primary} /></View>;
  }

  return (
    <View style={styles.root}>
      <ScrollView contentContainerStyle={styles.scroll} keyboardShouldPersistTaps="handled">
        <TouchableOpacity style={styles.fotoArea} onPress={escolherFoto} activeOpacity={0.8}>
          <Avatar uri={fotoUri} nome={adotante?.nome} size={90} />
          <Text style={styles.fotoLabel}>Alterar foto</Text>
        </TouchableOpacity>

        <Secao titulo="Dados pessoais">
          <Campo label="Nome completo" error={errors.nome?.message}>
            <Controller control={control} name="nome"
              render={({ field: { onChange, onBlur, value } }) => (
                <TextInput mode="outlined" value={value} onChangeText={onChange} onBlur={onBlur}
                  outlineColor={colors.border} activeOutlineColor={colors.primary}
                  style={styles.input} contentStyle={styles.inputContent} />
              )} />
          </Campo>

          <Campo label="CPF" error={errors.cpf?.message}>
            <Controller control={control} name="cpf"
              render={({ field: { onChange, value } }) => (
                <MaskInput value={value} onChangeText={onChange} mask={CPF_MASK}
                  style={styles.maskInput} keyboardType="numeric" />
              )} />
          </Campo>

          <Campo label="Data de nascimento (DD/MM/AAAA)" error={errors.data_nascimento?.message}>
            <Controller control={control} name="data_nascimento"
              render={({ field: { onChange, value } }) => (
                <MaskInput value={value} onChangeText={onChange} mask={DATE_MASK}
                  style={styles.maskInput} keyboardType="numeric" />
              )} />
          </Campo>

          <Campo label="Telefone 1" error={errors.telefone_1?.message}>
            <Controller control={control} name="telefone_1"
              render={({ field: { onChange, value } }) => (
                <MaskInput value={value} onChangeText={onChange} mask={PHONE_MASK}
                  style={styles.maskInput} keyboardType="phone-pad" />
              )} />
          </Campo>

          <Campo label="Telefone 2 (opcional)" error={errors.telefone_2?.message}>
            <Controller control={control} name="telefone_2"
              render={({ field: { onChange, value } }) => (
                <MaskInput value={value ?? ''} onChangeText={onChange} mask={PHONE_MASK}
                  style={styles.maskInput} keyboardType="phone-pad" />
              )} />
          </Campo>
        </Secao>

        <Secao titulo="Endereço">
          <Campo label="CEP" error={errors.cep?.message}>
            <Controller control={control} name="cep"
              render={({ field: { onChange, value } }) => (
                <MaskInput value={value} onChangeText={onChange} mask={CEP_MASK}
                  style={styles.maskInput} keyboardType="numeric" />
              )} />
          </Campo>

          <Campo label="Logradouro" error={errors.logradouro?.message}>
            <Controller control={control} name="logradouro"
              render={({ field: { onChange, onBlur, value } }) => (
                <TextInput mode="outlined" value={value} onChangeText={onChange} onBlur={onBlur}
                  outlineColor={colors.border} activeOutlineColor={colors.primary}
                  style={styles.input} contentStyle={styles.inputContent} />
              )} />
          </Campo>

          <Campo label="Número" error={errors.numero?.message}>
            <Controller control={control} name="numero"
              render={({ field: { onChange, onBlur, value } }) => (
                <TextInput mode="outlined" value={value} onChangeText={onChange} onBlur={onBlur}
                  keyboardType="numeric"
                  outlineColor={colors.border} activeOutlineColor={colors.primary}
                  style={styles.input} contentStyle={styles.inputContent} />
              )} />
          </Campo>

          <Campo label="Bairro" error={errors.bairro?.message}>
            <Controller control={control} name="bairro"
              render={({ field: { onChange, onBlur, value } }) => (
                <TextInput mode="outlined" value={value} onChangeText={onChange} onBlur={onBlur}
                  outlineColor={colors.border} activeOutlineColor={colors.primary}
                  style={styles.input} contentStyle={styles.inputContent} />
              )} />
          </Campo>

          <Campo label="Complemento (opcional)" error={errors.complemento?.message}>
            <Controller control={control} name="complemento"
              render={({ field: { onChange, onBlur, value } }) => (
                <TextInput mode="outlined" value={value ?? ''} onChangeText={onChange} onBlur={onBlur}
                  outlineColor={colors.border} activeOutlineColor={colors.primary}
                  style={styles.input} contentStyle={styles.inputContent} />
              )} />
          </Campo>

          <Campo label="Cidade" error={errors.cidade?.message}>
            <Controller control={control} name="cidade"
              render={({ field: { onChange, onBlur, value } }) => (
                <TextInput mode="outlined" value={value} onChangeText={onChange} onBlur={onBlur}
                  outlineColor={colors.border} activeOutlineColor={colors.primary}
                  style={styles.input} contentStyle={styles.inputContent} />
              )} />
          </Campo>

          <Campo label="Estado (UF)" error={errors.estado?.message}>
            <Controller control={control} name="estado"
              render={({ field: { onChange, onBlur, value } }) => (
                <TextInput mode="outlined" value={value} onChangeText={onChange} onBlur={onBlur}
                  maxLength={2} autoCapitalize="characters"
                  outlineColor={colors.border} activeOutlineColor={colors.primary}
                  style={styles.input} contentStyle={styles.inputContent} />
              )} />
          </Campo>
        </Secao>

        <TouchableOpacity
          style={[styles.btnSalvar, salvando && styles.btnDisabled]}
          onPress={handleSubmit(onSubmit)}
          disabled={salvando}
          activeOpacity={0.85}
        >
          {salvando
            ? <ActivityIndicator color={colors.white} />
            : <Text style={styles.btnLabel}>Salvar alterações</Text>}
        </TouchableOpacity>
      </ScrollView>

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

function Secao({ titulo, children }: { titulo: string; children: React.ReactNode }) {
  return (
    <View style={styles.secao}>
      <Text style={styles.secaoTitulo}>{titulo}</Text>
      {children}
    </View>
  );
}

function Campo({ label, children, error }: { label: string; children: React.ReactNode; error?: string }) {
  return (
    <View style={styles.campo}>
      <Text style={styles.campoLabel}>{label}</Text>
      {children}
      {error && <Text style={styles.campoErro}>{error}</Text>}
    </View>
  );
}

const styles = StyleSheet.create({
  root: { flex: 1, backgroundColor: colors.bgMuted },
  scroll: { padding: spacing.md },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  fotoArea: { alignItems: 'center', marginBottom: spacing.md },
  fotoLabel: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.sm, color: colors.primary, marginTop: spacing.xs, textDecorationLine: 'underline' },
  secao: { backgroundColor: colors.bg, borderRadius: 12, padding: spacing.md, marginBottom: spacing.md },
  secaoTitulo: {
    fontFamily: typography.fontFamily.titleBold,
    fontSize: typography.fontSize.md,
    color: colors.text,
    marginBottom: spacing.sm,
    borderLeftWidth: 3,
    borderLeftColor: colors.primary,
    paddingLeft: spacing.sm,
  },
  campo: { marginBottom: spacing.sm },
  campoLabel: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.sm, color: colors.secondary, marginBottom: 4 },
  campoErro: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.xs, color: colors.error, marginTop: 2 },
  input: { backgroundColor: colors.bg, height: 48 },
  inputContent: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.md },
  maskInput: {
    fontFamily: typography.fontFamily.body,
    fontSize: typography.fontSize.md,
    color: colors.text,
    borderWidth: 1,
    borderColor: colors.border,
    borderRadius: 4,
    paddingHorizontal: spacing.md,
    paddingVertical: spacing.sm,
    backgroundColor: colors.bg,
  },
  btnSalvar: { backgroundColor: colors.primary, borderRadius: 10, paddingVertical: spacing.md, alignItems: 'center', marginBottom: spacing.xl },
  btnDisabled: { backgroundColor: colors.border },
  btnLabel: { fontFamily: typography.fontFamily.bodyBold, fontSize: typography.fontSize.md, color: colors.white },
});
