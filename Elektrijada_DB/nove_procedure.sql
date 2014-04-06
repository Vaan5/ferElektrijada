 DELIMITER $$
CREATE  PROCEDURE `azurirajElektrijadu`(IN idElektrijade INT(10), IN mjestoOdrzavanja VARCHAR(100), IN datumPocetka DATE, IN datumKraja DATE, IN ukupniRezultat SMALLINT(6),IN rokZaZnanje DATE, IN rokZaSport DATE, IN drzava VARCHAR(100),IN ukupniBrojSudionika INT)
BEGIN
IF NOT EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN
	SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji trazena Elektrijada ';
ELSE
IF(datumPocetka<datumKraja) THEN
IF(datumPocetka>rokZaZnanje AND datumPocetka>rokZaSport) THEN
UPDATE ELEKTRIJADA
SET ELEKTRIJADA.datumKraja = datumKraja, ELEKTRIJADA.mjestoOdrzavanja=mjestoOdrzavanja , ELEKTRIJADA.ukupniRezultat=ukupniRezultat, ELEKTRIJADA.drzava=drzava, ELEKTRIJADA.datumPocetka=datumPocetka, ELEKTRIJADA.ukupniBrojSudionika=ukupniBrojSudionika, ELEKTRIJADA.rokZaSport=rokZaSport, ELEKTRIJADA.rokZaZnanje=rokZaZnanje
WHERE ELEKTRIJADA.idElektrijade = idElektrijade;

ELSE
    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Pogrešan unos datuma roka za znanje ili roka za sport Elektrijade!'; 
END IF;

ELSE
   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Greška: Pogrešan unos datuma pocetka i datuma kraja Elektrijade!';  
END IF;

