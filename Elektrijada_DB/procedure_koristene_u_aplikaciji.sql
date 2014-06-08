DELIMITER $$
CREATE  PROCEDURE `dohvatiPopisSvihSudionikaIzPodrucja`(IN idElektrijade INT(10), IN idPodrucja INT)
BEGIN
	IF EXISTS (SELECT * FROM elektrijada WHERE elektrijada.idElektrijade = idElektrijade) THEN
		IF EXISTS (SELECT * FROM podrucje WHERE podrucje.idPodrucja = idPodrucja) THEN
			SELECT DISTINCT osoba.*, podrucjesudjelovanja.*, sudjelovanje.*
			FROM osoba
			JOIN sudjelovanje ON sudjelovanje.idOsobe = osoba.idOsobe
			JOIN podrucjesudjelovanja ON sudjelovanje.idSudjelovanja = podrucjesudjelovanja.idSudjelovanja
			WHERE sudjelovanje.idElektrijade = idElektrijade AND podrucjesudjelovanja.idPodrucja = idPodrucja
			ORDER BY podrucjesudjelovanja.vrstaPodrucja;
		ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Unesen je nepostojeći identifikator područja';
		END IF;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Unesen je nepostojeći identifikator Elektrijade!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiOdredeniAtribut`(IN idOsoba INT(10), IN idElektrijade INT(10))
BEGIN
	IF EXISTS (SELECT * FROM osoba WHERE osoba.idOsobe=idOsoba) THEN
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
	IF EXISTS (SELECT * FROM osoba WHERE osoba.idOsobe=idOsobe) THEN
		IF EXISTS (SELECT * FROM elektrijada WHERE elektrijada.idElektrijade=idElektrijada) THEN

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
IF EXISTS (SELECT * FROM osoba WHERE osoba.idOsobe=idOsobe) THEN
IF EXISTS (SELECT * FROM elektrijada WHERE elektrijada.idElektrijade=idElektrijada) THEN

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
IF EXISTS (SELECT * FROM osoba WHERE osoba.idOsobe=idOsobe) THEN
IF EXISTS (SELECT * FROM elektrijada WHERE elektrijada.idElektrijade=idElektrijada) THEN

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

SELECT * FROM funkcija ORDER BY nazivFunkcije ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiGodineStudija`()
BEGIN

	SELECT * FROM godstud ORDER BY studij ASC, godina ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiZavode`()
BEGIN

	SELECT * FROM zavod ORDER BY skraceniNaziv ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiVelicine`()
BEGIN

	SELECT * FROM velmajice ORDER BY velicina ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiSmjerove`()
BEGIN

	SELECT * FROM smjer ORDER BY nazivSmjera ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiRadnaMjesta`()
BEGIN

	SELECT * FROM radnomjesto ORDER BY naziv ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiUdruge`()
BEGIN

SELECT * FROM udruga ORDER BY nazivUdruge ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiUsluge`()
BEGIN

SELECT * FROM usluga ORDER BY nazivUsluge ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiAtribute`()
BEGIN

SELECT * FROM atribut ORDER BY nazivAtributa;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiKategorijeSponzora`()
BEGIN

