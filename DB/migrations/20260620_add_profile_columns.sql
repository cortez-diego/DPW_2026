-- Migration to add avatar and bio columns to user tables
-- Date: 2026-06-20

-- Add avatar and bio columns to adotante table
ALTER TABLE adotante 
ADD COLUMN avatar VARCHAR(255) NULL AFTER logradouro,
ADD COLUMN bio TEXT NULL AFTER avatar;

-- Add avatar and bio columns to administrador table
ALTER TABLE administrador 
ADD COLUMN avatar VARCHAR(255) NULL AFTER telefone_2,
ADD COLUMN bio TEXT NULL AFTER avatar;

-- Add avatar and bio columns to veterinario table
ALTER TABLE veterinario 
ADD COLUMN avatar VARCHAR(255) NULL AFTER complemento,
ADD COLUMN bio TEXT NULL AFTER avatar;

-- Add avatar and bio columns to ong table
ALTER TABLE ong 
ADD COLUMN avatar VARCHAR(255) NULL AFTER logradouro,
ADD COLUMN bio TEXT NULL AFTER avatar;

-- Add avatar and bio columns to rastreador table
ALTER TABLE rastreador 
ADD COLUMN avatar VARCHAR(255) NULL AFTER complemento,
ADD COLUMN bio TEXT NULL AFTER avatar;
