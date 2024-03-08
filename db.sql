DROP TABLE IF EXISTS utenti;
DROP TABLE IF EXISTS recnsioni;
DROP TABLE IF EXISTS sogni;
DROP TABLE IF EXISTS acquisti;
DROP TABLE IF EXISTS chipOrders;
DROP TABLE IF EXISTS scissionOrders;


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
    'Surreale/Spirituale',
);

CREATE TABLE utenti (
  user_name VARCHAR(20) NOT NULL,
  password VARCHAR(20) NOT NULL,

  PRIMARY KEY (user_name)
);

INSERT INTO utenti(user_name, password) VALUES
    ('admin', 'admin'),
    ('user', 'user');

CREATE TABLE recnsioni (
  id BIGINT(20) AUTO_INCREMENT PRIMARY KEY 
  user VARCHAR(20) NOT NULL,
  testo VARCHAR(256) NOT NULL,
  sogno VARCHAR(20),
  data_ins TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (user) REFERENCES utenti(user_name) ON DELETE CASCADE,
  FOREIGN KEY (sogno) REFERENCES sogni(titolo) ON DELETE CASCADE
);

CREATE TABLE  sogni(
  titolo VARCHAR(20) PRIMARY KEY,
  descrizione VARCHAR(256), 
  prezzo INT NOT NULL, 
  categoria Categorie, 
  path VARCHAR(20) NOT NULL
  data_ins TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
);

CREATE TABLE acquisti(
  user VARCHAR(20) NOT NULL,
  titolo VARCHAR(20) NOT NULL,
  PRIMARY KEY (user, titolo),

  FOREIGN KEY (user) REFERENCES utenti(user_name) ON DELETE CASCADE,
  FOREIGN KEY (sogno) REFERENCES sogni(titolo) ON DELETE CASCADE
);

CREATE TABLE chipOrders(
  user VARCHAR(20) PRIMARY KEY,
  nome VARCHAR(20) NOT NULL,
  cognome VARCHAR(20) NOT NULL,
  età INT NOT NULL,
  mail VARCHAR(20) NOT NULL,
  telefono VARCHAR(20),
  messaggio VARCHAR(256),

  FOREIGN KEY (user) REFERENCES utenti(user_name) ON DELETE CASCADE,
)

CREATE TABLE scissionOrders(
  user VARCHAR(20) PRIMARY KEY,
  nome VARCHAR(20) NOT NULL,
  cognome VARCHAR(20) NOT NULL,
  età INT NOT NULL,
  mail VARCHAR(20) NOT NULL,
  telefono VARCHAR(20),
  nome_azienda VARCHAR(32),
  indirizzo(128),
  messaggio VARCHAR(256),

  FOREIGN KEY (user) REFERENCES utenti(user_name) ON DELETE CASCADE,
)