import { z } from 'zod';

/** Valida CPF incluindo os dois dígitos verificadores. Aceita com ou sem máscara. */
export function cpfValido(cpf: string): boolean {
  const d = cpf.replace(/\D/g, '');
  if (d.length !== 11 || /^(\d)\1+$/.test(d)) return false;
  let sum = 0;
  for (let i = 0; i < 9; i++) sum += parseInt(d[i]) * (10 - i);
  let check = 11 - (sum % 11);
  if (check >= 10) check = 0;
  if (check !== parseInt(d[9])) return false;
  sum = 0;
  for (let i = 0; i < 10; i++) sum += parseInt(d[i]) * (11 - i);
  check = 11 - (sum % 11);
  if (check >= 10) check = 0;
  return check === parseInt(d[10]);
}

/**
 * Valida CNPJ incluindo os dois dígitos verificadores.
 * Aceita com ou sem máscara.
 */
export function validarCNPJ(cnpj: string): boolean {
  const n = cnpj.replace(/\D/g, '');
  if (n.length !== 14 || /^(\d)\1+$/.test(n)) return false;

  const calcDigito = (size: number): number => {
    let s = 0, pos = size - 7;
    for (let i = size; i >= 1; i--) {
      s += parseInt(n.charAt(size - i), 10) * pos--;
      if (pos < 2) pos = 9;
    }
    const rem = s % 11;
    return rem < 2 ? 0 : 11 - rem;
  };

  return (
    calcDigito(12) === parseInt(n.charAt(12), 10) &&
    calcDigito(13) === parseInt(n.charAt(13), 10)
  );
}

/** Valida força de senha. Retorna true ou string com mensagem de erro. */
export function senhaForte(senha: string): true | string {
  if (senha.length < 8) return 'Mínimo 8 caracteres';
  if (!/[a-zA-Z0-9]/.test(senha)) return 'Deve conter ao menos 1 letra ou número';
  if (!/[^a-zA-Z0-9\s]/.test(senha)) return 'Deve conter ao menos 1 caractere especial (!@#$%...)';
  return true;
}

/** Schema Zod reutilizável para campos de senha com todas as regras de RF#01. */
export const senhaSchemaZod = z
  .string()
  .min(8, 'Mínimo 8 caracteres')
  .refine(v => /[a-zA-Z0-9]/.test(v), 'Deve conter ao menos 1 letra ou número')
  .refine(v => /[^a-zA-Z0-9\s]/.test(v), 'Deve conter ao menos 1 caractere especial (!@#$%...)');