END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajElektrijadu`(IN mjestoOdrzavanja VARCHAR(100), IN datumPocetka DATE, IN datumKraja DATE, IN ukupniRezultat SMALLINT(6), IN rokZaZnanje DATE, IN rokZaSport DATE, IN drzava VARCHAR(100),IN ukupanBrojSudionika INT)
BEGIN
IF NOT EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.datumPocetka = datumPocetka) THEN
IF (datumPocetka<datumKraja) THEN
IF(datumPocetka>rokZaZnanje AND datumPocetka>rokZaSport) THEN
	INSERT INTO ELEKTRIJADA VALUES (NULL,mjestoOdrzavanja,datumPocetka,datumKraja,ukupniRezultat,rokZaZnanje,rokZaSport,drzava,ukupanBrojSudionika);
ELSE
    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Pogrešan unos datuma roka za znanje ili roka za sport Elektrijade!';
END IF;
ELSE
    SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Greška: Pogrešan unos datuma pocetka i datuma kraja Elektrijade!!'; 
END IF;
ELSE
    SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Greška: Odabrana elektrijada je već unešena!';  
END IF;

END $$
DELIMITER ;


DELIMITER $$

CREATE  PROCEDURE `dodajOsobu`(IN ime VARCHAR(50), IN prezime VARCHAR(50), IN mail VARCHAR(50),
 IN ferId VARCHAR(50), IN brojMob VARCHAR(20), IN passwordVAR VARCHAR(255), IN JMBAG VARCHAR(10), IN datRod DATE, IN spol CHAR(1),
IN brOsobne VARCHAR(20),IN brPutovnice VARCHAR(30),IN osobnaVrijediDo DATE,IN putovnicaVrijediDo DATE,IN uloga CHAR(1), IN zivotopis BLOB, IN MBG VARCHAR(9), IN OIB VARCHAR(11), IN idNadredjena INT(10) )
BEGIN
IF (spol IN ('m','z','M','Z') OR spol IS NULL) THEN
IF NOT EXISTS (SELECT * FROM OSOBA WHERE OSOBA.JMBAG=JMBAG ) THEN
IF NOT EXISTS (SELECT * FROM OSOBA WHERE OSOBA.OIB=OIB ) THEN
IF NOT EXISTS (SELECT * FROM OSOBA WHERE OSOBA.MBG=MBG ) THEN
IF NOT EXISTS (SELECT * FROM OSOBA WHERE OSOBA.ferId=ferId ) THEN
IF ((osobnaVrijediDo>CURDATE() OR osobnaVrijediDo IS NULL) AND (putovnicaVrijediDo>CURDATE() OR putovnicaVrijediDo IS NULL)) THEN

INSERT INTO OSOBA VALUES (NULL, ime, prezime, mail, brojMob, ferId, passwordVAR,  JMBAG, spol,datRod,brOsobne,brPutovnice,osobnaVrijediDo,putovnicaVrijediDo,uloga,zivotopis,MBG,OIB,idNadredjena);

ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT =' Istekla je putovnica ili osobna iskaznica!';
END IF;
ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Greška: ferid već postoji u bazi !';
END IF;
ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Greška: MBG već postoji u bazi !';
END IF;
ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Greška: OIB već postoji u bazi !';
END IF;
ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Greška: JMBAG već postoji u bazi !';
END IF;
ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Greška: Pogrešno unešen spol! !';
END IF;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajOsobu`(IN idOsobe INT(10), ime VARCHAR(50), IN prezime VARCHAR(50), IN mail VARCHAR(50),
 IN ferId VARCHAR(50), IN brojMob VARCHAR(20), IN passwordVAR VARCHAR(255), IN JMBAG VARCHAR(10), IN datRod DATE, IN spol CHAR(1),
IN brOsobne VARCHAR(20),IN brPutovnice VARCHAR(30),IN osobnaVrijediDo DATE,IN putovnicaVrijediDo DATE,IN uloga CHAR(1), IN zivotopis BLOB, IN MBG VARCHAR(9), IN OIB VARCHAR(11),IN idNadredjena INT(10))
BEGIN
IF (spol IN ('m','z','M','Z')) THEN 
IF ((osobnaVrijediDo>CURDATE() OR osobnaVrijediDo IS NULL) AND (putovnicaVrijediDo>CURDATE() OR putovnicaVrijediDo IS NULL)) THEN
IF EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe=idOsobe) THEN
UPDATE OSOBA
SET OSOBA.JMBAG=JMBAG, OSOBA.password=passwordVAR, OSOBA.ime=ime, OSOBA.prezime=prezime, OSOBA.mail=mail, OSOBA.ferId=ferId, OSOBA.brojMob=brojMob, OSOBA.datRod=datRod, OSOBA.spol=spol, OSOBA.brOsobne=brOsobne, OSOBA.brPutovnice=brPutovnice, OSOBA.putovnicaVrijediDo=putovnicaVrijediDo, OSOBA.osobnaVrijediDo=osobnaVrijediDo, OSOBA.uloga=uloga,OSOBA.zivotopis=zivotopis, OSOBA.MBG=MBG, OSOBA.OIB=OIB, OSOBA.idNadredjena=idNadredjena
WHERE OSOBA.idOsobe=idOsobe ;


ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Greška: Unijeli ste pogrešni id osobe!';
END IF;
 ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Greška: Osobna ili putovnica su istekle !';
END IF;
    ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Greška: Pogrešno unseen spol! !';
END IF;

END $$
DELIMITER ;


