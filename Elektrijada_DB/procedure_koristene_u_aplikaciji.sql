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

DELIMITER $$
CREATE  PROCEDURE `dohvatiAtribute`()
BEGIN

SELECT * FROM ATRIBUT ORDER BY nazivAtributa;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiKategorijeSponzora`()
BEGIN

SELECT * FROM KATEGORIJA ORDER BY tipKategorijeSponzora ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiPopisSvihSponzora`(IN idElektrijade INT(10))
BEGIN
	IF EXISTS (SELECT * FROM Elektrijada WHERE Elektrijada.idElektrijade = idElektrijade) THEN
		SELECT DISTINCT *
		FROM sponzor
		JOIN imaSponzora ON sponzor.idSponzora = imaSponzora.idSponzora
		LEFT JOIN nacinPromocije ON imaSponzora.idPromocije = nacinPromocije.idPromocije
		LEFT JOIN kategorija ON imaSponzora.idKategorijeSponzora = kategorija.idKategorijeSponzora
		WHERE imaSponzora.idElektrijade = idElektrijade;
ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Nepoznata Elektrijada';
	END IF;
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

DELIMITER $$
CREATE  PROCEDURE `azurirajVelicinu`( IN id INT UNSIGNED, IN vel VARCHAR(5) )
BEGIN
	IF NOT EXISTS (SELECT * FROM VELMAJICE WHERE velicina = vel) THEN
		IF EXISTS (SELECT * FROM VELMAJICE WHERE idVelicine = id) THEN
			UPDATE VELMAJICE
			SET idVelicine=id,velicina=vel  WHERE idVelicine = id;
		ELSE 
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tražena veličina ne postoji!';
		END IF;
	ELSE
		SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Veličina majice već postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajZavod`( IN id INT UNSIGNED, IN naziv VARCHAR(100), IN skraceni VARCHAR(10)  )
BEGIN
	IF NOT EXISTS (SELECT * FROM ZAVOD WHERE nazivZavoda = naziv AND skraceniNaziv = skraceni) THEN
		IF EXISTS (SELECT * FROM ZAVOD WHERE idZavoda = id) THEN
			UPDATE  ZAVOD
			SET idZavoda=id, nazivZavoda=naziv, skraceniNaziv=skraceni WHERE idZavoda = id;
		ELSE 
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Traženi zavod ne postoji!';
		END IF;
	ELSE
		SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Naziv zavoda već postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$

CREATE  PROCEDURE `azurirajGodStud`( IN id INT UNSIGNED, IN stud VARCHAR(50), IN god VARCHAR(50) )
BEGIN
IF EXISTS (SELECT * FROM GODSTUD WHERE idGodStud = id) THEN
	UPDATE  GODSTUD
	SET idGodStud=id, studij=stud,godina=god  WHERE idGodStud = id;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tražena godina studija ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajAtribut`(IN idAtributa INT(10),IN nazivAtributa VARCHAR(100))
BEGIN
IF NOT EXISTS (SELECT * 
		FROM ATRIBUT WHERE ATRIBUT.idAtributa = idAtributa) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji traženi atribut!';
ELSE
	UPDATE ATRIBUT
	SET ATRIBUT.nazivAtributa=nazivAtributa
	WHERE ATRIBUT.idAtributa = idAtributa;
