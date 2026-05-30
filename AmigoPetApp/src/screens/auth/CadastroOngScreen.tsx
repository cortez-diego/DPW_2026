import React, { useState } from 'react';
import {
  View, Text, ScrollView, StyleSheet, TouchableOpacity, ActivityIndicator,
} from 'react-native';
import { TextInput, Snackbar } from 'react-native-paper';
import MaskInput from 'react-native-mask-input';
import { useForm, Controller } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { useAuth } from '../../hooks/useAuth';
import { senhaSchemaZod, validarCNPJ } from '../../utils/validators';
import { colors } from '../../theme/colors';
import { typography } from '../../theme/typography';
import { spacing } from '../../theme/spacing';

const CNPJ_MASK = [
  /\d/,/\d/,'.',/\d/,/\d/,/\d/,'.',/\d/,/\d/,/\d/,'/',/\d/,/\d/,/\d/,/\d/,'-',/\d/,/\d/,
];
const PHONE_MASK = ['(', /\d/, /\d/, ')', ' ', /\d/, /\d/, /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\d/];
const CEP_MASK = [/\d/, /\d/, /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/];

const schema = z.object({
  nome: z.string().min(3, 'Nome deve ter ao menos 3 caracteres'),
  cnpj: z.string().refine(v => validarCNPJ(v), 'CNPJ inválido'),
  email: z.string().email('E-mail inválido'),
  senha: senhaSchemaZod,
  confirmar_senha: z.string().min(1, 'Confirme a senha'),
  telefone_1: z.string().refine(v => v.replace(/\D/g, '').length >= 10, 'Telefone inválido'),
  telefone_2: z.string().optional(),
  cep: z.string().refine(v => v.replace(/\D/g, '').length === 8, 'CEP inválido'),
  logradouro: z.string().min(1, 'Obrigatório'),
  numero: z.string().min(1, 'Obrigatório'),
  bairro: z.string().min(1, 'Obrigatório'),
  complemento: z.string().optional(),
  cidade: z.string().min(1, 'Obrigatório'),
  estado: z.string().min(2, 'Obrigatório'),
}).superRefine((v, ctx) => {
  if (v.confirmar_senha !== v.senha) {
    ctx.addIssue({ code: z.ZodIssueCode.custom, message: 'As senhas não coincidem', path: ['confirmar_senha'] });
  }
});

type FormData = z.infer<typeof schema>;