DELIMITER $$
CREATE  PROCEDURE `dodajObavljaFunkciju`(IN idOsobe INT UNSIGNED, IN idFunkcije INT UNSIGNED, IN idElektrijade INT(10))
BEGIN 
IF EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe = idOsobe && OSOBA.uloga = "O") THEN
	IF NOT EXISTS ( SELECT * FROM ObavljaFunkciju WHERE ObavljaFunkciju.idOsobe = idOsobe && ObavljaFunkciju.idFunkcije = idFunkcije && ObavljaFunkciju.idElektrijade = idElektrijade) THEN
		IF EXISTS ( SELECT * FROM FUNKCIJA WHERE FUNKCIJA.idFunkcije = idFunkcije) THEN
			IF EXISTS ( SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN
				INSERT INTO ObavljaFunkciju VALUES (NULL,idOsobe, idFunkcije, idElektrijade);
			ELSE 
				SIGNAL SQLSTATE '23000'SET MESSAGE_TEXT = 'Greška: unesena nepostojeća elektrijada';
			END IF;
		ELSE
			SIGNAL SQLSTATE '23000'SET MESSAGE_TEXT = 'Greška: unesena nepostojeća funkcija!';
		END IF;
	ELSE
		SIGNAL SQLSTATE '42000'SET MESSAGE_TEXT = 'Greška: uneseni zapis već postoji!';
	END IF;
ELSE
	SIGNAL SQLSTATE '23000'SET MESSAGE_TEXT = 'Greška: navedena osoba ne postoji ili nije član OZSN!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `	azurirajObavljaFunkciju`(IN idObavljaFunkciju INT UNSIGNED, IN idOsobe INT UNSIGNED, IN idFunkcije INT UNSIGNED, IN idElektrijade INT(10))
BEGIN 
IF EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe = idOsobe && OSOBA.uloga = "O") THEN
	IF NOT EXISTS ( SELECT * FROM ObavljaFunkciju WHERE ObavljaFunkciju.idOsobe = idOsobe && ObavljaFunkciju.idFunkcije = idFunkcije && ObavljaFunkciju.idElektrijade = idElektrijade) THEN
		IF EXISTS ( SELECT * FROM FUNKCIJA WHERE FUNKCIJA.idFunkcije = idFunkcije) THEN
			IF EXISTS ( SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN
				UPDATE ObavljaFunkciju 
                SET ObavljaFunkciju.idOsobe=idOsobe, ObavljaFunkciju.idFunkcije=idFunkcije, ObavljaFunkciju.idElektrijade=idElektrijade
                WHERE ObavljaFunkciju.idObavljaFunkciju=idObavljaFunkciju;
			ELSE 
				SIGNAL SQLSTATE '23000'SET MESSAGE_TEXT = 'Greška: unesena nepostojeća elektrijada';
			END IF;
		ELSE
			SIGNAL SQLSTATE '23000'SET MESSAGE_TEXT = 'Greška: unesena nepostojeća funkcija!';
		END IF;
	ELSE
		SIGNAL SQLSTATE '42000'SET MESSAGE_TEXT = 'Greška: uneseni zapis već postoji!';
	END IF;
ELSE
	SIGNAL SQLSTATE '23000'SET MESSAGE_TEXT = 'Greška: navedena osoba ne postoji ili nije član OZSN!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiElektrijadu`(IN idElektrijade INT(10))
BEGIN
IF NOT EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN
	SIGNAL SQLSTATE '42000'SET MESSAGE_TEXT = 'Ne postoji tražena Elektrijada'; 
ELSE
DELETE FROM ELEKTRIJADA
WHERE ELEKTRIJADA.idElektrijade=idElektrijade ;

END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiOsobu`(IN idOsobe INT(10))
BEGIN
IF NOT EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe=idOsobe) THEN
	SIGNAL SQLSTATE '42000'SET MESSAGE_TEXT = 'Greška: Ne postoji OSOBA kuju želite izbrisati';
ELSE
DELETE FROM OSOBA
WHERE OSOBA.idOsobe=idOsobe ;

END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiObavljaFunkciju`(IN idObavljaFunkciju INT(10))
BEGIN
IF NOT EXISTS (SELECT * FROM ObavljaFunkciju WHERE ObavljaFunkciju.idObavljaFunkciju=idObavljaFunkciju) THEN
	SIGNAL SQLSTATE '42000'SET MESSAGE_TEXT = 'Greška: Ne postoji veza između osobe i fukncije kuju želite izbrisati';
ELSE
DELETE FROM ObavljaFunkciju
WHERE ObavljaFunkciju.idObavljaFunkciju=idObavljaFunkciju ;

END IF;
END $$
DELIMITER ;
