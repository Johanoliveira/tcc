CREATE DATABASE IF NOT EXISTS form_empatico
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE form_empatico;

-- 1. Tabela de Alunos (exatamente como esperada pelo cadaluno.php)
CREATE TABLE IF NOT EXISTS alunos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome_completo VARCHAR(120) NOT NULL,
  bi VARCHAR(30) NOT NULL UNIQUE,
  serie VARCHAR(20) NOT NULL,
  curso VARCHAR(80) NOT NULL,
  email_institucional VARCHAR(120) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  data_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- 2. Tabela da Equipe Sociopedagógica (baseada no cadequipe.html e loginequipe.html)
CREATE TABLE IF NOT EXISTS equipe (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome_completo VARCHAR(120) NOT NULL,
  cargo VARCHAR(80) NOT NULL,
  usuario VARCHAR(60) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  data_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- 3. Tabela de Relatos dos Alunos (baseada no relatoaluno.html, relatos.html e meusrelatos.html)
CREATE TABLE IF NOT EXISTS relatos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_aluno INT NOT NULL,
  texto_relato TEXT NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'Novo relato',
  atualizacao_equipe TEXT NULL,
  atendimento_presencial TINYINT(1) NOT NULL DEFAULT 0,
  data_envio TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_aluno) REFERENCES alunos(id) ON DELETE CASCADE
);

-- 4. Tabela de Acompanhamentos e Relatórios (baseada no acompanhamento.html e relatorios.html)
CREATE TABLE IF NOT EXISTS acompanhamentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_relato INT NOT NULL,
  id_aluno INT NOT NULL,
  id_equipe INT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'Em andamento',
  situacao_atual TEXT NOT NULL,
  resultado_final TEXT NULL,
  data_inicio DATE NOT NULL,
  ultimo_atendimento DATE NULL,
  proximo_atendimento DATE NULL,
  data_conclusao DATE NULL,
  FOREIGN KEY (id_relato) REFERENCES relatos(id) ON DELETE CASCADE,
  FOREIGN KEY (id_aluno) REFERENCES alunos(id) ON DELETE CASCADE,
  FOREIGN KEY (id_equipe) REFERENCES equipe(id) ON DELETE SET NULL
);