CREATE DATABASE sustentabilidade_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sustentabilidade_db;

CREATE TABLE respostas_pessoais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    genero VARCHAR(50),
    zona VARCHAR(50),
    pessoas VARCHAR(50),
    idade VARCHAR(50),
    escolaridade VARCHAR(50),
    estudo_tipo VARCHAR(50),
    trabalho VARCHAR(50),
    data_resposta TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE respostas_sustentabilidade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    resposta_id INT,
    pergunta_numero INT,
    resposta VARCHAR(255),
    FOREIGN KEY (resposta_id) REFERENCES respostas_pessoais(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
