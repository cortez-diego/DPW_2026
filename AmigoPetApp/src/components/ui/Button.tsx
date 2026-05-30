import React from 'react';
import { Button as PaperButton } from 'react-native-paper';
import type { ComponentProps } from 'react';
import { colors } from '../../theme/colors';
import { typography } from '../../theme/typography';

type Props = ComponentProps<typeof PaperButton>;

export function Button({ style, labelStyle, buttonColor, ...rest }: Props) {
  return (
    <PaperButton
      buttonColor={buttonColor ?? colors.primary}
      labelStyle={[{ fontFamily: typography.fontFamily.titleBold }, labelStyle]}
      style={[{ borderRadius: 8 }, style]}
      {...rest}
    />
  );
}