SELECT * FROM kategorija ORDER BY tipKategorijeSponzora ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiPopisSvihSponzora`(IN idElektrijade INT(10))
BEGIN
	IF EXISTS (SELECT * FROM elektrijada WHERE elektrijada.idElektrijade = idElektrijade) THEN
		SELECT DISTINCT *
		FROM sponzor
		JOIN imasponzora ON sponzor.idSponzora = imasponzora.idSponzora
		LEFT JOIN nacinpromocije ON imasponzora.idPromocije = nacinpromocije.idPromocije
		LEFT JOIN kategorija ON imasponzora.idKategorijeSponzora = kategorija.idKategorijeSponzora
		WHERE imasponzora.idElektrijade = idElektrijade;
ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Nepoznata Elektrijada';
	END IF;
END $$
DELIMITER ;

--			AŽURIRANJE PODATAKA

DELIMITER $$
CREATE  PROCEDURE `azurirajFunkcijuOdbora`(IN idFunkcije INT UNSIGNED, IN nazivFunkcije VARCHAR (100))
BEGIN
IF EXISTS (SELECT * FROM funkcija WHERE funkcija.idFunkcije = idFunkcije) THEN
	IF NOT EXISTS (SELECT * FROM funkcija WHERE funkcija.nazivFunkcije = nazivFunkcije) THEN
		IF (nazivFunkcije IS NOT NULL) THEN
			UPDATE funkcija SET
				funkcija.nazivFunkcije = nazivFunkcije
			WHERE funkcija.idFunkcije = idFunkcije;
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
	IF EXISTS (SELECT * FROM udruga WHERE udruga.idUdruge = idUdruge) THEN
		IF NOT EXISTS (SELECT * FROM udruga WHERE udruga.nazivUdruge = nazivUdruge) THEN
			IF (nazivUdruge IS NOT NULL) THEN
				UPDATE udruga SET
					udruga.nazivUdruge = nazivUdruge
					WHERE udruga.idUdruge = idUdruge;
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
CREATE  PROCEDURE `azurirajMedij`(IN idMedija INT(10), IN nazivMedija VARCHAR(100))
BEGIN
	IF NOT EXISTS (SELECT * FROM medij WHERE medij.nazivMedija=nazivMedija) THEN
		
				UPDATE medij
				SET medij.nazivMedija=nazivMedija
				WHERE medij.idMedija=idMedija;
			
	ELSE 
       SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Naziv medija već postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajUslugu`(IN idUsluge INT UNSIGNED, IN nazivUsluge VARCHAR (100))
BEGIN
	IF EXISTS (SELECT * FROM usluga WHERE usluga.idUsluge = idUsluge) THEN
		IF NOT EXISTS (SELECT * FROM usluga WHERE usluga.nazivUsluge = nazivUsluge) THEN
			IF (nazivUsluge IS NOT NULL) THEN
				UPDATE usluga SET
				usluga.nazivUsluge = nazivUsluge
				WHERE usluga.idUsluge = idUsluge;
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
	IF NOT EXISTS (SELECT * FROM radnomjesto WHERE naziv = ime) THEN
		IF EXISTS (SELECT * FROM radnomjesto WHERE idRadnogMjesta = id) THEN
			UPDATE radnomjesto 
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
	IF NOT EXISTS (SELECT * FROM smjer WHERE nazivSmjera = naziv) THEN
		IF EXISTS (SELECT * FROM smjer WHERE idSmjera = id) THEN
			UPDATE smjer
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
CREATE  PROCEDURE `azurirajVelicinu`( IN id INT UNSIGNED, IN vel VARCHAR(50) )
BEGIN
	IF NOT EXISTS (SELECT * FROM velmajice WHERE velicina = vel) THEN
		IF EXISTS (SELECT * FROM velmajice WHERE idVelicine = id) THEN
			UPDATE velmajice
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
CREATE  PROCEDURE `azurirajZavod`( IN id INT UNSIGNED, IN naziv VARCHAR(100), IN skraceni VARCHAR(20)  )
BEGIN
	IF NOT EXISTS (SELECT * FROM zavod WHERE nazivZavoda = naziv AND skraceniNaziv = skraceni) THEN
		IF EXISTS (SELECT * FROM zavod WHERE idZavoda = id) THEN
			UPDATE  zavod
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
IF EXISTS (SELECT * FROM godstud WHERE idGodStud = id) THEN
	UPDATE  godstud
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
		FROM atribut WHERE atribut.idAtributa = idAtributa) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji traženi atribut!';
ELSE
	UPDATE atribut
	SET atribut.nazivAtributa=nazivAtributa
	WHERE atribut.idAtributa = idAtributa;
