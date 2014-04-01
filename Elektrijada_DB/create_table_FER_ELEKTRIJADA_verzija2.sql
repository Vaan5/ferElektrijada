
CREATE DATABASE IF NOT EXISTS ferElektrijada
  DEFAULT CHARACTER SET latin2
  DEFAULT COLLATE latin2_croatian_ci;			
USE ferElektrijada;


CREATE TABLE ELEKTRIJADA (
    idElektrijade INT UNSIGNED AUTO_INCREMENT,
    mjestoOdrzavanja VARCHAR(100) NOT NULL,
    datumPocetka DATE NOT NULL,
    datumKraja DATE NOT NULL,
    ukupniRezultat SMALLINT,
    drzava VARCHAR(100) NOT NULL,
    PRIMARY KEY (idElektrijade),
    UNIQUE (datumKraja),
    UNIQUE (datumPocetka)
);

CREATE TABLE FUNKCIJA (
    idFunkcije INT UNSIGNED AUTO_INCREMENT,
    nazivFunkcije VARCHAR(100) NOT NULL,
    PRIMARY KEY (idFunkcije),
    UNIQUE (nazivFunkcije)
);

CREATE TABLE UDRUGA (
    idUdruge INT UNSIGNED AUTO_INCREMENT,
    nazivUdruge VARCHAR(50) NOT NULL,
    PRIMARY KEY (idUdruge),
    UNIQUE (nazivUdruge)
);

CREATE TABLE OSOBA (
    idOsobe INT UNSIGNED AUTO_INCREMENT,
    ime VARCHAR(50),
    prezime VARCHAR(50),
    mail VARCHAR(50),
    brojMob VARCHAR(20),
    ferId VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    JMBAG VARCHAR(10),
    spol CHAR(1),
    datRod DATE,
    brOsobne VARCHAR(20),
    brPutovnice VARCHAR(30),
    osobnaVrijediDo DATE,
    putovnicaVrijediDo DATE,
    uloga CHAR(1) NOT NULL,
    zivotopis BLOB,
    MBG VARCHAR(9),
    OIB VARCHAR(11) ,
    PRIMARY KEY (idOsobe),
    UNIQUE (ferId),
    UNIQUE (JMBAG),
    UNIQUE (OIB),
    UNIQUE (mbrOsigOsobe)
);


CREATE TABLE PODRUCJE (
    idPodrucja INT UNSIGNED AUTO_INCREMENT,
    nazivPodrucja VARCHAR(100) NOT NULL,
    idNadredjenog INT UNSIGNED DEFAULT NULL,
    PRIMARY KEY (idPodrucja),
    FOREIGN KEY (idNadredjenog)
        REFERENCES PODRUCJE (idPodrucja)
        ON DELETE SET NULL,
    UNIQUE (nazivPodrucja)
);

CREATE TABLE SPONZOR (
    idSponzora INT UNSIGNED AUTO_INCREMENT,
    imeTvrtke VARCHAR(100) NOT NULL,
    adresaTvrtke VARCHAR(100) NOT NULL,
    PRIMARY KEY (idSponzora)
);

CREATE TABLE ElekPodrucje (
    idElekPodrucje INT UNSIGNED AUTO_INCREMENT,
    idPodrucja INT UNSIGNED NOT NULL,
    rezultatGrupni SMALLINT,
    slikaLink VARCHAR(255),
    slikaBLOB BLOB,
    idElektrijade INT UNSIGNED NOT NULL,
    idSponzora INT UNSIGNED,
    PRIMARY KEY (idElekPodrucje),
    UNIQUE (idElekPodrucje , idPodrucja),
    FOREIGN KEY (idElektrijade)
        REFERENCES ELEKTRIJADA (idElektrijade)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idPodrucja)
        REFERENCES PODRUCJE (idPodrucja)
        ON UPDATE CASCADE ON DELETE CASCADE,
   FOREIGN KEY (idSponzora)
        REFERENCES SPONZOR (idSponzora)
        ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE NACINPROMOCIJE (
    idPromocije INT UNSIGNED AUTO_INCREMENT,
    tipPromocije VARCHAR(100) NOT NULL,
    UNIQUE (tipPromocije),
    PRIMARY KEY (IdPromocije)
);

