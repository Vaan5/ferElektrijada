 DELIMITER $$
CREATE  PROCEDURE `azurirajElektrijadu`(IN idElektrijade INT(10), IN mjestoOdrzavanja VARCHAR(100), IN datumPocetka DATE, IN datumKraja DATE, IN ukupniRezultat SMALLINT(6),IN rokZaZnanje DATE, IN rokZaSport DATE, IN drzava VARCHAR(100),IN ukupanBrojSudionika INT)
BEGIN
IF NOT EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN
	SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji trazena Elektrijada ';
ELSE
IF(datumPocetka<datumKraja) THEN
IF(datumPocetka>rokZaZnanje AND datumPocetka>rokZaSport) THEN
IF (datumPocetka<datumKraja) THEN
IF (mjestoOdrzavanja IS NOT NULL) THEN
UPDATE ELEKTRIJADA
SET ELEKTRIJADA.datumKraja = datumKraja, ELEKTRIJADA.mjestoOdrzavanja=mjestoOdrzavanja , ELEKTRIJADA.ukupniRezultat=ukupniRezultat, ELEKTRIJADA.drzava=drzava, ELEKTRIJADA.datumPocetka=datumPocetka, ELEKTRIJADA.ukupanBrojSudionika=ukupanBrojSudionika, ELEKTRIJADA.rokZaSport=rokZaSport, ELEKTRIJADA.rokZaZnanje=rokZaZnanje
WHERE ELEKTRIJADA.idElektrijade = idElektrijade;

ELSE
    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Unesite drzavu!';
END IF;
ELSE
    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Unesite mjesto odrzavanja!';
END IF;
ELSE
    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Pogrešan unos datuma roka za znanje ili roka za sport Elektrijade!'; 
END IF;

ELSE
   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Pogrešan unos datuma pocetka i datuma kraja Elektrijade!';  
END IF;

END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajElektrijadu`(IN mjestoOdrzavanja VARCHAR(100), IN datumPocetka DATE, IN datumKraja DATE, IN ukupniRezultat SMALLINT(6), IN rokZaZnanje DATE, IN rokZaSport DATE, IN drzava VARCHAR(100),IN ukupanBrojSudionika INT)
BEGIN
IF NOT EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.datumPocetka = datumPocetka) THEN
IF (datumPocetka<datumKraja) THEN
IF (mjestoOdrzavanja IS NOT NULL) THEN
IF (drzava IS NOT NULL) THEN
IF(datumPocetka>rokZaZnanje AND datumPocetka>rokZaSport) THEN
	INSERT INTO ELEKTRIJADA VALUES (NULL,mjestoOdrzavanja,datumPocetka,datumKraja,ukupniRezultat,rokZaZnanje,rokZaSport,drzava,ukupanBrojSudionika);
ELSE
    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Pogrešan unos datuma roka za znanje ili roka za sport Elektrijade!';
END IF;
ELSE
    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Unesite drzavu!';
END IF;
ELSE
    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Unesite mjesto odrzavanja!';
END IF;
ELSE
    SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Pogrešan unos datuma pocetka i datuma kraja Elektrijade!!'; 
END IF;
ELSE
    SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Odabrana elektrijada je već unešena!';  
END IF;

END $$
DELIMITER ;


DELIMITER $$

CREATE  PROCEDURE `dodajOsobu`(IN ime VARCHAR(50), IN prezime VARCHAR(50), IN mail VARCHAR(50),
 IN ferId VARCHAR(50), IN brojMob VARCHAR(20), IN passwordVAR VARCHAR(255), IN JMBAG VARCHAR(10), IN datRod DATE, IN spol CHAR(1),
IN brOsobne VARCHAR(20),IN brPutovnice VARCHAR(30),IN osobnaVrijediDo DATE,IN putovnicaVrijediDo DATE,IN aktivanDokument BOOLEAN,IN uloga CHAR(1), IN zivotopis VARCHAR(200), IN MBG VARCHAR(9), IN OIB VARCHAR(11), IN idNadredjena INT(10))
BEGIN
IF (NOT EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe = idNadredjena) OR idNadredjena IS NULL) THEN
	SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji nadređena osoba. ';
ELSE
IF (spol IN ('m','z','M','Z') OR spol IS NULL) THEN
IF NOT EXISTS (SELECT * FROM OSOBA WHERE OSOBA.brOsobne=brOsobne ) THEN
IF NOT EXISTS (SELECT * FROM OSOBA WHERE OSOBA.brPutovnice=brPutovnice ) THEN
IF NOT EXISTS (SELECT * FROM OSOBA WHERE OSOBA.mail=mail ) THEN
IF NOT EXISTS (SELECT * FROM OSOBA WHERE OSOBA.JMBAG=JMBAG ) THEN
IF NOT EXISTS (SELECT * FROM OSOBA WHERE OSOBA.OIB=OIB ) THEN
IF NOT EXISTS (SELECT * FROM OSOBA WHERE OSOBA.MBG=MBG ) THEN
IF NOT EXISTS (SELECT * FROM OSOBA WHERE OSOBA.ferId=ferId ) THEN
IF ((osobnaVrijediDo>CURDATE() OR osobnaVrijediDo IS NULL) AND (putovnicaVrijediDo>CURDATE() OR putovnicaVrijediDo IS NULL)) THEN

INSERT INTO OSOBA VALUES (NULL, ime, prezime, mail, brojMob, ferId, passwordVAR,  JMBAG, spol,datRod,brOsobne,brPutovnice,osobnaVrijediDo,putovnicaVrijediDo,aktivanDokument,uloga,zivotopis,MBG,OIB,idNadredjena);

ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT =' Istekla je putovnica ili osobna iskaznica!';
END IF;
ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'ferid već postoji u bazi !';
END IF;
ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'MBG već postoji u bazi !';
END IF;
ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'OIB već postoji u bazi !';
END IF;
ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'JMBAG već postoji u bazi !';
END IF;
ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT ='Mail već postoji u bazi!';
END IF;
ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Broj putovnice već postoji u bazi !';
END IF;
ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Broj osobne već postoji !';
END IF;
ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Pogrešno unešen spol! !';
END IF;

END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajOsobu`(IN idOsobe INT(10), ime VARCHAR(50), IN prezime VARCHAR(50), IN mail VARCHAR(50),
 IN ferId VARCHAR(50), IN brojMob VARCHAR(20), IN passwordVAR VARCHAR(255), IN JMBAG VARCHAR(10), IN datRod DATE, IN spol CHAR(1),
IN brOsobne VARCHAR(20),IN brPutovnice VARCHAR(30),IN osobnaVrijediDo DATE,IN putovnicaVrijediDo DATE,IN aktivanDokument BOOLEAN,IN uloga CHAR(1), IN zivotopis VARCHAR(200), IN MBG VARCHAR(9), IN OIB VARCHAR(11),IN idNadredjena INT(10))
BEGIN
IF (NOT EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe = idNadredjena) OR idNadredjena IS NULL) THEN
	SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji nadređena osoba. ';
