CREATE DATABASE IF NOT EXISTS urbanline_banco;
use urbanline_banco;
-- ROLES

CREATE TABLE IF NOT EXISTS roles (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome VARCHAR(50) NOT NULL UNIQUE,
  PRIMARY KEY(id)
);

INSERT INTO roles (nome) VALUES ('cliente'), ('proprietario'), ('admin');

-- PERMISSÕES

CREATE TABLE IF NOT EXISTS permissoes (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL UNIQUE,
  descricao VARCHAR(255),
  PRIMARY KEY(id)
);

-- Exemplo de permissões
INSERT INTO permissoes (nome, descricao) VALUES
('ver_imoveis', 'Pode visualizar imóveis'),
('cadastrar_imovel', 'Pode cadastrar novos imóveis'),
('editar_imovel', 'Pode editar imóveis'),
('deletar_imovel', 'Pode deletar imóveis'),
('gerenciar_usuarios', 'Pode criar, editar e excluir usuários');

-- ========================================
-- 4. RELACIONAR PERMISSÕES COM ROLES
-- ========================================
CREATE TABLE IF NOT EXISTS role_permissoes (
  role_id INT UNSIGNED NOT NULL,
  permissao_id INT UNSIGNED NOT NULL,
  PRIMARY KEY(role_id, permissao_id),
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (permissao_id) REFERENCES permissoes(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Cliente só vê imóveis
INSERT INTO role_permissoes (role_id, permissao_id)
SELECT r.id, p.id
FROM roles r, permissoes p
WHERE r.nome = 'cliente' AND p.nome = 'ver_imoveis';

-- Admin pode tudo
INSERT INTO role_permissoes (role_id, permissao_id)
SELECT r.id, p.id
FROM roles r, permissoes p
WHERE r.nome = 'admin';

-- ENDEREÇO

CREATE TABLE IF NOT EXISTS endereco(
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  cep VARCHAR(20) NOT NULL,
  uf CHAR(2) NOT NULL,
  cidade VARCHAR(100) NOT NULL,
  bairro VARCHAR(100) NOT NULL,
  logradouro VARCHAR(100) NOT NULL,
  numero VARCHAR(15),
  PRIMARY KEY(id)
);

-- USUÁRIOS E MENSAGENS

CREATE TABLE IF NOT EXISTS usuarios (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(200) NOT NULL UNIQUE,
  senha VARCHAR(255) DEFAULT NULL,
  telefone VARCHAR(20),
  cpf VARCHAR(14) UNIQUE,
  foto_url VARCHAR(255),
  role_id INT UNSIGNED NOT NULL DEFAULT 1,
  endereco_id INT UNSIGNED DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY(id),
  FOREIGN KEY (endereco_id) REFERENCES endereco(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS mensagens (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  remetente_id INT UNSIGNED NULL,        -- quem enviou a notificação (NULL se automático)
  destinatario_id INT UNSIGNED NOT NULL, -- quem recebe
  titulo VARCHAR(255) NOT NULL,
  mensagem TEXT NOT NULL,
  link VARCHAR(255),
  lida BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (remetente_id) REFERENCES usuarios(id) ON DELETE SET NULL,
  FOREIGN KEY (destinatario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS password_resets (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id INT UNSIGNED NOT NULL,
  token VARCHAR(255) NOT NULL UNIQUE,
  expire_at TIMESTAMP NOT NULL,
  usado BOOLEAN DEFAULT FALSE,
  PRIMARY KEY(id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- IMÓVEIS

CREATE TABLE IF NOT EXISTS imoveis (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL,
  usuario_id INT UNSIGNED NOT NULL,
  endereco_id INT UNSIGNED NOT NULL,
  tipo_imovel ENUM('casa', 'apartamento', 'kitnet', 'sobrado', 'terreno', 'comercial', 'cobertura', 'galpao', 'chacara') DEFAULT 'casa',
  valor DECIMAL(15,2) NOT NULL DEFAULT 0,
  area DECIMAL(8,2) NOT NULL DEFAULT 0,
  descricao TEXT,
  condicao ENUM('novo', 'usado', 'em_construcao') DEFAULT 'usado',
  quant_quartos TINYINT UNSIGNED NOT NULL DEFAULT 0,
  quant_suites TINYINT UNSIGNED NOT NULL DEFAULT 0,
  quant_cozinhas TINYINT UNSIGNED NOT NULL DEFAULT 0,
  quant_banheiros TINYINT UNSIGNED NOT NULL DEFAULT 0,
  quant_piscinas TINYINT UNSIGNED NOT NULL DEFAULT 0,
  vagas_garagem TINYINT UNSIGNED NOT NULL DEFAULT 0,
  status ENUM('disponivel', 'alugado', 'vendido') DEFAULT 'disponivel',
  mobiliado BOOLEAN DEFAULT FALSE,
  data_cad TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY(id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (endereco_id) REFERENCES endereco(id) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX (quant_quartos),
  INDEX (quant_suites),
  INDEX (valor),
  INDEX (area),
  INDEX (status),
  INDEX (tipo_imovel)
);

-- FOTOS DOS IMÓVEIS

CREATE TABLE IF NOT EXISTS imovel_fotos (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  imovel_id INT UNSIGNED NOT NULL,
  url VARCHAR(255) NOT NULL,
  destaque BOOLEAN DEFAULT FALSE,
  PRIMARY KEY (id),
  FOREIGN KEY (imovel_id) REFERENCES imoveis(id) ON DELETE CASCADE ON UPDATE CASCADE
);