CREATE TABLE KATEGORIJA (
    idKategorijeSponzora INT UNSIGNED AUTO_INCREMENT,
    tipKategorijeSponzora VARCHAR(100) NOT NULL,
    UNIQUE (tipKategorijeSponzora),
    PRIMARY KEY (IdKategorijeSponzora)
);


CREATE TABLE TVRTKA (
    idTvrtke INT UNSIGNED AUTO_INCREMENT,
    imeTvrtke VARCHAR(100) NOT NULL,
    adresaTvrtke VARCHAR(100) NOT NULL,
    PRIMARY KEY (idTvrtke)
);

CREATE TABLE USLUGA (
    idUsluge INT UNSIGNED AUTO_INCREMENT,
    nazivUsluge VARCHAR(100) NOT NULL,
    UNIQUE (nazivUsluge),
    PRIMARY KEY (idUsluge)
);

CREATE TABLE ImaSponzora (
    idImaSponzora INT UNSIGNED AUTO_INCREMENT,
    idSponzora INT UNSIGNED NOT NULL,
    idKategorijeSponzora INT UNSIGNED,
    idPromocije INT UNSIGNED,
    idElektrijade INT UNSIGNED NOT NULL,
    iznosDonacije DECIMAL(13 , 2 ) NOT NULL,
    valutaDonacije VARCHAR(3) NOT NULL,
    napomena VARCHAR(300),
    PRIMARY KEY (idImaSponzora),
    UNIQUE (idSponzora , idElektrijade),
    FOREIGN KEY (idSponzora)
        REFERENCES SPONZOR (idSponzora)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idKategorijeSponzora)
        REFERENCES KATEGORIJA (idKategorijeSponzora)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idPromocije)
        REFERENCES NACINPROMOCIJE (idPromocije)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idElektrijade)
        REFERENCES ELEKTRIJADA (idElektrijade)
        ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE KoristiPruza (
    idKoristiPruza INT UNSIGNED AUTO_INCREMENT,
    idUsluge INT UNSIGNED NOT NULL,
    idTvrtke INT UNSIGNED NOT NULL,
    idElektrijade INT UNSIGNED NOT NULL,
    iznosRacuna DECIMAL(13 , 2 ) NOT NULL,
    valutaRacuna VARCHAR(3) NOT NULL,    
    nacinPlacanja VARCHAR(100),
    napomena VARCHAR(300),
    UNIQUE (idTvrtke , idElektrijade , idUsluge),
    PRIMARY KEY (idKoristiPruza),
    FOREIGN KEY (idUsluge)
        REFERENCES USLUGA (idUsluge)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idTvrtke)
        REFERENCES TVRTKA (idTvrtke)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idElektrijade)
        REFERENCES ELEKTRIJADA (idElektrijade)
        ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE ATRIBUT (
    idAtributa INT UNSIGNED AUTO_INCREMENT,
    nazivAtributa VARCHAR(100) NOT NULL,
    PRIMARY KEY (idAtributa),
    UNIQUE (nazivAtributa)
);

CREATE TABLE VELMAJICE (
    idVelicine INT UNSIGNED AUTO_INCREMENT,
    velicina VARCHAR(5) NOT NULL,
    PRIMARY KEY (idVelicine),
    UNIQUE (velicina)
);

CREATE TABLE GODSTUD (
    idGodStud INT UNSIGNED AUTO_INCREMENT,
    studij VARCHAR(50) NOT NULL,
    godina VARCHAR(50) NOT NULL,
    PRIMARY KEY (idGodStud),
    UNIQUE (godina , studij)
);

CREATE TABLE SMJER (
    idSmjera INT UNSIGNED AUTO_INCREMENT,
    nazivSmjera VARCHAR(100) NOT NULL,
    PRIMARY KEY (idSmjera),
    UNIQUE (nazivSmjera)
);

