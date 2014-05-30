
CREATE DATABASE IF NOT EXISTS ferElektrijada
  DEFAULT CHARACTER SET latin2
  DEFAULT COLLATE latin2_croatian_ci;			
USE ferElektrijada;


CREATE TABLE elektrijada (
    idElektrijade INT UNSIGNED AUTO_INCREMENT,
    mjestoOdrzavanja VARCHAR(100) NOT NULL,
    datumPocetka DATE NOT NULL,
    datumKraja DATE NOT NULL,
    ukupniRezultat SMALLINT,
    rokZaZnanje DATE,
    rokZaSport DATE,
    drzava VARCHAR(100) NOT NULL,
    ukupanBrojSudionika INT,
    PRIMARY KEY (idElektrijade),
    UNIQUE (datumKraja),
    UNIQUE (datumPocetka)
);

CREATE TABLE funkcija (
    idFunkcije INT UNSIGNED AUTO_INCREMENT,
    nazivFunkcije VARCHAR(100) NOT NULL,
    PRIMARY KEY (idFunkcije),
    UNIQUE (nazivFunkcije)
);

CREATE TABLE udruga (
    idUdruge INT UNSIGNED AUTO_INCREMENT,
    nazivUdruge VARCHAR(50) NOT NULL,
    PRIMARY KEY (idUdruge),
    UNIQUE (nazivUdruge)
);

CREATE TABLE osoba (
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
    aktivanDokument BOOLEAN,
    uloga CHAR(1) NOT NULL,
    zivotopis VARCHAR(200),
    MBG VARCHAR(9),
    OIB VARCHAR(11) ,
    idNadredjena INT UNSIGNED,
    PRIMARY KEY (idOsobe),
    UNIQUE (ferId),
    UNIQUE (JMBAG),
    UNIQUE (OIB),
    UNIQUE (MBG),
    FOREIGN KEY (idNadredjena)
	REFERENCES osoba (idOsobe)
	ON UPDATE CASCADE ON DELETE SET NULL
);


CREATE TABLE podrucje (
    idPodrucja INT UNSIGNED AUTO_INCREMENT,
    nazivPodrucja VARCHAR(100) NOT NULL,
    idNadredjenog INT UNSIGNED DEFAULT NULL,
    PRIMARY KEY (idPodrucja),
    FOREIGN KEY (idNadredjenog)
        REFERENCES podrucje (idPodrucja)
        ON DELETE SET NULL,
    UNIQUE (nazivPodrucja)
);

CREATE TABLE sponzor (
    idSponzora INT UNSIGNED AUTO_INCREMENT,
    imeTvrtke VARCHAR(100) NOT NULL,
    adresaTvrtke VARCHAR(100) NOT NULL,
    logotip VARCHAR(200),
    PRIMARY KEY (idSponzora)
);

CREATE TABLE elekpodrucje (
    idElekPodrucje INT UNSIGNED AUTO_INCREMENT,
    idPodrucja INT UNSIGNED NOT NULL,
    rezultatGrupni SMALLINT,
    slikaLink VARCHAR(255),
    idElektrijade INT UNSIGNED NOT NULL,    
    ukupanBrojEkipa INT,
    PRIMARY KEY (idElekPodrucje),
    UNIQUE (idElektrijade , idPodrucja),
    FOREIGN KEY (idElektrijade)
        REFERENCES elektrijada (idElektrijade)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idPodrucja)
        REFERENCES podrucje (idPodrucja)
        ON UPDATE CASCADE ON DELETE CASCADE  
);

CREATE TABLE sponelekpod (
    idSponElekPod INT UNSIGNED AUTO_INCREMENT,
    idSponzora INT UNSIGNED NOT NULL,
    idPodrucja INT UNSIGNED NOT NULL,
    idElektrijade INT UNSIGNED NOT NULL, 
	iznosDonacije DECIMAL(13 , 2 ) NOT NULL,
    valutaDonacije VARCHAR(3) NOT NULL,
    napomena VARCHAR(300),
    PRIMARY KEY (idSponElekPod),
    UNIQUE (idSponzora , idPodrucja, idElektrijade),
    FOREIGN KEY (idElektrijade)
        REFERENCES elektrijada (idElektrijade)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idPodrucja)
        REFERENCES podrucje (idPodrucja)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idSponzora)
        REFERENCES sponzor (idSponzora)
        ON UPDATE CASCADE ON DELETE CASCADE  
);