END IF;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajNacinPromocije`(IN idPromocije INT UNSIGNED, IN tipPromocije VARCHAR(100))
BEGIN
IF EXISTS ( SELECT * FROM NACINPROMOCIJE WHERE NACINPROMOCIJE.idPromocije = idPromocije) THEN
	IF NOT EXISTS (SELECT * FROM NACINPROMOCIJE WHERE NACINPROMOCIJE.tipPromocije = tipPromocije) THEN
		IF (tipPromocije IS NOT NULL) THEN		
			UPDATE NACINPROMOCIJE SET
			NACINPROMOCIJE.tipPromocije = tipPromocije
			WHERE NACINPROMOCIJE.idPromocije = idPromocije;
		ELSE 
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tip promocije je obavezan!';
		END IF;
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tip promocije već postoji!';
	END IF;
ELSE
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Traženi tip promocije nije pronađen!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajKategorijuSponzora`(IN idKategorijeSponzora INT(10), IN tipKategorijeSponzora VARCHAR(100))
BEGIN
	IF NOT EXISTS (SELECT * FROM KATEGORIJA WHERE KATEGORIJA.tipKategorijeSponzora=tipKategorijeSponzora) THEN
		IF EXISTS (SELECT * FROM KATEGORIJA WHERE KATEGORIJA.idKategorijeSponzora=idKategorijeSponzora) THEN
			UPDATE KATEGORIJA
			SET KATEGORIJA.tipKategorijeSponzora=tipKategorijeSponzora
			WHERE KATEGORIJA.idKategorijeSponzora=idKategorijeSponzora;
		ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji kategorija sponzora sa zadanim identifikatorom!';
		END IF;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tip kategorije već postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajSponElekPod`(IN idSponElekPod INT(10), IN idSponzora INT(10), IN idPodrucja INT(10),IN idElektrijade INT(10), IN iznosDonacije DECIMAL(13,2), IN valutaDonacije VARCHAR(3), IN napomena VARCHAR(300))
BEGIN
IF EXISTS (SELECT* FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idPodrucja) THEN
IF EXISTS (SELECT * FROM SPONZOR WHERE SPONZOR.idSponzora = idSponzora) THEN
IF EXISTS (SELECT* FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN
IF NOT EXISTS (SELECT* 
		FROM SponElekPod WHERE SponElekPod.idSponElekPod = idSponElekPod ) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji disciplina za sponzora na Elektrijadi koju želite ažurirati!';
ELSE
	UPDATE SponElekPod
    SET SponElekPod.idElektrijade=idElektrijade, SponElekPod.idPodrucja=idPodrucja, SponElekPod.idSponzora=idSponzora, SponElekPod.iznosDonacije=iznosDonacije,SponElekPod.valutaDonacije=valutaDonacije,SponElekPod.napomena=napomena
	WHERE SponElekPod.idSponElekPod= idSponElekPod  ;

END IF;
ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Upisana nepostojeća Elektrijada!';
END IF;

ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Upisan nepostojeći sponzor!';
END IF;

ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tražena disciplina ne postoji!';
END IF;

END $$
DELIMITER ; 

DELIMITER $$
CREATE  PROCEDURE `azurirajTvrtku`(IN idTvrtke INT UNSIGNED, IN imeTvrtke VARCHAR (100), IN adresaTvrtke VARCHAR (100))
BEGIN
	IF EXISTS (SELECT * FROM TVRTKA WHERE TVRTKA.idTvrtke = idTvrtke) THEN
		IF ((imeTvrtke IS NOT NULL) && (adresaTvrtke IS NOT NULL)) THEN
				UPDATE TVRTKA SET
				TVRTKA.imeTvrtke = imeTvrtke,
				TVRTKA.adresaTvrtke = adresaTvrtke
				WHERE TVRTKA.idTvrtke = idTvrtke;
		ELSE 
				 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Naziv i adresa tvrtke su obavezni!';
			END IF;
	ELSE
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tražena tvrtka ne postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajPodrucje`(IN idPodrucja INT(10),IN nazivPodrucja VARCHAR(100),IN idNadredjenog INT(10))
BEGIN
IF NOT EXISTS (SELECT* FROM PODRUCJE WHERE PODRUCJE.nazivPodrucja = nazivPodrucja) THEN
IF NOT EXISTS (SELECT * FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idPodrucja) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Nepoznata disciplina!';
ELSE
UPDATE PODRUCJE
SET  PODRUCJE.nazivPodrucja=nazivPodrucja,PODRUCJE.idNadredjenog=idNadredjenog
WHERE PODRUCJE.idPodrucja = idPodrucja ;

END IF;
ELSE
 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Disciplina već postoji!';
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