END IF;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajNacinPromocije`(IN idPromocije INT UNSIGNED, IN tipPromocije VARCHAR(100))
BEGIN
IF EXISTS ( SELECT * FROM nacinpromocije WHERE nacinpromocije.idPromocije = idPromocije) THEN
	IF NOT EXISTS (SELECT * FROM nacinpromocije WHERE nacinpromocije.tipPromocije = tipPromocije) THEN
		IF (tipPromocije IS NOT NULL) THEN		
			UPDATE nacinpromocije SET
			nacinpromocije.tipPromocije = tipPromocije
			WHERE nacinpromocije.idPromocije = idPromocije;
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
	IF NOT EXISTS (SELECT * FROM kategorija WHERE kategorija.tipKategorijeSponzora=tipKategorijeSponzora) THEN
		IF EXISTS (SELECT * FROM kategorija WHERE kategorija.idKategorijeSponzora=idKategorijeSponzora) THEN
			UPDATE kategorija
			SET kategorija.tipKategorijeSponzora=tipKategorijeSponzora
			WHERE kategorija.idKategorijeSponzora=idKategorijeSponzora;
		ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji kategorija sponzora sa zadanim identifikatorom!';
		END IF;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tip kategorije već postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajSponElekPod`(IN idSponElekPod INT(10), IN idSponzora INT(10), IN idPodrucja INT(10),IN idElektrijade INT(10), IN iznosDonacije DECIMAL(13,2), IN valutaDonacije VARCHAR(3), IN napomena VARCHAR(300))
BEGIN
IF EXISTS (SELECT* FROM podrucje WHERE podrucje.idPodrucja = idPodrucja) THEN
IF EXISTS (SELECT * FROM sponzor WHERE sponzor.idSponzora = idSponzora) THEN
IF EXISTS (SELECT* FROM elektrijada WHERE elektrijada.idElektrijade = idElektrijade) THEN
IF NOT EXISTS (SELECT* 
		FROM sponelekpod WHERE sponelekpod.idSponElekPod = idSponElekPod ) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji disciplina za sponzora na Elektrijadi koju želite ažurirati!';
ELSE
	UPDATE sponelekpod
    SET sponelekpod.idElektrijade=idElektrijade, sponelekpod.idPodrucja=idPodrucja, sponelekpod.idSponzora=idSponzora, sponelekpod.iznosDonacije=iznosDonacije,sponelekpod.valutaDonacije=valutaDonacije,sponelekpod.napomena=napomena
	WHERE sponelekpod.idSponElekPod= idSponElekPod  ;

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
	IF EXISTS (SELECT * FROM tvrtka WHERE tvrtka.idTvrtke = idTvrtke) THEN
		IF ((imeTvrtke IS NOT NULL) && (adresaTvrtke IS NOT NULL)) THEN
				UPDATE tvrtka SET
				tvrtka.imeTvrtke = imeTvrtke,
				tvrtka.adresaTvrtke = adresaTvrtke
				WHERE tvrtka.idTvrtke = idTvrtke;
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
IF NOT EXISTS (SELECT* FROM podrucje WHERE podrucje.nazivPodrucja = nazivPodrucja AND podrucje.idPodrucja <> idPodrucja) THEN
IF NOT EXISTS (SELECT * FROM podrucje WHERE podrucje.idPodrucja = idPodrucja) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Nepoznata disciplina!';
ELSE
UPDATE podrucje
SET  podrucje.nazivPodrucja=nazivPodrucja,podrucje.idNadredjenog=idNadredjenog
WHERE podrucje.idPodrucja = idPodrucja ;

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
IF EXISTS ( SELECT * FROM obavljafunkciju WHERE obavljafunkciju.idObavljaFunkciju = idObavljaFunkciju) THEN
	DELETE FROM obavljafunkciju
    WHERE obavljafunkciju.idObavljaFunkciju = idObavljaFunkciju;
ELSE
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Traženi zapis nije pronađen!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiFunkcijuOdbora`(IN idFunkcije INT UNSIGNED)
BEGIN
	IF EXISTS (SELECT * FROM funkcija WHERE funkcija.idFunkcije = idFunkcije) THEN
		DELETE FROM funkcija WHERE funkcija.idFunkcije = idFunkcije;
	ELSE
		SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Traženi zapis ne postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajFunkcijuOdbora`(IN nazivFunkcije VARCHAR (100))