CREATE TABLE nacinpromocije (
    idPromocije INT UNSIGNED AUTO_INCREMENT,
    tipPromocije VARCHAR(100) NOT NULL,
    UNIQUE (tipPromocije),
    PRIMARY KEY (idPromocije)
);

CREATE TABLE kategorija (
    idKategorijeSponzora INT UNSIGNED AUTO_INCREMENT,
    tipKategorijeSponzora VARCHAR(100) NOT NULL,
    UNIQUE (tipKategorijeSponzora),
    PRIMARY KEY (IdKategorijeSponzora)
);


CREATE TABLE tvrtka (
    idTvrtke INT UNSIGNED AUTO_INCREMENT,
    imeTvrtke VARCHAR(100) NOT NULL,
    adresaTvrtke VARCHAR(100) NOT NULL,
    PRIMARY KEY (idTvrtke)
);

CREATE TABLE usluga (
    idUsluge INT UNSIGNED AUTO_INCREMENT,
    nazivUsluge VARCHAR(100) NOT NULL,
    UNIQUE (nazivUsluge),
    PRIMARY KEY (idUsluge)
);

CREATE TABLE imasponzora (
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
        REFERENCES sponzor (idSponzora)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idKategorijeSponzora)
        REFERENCES kategorija (idKategorijeSponzora)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idPromocije)
        REFERENCES nacinpromocije (idPromocije)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idElektrijade)
        REFERENCES elektrijada (idElektrijade)
        ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE koristipruza (
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
        REFERENCES usluga (idUsluge)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idTvrtke)
        REFERENCES tvrtka (idTvrtke)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idElektrijade)
        REFERENCES elektrijada (idElektrijade)
        ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE atribut (
    idAtributa INT UNSIGNED AUTO_INCREMENT,
    nazivAtributa VARCHAR(100) NOT NULL,
    PRIMARY KEY (idAtributa),
    UNIQUE (nazivAtributa)
);

CREATE TABLE velmajice (
    idVelicine INT UNSIGNED AUTO_INCREMENT,
    velicina VARCHAR(5) NOT NULL,
    PRIMARY KEY (idVelicine),
    UNIQUE (velicina)
);

CREATE TABLE godstud (
    idGodStud INT UNSIGNED AUTO_INCREMENT,
    studij VARCHAR(50) NOT NULL,
    godina VARCHAR(50) NOT NULL,
    PRIMARY KEY (idGodStud),
    UNIQUE (godina , studij)
);

CREATE TABLE smjer (
    idSmjera INT UNSIGNED AUTO_INCREMENT,
    nazivSmjera VARCHAR(100) NOT NULL,
    PRIMARY KEY (idSmjera),
    UNIQUE (nazivSmjera)
);

CREATE TABLE zavod (
    idZavoda INT UNSIGNED AUTO_INCREMENT,
    nazivZavoda VARCHAR(100) NOT NULL,
    skraceniNaziv VARCHAR(10) NOT NULL,
    PRIMARY KEY (idZavoda),
    UNIQUE (nazivZAvoda),
    UNIQUE (skraceniNaziv)
);

CREATE TABLE radnomjesto (
    idRadnogMjesta INT UNSIGNED AUTO_INCREMENT,
    naziv VARCHAR(100) NOT NULL,
    PRIMARY KEY (idRadnogMjesta),
    UNIQUE (naziv)
);

CREATE TABLE bus (
    idBusa INT UNSIGNED AUTO_INCREMENT,
    registracija VARCHAR(100),
    brojMjesta INT UNSIGNED,
	brojBusa INT UNSIGNED,
    nazivBusa VARCHAR(200),
    PRIMARY KEY (idBusa),
    UNIQUE (registracija)
);

