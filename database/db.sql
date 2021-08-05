-- DROP DATABASE IF EXISTS sharearts;

CREATE DATABASE sharearts;

USE sharearts;

SET FOREIGN_KEY_CHECKS = 0; -- Disabilita check su vincoli di integrità referenziale

DROP TABLE IF EXISTS autore, categoria, opera;

CREATE TABLE autore (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  password    VARCHAR(64) NOT NULL,
  username    VARCHAR(30) NOT NULL,
  bio         VARCHAR(2000)
);

CREATE TABLE categoria (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  nome_cat    VARCHAR(30) NOT NULL
);

CREATE TABLE opera (
  id                 INT AUTO_INCREMENT PRIMARY KEY,
  titolo             VARCHAR(2000) NOT NULL,
  descrizione_short  VARCHAR(200) NOT NULL,
  descrizione        VARCHAR(2000),
  data_creazione     VARCHAR(2000) NOT NULL,
  id_autore          INT NOT NULL,
  id_categoria       INT NOT NULL,
  likes              INT NOT NULL,
  dilikes            INT NOT NULL,
  img_path           VARCHAR(256) NOT NULL,

  FOREIGN KEY (id_autore) REFERENCES autore(id),
  FOREIGN KEY (id_categoria) REFERENCES categoria(id)
);

CREATE TABLE commento (
  id                 INT AUTO_INCREMENT PRIMARY KEY,
  nickname           VARCHAR(200) NOT NULL,
  testo_commento     VARCHAR(2000) NOT NULL,
  id_opera           INT NOT NULL,

  FOREIGN KEY (id_opera) REFERENCES opera(id)
);


SET FOREIGN_KEY_CHECKS = 1; -- Riabilita check