BEGIN
IF EXISTS ( SELECT * FROM funkcija WHERE funkcija.nazivFunkcije = nazivFunkcije) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Naziv funkcije već postoji!';
	ELSE
		IF (nazivFunkcije IS NULL) THEN
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Naziv funkcije je obavezan!';
		ELSE 
			INSERT INTO funkcija(nazivFunkcije) VALUES (nazivFunkcije);
		END IF;
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiClanaUdruge`(IN idUdruge INT UNSIGNED, IN idOsobe INT UNSIGNED)
BEGIN
	IF EXISTS (SELECT * FROM jeuudruzi WHERE jeuudruzi.idUdruge = idUdruge && jeuudruzi.idOsobe = idOsobe) THEN
		DELETE FROM jeuudruzi WHERE jeuudruzi.idUdruge = idUdruge && jeuudruzi.idOsobe = idOsobe;
	ELSE
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Traženi zapis ne postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiUdrugu`(IN idUdruge INT UNSIGNED)
BEGIN
	IF EXISTS (SELECT * FROM udruga WHERE udruga.idUdruge = idUdruge) THEN
		DELETE FROM udruga WHERE udruga.idUdruge = idUdruge;
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tražena udruga nije pronađena!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiMedij`(IN idMedija INT(10))
BEGIN
	IF EXISTS (SELECT * FROM medij WHERE medij.idMedija=idMedija) THEN
		DELETE FROM medij
		WHERE medij.idMedija=idMedija;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji traženi medij!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiUslugu`(IN idUsluge INT UNSIGNED)
BEGIN
	IF EXISTS ( SELECT * FROM usluga WHERE usluga.idUsluge = idUsluge) THEN
		DELETE FROM usluga WHERE usluga.idUsluge = idUsluge;
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tražena usluga ne postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiSmjer`( IN id INT UNSIGNED )
BEGIN
IF EXISTS (SELECT * FROM smjer WHERE idSmjera = id) THEN
DELETE FROM smjer WHERE idSmjera = id;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Traženi smjer ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiRadnoMjesto`( IN id INT UNSIGNED )
BEGIN
IF EXISTS (SELECT * FROM radnomjesto  WHERE idRadnogMjesta = id) THEN
DELETE FROM radnomjesto WHERE idRadnogMjesta = id;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Traženo radno mjesto ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiVelicinu`( IN id INT UNSIGNED )
BEGIN
IF EXISTS (SELECT * FROM velmajice WHERE idVelicine = id) THEN
DELETE FROM  velmajice WHERE idVelicine = id;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tražena veličina majice ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiZavod`( IN id INT UNSIGNED )
BEGIN
IF EXISTS (SELECT * FROM  zavod WHERE idZavoda = id) THEN
DELETE FROM zavod WHERE idZavoda = id;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Traženi zavod ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiGodStud`( IN id INT UNSIGNED )
BEGIN
IF EXISTS (SELECT * FROM godstud WHERE idGodStud = id) THEN
DELETE FROM godstud WHERE idGodStud = id;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tražena godina studija ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiAtribut`(IN idAtributa INT(10))
BEGIN
IF NOT EXISTS (SELECT * 
		FROM atribut WHERE atribut.idAtributa = idAtributa) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji traženi atribut!';
ELSE
	DELETE FROM atribut
	WHERE atribut.idAtributa = idAtributa ;

END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiNacinPromocije`(IN idPromocije INT UNSIGNED)
BEGIN
IF EXISTS ( SELECT * FROM nacinpromocije WHERE nacinpromocije.idPromocije = idPromocije) THEN
	DELETE FROM nacinpromocije WHERE nacinpromocije.idPromocije = idPromocije;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Traženi tip promocije ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiKategorijuSponzora`(IN idKategorijeSponzora INT(10))