CREATE TABLE busgrupa (
    idGrupe INT UNSIGNED AUTO_INCREMENT,
    nazivGrupe VARCHAR(200),
    idBusa INT UNSIGNED,
    PRIMARY KEY (idGrupe),
    UNIQUE (nazivGrupe),
    FOREIGN KEY (idBusa)
        REFERENCES bus (idBusa)
        ON UPDATE CASCADE ON DELETE CASCADE
);




CREATE TABLE sudjelovanje (
    idSudjelovanja INT UNSIGNED AUTO_INCREMENT,
    idOsobe INT UNSIGNED NOT NULL,
    idElektrijade INT UNSIGNED NOT NULL,
    tip CHAR(1),
    idVelicine INT UNSIGNED,
    idGodStud INT UNSIGNED,
    idSmjera INT UNSIGNED,
    idRadnogMjesta INT UNSIGNED,
    idZavoda INT UNSIGNED,
    FOREIGN KEY (idRadnogMjesta)
        REFERENCES radnomjesto (idRadnogMjesta)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idSmjera)
        REFERENCES smjer (idSmjera)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idZavoda)
        REFERENCES zavod (idZavoda)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idGodStud)
        REFERENCES godstud (idGodStud)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idVelicine)
        REFERENCES velmajice (idVelicine)
        ON UPDATE CASCADE ON DELETE CASCADE,    
    PRIMARY KEY (idSudjelovanja),
    UNIQUE (idOsobe , idElektrijade),
    FOREIGN KEY (idOsobe)
        REFERENCES osoba (idOsobe)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idElektrijade)
        REFERENCES elektrijada (idElektrijade)
);

CREATE TABLE putovanje (
    idPutovanja INT UNSIGNED AUTO_INCREMENT,
    idSudjelovanja INT UNSIGNED,
    idGrupe INT UNSIGNED,
    polazak BOOLEAN,
    povratak BOOLEAN,
    napomena VARCHAR(200),
    brojSjedala INT NOT NULL,
    PRIMARY KEY (idPutovanja),
    FOREIGN KEY (idGrupe)
        REFERENCES busgrupa (idGrupe)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idSudjelovanja)
        REFERENCES sudjelovanje (idSudjelovanja)
        ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE obavljafunkciju (
    idObavljaFunkciju INT UNSIGNED AUTO_INCREMENT,
    idOsobe INT UNSIGNED NOT NULL,
    idFunkcije INT UNSIGNED ,
    idElektrijade INT UNSIGNED NOT NULL,
    PRIMARY KEY (idObavljaFunkciju),
    UNIQUE (idOsobe , idFunkcije , idElektrijade),
    FOREIGN KEY (idOsobe)
        REFERENCES osoba (idOsobe)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idFunkcije)
        REFERENCES funkcija (idFunkcije)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idElektrijade)
        REFERENCES elektrijada (idElektrijade)
        ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE TABLE jeuudruzi (
    idJeUUdruzi INT UNSIGNED AUTO_INCREMENT,
    idUdruge INT UNSIGNED NOT NULL,
    idOsobe INT UNSIGNED NOT NULL,
    PRIMARY KEY (idJeUUdruzi),
    UNIQUE (idUdruge , idOsobe),
    FOREIGN KEY (idUdruge)
        REFERENCES udruga (idUdruge)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idOsobe)
        REFERENCES osoba (idOsobe)
        ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE medij (
    idMedija INT UNSIGNED AUTO_INCREMENT,
    nazivMedija VARCHAR(100) NOT NULL,    
    PRIMARY KEY (idMedija),
    UNIQUE(nazivMedija)

);

CREATE TABLE kontaktosobe (
    idKontakta INT UNSIGNED AUTO_INCREMENT,
    imeKontakt VARCHAR(100) NOT NULL,
    prezimeKontakt VARCHAR(100) NOT NULL,
    telefon VARCHAR(20),
    radnoMjesto VARCHAR(100),
    idTvrtke INT UNSIGNED,
    idSponzora INT UNSIGNED,
    idMedija INT UNSIGNED,
    FOREIGN KEY (idSponzora)
        REFERENCES sponzor (idSponzora)
        ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (idMedija)
        REFERENCES medij (idMedija)
        ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (idTvrtke)
        REFERENCES tvrtka (idTvrtke)
        ON UPDATE CASCADE ON DELETE SET NULL,
    PRIMARY KEY (idKontakta)
);

