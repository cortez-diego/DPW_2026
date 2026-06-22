-- Alter chamado table to make fk_ong_id nullable
ALTER TABLE chamado MODIFY COLUMN fk_ong_id INT(11) NULL;
