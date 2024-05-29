DROP TABLE IF EXISTS recensioni;
DROP TABLE IF EXISTS acquisti;
DROP TABLE IF EXISTS chipOrders;
DROP TABLE IF EXISTS scissionOrders;
DROP TABLE IF EXISTS sogni;
DROP TABLE IF EXISTS prenotazioni;
DROP TABLE IF EXISTS utenti;
DROP TABLE IF EXISTS categorie;
DROP TABLE IF EXISTS assistenza;

-- Utenti
CREATE TABLE utenti (
  user_name VARCHAR(20) NOT NULL,
  password VARCHAR(20) NOT NULL,

  PRIMARY KEY (user_name)
);

INSERT INTO utenti(user_name, password) VALUES
  ('admin', 'admin'),
  ('user', 'user');


-- Sogni
CREATE TABLE sogni(
  titolo VARCHAR(50) PRIMARY KEY,
  descrizione VARCHAR(256), 
  prezzo INT NOT NULL, 
  nomeFile VARCHAR(256) NOT NULL,
  data_ins TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  categoria VARCHAR(20)
);

INSERT INTO sogni(titolo, descrizione, prezzo, nomeFile, categoria) VALUES
  ('Sogno 1', 'Il primo sogno di prova', 23, 'sogno1.jpg', 'Avventura'),
  ('Sogno 2', 'Il secondo sogno di prova', 123, 'sogno2.png', 'Avventura');


-- Acquisti
CREATE TABLE acquisti(
  user_name VARCHAR(20) NOT NULL,
  articolo VARCHAR(20) NOT NULL,
  data DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_name, articolo),

  FOREIGN KEY (user_name) REFERENCES utenti(user_name) ON DELETE CASCADE  ON UPDATE CASCADE,
  FOREIGN KEY (articolo) REFERENCES sogni(titolo) ON DELETE CASCADE  ON UPDATE CASCADE
);

-- Recensioni
CREATE TABLE recensioni (
  id SERIAL PRIMARY KEY,
  user_name VARCHAR(20) NOT NULL,
  testo TEXT NOT NULL,
  articolo VARCHAR(20),
  stelle INT CHECK (stelle >= 1 AND stelle <= 5),
  data TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (user_name) REFERENCES utenti(user_name) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (articolo) REFERENCES sogni(titolo) ON DELETE CASCADE  ON UPDATE CASCADE
);

INSERT INTO recensioni(user_name, testo, articolo, stelle) VALUES 
  ('user', 'Recensione di prova', 'Sogno 1', '5'),
  ('user', 'No vabbe bellissimo !!! Incredibile !!!', 'Sogno 1', '4');


CREATE TABLE prenotazioni (
  data DATE PRIMARY KEY,
  user_name VARCHAR(20),

  FOREIGN KEY (user_name) REFERENCES utenti(user_name) ON DELETE CASCADE  ON UPDATE CASCADE
);

CREATE TABLE assistenza(
  data_ins TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email VARCHAR(256),
  motivo VARCHAR(20),
  descrizione VARCHAR(256),
  PRIMARY KEY (email, descrizione)
);

CREATE TABLE categorie(
  nome VARCHAR(20) PRIMARY KEY
);

INSERT INTO categorie(nome) VALUES
('Avventura'),
 ('Fantasia'),
  ('Romantico'),
  ('Horror'),
  ('Fantascienza'),
  ('Mistero'),
  ('Storico'),
  ('Azione'),
  ('Commedia'),
  ('Drammatico'),
  ('Documentario'),
  ('Educativo'),
  ('Surreale/Spirituale');


INSERT INTO prenotazioni (data, user_name) VALUES
('2024-05-31', NULL),

('2024-06-03', NULL),
('2024-06-04', 'user'),
('2024-06-05', NULL),
('2024-06-06', NULL),
('2024-06-07', NULL),
('2024-06-10', 'user'),
('2024-06-11', NULL),
('2024-06-12', NULL),
('2024-06-13', NULL),
('2024-06-14', NULL),
('2024-06-17', NULL),
('2024-06-18', NULL),
('2024-06-19', NULL),
('2024-06-20', NULL),
('2024-06-21', NULL),
('2024-06-24', NULL),
('2024-06-25', NULL),
('2024-06-26', NULL),
('2024-06-27', NULL),
('2024-06-28', NULL),

('2024-07-01', NULL),
('2024-07-02', NULL),
('2024-07-03', 'user'),
('2024-07-04', NULL),
('2024-07-05', NULL),
('2024-07-08', NULL),
('2024-07-09', NULL),
('2024-07-10', NULL),
('2024-07-11', NULL),
('2024-07-12', NULL),
('2024-07-15', NULL),
('2024-07-16', NULL),
('2024-07-17', NULL),
('2024-07-18', NULL),
('2024-07-19', NULL),
('2024-07-22', NULL),
('2024-07-23', NULL),
('2024-07-24', NULL),
('2024-07-25', NULL),
('2024-07-26', NULL),
('2024-07-29', NULL),
('2024-07-30', NULL),
('2024-07-31', NULL),

('2024-08-01', NULL),
('2024-08-02', NULL),
('2024-08-05', NULL),
('2024-08-06', NULL),
('2024-08-07', NULL),
('2024-08-08', 'user'),
('2024-08-09', NULL),
('2024-08-12', NULL),
('2024-08-13', NULL),
('2024-08-14', NULL),
('2024-08-15', NULL),
('2024-08-16', NULL),
('2024-08-19', NULL),
('2024-08-20', NULL),
('2024-08-21', NULL),
('2024-08-22', NULL),
('2024-08-23', NULL),
('2024-08-26', NULL),
('2024-08-27', NULL),
('2024-08-28', NULL),
('2024-08-29', NULL),
('2024-08-30', NULL),


('2024-09-02', NULL),
('2024-09-03', NULL),
('2024-09-04', NULL),
('2024-09-05', 'user'),
('2024-09-06', NULL),
('2024-09-09', NULL),
('2024-09-10', NULL),
('2024-09-11', NULL),
('2024-09-12', NULL),
('2024-09-13', NULL),
('2024-09-16', NULL),
('2024-09-17', NULL),
('2024-09-18', NULL),
('2024-09-19', NULL),
('2024-09-20', NULL),
('2024-09-23', NULL),
('2024-09-24', NULL),
('2024-09-25', NULL),
('2024-09-26', NULL),
('2024-09-27', NULL);


/*
-- Ordini dei chip
CREATE TABLE chipOrders(
  user_name VARCHAR(20) PRIMARY KEY,
  nome VARCHAR(20) NOT NULL,
  cognome VARCHAR(20) NOT NULL,
  eta INT NOT NULL,
  mail VARCHAR(20) NOT NULL,
  telefono VARCHAR(20),
  messaggio VARCHAR(256),

  FOREIGN KEY (user_name) REFERENCES utenti(user_name) ON DELETE CASCADE
);
*/