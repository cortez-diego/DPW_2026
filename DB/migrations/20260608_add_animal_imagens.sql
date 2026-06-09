-- Tabela para armazenar até 5 imagens por animal (1 principal + 4 adicionais)
CREATE TABLE IF NOT EXISTS animal_imagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fk_animal_id INT NOT NULL,
    caminho_imagem VARCHAR(255) NOT NULL,
    ordem INT NOT NULL DEFAULT 0, -- 0 = principal, 1-4 = adicionais
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (fk_animal_id) REFERENCES animal(id) ON DELETE CASCADE,
    UNIQUE KEY uk_animal_ordem (fk_animal_id, ordem)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