BEGIN
	IF EXISTS (SELECT * FROM kategorija WHERE kategorija.idKategorijeSponzora=idKategorijeSponzora) THEN
		DELETE FROM kategorija
		WHERE kategorija.idKategorijeSponzora=idKategorijeSponzora;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji kategorija sa zadanim identifikatorom!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiSponzorstvo`(IN idS INT(10), IN idE INT(10))
BEGIN
	IF EXISTS (SELECT * FROM imasponzora WHERE idSponzora=idS AND idElektrijade = idE) THEN
		DELETE FROM imasponzora WHERE idSponzora = idS AND idElektrijade = idE;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji traženi zapis o sponzorstvu!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiSponElekPod`(IN idSponElekPod INT(10))
BEGIN

IF NOT EXISTS (SELECT* 
		FROM sponelekpod WHERE sponelekpod.idSponElekPod = idSponElekPod ) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji sponzorstvo za disciplinu koje želite izbrisati';
ELSE
	DELETE FROM sponelekpod
	WHERE sponelekpod.idSponElekPod = idSponElekPod ;

END IF;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiTvrtku`(IN idtvrtke INT UNSIGNED)
BEGIN
	IF EXISTS (SELECT * FROM tvrtka WHERE tvrtka.idTvrtke = idTvrtke) THEN
		DELETE FROM tvrtka WHERE tvrtka.idTvrtke = idTvrtke;
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tražena tvrtka ne postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiKoristenjeUsluga`(IN idKoristiPruza INT(10))
BEGIN
	IF EXISTS (SELECT * FROM koristipruza WHERE koristipruza.idKoristiPruza = idKoristiPruza) THEN
					DELETE FROM koristipruza WHERE koristipruza.idKoristiPruza = idKoristiPruza;
				
ELSE
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Traženi zapis ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiPodrucje`(IN idPodrucja INT(10))
BEGIN
IF NOT EXISTS (SELECT * FROM podrucje WHERE podrucje.idPodrucja = idPodrucja) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Nepoznata disciplina!';
ELSE
IF NOT EXISTS (SELECT * FROM podrucje WHERE podrucje.idNadredjenog = idPodrucja) THEN
DELETE FROM podrucje
WHERE podrucje.idPodrucja = idPodrucja ;
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
	IF NOT EXISTS (SELECT * FROM udruga WHERE udruga.nazivUdruge = nazivUdruge) THEN
		IF (nazivUdruge IS NOT NULL) THEN
			INSERT INTO udruga VALUES (NULL,nazivUdruge);
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
	IF EXISTS (SELECT * FROM osoba WHERE osoba.idOsobe = idOsobe && osoba.uloga = "O") THEN
		IF NOT EXISTS (SELECT * FROM jeuudruzi WHERE jeuudruzi.idUdruge = idUdruge && jeuudruzi.idOsobe = idOsobe) THEN
			IF EXISTS (SELECT * FROM udruga WHERE udruga.idUdruge = idUdruge) THEN
				INSERT INTO jeuudruzi VALUES (NULL,idUdruge, idOsobe);
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
	IF NOT EXISTS (SELECT * FROM usluga WHERE usluga.nazivUsluge = nazivUsluge) THEN
		IF (nazivUsluge IS NOT NULL) THEN
			INSERT INTO usluga(nazivUsluge) VALUES (nazivUsluge);
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
IF EXISTS (SELECT* FROM smjer WHERE nazivSmjera=naziv) THEN 
      	  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Naziv smjera već postoji!';
ELSE
INSERT INTO smjer VALUES(NULL, naziv );
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajRadnoMjesto`(  IN naziv VARCHAR(100) )
BEGIN
IF EXISTS (SELECT* FROM radnomjesto WHERE radnomjesto.naziv=naziv) THEN 
 	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Radno mjesto već postoji!';
ELSE
INSERT INTO radnomjesto VALUES(NULL, naziv );
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajGodStud`( IN stud VARCHAR(50), IN god VARCHAR(50) )
BEGIN
IF EXISTS (SELECT* FROM godstud  WHERE studij=stud AND godina=god) THEN  
               SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Godina studija već postoji!';
ELSE
INSERT INTO godstud VALUES(NULL, stud, god );
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajVelicinu`( IN velicina VARCHAR(50) )
BEGIN
 IF EXISTS (SELECT* FROM velmajice WHERE  velmajice.velicina=velicina) THEN 
 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Veličina majice već postoji!';
