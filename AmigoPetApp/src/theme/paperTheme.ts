import { MD3LightTheme } from 'react-native-paper';
import { colors } from './colors';

export const paperTheme = {
  ...MD3LightTheme,
  colors: {
    ...MD3LightTheme.colors,
    primary: colors.primary,
    secondary: colors.secondary,
    background: colors.bg,
    surface: colors.bg,
    surfaceVariant: colors.bgMuted,
    error: colors.error,
    onPrimary: colors.white,
    onSecondary: colors.white,
  },
};
