import React from 'react';
import { View, Text, ScrollView, StyleSheet, TouchableOpacity } from 'react-native';
import { NativeStackScreenProps } from '@react-navigation/native-stack';
import { HomeStackParamList } from '../../navigation/stacks/HomeStack';
import { colors } from '../../theme/colors';
import { typography } from '../../theme/typography';
import { spacing } from '../../theme/spacing';

type Props = NativeStackScreenProps<HomeStackParamList, 'TermoResponsabilidade'>;

const TEXTO_TERMO = `TERMO DE RESPONSABILIDADE PARA ADOÇÃO DE ANIMAL

Eu, adotante identificado(a) no sistema AmigoPet, declaro que:

1. COMPROMISSO COM O BEM-ESTAR ANIMAL
Comprometo-me a oferecer ao animal adotado condições dignas de vida, incluindo alimentação adequada, água fresca disponível, abrigo seguro, atendimento veterinário regular e carinho.

2. VEDAÇÃO AO ABANDONO
Estou ciente de que o abandono de animais é crime previsto na Lei Federal nº 9.605/1998 (Lei de Crimes Ambientais), sujeito a pena de detenção de três meses a um ano, e multa.

3. PROIBIÇÃO DE MAUS-TRATOS
Comprometo-me a não praticar nenhum ato de crueldade, violência ou negligência contra o animal, conforme vedado pela mesma lei.

4. DEVOLUÇÃO RESPONSÁVEL
Caso não seja possível manter a adoção, comprometo-me a contatar a ONG responsável antes de qualquer outra medida, para que o animal seja reintegrado ao sistema de adoção com segurança.

5. AUTORIZAÇÃO DE VISITAS
Autorizo a ONG responsável a realizar visitas de acompanhamento ao animal adotado, mediante agendamento prévio.

6. RESPONSABILIDADE CIVIL
Reconheço que sou responsável por quaisquer danos causados pelo animal a terceiros após a adoção.

7. VERACIDADE DAS INFORMAÇÕES
Declaro que todas as informações prestadas no cadastro são verdadeiras, e estou ciente de que informações falsas poderão implicar no cancelamento da adoção.

Ao aceitar este termo no aplicativo AmigoPet, expresso meu pleno entendimento e concordância com todas as cláusulas acima.`;

export function TermoResponsabilidadeScreen({ navigation }: Props) {
  return (
    <View style={styles.root}>
      <ScrollView contentContainerStyle={styles.scroll}>
        <Text style={styles.texto}>{TEXTO_TERMO}</Text>
      </ScrollView>
      <View style={styles.footer}>
        <TouchableOpacity style={styles.btn} onPress={() => navigation.goBack()} activeOpacity={0.85}>
          <Text style={styles.btnLabel}>Fechar e Voltar</Text>
        </TouchableOpacity>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  root: { flex: 1, backgroundColor: colors.bg },
  scroll: { padding: spacing.lg },
  texto: {
    fontFamily: typography.fontFamily.body,
    fontSize: typography.fontSize.sm,
    color: colors.text,
    lineHeight: 22,
  },
  footer: { padding: spacing.md, borderTopWidth: 1, borderTopColor: colors.border },
  btn: { backgroundColor: colors.primary, borderRadius: 10, paddingVertical: spacing.md, alignItems: 'center' },
  btnLabel: { fontFamily: typography.fontFamily.bodyBold, fontSize: typography.fontSize.md, color: colors.white },
});
