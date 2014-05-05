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

DELIMITER $$

CREATE PROCEDURE `provjeriActiveOzsn`(IN idOsobe INT(10), IN idElektrijada INT(10))
BEGIN
IF EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe=idOsobe) THEN
IF EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade=idElektrijada) THEN

SELECT * FROM obavljafunkciju
WHERE obavljafunkciju.idOsobe = idOsobe AND obavljafunkciju.idElektrijade=idElektrijada;

ELSE
   SIGNAL SQLSTATE '23000'SET MESSAGE_TEXT = 'Unesena je nepostojeća elektrijada.';
END IF;
ELSE
   SIGNAL SQLSTATE '23000'SET MESSAGE_TEXT = 'Unesena je nepostojeća osoba.';
END IF;
END $$
DELIMITER ;
DELIMITER $$

CREATE PROCEDURE `provjeriActiveSudionik`(IN idOsobe INT(10), IN idElektrijada INT(10))
BEGIN
IF EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe=idOsobe) THEN
IF EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade=idElektrijada) THEN

SELECT * FROM sudjelovanje
JOIN podrucjesudjelovanja ON sudjelovanje.idSudjelovanja = podrucjesudjelovanja.idSudjelovanja
WHERE sudjelovanje.idOsobe = idOsobe AND sudjelovanje.idElektrijade=idElektrijada;

ELSE
   SIGNAL SQLSTATE '23000'SET MESSAGE_TEXT = 'Unesena je nepostojeća elektrijada.';
END IF;
ELSE
   SIGNAL SQLSTATE '23000'SET MESSAGE_TEXT = 'Unesena je nepostojeća osoba.';
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

DELIMITER $$
CREATE  PROCEDURE `dohvatiUdruge`()
BEGIN

SELECT * FROM UDRUGA ORDER BY nazivUdruge ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiUsluge`()
BEGIN

SELECT * FROM USLUGA ORDER BY nazivUsluge ASC;

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

DELIMITER $$
CREATE  PROCEDURE `azurirajUdrugu`(IN idUdruge INT UNSIGNED, IN nazivUdruge VARCHAR (50))
BEGIN
	IF EXISTS (SELECT * FROM UDRUGA WHERE UDRUGA.idUdruge = idUdruge) THEN
		IF NOT EXISTS (SELECT * FROM UDRUGA WHERE UDRUGA.nazivUdruge = nazivUdruge) THEN
			IF (nazivUdruge IS NOT NULL) THEN
				UPDATE UDRUGA SET
					UDRUGA.nazivUdruge = nazivUdruge
					WHERE UDRUGA.idUdruge = idUdruge;
			ELSE
				 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Naziv udruge je obavezan!';
			END IF;			
		ELSE
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Naziv udruge već postoji!';
		END IF;
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tražena udruga ne postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajMedij`(IN idMedija INT(10), IN nazivMedija VARCHAR(10))
BEGIN
	IF NOT EXISTS (SELECT * FROM MEDIJ WHERE MEDIJ.nazivMedija=nazivMedija) THEN
		
				UPDATE MEDIJ
				SET MEDIJ.nazivMedija=nazivMedija
				WHERE MEDIJ.idMedija=idMedija;
			
	ELSE 
       SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Naziv medija već postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajUslugu`(IN idUsluge INT UNSIGNED, IN nazivUsluge VARCHAR (100))
BEGIN
	IF EXISTS (SELECT * FROM USLUGA WHERE USLUGA.idUsluge = idUsluge) THEN
		IF NOT EXISTS (SELECT * FROM USLUGA WHERE USLUGA.nazivUsluge = nazivUsluge) THEN
			IF (nazivUsluge IS NOT NULL) THEN
				UPDATE USLUGA SET
				USLUGA.nazivUsluge = nazivUsluge
				WHERE USLUGA.idUsluge = idUsluge;
			ELSE 
				 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Naziv usluge je obavezan!';
			END IF;
		ELSE
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Naziv usluge već postoji!';
		END IF;
	ELSE
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Nepoznata usluga!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajRadnoMjesto`( IN id INT UNSIGNED,  IN ime VARCHAR(100)  )
BEGIN
	IF NOT EXISTS (SELECT * FROM RADNOMJESTO WHERE naziv = ime) THEN
		IF EXISTS (SELECT * FROM RADNOMJESTO WHERE idRadnogMjesta = id) THEN
			UPDATE RADNOMJESTO 
			SET idRadnogMjesta=id, naziv=ime WHERE idRadnogMjesta = id;
		ELSE 
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Nepoznato radno mjesto!';
		END IF;
	ELSE
		SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Radno mjesto već postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajSmjer`( IN id INT UNSIGNED,  IN naziv VARCHAR(100)  )
BEGIN
	IF NOT EXISTS (SELECT * FROM SMJER WHERE nazivSmjera = naziv) THEN
		IF EXISTS (SELECT * FROM SMJER WHERE idSmjera = id) THEN
			UPDATE SMJER
			SET idSmjera=id, nazivSmjera=naziv WHERE idSmjera = id;
		ELSE 
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Nepoznati identifikator smjera!';
		END IF;
	ELSE
		SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Naziv smjera već postoji!';
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

DELIMITER $$
CREATE  PROCEDURE `brisiClanaUdruge`(IN idUdruge INT UNSIGNED, IN idOsobe INT UNSIGNED)
BEGIN
	IF EXISTS (SELECT * FROM JeUUdruzi WHERE JeUUdruzi.idUdruge = idUdruge && JeUUdruzi.idOsobe = idOsobe) THEN
		DELETE FROM JeUUdruzi WHERE JeUUdruzi.idUdruge = idUdruge && JeUUdruzi.idOsobe = idOsobe;
	ELSE
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Traženi zapis ne postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiUdrugu`(IN idUdruge INT UNSIGNED)
BEGIN
	IF EXISTS (SELECT * FROM UDRUGA WHERE UDRUGA.idUdruge = idUdruge) THEN
		DELETE FROM UDRUGA WHERE UDRUGA.idUdruge = idUdruge;
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tražena udruga nije pronađena!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiMedij`(IN idMedija INT(10))
BEGIN
	IF EXISTS (SELECT * FROM MEDIJ WHERE MEDIJ.idMedija=idMedija) THEN
		DELETE FROM MEDIJ
		WHERE MEDIJ.idMedija=idMedija;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji traženi medij!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiUslugu`(IN idUsluge INT UNSIGNED)