ELSE
IF (spol IN ('m','z','M','Z') OR spol IS NULL) THEN 
IF ((osobnaVrijediDo>CURDATE() OR osobnaVrijediDo IS NULL) AND (putovnicaVrijediDo>CURDATE() OR putovnicaVrijediDo IS NULL)) THEN
IF EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe=idOsobe) THEN
UPDATE OSOBA
SET OSOBA.JMBAG=JMBAG, OSOBA.password=passwordVAR, OSOBA.ime=ime, OSOBA.prezime=prezime, OSOBA.mail=mail, OSOBA.ferId=ferId, OSOBA.brojMob=brojMob, OSOBA.datRod=datRod, OSOBA.spol=spol, OSOBA.brOsobne=brOsobne, OSOBA.brPutovnice=brPutovnice, OSOBA.putovnicaVrijediDo=putovnicaVrijediDo, OSOBA.osobnaVrijediDo=osobnaVrijediDo, OSOBA.uloga=uloga,OSOBA.zivotopis=zivotopis, OSOBA.MBG=MBG, OSOBA.OIB=OIB, OSOBA.idNadredjena=idNadredjena,OSOBA.aktivanDokument=aktivanDokument
WHERE OSOBA.idOsobe=idOsobe ;


ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Unijeli ste pogrešni id osobe!';
END IF;
 ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Osobna ili putovnica su istekle !';
END IF;
    ELSE 
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Pogrešno unesen spol! !';
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
				SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'unesena nepostojeća elektrijada';
			END IF;
		ELSE
			SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'unesena nepostojeća funkcija!';
		END IF;
	ELSE
		SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'uneseni zapis već postoji!';
	END IF;
ELSE
	SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'navedena osoba ne postoji ili nije član OZSN!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `	azurirajObavljaFunkciju`(IN idObavljaFunkciju INT UNSIGNED, IN idOsobe INT UNSIGNED, IN idFunkcije INT UNSIGNED, IN idElektrijade INT(10))
BEGIN 
IF NOT EXISTS (SELECT * FROM ObavljaFunkciju WHERE ObavljaFunkciju.idObavljaFunkciju = idObavljaFunkciju) THEN
	SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji veza osobe i funkcije koju želite izmijeniti! ';