ELSE

INSERT INTO velmajice VALUES(NULL, velicina );
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajZavod`(  IN naziv VARCHAR(100), IN skraceni VARCHAR(20) )
BEGIN
IF EXISTS (SELECT* FROM zavod WHERE nazivZavoda=naziv AND skraceniNaziv=skraceni) THEN 
 	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Zavod već postoji!';
ELSE
 	INSERT INTO zavod VALUES(NULL, naziv, skraceni );
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajAtribut`(IN nazivAtributa VARCHAR(100))
BEGIN
INSERT INTO atribut VALUES (NULL,nazivAtributa);
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajNacinPromocije`(IN tipPromocije VARCHAR(100))
BEGIN
IF EXISTS ( SELECT * FROM nacinpromocije WHERE nacinpromocije.tipPromocije = tipPromocije) THEN 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tip promocije već postoji!';
ELSE IF (tipPromocije IS NULL) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Tip promocije je obavezan!';
ELSE
	INSERT INTO nacinpromocije(tipPromocije) VALUES (tipPromocije);
END IF;
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajKategorijuSponzora`(IN tipKategorijeSponzora VARCHAR(100))
BEGIN
IF NOT EXISTS (SELECT * FROM kategorija WHERE kategorija.tipKategorijeSponzora=tipKategorijeSponzora) THEN
	INSERT INTO kategorija values (NULL, tipKategorijeSponzora);
ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Zadani tip kategorije sponzora već postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajSponElekPod`(IN idSponzora INT(10), IN idPodrucja INT(10),IN idElektrijade INT(10), IN iznosDonacije DECIMAL(13,2), IN valutaDonacije VARCHAR(3), IN napomena VARCHAR(300))
BEGIN
	IF EXISTS (SELECT * FROM podrucje WHERE podrucje.idPodrucja = idPodrucja) THEN
		IF EXISTS (SELECT * FROM elektrijada WHERE elektrijada.idElektrijade = idElektrijade) THEN
			IF EXISTS (SELECT * FROM sponzor WHERE sponzor.idSponzora = idSponzora) THEN
				IF NOT EXISTS (SELECT * FROM sponelekpod WHERE sponelekpod.idElektrijade = idElektrijade  AND sponelekpod.idPodrucja = idPodrucja AND sponelekpod.idSponzora = idSponzora ) THEN

						INSERT INTO sponelekpod VALUES (NULL,idSponzora, idPodrucja, idElektrijade, iznosDonacije, valutaDonacije, napomena );

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
			INSERT INTO tvrtka VALUES (NULL,imeTvrtke, adresaTvrtke);
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
IF NOT EXISTS (SELECT * FROM podrucje WHERE podrucje.nazivPodrucja = nazivPodrucja) THEN
	IF idNadredjenog IS NULL OR EXISTS (SELECT * FROM podrucje WHERE podrucje.idPodrucja = idNadredjenog) THEN
		INSERT INTO podrucje VALUES (NULL,nazivPodrucja,idNadredjenog);
	ELSE
SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Nadređena disciplina je pogrešno zadana!';

	END IF;
	ELSE
		SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Disciplina je već unesena!';
	END IF;

END $$
DELIMITER ;
DELIMITER $$

CREATE  PROCEDURE `dohvatiPodredenaPodrucja`(IN idPodrucja INT(10))
BEGIN
	IF EXISTS (SELECT * FROM podrucje WHERE podrucje.idPodrucja=idPodrucja) THEN
		
			SELECT DISTINCT podrucje.idPodrucja FROM  podrucje 
			WHERE podrucje.idNadredjenog = idPodrucja;
	ELSE
	   SIGNAL SQLSTATE '02000'SET MESSAGE_TEXT = 'Nepoznato područje!';
	END IF;
END $$
DELIMITER ;