CREATE TABLE emailadrese (
    idAdrese INT UNSIGNED AUTO_INCREMENT,
    idKontakta INT UNSIGNED,
    email VARCHAR(100) NOT NULL,
    UNIQUE (email),
    PRIMARY KEY (idAdrese),
    FOREIGN KEY (idKontakta)
        REFERENCES kontaktosobe (idKontakta)
        ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE brojevimobitela (
    idBroja INT UNSIGNED AUTO_INCREMENT,
    idKontakta INT UNSIGNED,
    broj VARCHAR(20) NOT NULL,
    UNIQUE (broj),
    PRIMARY KEY (idBroja),
    FOREIGN KEY (idKontakta)
        REFERENCES kontaktosobe (idKontakta)
        ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE imaatribut (
    idImaAtribut INT UNSIGNED AUTO_INCREMENT,
    idPodrucja INT UNSIGNED NOT NULL,
    idAtributa INT UNSIGNED NOT NULL,
    idSudjelovanja INT UNSIGNED NOT NULL,
    PRIMARY KEY (idImaAtribut),
    UNIQUE (idPodrucja , idAtributa , idSudjelovanja),
    FOREIGN KEY (idPodrucja)
        REFERENCES podrucje (idPodrucja)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idAtributa)
        REFERENCES atribut (idAtributa)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idSudjelovanja)
        REFERENCES sudjelovanje (idSudjelovanja)
        ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE podrucjesudjelovanja (
    idPodrucjeSudjelovanja INT UNSIGNED AUTO_INCREMENT,
    idPodrucja INT UNSIGNED NOT NULL,
    idSudjelovanja INT UNSIGNED NOT NULL,
    rezultatPojedinacni SMALLINT,
    vrstaPodrucja TINYINT(1) DEFAULT '0',
    ukupanBrojSudionika INT,
	iznosUplate decimal(13,2) DEFAULT NULL,
    valuta VARCHAR(3) DEFAULT NULL,
    PRIMARY KEY (idPodrucjeSudjelovanja),
    UNIQUE (idPodrucja , idSudjelovanja, vrstaPodrucja),
    FOREIGN KEY (idPodrucja)
        REFERENCES podrucje (idPodrucja)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idSudjelovanja)
        REFERENCES sudjelovanje (idSudjelovanja)
        ON UPDATE CASCADE ON DELETE CASCADE
);




CREATE TABLE objava (
    idObjave INT UNSIGNED AUTO_INCREMENT,
    datumObjave DATE NOT NULL,
    link VARCHAR(100),
    autorIme VARCHAR(50) NOT NULL,
    autorPrezime VARCHAR(50) NOT NULL,
    idMedija INT UNSIGNED NOT NULL,
    dokument VARCHAR(200),
    PRIMARY KEY (idObjave),
    FOREIGN KEY (idMedija)
        REFERENCES medij (idMedija)
        ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE objavaoelektrijadi (
    idObjavaOElektrijadi INT UNSIGNED AUTO_INCREMENT,
    idObjave INT UNSIGNED NOT NULL,
    idElektrijade INT UNSIGNED NOT NULL,
    PRIMARY KEY (idObjavaOElektrijadi),
    UNIQUE (idObjave , idElektrijade),
    FOREIGN KEY (idObjave)
        REFERENCES objava (idObjave)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (idElektrijade)
        REFERENCES elektrijada (idElektrijade)
        ON UPDATE CASCADE ON DELETE CASCADE 
);


INSERT INTO `osoba` (`idOsobe`, `ime`, `prezime`, `mail`, `brojMob`, `ferId`, `password`, `JMBAG`, `spol`, `datRod`, `brOsobne`, `brPutovnice`, `osobnaVrijediDo`, `putovnicaVrijediDo`, `uloga`, `zivotopis`, `MBG`, `OIB`, `idNadredjena`, `aktivanDokument`) VALUES
(1, 'Root', 'Root', 'root@fer.hr', NULL, 'Root', 'dc76e9f0c0006e8f919e0c515c66dbba3982f785', NULL, 'M', NULL, NULL, NULL, NULL, NULL, 'A', NULL, NULL, NULL, NULL, 1);