ELSE
IF EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe = idOsobe && OSOBA.uloga = "O") THEN
	IF NOT EXISTS ( SELECT * FROM ObavljaFunkciju WHERE ObavljaFunkciju.idOsobe = idOsobe && ObavljaFunkciju.idFunkcije = idFunkcije && ObavljaFunkciju.idElektrijade = idElektrijade) THEN
		IF EXISTS ( SELECT * FROM FUNKCIJA WHERE FUNKCIJA.idFunkcije = idFunkcije) THEN
			IF EXISTS ( SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN
				UPDATE ObavljaFunkciju 
                SET ObavljaFunkciju.idOsobe=idOsobe, ObavljaFunkciju.idFunkcije=idFunkcije, ObavljaFunkciju.idElektrijade=idElektrijade
                WHERE ObavljaFunkciju.idObavljaFunkciju=idObavljaFunkciju;
			ELSE 
				SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'unesena nepostojeća elektrijada!';
			END IF;
		ELSE
			SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'unesena nepostojeća funkcija!';
		END IF;
	ELSE
		SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'uneseni zapis već postoji!';
	END IF;
ELSE
	SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'navedena osoba ne postoji ili nije član OZSN!';
END IF;
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiElektrijadu`(IN idElektrijade INT(10))
BEGIN
IF NOT EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN
	SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Ne postoji tražena Elektrijada'; 
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
	SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Ne postoji osoba kuju želite izbrisati';
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
	SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Ne postoji veza između osobe i fukncije koju želite izbrisati';
ELSE
DELETE FROM ObavljaFunkciju
WHERE ObavljaFunkciju.idObavljaFunkciju=idObavljaFunkciju ;

END IF;
END $$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `dohvatiPopisSvihBusevaNaElektrijadi`(IN idElektrijade INT(10))
BEGIN
     IF EXISTS (SELECT * FROM Elektrijada WHERE Elektrijada.idElektrijade = idElektrijade) THEN
		SELECT DISTINCT BUS.idBusa, BUS.registracija , BUS.brojMjesta, BUS.brojBusa
		FROM BUS 
		RIGHT JOIN PUTOVANJE ON PUTOVANJE.idBusa=BUS.idBusa
		RIGHT JOIN SUDJELOVANJE ON SUDJELOVANJE.idPutovanja=PUTOVANJE.idPutovanja
		WHERE SUDJELOVANJE.idElektrijade = idElektrijade;
		
      ELSE
          SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Unesen je nepostojeci idElektrijade';
	  END IF;


END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `dohvatiPopisSvihObjavaOElektrijadi`(IN idElektrijade INT(10))
BEGIN
	IF EXISTS (SELECT * FROM Elektrijada WHERE Elektrijada.idElektrijade = idElektrijade) THEN
		SELECT DISTINCT OBJAVA.idObjave, OBJAVA.datumObjave,OBJAVA.link, OBJAVA.autorIme ,OBJAVA.autorPrezime,OBJAVA.idMedija ,OBJAVA.dokument
		FROM OBJAVA
		JOIN 	objavaOElektrijadi ON OBJAVA.idObjave = objavaOElektrijadi.idObjave		
		WHERE objavaOElektrijadi.idElektrijade = idElektrijade;
    ELSE 
       SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Unesen je nepostojeci idElektrijade';
	END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiOdredeniAtribut`(IN idOsoba INT(10))
BEGIN
IF EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe=idOsoba) THEN
SELECT atribut.nazivAtributa FROM sudjelovanje 
LEFT JOIN imaatribut ON sudjelovanje.idSudjelovanja = imaatribut.idSudjelovanja
 JOIN atribut ON imaatribut.idAtributa = atribut.idAtributa
WHERE sudjelovanje.idOsobe = idOsoba;
ELSE
   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Unesena je nepostojeća osoba.';
END IF;
END $$
DELIMITER ;

DELIMITER $$

CREATE  PROCEDURE `dohvatiOsobnaPodrucja`(IN idElektrijada INT(10), IN idOsobe INT(10))
BEGIN
IF EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe=idOsobe) THEN
IF EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade=idElektrijada) THEN

SELECT podrucje.idPodrucja FROM sudjelovanje 
LEFT JOIN podrucjeSudjelovanja ON sudjelovanje.idSudjelovanja = podrucjeSudjelovanja.idSudjelovanja AND sudjelovanje.idElektrijade=idElektrijada
 JOIN podrucje ON podrucje.idPodrucja = podrucjeSudjelovanja.idPodrucja
WHERE sudjelovanje.idOsobe = idOsobe;

ELSE
   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Unesena je nepostojeća elektrijada.';
END IF;
ELSE
   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Unesena je nepostojeća osoba.';
END IF;
END $$
DELIMITER ;


