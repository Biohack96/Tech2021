DROP DATABASE IF EXISTS sharearts;

CREATE DATABASE sharearts;

USE sharearts;

SET FOREIGN_KEY_CHECKS = 0; -- Disabilita check su vincoli di integrità referenziale

DROP TABLE IF EXISTS autore, categoria, opera, commento;

CREATE TABLE autore (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  password    VARCHAR(64) NOT NULL,
  username    VARCHAR(30) NOT NULL,
  bio         VARCHAR(2000),
  segnalato   BOOLEAN NOT NULL,
  isAdmin     BOOLEAN NOT NULL
);

CREATE TABLE categoria (
  id                 INT AUTO_INCREMENT PRIMARY KEY,
  nome_categoria     VARCHAR(200) NOT NULL
  
);

CREATE TABLE opera (
  id                 INT AUTO_INCREMENT PRIMARY KEY,
  titolo             VARCHAR(2000) NOT NULL,
  descrizione_short  VARCHAR(200) NOT NULL,
  descrizione        TEXT NOT NULL,
  data_creazione     VARCHAR(20) NOT NULL,
  id_autore          INT NOT NULL,
  id_categoria       INT NOT NULL,
  img_path           VARCHAR(256) NOT NULL,
  segnalata          BOOLEAN NOT NULL

  FOREIGN KEY (id_autore) REFERENCES autore(id),
  FOREIGN KEY (id_categoria) REFERENCES categoria(id)
);


CREATE TABLE commento (
  id                 INT AUTO_INCREMENT PRIMARY KEY,
  testo_commento     VARCHAR(2000) NOT NULL,
  id_opera           INT NOT NULL,
  id_autore          INT NOT NULL,

  FOREIGN KEY (id_opera) REFERENCES opera(id),
  FOREIGN KEY (id_autore) REFERENCES autore(id)
);


SET FOREIGN_KEY_CHECKS = 1; -- Riabilita check

INSERT into autore(password,username,bio,isAdmin) VALUES ('04f8996da763b7a969b1028ee3007569eaf3a635486ddab211d512c85b9df8fb','user','just a user', false);
INSERT into autore(password,username,bio,isAdmin) VALUES ('8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918','admin','admin', true);