DELIMITER $$
CREATE  PROCEDURE `brisiVelicinu`( IN id INT UNSIGNED )
BEGIN
IF EXISTS (SELECT * FROM VELMAJICE WHERE idVelicine = id) THEN
DELETE FROM  VELMAJICE WHERE idVelicine = id;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tražena veličina majice ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiZavod`( IN id INT UNSIGNED )
BEGIN
IF EXISTS (SELECT * FROM  ZAVOD WHERE idZavoda = id) THEN
DELETE FROM ZAVOD WHERE idZavoda = id;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Traženi zavod ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiGodStud`( IN id INT UNSIGNED )
BEGIN
IF EXISTS (SELECT * FROM GODSTUD WHERE idGodStud = id) THEN
DELETE FROM GODSTUD WHERE idGodStud = id;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tražena godina studija ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiAtribut`(IN idAtributa INT(10))
BEGIN
IF NOT EXISTS (SELECT * 
		FROM ATRIBUT WHERE ATRIBUT.idAtributa = idAtributa) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji traženi atribut!';
ELSE
	DELETE FROM ATRIBUT
	WHERE ATRIBUT.idAtributa = idAtributa ;

END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiNacinPromocije`(IN idPromocije INT UNSIGNED)
BEGIN
IF EXISTS ( SELECT * FROM NACINPROMOCIJE WHERE NACINPROMOCIJE.idPromocije = idPromocije) THEN
	DELETE FROM NACINPROMOCIJE WHERE NACINPROMOCIJE.idPromocije = idPromocije;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Traženi tip promocije ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiKategorijuSponzora`(IN idKategorijeSponzora INT(10))
BEGIN
	IF EXISTS (SELECT * FROM KATEGORIJA WHERE KATEGORIJA.idKategorijeSponzora=idKategorijeSponzora) THEN
		DELETE FROM KATEGORIJA
		WHERE KATEGORIJA.idKategorijeSponzora=idKategorijeSponzora;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji kategorija sa zadanim identifikatorom!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiSponzorstvo`(IN idS INT(10), IN idE INT(10))
BEGIN
	IF EXISTS (SELECT * FROM ImaSponzora WHERE idSponzora=idS AND idElektrijade = idE) THEN
		DELETE FROM imasponzora WHERE idSponzora = idS AND idElektrijade = idE;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji traženi zapis o sponzorstvu!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiSponElekPod`(IN idSponElekPod INT(10))
BEGIN

IF NOT EXISTS (SELECT* 
		FROM SponElekPod WHERE SponElekPod.idSponElekPod = idSponElekPod ) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji sponzorstvo za disciplinu koje želite izbrisati';
ELSE
	DELETE FROM SponElekPod
	WHERE SponElekPod.idSponElekPod = idSponElekPod ;

END IF;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiTvrtku`(IN idtvrtke INT UNSIGNED)
BEGIN
	IF EXISTS (SELECT * FROM TVRTKA WHERE TVRTKA.idTvrtke = idTvrtke) THEN
		DELETE FROM TVRTKA WHERE TVRTKA.idTvrtke = idTvrtke;
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tražena tvrtka ne postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiKoristenjeUsluga`(IN idKoristiPruza INT(10))
BEGIN
	IF EXISTS (SELECT * FROM KoristiPruza WHERE KoristiPruza.idKoristiPruza = idKoristiPruza) THEN
					DELETE FROM KoristiPruza WHERE KoristiPruza.idKoristiPruza = idKoristiPruza;
				
ELSE
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Traženi zapis ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiPodrucje`(IN idPodrucja INT(10))
BEGIN
IF NOT EXISTS (SELECT * FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idPodrucja) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Nepoznata disciplina!';
ELSE
IF NOT EXISTS (SELECT * FROM PODRUCJE WHERE PODRUCJE.idNadredjenog = idPodrucja) THEN
DELETE FROM PODRUCJE
WHERE PODRUCJE.idPodrucja = idPodrucja ;
ELSE
     SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Najprije morate obrisati sve discipline kojima je ova disciplina nadređena!';
   END IF;

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

DELIMITER $$
CREATE  PROCEDURE `dodajGodStud`( IN stud VARCHAR(50), IN god VARCHAR(50) )
BEGIN
IF EXISTS (SELECT* FROM GODSTUD  WHERE studij=stud AND godina=god) THEN  
               SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Godina studija već postoji!';
ELSE
INSERT INTO GODSTUD VALUES(NULL, stud, god );
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajVelicinu`( IN velicina VARCHAR(5) )
BEGIN
 IF EXISTS (SELECT* FROM VELMAJICE WHERE  VELMAJICE.velicina=velicina) THEN 
 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Veličina majice već postoji!';
ELSE

INSERT INTO VELMAJICE VALUES(NULL, velicina );
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajZavod`(  IN naziv VARCHAR(100), IN skraceni VARCHAR(10) )
BEGIN
IF EXISTS (SELECT* FROM ZAVOD WHERE nazivZavoda=naziv AND skraceniNaziv=skraceni) THEN 
 	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Zavod već postoji!';