DELIMITER $$
CREATE  PROCEDURE `dodajPojedinacniRezultat`(IN idSudjelovanja INT UNSIGNED,IN idPodrucja INT UNSIGNED, IN rezultatPojedinacni SMALLINT)
BEGIN
IF EXISTS (SELECT * FROM PODRUCJESUDJELOVANJA WHERE PODRUCJESUDJELOVANJA.idSudjelovanja=idSudjelovanja AND PODRUCJESUDJELOVANJA.idPodrucja=idPodrucja && PODRUCJESUDJELOVANJA.vrstaPodrucja <> 0) THEN
	UPDATE PODRUCJESUDJELOVANJA SET PODRUCJESUDJELOVANJA.rezultatPojedinacni = rezultatPojedinacni
	WHERE PODRUCJESUDJELOVANJA.idSudjelovanja=idSudjelovanja AND PODRUCJESUDJELOVANJA.idPodrucja=idPodrucja;
ELSE
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Traženi zapis ne postoji! / Navedena disciplina nema pojedinačnih rezultata!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `dodajGrupniRezultat`(IN idPodrucja INT(10),IN idElektrijade INT,IN rezultatGrupni SMALLINT(6))
BEGIN
IF EXISTS (SELECT* FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idPodrucja) THEN
IF EXISTS (SELECT* FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN

IF NOT EXISTS (SELECT* 
		FROM ElekPodrucje WHERE ElekPodrucje.idPodrucja = idPodrucja and ElekPodrucje.idElektrijade=idElektrijade) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Na toj elektrijadi ne postoji to područje!';
ELSE
	UPDATE ElekPodrucje
    SET ElekPodrucje.rezultatGrupni=rezultatGrupni
    WHERE ElekPodrucje.idPodrucja = idPodrucja and ElekPodrucje.idElektrijade=idElektrijade ;

END IF;
ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Upisan nepostojeći id Elektrijade!';
END IF;

ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Upisano nepostojeće područje!';
END IF;


END$$
DELIMITER ;


DELIMITER $$
CREATE  PROCEDURE `azurirajElekPodrucje`(IN idElekPodrucje INT(10), IN idPodrucja INT(10), IN rezultatGrupni SMALLINT(6),IN slikaLink VARCHAR(255), IN idElektrijade INT(10), IN ukupanBrojEkipa INT)
BEGIN
IF EXISTS (SELECT* FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idPodrucja) THEN
IF EXISTS (SELECT* FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN
IF NOT EXISTS (SELECT* 
		FROM ElekPodrucje WHERE ElekPodrucje.idElekPodrucje = idElekPodrucje ) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji područje koje želite ažurirati';
ELSE
	UPDATE ElekPodrucje
    SET ElekPodrucje.datumPocetka=datumPocetka, ElekPodrucje.rezultatGrupni=rezultatGrupni, ElekPodrucje.slikaLink=slikaLink, ElekPodrucje.idPodrucja=idPodrucja, ElekPodrucje.ukupanBrojEkipa=ukupanBrojEkipa
	WHERE ElekPodrucje.idElekPodrucje = idElekPodrucje  ;

END IF;
ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Upisana nepostojeća Elektrijada!';
END IF;

ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Upisan nepostojeće područje!';
END IF;

END $$
DELIMITER ; 

DELIMITER $$
CREATE  PROCEDURE `brisiElekPodrucje`(IN idElekPodrucje INT(10))
BEGIN

IF NOT EXISTS (SELECT* 
		FROM ElekPodrucje WHERE ElekPodrucje.idElekPodrucje = idElekPodrucje ) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji područje koje želite izbrisati';
ELSE
	DELETE FROM ElekPodrucje
	WHERE ElekPodrucje.idElekPodrucje = idElekPodrucje ;

END IF;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajElekPodrucje`(IN idPodrucja INT(10), IN rezultatGrupni SMALLINT(6),IN slikaLink VARCHAR(255), IN idElektrijade INT(10), IN ukupanBrojEkipa INT(10))
BEGIN
IF EXISTS (SELECT * FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idPodrucja) THEN
IF EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN
IF NOT EXISTS (SELECT * FROM ElekPodrucje WHERE ElekPodrucje.idElektrijade = idElektrijade  AND ElekPodrucje.idPodrucja = idPodrucja ) THEN

		INSERT INTO ElekPodrucje VALUES (NULL,idPodrucja,rezultatGrupni,slikaLink,idElektrijade,ukupanBrojEkipa);
   
ELSE
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Već postoji unos za ovo podrucje na ovoj Elektrijadi!';
END IF;

ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Upisan nepostojeći datum Elektrijade!';
END IF;

ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Upisan nepostojeće područje!';
END IF;

END $$
DELIMITER ;

