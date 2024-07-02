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
('Il pazzo veliero del capitano', 'Vivi questa avventura a bordo di un grande veliero in giro per l’america del 1700. Sarai tu il capitano e dovrai gestire questa ciurma. Il tuo obiettivo è quello di esplorare nuove terre e nuovi mari in cerca di un grande tesoro lasciato in eredità dal re dei pirati.', 50, 'veliero.jpg', 'Avventura'),
('Impara le arti marziali', 'In questo sogno dovrai affrontare diversi agenti vestiti di nero sotto la pioggia a suoni di pugni. È disponibile anche una modalità allenamento per imparare al meglio tutte le arti marziali.', 40, 'arti_marziali.jpg', 'Azione'),
('I valzer Viennesi', 'In questo sogno puoi ballare nei valzer viennesi e ascoltare storie divertente come si usava fare nei salotti viennesi.', 30, 'valzer.jpg', 'Comico'),
('La vita di un Lamantino', 'In questo sogno puoi simularti in un lamantino per vivere la sua vita dal suo punto di vista. Questo documentario offre un’esperienza del tutto nuova sulla prospettiva di un animale, fornisce anche dei filtri per simulare la visione e la percezione dell’animale.', 45, 'lamantino.jpg', 'Documentario'),
('Parola Chiave', 'Vieni invitato in una villa insieme ad un gruppo di amici. Una volta entrati nella villa le porte vengono blindate dall’esterno e rimanete tutti intrappolati per tutta la notte. In questo lasso di tempo tocca trovare una via di fuga. Ciò che fa più inquietudine è che per ogni ostacolo ci sarà una rivelazione… Sarai ancora in grado di fidarti dei tuoi amici e superare i vari traumi.', 60, 'parola_chiave.jpg', 'Drammatico'),
('Come funzionano i motori di ricerca?', 'In questo sogno assisterai ad un corso universitario che ti insegnerà il funzionamento delle Tecnologie dietro i motori di ricerca più famosi.', 35, 'motori_di_ricerca.jpg', 'Educativo'),
('Ricomincia', 'Sei stato selezionato per un collaudo di un esperimento di Saudade. A quanto pare l’azienda ha inventato questo orologio che permette di tornare indietro nel tempo di 1 minuto. Riuscirai ad utilizzarlo correttamente senza rompere lo spazio tempo.', 55, 'ricomincia.jpg', 'Fantascienza'),
('Simulatore di Magia', 'In questo sogno puoi sperimentare alcuni incantesimi proposti dal team saudade su ambienti reali e simulati. Inoltre è presente una modalità allenamento per diventare il miglior mago in circolazione.', 50, 'magia.jpg', 'Fantasia'),
('Ci scommettiamo la vita', 'Hai trovato un vecchio dischetto nella cantina del nonno, inserendolo nel tuo pc prendi la scossa e ti risvegli in mondo digitale dove dei robot ti sfidano per ottenere la tua anima. Se vuoi sopravvivere allora inizia vincendo giochi di carte e la roulette russa contro robot assassini.', 65, 'scommessa.jpg', 'Horror'),
('Iscrizione', 'Trovi su una parete delle cordinate che potrebbero rappresentare qualcosa di molto molto grande. Ad ogni nuova scoperta se ne sussegue un altra dilettati quindi a risolvere ogni singolo puzzle per giungere alla conclusione o alla torta… se esiste.', 45, 'iscrizione.jpg', 'Mistero'),
('I treni per Vienna', 'In questo sogno incontri l’amore della tua vita nel vagone vicino al tuo, vivi un avventura di una settimana in un vecchio treno diretto per la meravigliosa città europea esplorando la bellissima nazione dell’austria insieme alla tua anima gemella.', 70, 'treni_vienna.jpg', 'Romantico'),
('Vivi il medioevo', 'In questo sogno puoi simulare di essere un RE, un Nobile o un plebeo. Cammina per le nostre ricostruzione storiche di città medievale e sentiti come una persona che viveva nel 1300.', 60, 'medioevo.jpg', 'Storico'),
('La caduta', 'In questo sogno immagini di cadere… Solo che la caduta è infinita e tanto lunga che prima di schiantarti a terra avrai tempo per riflettere sulle tue scelte di vita e riflettere sui tuoi errori. Infatti i nostri studi dimostrano che per una riflessione migliore c’è bisogno di un adrenalina molto molto alta.', 55, 'caduta.jpg', 'Surreale/Spirituale');



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

-- INSERT INTO recensioni(user_name, testo, articolo, stelle) VALUES  ;


CREATE TABLE prenotazioni (
  data DATE PRIMARY KEY,
  user_name VARCHAR(20),
  FOREIGN KEY (user_name) REFERENCES utenti(user_name) ON DELETE CASCADE ON UPDATE CASCADE,
  UNIQUE (user_name)
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
('2024-08-08', NULL),
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
('2024-09-05', NULL),
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