export function CadastroOngScreen() {
  const { cadastrarOng } = useAuth();
  const [enviando, setEnviando] = useState(false);
  const [buscandoCep, setBuscandoCep] = useState(false);
  const [snack, setSnack] = useState({ visible: false, msg: '' });

  const { control, handleSubmit, setValue, formState: { errors } } = useForm<FormData>({
    resolver: zodResolver(schema),
    defaultValues: {
      nome: '', cnpj: '', email: '', senha: '', confirmar_senha: '',
      telefone_1: '', telefone_2: '', cep: '', logradouro: '', numero: '',
      bairro: '', complemento: '', cidade: '', estado: '',
    },
  });

  async function buscarCep(cepMasked: string) {
    const cep = cepMasked.replace(/\D/g, '');
    if (cep.length !== 8) return;
    setBuscandoCep(true);
    try {
      const res = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
      const json = await res.json();
      if (json.erro) return;
      setValue('logradouro', json.logradouro ?? '');
      setValue('bairro', json.bairro ?? '');
      setValue('cidade', json.localidade ?? '');
      setValue('estado', json.uf ?? '');
    } catch { /* ViaCEP indisponível */ } finally { setBuscandoCep(false); }
  }

  async function onSubmit(data: FormData) {
    setEnviando(true);
    try {
      await cadastrarOng({
        nome: data.nome,
        cnpj: data.cnpj.replace(/\D/g, ''),
        email: data.email,
        senha: data.senha,
        telefone_1: data.telefone_1.replace(/\D/g, ''),
        telefone_2: data.telefone_2?.replace(/\D/g, ''),
        cep: data.cep.replace(/\D/g, ''),
        logradouro: data.logradouro,
        numero: Number(data.numero),
        bairro: data.bairro,
        complemento: data.complemento,
        cidade: data.cidade,
        estado: data.estado,
      });
    } catch (err: any) {
      const msg = err?.response?.data?.mensagem ?? err?.message ?? 'Erro ao criar conta. Tente novamente.';
      setSnack({ visible: true, msg });
    } finally { setEnviando(false); }
  }

  return (
    <View style={styles.root}>
      <ScrollView contentContainerStyle={styles.scroll} keyboardShouldPersistTaps="handled">
        <Text style={styles.heading}>Cadastro de ONG</Text>
        <Text style={styles.sub}>Registre sua organização para publicar animais para adoção.</Text>

        <Secao titulo="Dados da organização">
          <Campo label="Nome da ONG" error={errors.nome?.message}>
            <Controller control={control} name="nome"
              render={({ field: { onChange, onBlur, value } }) => (
                <TextInput mode="outlined" value={value} onChangeText={onChange} onBlur={onBlur}
                  autoCapitalize="words" outlineColor={colors.border} activeOutlineColor={colors.primary}
                  style={styles.input} contentStyle={styles.inputContent} />
              )} />
          </Campo>

          <Campo label="CNPJ" error={errors.cnpj?.message}>
            <Controller control={control} name="cnpj"
              render={({ field: { onChange, value } }) => (
                <MaskInput value={value} onChangeText={onChange} mask={CNPJ_MASK}
                  style={styles.maskInput} keyboardType="numeric" />
              )} />
          </Campo>

          <Campo label="E-mail" error={errors.email?.message}>
            <Controller control={control} name="email"
              render={({ field: { onChange, onBlur, value } }) => (
                <TextInput mode="outlined" value={value} onChangeText={onChange} onBlur={onBlur}
                  keyboardType="email-address" autoCapitalize="none"
                  outlineColor={colors.border} activeOutlineColor={colors.primary}
                  style={styles.input} contentStyle={styles.inputContent} />
              )} />
          </Campo>

          <Campo label="Senha" error={errors.senha?.message}>
            <Controller control={control} name="senha"
              render={({ field: { onChange, onBlur, value } }) => (
                <TextInput mode="outlined" value={value} onChangeText={onChange} onBlur={onBlur}
                  secureTextEntry autoComplete="new-password"
                  outlineColor={colors.border} activeOutlineColor={colors.primary}
                  style={styles.input} contentStyle={styles.inputContent} />
              )} />
          </Campo>

          <Campo label="Confirmar senha" error={errors.confirmar_senha?.message}>
            <Controller control={control} name="confirmar_senha"
              render={({ field: { onChange, onBlur, value } }) => (
                <TextInput mode="outlined" value={value} onChangeText={onChange} onBlur={onBlur}
                  secureTextEntry autoComplete="new-password"
                  outlineColor={colors.border} activeOutlineColor={colors.primary}
                  style={styles.input} contentStyle={styles.inputContent} />
              )} />
          </Campo>
        </Secao>

        <Secao titulo="Contato">
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
                <View>
                  <MaskInput value={value}
                    onChangeText={(m) => { onChange(m); buscarCep(m); }}
                    mask={CEP_MASK} style={styles.maskInput} keyboardType="numeric" />
                  {buscandoCep && <Text style={styles.hint}>Buscando endereço...</Text>}
                </View>
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
                  keyboardType="numeric" outlineColor={colors.border} activeOutlineColor={colors.primary}
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
          style={[styles.btnCriar, enviando && styles.btnDisabled]}
          onPress={handleSubmit(onSubmit)}
          disabled={enviando}
          activeOpacity={0.85}
        >
          {enviando
            ? <ActivityIndicator color={colors.white} />
            : <Text style={styles.btnLabel}>Criar conta</Text>}
        </TouchableOpacity>
      </ScrollView>

      <Snackbar
        visible={snack.visible}
        onDismiss={() => setSnack(s => ({ ...s, visible: false }))}
        duration={4000}
        style={{ backgroundColor: colors.error }}
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
  scroll: { padding: spacing.md, paddingBottom: spacing.xxl },
  heading: { fontFamily: typography.fontFamily.titleBold, fontSize: typography.fontSize.xxl, color: colors.text, marginBottom: 4 },
  sub: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.md, color: colors.secondary, marginBottom: spacing.lg },
  secao: { backgroundColor: colors.bg, borderRadius: 12, padding: spacing.md, marginBottom: spacing.md },
  secaoTitulo: {
    fontFamily: typography.fontFamily.titleBold, fontSize: typography.fontSize.md,
    color: colors.text, marginBottom: spacing.sm,
    borderLeftWidth: 3, borderLeftColor: colors.primary, paddingLeft: spacing.sm,
  },
  campo: { marginBottom: spacing.sm },
  campoLabel: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.sm, color: colors.secondary, marginBottom: 4 },
  campoErro: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.xs, color: colors.error, marginTop: 2 },
  input: { backgroundColor: colors.bg, height: 48 },
  inputContent: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.md },
  maskInput: {
    fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.md, color: colors.text,
    borderWidth: 1, borderColor: colors.border, borderRadius: 4,
    paddingHorizontal: spacing.md, paddingVertical: spacing.sm, backgroundColor: colors.bg,
  },
  hint: { fontFamily: typography.fontFamily.body, fontSize: typography.fontSize.xs, color: colors.secondary, marginTop: 2 },
  btnCriar: { backgroundColor: colors.primary, borderRadius: 10, paddingVertical: spacing.md, alignItems: 'center', marginTop: spacing.sm },
  btnDisabled: { backgroundColor: colors.border },
  btnLabel: { fontFamily: typography.fontFamily.bodyBold, fontSize: typography.fontSize.md, color: colors.white },
});
