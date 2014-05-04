DELIMITER $$
CREATE  PROCEDURE `dohvatiPopisSvihSudionikaIzPodrucja`(IN idElektrijade INT(10), IN idPodrucja INT)
BEGIN
	IF EXISTS (SELECT * FROM Elektrijada WHERE Elektrijada.idElektrijade = idElektrijade) THEN
		IF EXISTS (SELECT * FROM podrucje WHERE podrucje.idPodrucja = idPodrucja) THEN
			SELECT DISTINCT osoba.*, podrucjeSudjelovanja.*, sudjelovanje.*
			FROM osoba
			JOIN sudjelovanje ON sudjelovanje.idOsobe = osoba.idOsobe
			JOIN podrucjeSudjelovanja ON sudjelovanje.idSudjelovanja = podrucjeSudjelovanja.idSudjelovanja
			WHERE sudjelovanje.idElektrijade = idElektrijade AND podrucjeSudjelovanja.idPodrucja = idPodrucja;
		ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Unesen je nepostojeći identifikator područja';
		END IF;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Unesen je nepostojeći identifikator Elektrijade!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiOdredeniAtribut`(IN idOsoba INT(10), IN idElektrijade INT(10))
BEGIN
	IF EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe=idOsoba) THEN
		SELECT atribut.nazivAtributa FROM sudjelovanje 
		LEFT JOIN imaatribut ON sudjelovanje.idSudjelovanja = imaatribut.idSudjelovanja AND sudjelovanje.idElektrijade=idElektrijade
		JOIN atribut ON imaatribut.idAtributa = atribut.idAtributa
		WHERE sudjelovanje.idOsobe = idOsoba;
	ELSE
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Nepoznati korisnik!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$

CREATE  PROCEDURE `dohvatiOsobnaPodrucja`(IN idElektrijada INT(10), IN idOsobe INT(10))
BEGIN
	IF EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe=idOsobe) THEN
		IF EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade=idElektrijada) THEN

			SELECT DISTINCT podrucje.idPodrucja FROM sudjelovanje 
			LEFT JOIN imaatribut ON sudjelovanje.idSudjelovanja = imaatribut.idSudjelovanja AND sudjelovanje.idElektrijade=idElektrijada
			 JOIN podrucje ON podrucje.idPodrucja = imaatribut.idPodrucja
			JOIN atribut ON imaatribut.idAtributa = atribut.idAtributa AND UPPER(nazivAtributa)='VODITELJ'
			WHERE sudjelovanje.idOsobe = idOsobe;

		ELSE
		   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Nepoznati identifikator Elektrijade!';
		END IF;
	ELSE
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Nepoznati korisnik!';
	END IF;
END $$
DELIMITER ;

--                            DOHVAT PODATAKA

DELIMITER $$
CREATE  PROCEDURE `dohvatiFunkcijeOdbora`()
BEGIN

SELECT * FROM FUNKCIJA ORDER BY nazivFunkcije ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiGodineStudija`()
BEGIN

	SELECT * FROM GODSTUD ORDER BY studij ASC, godina ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiZavode`()
BEGIN

	SELECT * FROM ZAVOD ORDER BY skraceniNaziv ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiVelicine`()
BEGIN

	SELECT * FROM VELMAJICE ORDER BY velicina ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiSmjerove`()
BEGIN

	SELECT * FROM SMJER ORDER BY nazivSmjera ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiRadnaMjesta`()
BEGIN

	SELECT * FROM RADNOMJESTO ORDER BY naziv ASC;

END $$
DELIMITER ;

--			AŽURIRANJE PODATAKA

DELIMITER $$
CREATE  PROCEDURE `azurirajFunkcijuOdbora`(IN idFunkcije INT UNSIGNED, IN nazivFunkcije VARCHAR (100))
BEGIN
IF EXISTS (SELECT * FROM FUNKCIJA WHERE FUNKCIJA.idFunkcije = idFunkcije) THEN
	IF NOT EXISTS (SELECT * FROM FUNKCIJA WHERE FUNKCIJA.nazivFunkcije = nazivFunkcije) THEN
		IF (nazivFunkcije IS NOT NULL) THEN
			UPDATE FUNKCIJA SET
				FUNKCIJA.nazivFunkcije = nazivFunkcije
			WHERE FUNKCIJA.idFunkcije = idFunkcije;
		ELSE 
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Naziv funkcije je obavezan!';
		END IF;
	ELSE
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Uneseni naziv funkcije već postoji!';
	END IF;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Nepoznati identifikator funkcije!';
END IF;
END $$
DELIMITER ;

--			BRISANJE PODATAKA
DELIMITER $$
CREATE  PROCEDURE `brisiFunkciju`(IN idObavljaFunkciju  INT(10))
BEGIN
IF EXISTS ( SELECT * FROM ObavljaFunkciju WHERE ObavljaFunkciju.idObavljaFunkciju = idObavljaFunkciju) THEN
	DELETE FROM ObavljaFunkciju
    WHERE ObavljaFunkciju.idObavljaFunkciju = idObavljaFunkciju;
ELSE
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Traženi zapis nije pronađen!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiFunkcijuOdbora`(IN idFunkcije INT UNSIGNED)
BEGIN
	IF EXISTS (SELECT * FROM FUNKCIJA WHERE FUNKCIJA.idFunkcije = idFunkcije) THEN
		DELETE FROM FUNKCIJA WHERE FUNKCIJA.idFunkcije = idFunkcije;
	ELSE
		SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Traženi zapis ne postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajFunkcijuOdbora`(IN nazivFunkcije VARCHAR (100))
BEGIN
IF EXISTS ( SELECT * FROM FUNKCIJA WHERE FUNKCIJA.nazivFunkcije = nazivFunkcije) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Naziv funkcije već postoji!';
	ELSE
		IF (nazivFunkcije IS NULL) THEN
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Naziv funkcije je obavezan!';
		ELSE 
			INSERT INTO FUNKCIJA(nazivFunkcije) VALUES (nazivFunkcije);
		END IF;
END IF;
END $$
DELIMITER ;