ELSE
 	INSERT INTO ZAVOD VALUES(NULL, naziv, skraceni );
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajAtribut`(IN nazivAtributa VARCHAR(100))
BEGIN
INSERT INTO ATRIBUT VALUES (NULL,nazivAtributa);
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajNacinPromocije`(IN tipPromocije VARCHAR(100))
BEGIN
IF EXISTS ( SELECT * FROM NACINPROMOCIJE WHERE NACINPROMOCIJE.tipPromocije = tipPromocije) THEN 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tip promocije već postoji!';
ELSE IF (tipPromocije IS NULL) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tip promocije je obavezan!';
ELSE
	INSERT INTO NACINPROMOCIJE(tipPromocije) VALUES (tipPromocije);
END IF;
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajKategorijuSponzora`(IN tipKategorijeSponzora VARCHAR(100))
BEGIN
IF NOT EXISTS (SELECT * FROM KATEGORIJA WHERE KATEGORIJA.tipKategorijeSponzora=tipKategorijeSponzora) THEN
	INSERT INTO KATEGORIJA values (NULL, tipKategorijeSponzora);
ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Zadani tip kategorije sponzora već postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajSponElekPod`(IN idSponzora INT(10), IN idPodrucja INT(10),IN idElektrijade INT(10), IN iznosDonacije DECIMAL(13,2), IN valutaDonacije VARCHAR(3), IN napomena VARCHAR(300))
BEGIN
	IF EXISTS (SELECT * FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idPodrucja) THEN
		IF EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN
			IF EXISTS (SELECT * FROM SPONZOR WHERE SPONZOR.idSponzora = idSponzora) THEN
				IF NOT EXISTS (SELECT * FROM SponElekPod WHERE SponElekPod.idElektrijade = idElektrijade  AND SponElekPod.idPodrucja = idPodrucja AND SponElekPod.idSponzora = idSponzora ) THEN

						INSERT INTO SponElekPod VALUES (NULL,idSponzora, idPodrucja, idElektrijade, iznosDonacije, valutaDonacije, napomena );

				ELSE
						SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Već postoji zapis za traženu disciplinu na aktualnoj Elektrijadi!';
				END IF;

			ELSE 
					SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Upisan nepostojeći sponzor!';
			END IF;

		ELSE 
				SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Upisana nepostojeća Elektrijada!';
		END IF;

	ELSE 
			SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tražena disciplina ne postoji!';
	END IF;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajTvrtku`(IN imeTvrtke VARCHAR (100), IN adresaTvrtke VARCHAR (100))
BEGIN
	IF (imeTvrtke IS NOT NULL) THEN
		IF (adresaTvrtke IS NOT NULL) THEN
			INSERT INTO TVRTKA VALUES (NULL,imeTvrtke, adresaTvrtke);
		ELSE 
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Adresa tvrtke je obavezna!';
		END IF;
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ime tvrtke je obavezno!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajPodrucje`(IN nazivPodrucja VARCHAR(100),IN idNadredjenog INT(10))
BEGIN
IF NOT EXISTS (SELECT * FROM PODRUCJE WHERE PODRUCJE.nazivPodrucja = nazivPodrucja) THEN
	IF NOT EXISTS (SELECT * FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idNadredjenog) THEN
		SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Nadređena disciplina je pogrešno zadana!';
	ELSE
		INSERT INTO PODRUCJE VALUES (NULL,nazivPodrucja,idNadredjenog);

	END IF;
	ELSE
		SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Disciplina je već unesena!';
	END IF;

END $$
DELIMITER ;
DELIMITER $$

CREATE  PROCEDURE `dohvatiPodredenaPodrucja`(IN idPodrucja INT(10))
BEGIN
	IF EXISTS (SELECT * FROM PODRUCJE WHERE PODRUCJE.idPodrucja=idPodrucja) THEN
		
			SELECT DISTINCT podrucje.idPodrucja FROM  podrucje 
			WHERE podrucje.idNadredjenog = idPodrucja;
	ELSE
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Nepoznato područje!';
	END IF;
END $$
DELIMITER ;