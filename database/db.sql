-- DROP DATABASE IF EXISTS workeradvisor;

CREATE DATABASE workeradvisor;

USE workeradvisor;

SET FOREIGN_KEY_CHECKS = 0; -- Disabilita check su vincoli di integrità referenziale

DROP TABLE IF EXISTS utente, recensione;

CREATE TABLE utente (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  email       VARCHAR(50) NOT NULL UNIQUE,
  password    VARCHAR(64) NOT NULL,
  nome        VARCHAR(30) NOT NULL,
  cognome     VARCHAR(30) NOT NULL,
  telefono      VARCHAR(30) NOT NULL,
  datanascita DATE NOT NULL,
  cf          VARCHAR(16) NOT NULL UNIQUE,
  professione VARCHAR(50) NOT NULL,
  luogo        VARCHAR(50) NOT NULL,
  bio         TEXT NOT NULL,
  img_path    VARCHAR(256) NOT NULL DEFAULT 'img/upload/7a571fe240316d199ebe5e106f4cac93b77342aa2cf0fa86baf7aa914d9582b1'
);

CREATE TABLE recensione (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  descrizione     VARCHAR(2000) NOT NULL,
  voto            INT NOT NULL,
  data_recensione DATE NOT NULL,
  id_autore       INT NOT NULL,
  id_utente       INT NOT NULL,

  FOREIGN KEY (id_autore) REFERENCES utente(id),
  FOREIGN KEY (id_utente) REFERENCES utente(id)
);


SET FOREIGN_KEY_CHECKS = 1; -- Riabilita check
