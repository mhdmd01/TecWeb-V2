DROP TABLE IF EXISTS recensioni;
DROP TABLE IF EXISTS acquisti;
DROP TABLE IF EXISTS chipOrders;
DROP TABLE IF EXISTS scissionOrders;
DROP TABLE IF EXISTS sogni;
DROP TABLE IF EXISTS utenti;


/*
CREATE TYPE Categorie AS ENUM (
  'Avventura',
  'Fantasia',
  'Romantico',
  'Horror',
  'Fantascienza',
  'Mistero',
  'Storico',
  'Azione',
  'Commedia',
  'Drammatico',
  'Documentario',
  'Educativo',
  'Surreale/Spirituale'
);
*/
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
  titolo VARCHAR(20) PRIMARY KEY,
  descrizione VARCHAR(256), 
  prezzo INT NOT NULL, 
  estensioneFile VARCHAR(20) NOT NULL,
  data_ins TIMESTAMP DEFAULT CURRENT_TIMESTAMP

  /*categoria Categorie, */
);

INSERT INTO sogni(titolo, descrizione, prezzo, estensioneFile) VALUES
  ('Sogno 1', 'Il primo sogno di prova', 23, 'jpg'),
  ('Sogno 2', 'Il secondo sogno di prova', 123, 'png');


-- Acquisti
CREATE TABLE acquisti(
  user_name VARCHAR(20) NOT NULL,
  articolo VARCHAR(20) NOT NULL,
  data DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_name, articolo),

  FOREIGN KEY (user_name) REFERENCES utenti(user_name) ON DELETE CASCADE,
  FOREIGN KEY (articolo) REFERENCES sogni(titolo) ON DELETE CASCADE
);

-- Recensioni
CREATE TABLE recensioni (
  id SERIAL PRIMARY KEY,
  user_name VARCHAR(20) NOT NULL,
  testo VARCHAR(256) NOT NULL,
  sogno VARCHAR(20),
  data_ins TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (user_name) REFERENCES utenti(user_name) ON DELETE CASCADE,
  FOREIGN KEY (sogno) REFERENCES sogni(titolo) ON DELETE CASCADE
);

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


-- Ordini scission
CREATE TABLE scissionOrders(
  user_name VARCHAR(20) PRIMARY KEY,
  nome VARCHAR(20) NOT NULL,
  cognome VARCHAR(20) NOT NULL,
  eta INT NOT NULL,
  mail VARCHAR(20) NOT NULL,
  telefono VARCHAR(20),
  nome_azienda VARCHAR(32),
  indirizzo VARCHAR(128),
  messaggio VARCHAR(256),

  FOREIGN KEY (user_name) REFERENCES utenti(user_name) ON DELETE CASCADE
);
*/