CREATE TABLE ZAVOD (
    idZavoda INT UNSIGNED AUTO_INCREMENT,
    nazivZavoda VARCHAR(100) NOT NULL,
    skraceniNaziv VARCHAR(10) NOT NULL,
    PRIMARY KEY (idZavoda),
    UNIQUE (nazivZAvoda),
    UNIQUE (skraceniNaziv)
);

CREATE TABLE RADNOMJESTO (
    idRadnogMjesta INT UNSIGNED AUTO_INCREMENT,
    naziv VARCHAR(100) NOT NULL,
    PRIMARY KEY (idRadnogMjesta),
    UNIQUE (naziv)
);

CREATE TABLE BUS (
    idBusa INT UNSIGNED AUTO_INCREMENT,
    registracija VARCHAR(10) NOT NULL,
    brojMjesta INT,
    brojBusa INT,
    nazivFunkcije VARCHAR(100) NOT NULL,
    PRIMARY KEY (idBusa),
    UNIQUE (registracija)
);

CREATE TABLE PUTOVANJE (
    idPutovanja INT UNSIGNED AUTO_INCREMENT,
    idBusa INT UNSIGNED,
    polazak BOOLEAN,
    povratak BOOLEAN, 
    napomena VARCHAR(200),
    brojSjedala INT NOT NULL,
    PRIMARY KEY (idPutovanja),
    FOREIGN KEY (idBusa)
        REFERENCES BUS (idBusa)
        ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE SUDJELOVANJE (
    idSudejlovanja INT UNSIGNED AUTO_INCREMENT,
    idOsobe INT UNSIGNED NOT NULL,
    idElektrijade INT UNSIGNED NOT NULL,
    tip CHAR(1),
    idVelicine INT UNSIGNED,
    idGodStud INT UNSIGNED,
    idSmjera INT UNSIGNED,
    idRadnogMjesta INT UNSIGNED,
    idZavoda INT UNSIGNED,
    idPutovanja INT UNSIGNED,
    FOREIGN KEY (idRadnogMjesta)
        REFERENCES RADNOMJESTO (idRadnogMjesta)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idSmjera)
        REFERENCES SMJER (idSmjera)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idZavoda)
        REFERENCES ZAVOD (idZavoda)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idGodStud)
        REFERENCES GODSTUD (idGodStud)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idVelicine)
        REFERENCES VELMAJICE (idVelicine)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idPutovanja)
        REFERENCES PUTOVANJE (idPutovanja)
        ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (idSudejlovanja),
    UNIQUE (idOsobe , idElektrijade),
    FOREIGN KEY (idOsobe)
        REFERENCES OSOBA (idOsobe)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idElektrijade)
        REFERENCES ELEKTRIJADA (idElektrijade)
);

CREATE TABLE ObavljaFunkciju (
    idObavljaFunkciju INT UNSIGNED AUTO_INCREMENT,
    idOsobe INT UNSIGNED NOT NULL,
    idFunkcije INT UNSIGNED NOT NULL,
    idElektrijade INT UNSIGNED NOT NULL,
    PRIMARY KEY (idObavljaFunkciju),
    UNIQUE (idOsobe , idFunkcije , idElektrijade),
    FOREIGN KEY (idOsobe)
        REFERENCES OSOBA (idOsobe)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idFunkcije)
        REFERENCES FUNKCIJA (idFunkcije)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idElektrijade)
        REFERENCES ELEKTRIJADA (idElektrijade)
        ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE TABLE JeUUdruzi (
    idJeUUdruzi INT UNSIGNED AUTO_INCREMENT,
    idUdruge INT UNSIGNED NOT NULL,
    idOsobe INT UNSIGNED NOT NULL,
    PRIMARY KEY (idJeUUdruzi),
    UNIQUE (idUdruge , idOsobe),
    FOREIGN KEY (idUdruge)
        REFERENCES UDRUGA (idUdruge)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idOsobe)
        REFERENCES OSOBA (idOsobe)
        ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE KONTAKTOSOBE (
    idKontakta INT UNSIGNED AUTO_INCREMENT,
    imeKontakt VARCHAR(100) NOT NULL,
    prezimeKontakt VARCHAR(100) NOT NULL,
    telefon VARCHAR(20),
    radnoMjesto VARCHAR(100),
    idTvrtke INT UNSIGNED,
    idSponzora INT UNSIGNED,
    FOREIGN KEY (idSponzora)
        REFERENCES SPONZOR (idSponzora)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idTvrtke)
        REFERENCES TVRTKA (idTvrtke)
        ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (idKontakta)
);

