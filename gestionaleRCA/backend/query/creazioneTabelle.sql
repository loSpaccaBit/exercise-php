CREATE TABLE IF not EXISTS Cliente(
codice_fiscale VARCHAR(16) NOT NULL PRIMARY KEY,
nome VARCHAR(20) NOT NULL,
cognome VARCHAR(20) NOT NULL
);

CREATE TABLE IF not EXISTS Auto(
targa VARCHAR(7) NOT NULL PRIMARY KEY,
id_cliente VARCHAR(16),
modello VARCHAR(30) NOT NULL,
marca VARCHAR(20) NOT NULL,
FOREIGN KEY (id_cliente) REFERENCES Cliente(codice_fiscale)
);

CREATE TABLE IF not EXISTS Perizia(
id_perizia INT PRIMARY KEY AUTO_INCREMENT,
id_auto VARCHAR(7) NOT NULL,
costo_danno DECIMAL NOT NULL,
percentuale_danno DECIMAL NOT NULL,
FOREIGN KEY(id_auto) REFERENCES Auto(targa)
);

CREATE TABLE IF not EXISTS Incidente(
id_incidente INT PRIMARY KEY AUTO_INCREMENT,
auto_1 INT,
auto_2 INT,
FOREIGN KEY(auto_1) REFERENCES Perizia(id_perizia),
FOREIGN KEY(auto_2) REFERENCES Perizia(id_perizia)
);
  