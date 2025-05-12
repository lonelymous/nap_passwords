CREATE DATABASE IF NOT EXISTS adatok;
USE adatok;

CREATE TABLE IF NOT EXISTS tabla (
  Username VARCHAR(255) PRIMARY KEY,
  Titkos VARCHAR(255)
);

INSERT INTO tabla (Username, Titkos) VALUES
('katika@gmail.com', 'piros'),
('arpi40@freemail.hu', 'zold'),
('zsanettka@hotmail.com', 'sarga'),
('hatizsak@protonmail.com', 'kek'),
('terpeszterez@citromail.hu', 'fekete'),
('nagysanyi@gmail.hu', 'feher');