CREATE TABLE EMAILADRESE (
    idAdrese INT UNSIGNED AUTO_INCREMENT,
    idKontakta INT UNSIGNED,
    email VARCHAR(100) NOT NULL,
    UNIQUE (email),
    PRIMARY KEY (idAdrese),
    FOREIGN KEY (idKontakta)
        REFERENCES KONTAKTOSOBE (idKontakta)
        ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE BROJEVIMOBITELA (
    idBroja INT UNSIGNED AUTO_INCREMENT,
    idKontakta INT UNSIGNED,
    broj VARCHAR(20) NOT NULL,
    UNIQUE (broj),
    PRIMARY KEY (idBroja),
    FOREIGN KEY (idKontakta)
        REFERENCES KONTAKTOSOBE (idKontakta)
        ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE ImaAtribut (
    idImaAtribut INT UNSIGNED AUTO_INCREMENT,
    idPodrucja INT UNSIGNED NOT NULL,
    idAtributa INT UNSIGNED NOT NULL,
    idSudejlovanja INT UNSIGNED NOT NULL,
    PRIMARY KEY (idImaAtribut),
    UNIQUE (idPodrucja , idAtributa , idSudejlovanja),
    FOREIGN KEY (idPodrucja)
        REFERENCES PODRUCJE (idPodrucja)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idAtributa)
        REFERENCES ATRIBUT (idAtributa)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idSudejlovanja)
        REFERENCES SUDJELOVANJE (idSudejlovanja)
        ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE PodrucjeSudjelovanja (
    idPodrucjeSudejlovanja INT UNSIGNED AUTO_INCREMENT,
    idPodrucja INT UNSIGNED NOT NULL,
    idSudejlovanja INT UNSIGNED NOT NULL,
    rezultatPojedinacni SMALLINT,
    vrstaPodrucja TINYINT(1),
    PRIMARY KEY (idPodrucjeSudejlovanja),
    UNIQUE (idPodrucja , idSudejlovanja),
    FOREIGN KEY (idPodrucja)
        REFERENCES PODRUCJE (idPodrucja)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idSudejlovanja)
        REFERENCES SUDJELOVANJE (idSudejlovanja)
        ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE MEDIJ (
    idMedija INT UNSIGNED AUTO_INCREMENT,
    nazivMedija VARCHAR(100) NOT NULL,
    idKontakta INT(10) UNSIGNED,
    PRIMARY KEY (idMedija),
    FOREIGN KEY (idKontakta)
        REFERENCES KONTAKTOSOBE (idKontakta)
        ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE (nazivMedija)
);

CREATE TABLE OBJAVA (
    idObjave INT UNSIGNED AUTO_INCREMENT,
    datumObjave DATE NOT NULL,
    link VARCHAR(100),
    autorIme VARCHAR(50) NOT NULL,
    autorPrezime VARCHAR(50) NOT NULL,
    idMedija INT UNSIGNED NOT NULL,
    dokument BLOB,
    PRIMARY KEY (idObjave),
    FOREIGN KEY (idMedija)
        REFERENCES MEDIJ (idMedija)
        ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE ObjavaOElektrijadi (
    idObjavaOElektrijadi INT UNSIGNED AUTO_INCREMENT,
    idObjave INT UNSIGNED NOT NULL,
    idElektrijade INT UNSIGNED NOT NULL,
    PRIMARY KEY (idObjavaOElektrijadi),
    UNIQUE (idObjave , idElektrijade),
    FOREIGN KEY (idObjave)
        REFERENCES OBJAVA (idObjave)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idElektrijade)
        REFERENCES ELEKTRIJADA (idElektrijade)
        ON UPDATE CASCADE ON DELETE CASCADE 
);