BEGIN
	IF EXISTS ( SELECT * FROM USLUGA WHERE USLUGA.idUsluge = idUsluge) THEN
		DELETE FROM USLUGA WHERE USLUGA.idUsluge = idUsluge;
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tražena usluga ne postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiSmjer`( IN id INT UNSIGNED )
BEGIN
IF EXISTS (SELECT * FROM SMJER WHERE idSmjera = id) THEN
DELETE FROM SMJER WHERE idSmjera = id;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Traženi smjer ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiRadnoMjesto`( IN id INT UNSIGNED )
BEGIN
IF EXISTS (SELECT * FROM RADNOMJESTO  WHERE idRadnogMjesta = id) THEN
DELETE FROM RADNOMJESTO WHERE idRadnogMjesta = id;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Traženo radno mjesto ne postoji!';
END IF;
END $$
DELIMITER ;

--				DODAVANJE PODATAKA
DELIMITER $$
CREATE  PROCEDURE `dodajUdrugu`( IN nazivUdruge VARCHAR (50))
BEGIN	
	IF NOT EXISTS (SELECT * FROM UDRUGA WHERE UDRUGA.nazivUdruge = nazivUdruge) THEN
		IF (nazivUdruge IS NOT NULL) THEN
			INSERT INTO UDRUGA VALUES (NULL,nazivUdruge);
		ELSE
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Naziv udruge je obavezan!';
		END IF;
	ELSE
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Naziv udruge već postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajClanaUdruge`(IN idUdruge INT UNSIGNED, IN idOsobe INT UNSIGNED)
BEGIN
	IF EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe = idOsobe && OSOBA.uloga = "O") THEN
		IF NOT EXISTS (SELECT * FROM JeUUdruzi WHERE JeUUdruzi.idUdruge = idUdruge && JeUUdruzi.idOsobe = idOsobe) THEN
			IF EXISTS (SELECT * FROM UDRUGA WHERE UDRUGA.idUdruge = idUdruge) THEN
				INSERT INTO JeUUdruzi VALUES (NULL,idUdruge, idOsobe);
			ELSE 
				 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Navedeni identifikator udruge nije pronađen!';
			END IF;
		ELSE 
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Uneseni član već postoji!';
		END IF;
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Uneseni identifikator osobe ne postoji ili osoba nije član Ozsn-a!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajUslugu`(IN nazivUsluge VARCHAR (100))
BEGIN
	IF NOT EXISTS (SELECT * FROM USLUGA WHERE USLUGA.nazivUsluge = nazivUsluge) THEN
		IF (nazivUsluge IS NOT NULL) THEN
			INSERT INTO USLUGA(nazivUsluge) VALUES (nazivUsluge);
		ELSE
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Naziv usluge je obavezan!';
		END IF;
	ELSE
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Naziv usluge već postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajSmjer`(  IN naziv VARCHAR(100) )
BEGIN
IF EXISTS (SELECT* FROM SMJER WHERE nazivSmjera=naziv) THEN 
      	  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Naziv smjera već postoji!';
ELSE
INSERT INTO SMJER VALUES(NULL, naziv );
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajRadnoMjesto`(  IN naziv VARCHAR(100) )
BEGIN
IF EXISTS (SELECT* FROM RADNOMJESTO WHERE RADNOMJESTO.naziv=naziv) THEN 
 	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Radno mjesto već postoji!';
ELSE
INSERT INTO RADNOMJESTO VALUES(NULL, naziv );
END IF;
END $$
DELIMITER ;
