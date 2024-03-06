DROP TABLE IF EXISTS utenti;

CREATE TABLE utenti (
  user_name VARCHAR(20) NOT NULL,
  password VARCHAR(20) NOT NULL,

  PRIMARY KEY (user_name)
);

INSERT INTO utenti(user_name, password) VALUES
    ('admin', 'admin'),
    ('user', 'user');