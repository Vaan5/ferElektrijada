
DELIMITER $$
CREATE  PROCEDURE `azurirajElekPodrucje`(IN idElekPodrucje INT(10), IN idPodrucja INT(10),IN datumPocetka DATE, IN rezultatGrupni SMALLINT(6),IN slikaLink VARCHAR(255), IN idSponzora INT(10))
BEGIN
IF EXISTS (SELECT* FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idPodrucja) THEN
IF EXISTS (SELECT* FROM ELEKTRIJADA WHERE ELEKTRIJADA.datumPocetka = datumPocetka) THEN
IF NOT EXISTS (SELECT* 
		FROM ElekPodrucje WHERE ElekPodrucje.idElekPodrucje = idElekPodrucje ) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji područje koje želite ažurirati';
ELSE
	UPDATE ElekPodrucje
    SET ElekPodrucje.datumPocetka=datumPocetka, ElekPodrucje.rezultatGrupni=rezultatGrupni, ElekPodrucje.slikaLink=slikaLink, ElekPodrucje.idPodrucja=idPodrucja, ElekPodrucje.idSponzora=idSponzora
	WHERE ElekPodrucje.idElekPodrucje = idElekPodrucje  ;

END IF;
ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Upisan nepostojeći datum Elektrijade!';
END IF;

ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Upisan nepostojeće područje!';
END IF;

END $$
DELIMITER ; 

DELIMITER $$
CREATE  PROCEDURE `azurirajElektrijadu`(IN idElektrijade INT(10), IN mjestoOdrzavanja VARCHAR(100), IN datumPocetka DATE, IN datumKraja DATE, IN ukupniRezultat SMALLINT(6), IN drzava VARCHAR(100))
BEGIN
IF NOT EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji Elektrijada na zadanom mjestu';
ELSE
IF(datumPocetka<datumKraja) THEN
UPDATE ELEKTRIJADA
SET ELEKTRIJADA.datumKraja = datumKraja, ELEKTRIJADA.mjestoOdrzavanja=mjestoOdrzavanja , ELEKTRIJADA.ukupniRezultat=ukupniRezultat, ELEKTRIJADA.drzava=drzava, ELEKTRIJADA.datumPocetka=datumPocetka
WHERE ELEKTRIJADA.idElektrijade = idElektrijade;
ELSE
     SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Pogrešan unos datuma pocetka i datuma kraja Elektrijade!';
END IF;

END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajKontakt`(IN idKontakta INT(10), IN imeKontakt VARCHAR(100), IN prezimeKontakt VARCHAR(100), IN telefon VARCHAR(20), IN radnoMjesto VARCHAR(100), IN idTvrtke INT(10), IN idSponzora INT(10),IN idMedija INT(10))
BEGIN
	IF EXISTS (SELECT * FROM KONTAKTOSOBE WHERE KONTAKTOSOBE.idKontakta=idKontakta) && (telefon REGEXP '[0-9]') THEN
     IF EXISTS (SELECT * FROM MEDIJ  WHERE MEDIJ.idMedija = idMedija ) || (idMedija IS NULL ) THEN
       IF EXISTS (SELECT * FROM TVRTKA  WHERE TVRTKA.idTvrtke = idTvrtke) || (idTvrtke IS NULL ) THEN
        IF EXISTS (SELECT * FROM SPONZOR  WHERE SPONZOR.idSponzora = idSponzora) || (idSponzora IS NULL ) THEN
		UPDATE KONTAKTOSOBE
		SET KONTAKTOSOBE.imeKontakt=imeKontakt, KONTAKTOSOBE.prezimeKontakt=prezimeKontakt, KONTAKTOSOBE.telefon=telefon, KONTAKTOSOBE.radnoMjesto=radnoMjesto, KONTAKTOSOBE.idTvrtke=idTvrtke, KONTAKTOSOBE.idSponzora=idSponzora
		WHERE KONTAKTOSOBE.idKontakta=idKontakta;
        ELSE
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Odabrani sponzor ne postoji! ';
	    END IF;
       ELSE
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Odabrana tvrtka ne postoji! ';
	   END IF;
	 ELSE 
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Odabrani medij ne postoji!';
	 END IF;
	ELSE 
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Odabrani kontakt ne postoji!';
	END IF;
END $$
DELIMITER ;


DELIMITER $$
CREATE  PROCEDURE `azurirajMail`(IN idAdrese INT(10), IN idKontakta INT(10), IN email VARCHAR(100))
BEGIN
	IF NOT EXISTS (SELECT * FROM EMAILADRESE WHERE EMAILADRESE.email=email) THEN
		IF EXISTS (SELECT * FROM KONTAKTOSOBE WHERE KONTAKTOSOBE.idKontakta=idKontakta) THEN
			IF (email REGEXP '^[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9]@[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9]\.[a-zA-Z]{2,4}$') THEN
				UPDATE EMAILADRESE
				SET EMAILADRESE.idKontakta=idKontakta,EMAILADRESE.email=email
				WHERE EMAILADRESE.idAdrese=idAdrese;
			ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Krivi format email adrese!';
			END IF;
		ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji kontakt sa upisanim identifikatorom!';
		END IF;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Navedeni email je vec u bazi!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajMobitel`(IN idBroja INT(10), IN idKontakta INT(10), IN broj VARCHAR(20))
BEGIN
	IF NOT EXISTS (SELECT * FROM BROJEVIMOBITELA WHERE BROJEVIMOBITELA.broj=broj) THEN
		IF EXISTS (SELECT * FROM KONTAKTOSOBE WHERE KONTAKTOSOBE.idKontakta=idKontakta) THEN
			IF broj REGEXP '[0-9]' THEN
				UPDATE BROJEVIMOBITELA
				SET BROJEVIMOBITELA.idKontakta=idKontakta,BROJEVIMOBITELA.broj=broj
				WHERE BROJEVIMOBITELA.idBroja=idBroja;
			ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Broj mobitela moze sadrzavati samo znamenke!';
			END IF;
		ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji kontakt sa zadanim identifikatorom!';
		END IF;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Navedeni broj mobitela se vec nalazi u bazi!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajOsobu`(IN idOsobe INT(10), ime VARCHAR(50), IN prezime VARCHAR(50), IN mail VARCHAR(50),
 IN ferId VARCHAR(50), IN brojMob VARCHAR(20), IN passwordVAR VARCHAR(255), IN JMBAG VARCHAR(10), IN datRod DATE, IN spol CHAR(1),
IN brOsobne VARCHAR(20),IN brPutovnice VARCHAR(30),IN osobnaVrijediDo DATE,IN putovnicaVrijediDo DATE,IN uloga CHAR(1), IN zivotopis VARCHAR(200), IN MBG VARCHAR(9), IN OIB VARCHAR(11), IN idNadredjena INT(10), IN vrijedi BOOLEAN)
BEGIN
IF (spol IN ('m','z','M','Z')) THEN
IF ((osobnaVrijediDo>CURDATE() OR osobnaVrijediDo IS NULL) AND (putovnicaVrijediDo>CURDATE() OR putovnicaVrijediDo IS NULL)) THEN
IF EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe=idOsobe) THEN
IF ((CHAR_LENGTH(JMBAG) = 10 AND JMBAG REGEXP '[0-9]')OR (JMBAG IS NULL) ) THEN
IF ((CHAR_LENGTH(MBG) = 10 AND MBG REGEXP '[0-9]') OR (MBG IS NULL) ) THEN
IF ((CHAR_LENGTH(OIB) = 10 AND JMBAG REGEXP '[0-9]') OR (OIB IS NULL) ) THEN
IF ((brPutovnice REGEXP '[0-9]' OR brPutovnice IS NULL) AND (brOsobne REGEXP '[0-9]' OR brOsobne IS NULL) ) THEN
		IF ( brojMob REGEXP '[0-9]') THEN
			IF (mail REGEXP '^[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9]@[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9]\.[a-zA-Z]{2,4}$') THEN
               IF(ferId REGEXP '[a-z][a-z][0-9][0-9][0-9][0-9][0-9]'  OR ferId REGEXP '[a-z]*[0-9]') THEN
				
UPDATE OSOBA
SET OSOBA.JMBAG=JMBAG, OSOBA.password=passwordVAR, OSOBA.ime=ime, OSOBA.prezime=prezime, OSOBA.mail=mail, OSOBA.ferId=ferId, OSOBA.brojMob=brojMob, OSOBA.datRod=datRod, OSOBA.spol=spol, OSOBA.brOsobne=brOsobne, OSOBA.brPutovnice=brPutovnice, OSOBA.putovnicaVrijediDo=putovnicaVrijediDo, OSOBA.osobnaVrijediDo=osobnaVrijediDo, OSOBA.uloga=uloga,OSOBA.zivotopis=zivotopis, OSOBA.MBG=MBG, OSOBA.OIB=OIB, OSOBA.idNadredjena=idNadredjena,OSOBA.vrijedi=vrijedi
WHERE OSOBA.idOsobe=idOsobe ;


 

	ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Pogrešno upisan ferId! Unesite ferId u obliku "ab22222" ili "mivic3" !';
END IF;
    ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Niste unijeli dobar email!';
END IF;
	ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Broj  mobitela smiju sadrzavati samo brojke!';
	END IF;

	ELSE
        SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Broj putovnice ili broj osobne trebaju sadržavati samo znamenke!';

	END IF;
 ELSE
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: OIB mora sadržavati samo znamenke i biti jedanaesteroznamenkast!';
END IF;
 ELSE
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: MBG mora sadržavati samo znamenke i biti deveteroznamenkast!';
END IF;
    ELSE
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: JMBAG mora sadržavati samo znamenke i biti deseteroznamenkast!';
END IF;
ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Unijeli ste pogrešni id osobe!';
END IF;


 ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Osobna ili putovnica su istekle !';
END IF;
    ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Pogrešno unseen spol! !';
END IF;

END $$
DELIMITER ;


DELIMITER $$
CREATE  PROCEDURE `azurirajPodrucjeSudjelovanja`(IN idPodrucjeSudjelovanja INT UNSIGNED, IN idPodrucja INT UNSIGNED, IN idSudjelovanja INT UNSIGNED, IN rezultatPojedinacni SMALLINT, IN vrstaPodrucja TINYINT(1),IN iznosUplate INT, IN valuta VARCHAR(3))
BEGIN

IF EXISTS (SELECT * FROM PODRUCJESUDJELOVANJA WHERE PODRUCJESUDJELOVANJA.idPodrucjeSudjelovanja = idPodrucjeSudjelovanja) THEN
IF EXISTS (SELECT* FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idPodrucja) THEN
IF EXISTS (SELECT* FROM SUDJELOVANJE WHERE SUDJELOVANJE.idSudjelovanja = idSudjelovanja) THEN
	IF (vrstaPodrucja = 0 AND rezultatPojedinacni IS NOT NULL) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Pojedinacni rezultat se ne može upisat u ekipnu disciplinu.';
	ELSE
		UPDATE PODRUCJESUDJELOVANJA SET PODRUCJESUDJELOVANJA.rezultatPojedinacni = rezultatPojedinacni, PODRUCJESUDJELOVANJA.vrstaPodrucja = vrstaPodrucja, PODRUCJESUDJELOVANJA.idPodrucja=idPodrucja ,PODRUCJESUDJELOVANJA.idSudjelovanja=idSudjelovanja,PODRUCJESUDJELOVANJA.iznosUplate=iznosUplate,PODRUCJESUDJELOVANJA.valuta=valuta
		WHERE PODRUCJESUDJELOVANJA.idPodrucjeSudjelovanja=idPodrucjeSudjelovanja;
	END IF;
ELSE
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Upisano nepostojeće sudjelovanje (id sudjelovanja)!';
END IF;

ELSE
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Upisano nepostojeće podrucje (id podrucja)!';
END IF;

ELSE
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Traženi zapis ne postoji!';
END IF;
END $$
DELIMITER ;


DELIMITER $$
CREATE  PROCEDURE `azurirajSudjelovanje`(IN idSudjelovanja INT UNSIGNED, IN idOsobe INT UNSIGNED, IN idElektrijade INT UNSIGNED, IN tip CHAR(1), IN idVelicine INT UNSIGNED, IN idGodStud INT UNSIGNED, IN idSmjera INT UNSIGNED, IN idRadnogMjesta INT UNSIGNED, IN idZavoda INT UNSIGNED, IN idPutovanja INT UNSIGNED)
BEGIN
IF EXISTS (SELECT * FROM SUDJELOVANJE WHERE SUDJELOVANJE.idSudjelovanja=idSudjelovanja) THEN
	IF tip NOT IN('D','S') THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Varijabla tip mora biti ili S ili D!';
	ELSE
		IF tip = 'S' THEN
			IF idRadnogMjesta IS NOT NULL || idZavoda IS NOT NULL THEN
				 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Sudent ne može imati radno mjesto / zavod!';
			ELSE
				IF EXISTS (SELECT* FROM OSOBA WHERE OSOBA.idOsobe = idOsobe) THEN
				IF EXISTS (SELECT* FROM ELEKTRIJADA WHERE SUDJELOVANJE.idElektrijade = idElektrijade) THEN
				IF EXISTS (SELECT * FROM GODSTUD WHERE GODSTUD.idGodStud = idGodStud) THEN
					IF EXISTS (SELECT * FROM SMJER WHERE SMJER.idSmjera = idSmjera) THEN
						IF EXISTS (SELECT * FROM VELMAJICE WHERE VELMAJICE.idVelicine = idVelicine) THEN
							UPDATE SUDJELOVANJE SET
								SUDJELOVANJE.tip = tip,
								SUDJELOVANJE.idVelicine = idVelicine,
								SUDJELOVANJE.idGodStud = idGodStud,
								SUDJELOVANJE.idSmjera = idSmjera,
								SUDJELOVANJE.idRadnogMjesta = idRadnogMjesta,
                                SUDJELOVANJE.idOsobe = idOsobe,
                                SUDJELOVANJE.idElektrijade = idElektrijade,
								SUDJELOVANJE.idZavoda = idZavoda
							WHERE SUDJELOVANJE.idSudjelovanja=idSudjelovanja;
                          ELSE
							 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Id osobe je pogrešan!';
						END IF;
						ELSE
							 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Id eleektrijade je pogrešan!';
						END IF;
						ELSE
							 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Nije odabrana veličina majice!';
						END IF;
					ELSE
						 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Nije odabran smjer!';
					END IF;
				ELSE
					 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Nije odabrana godina studija!';
				END IF;
			END IF;
		ELSE
			    IF EXISTS (SELECT* FROM OSOBA WHERE OSOBA.idOsobe = idOsobe) THEN
				IF EXISTS (SELECT* FROM ELEKTRIJADA WHERE SUDJELOVANJE.idElektrijade = idElektrijade) THEN
				IF EXISTS (SELECT * FROM RADNOMJESTO WHERE RADNOMJESTO.idRadnogMjesta = idRadnogMjesta) THEN
					IF EXISTS (SELECT * FROM ZAVOD WHERE ZAVOD.idZavoda = idZavoda) THEN
						IF EXISTS (SELECT * FROM VELMAJICE WHERE VELMAJICE.idVelicine = idVelicine) THEN
							UPDATE SUDJELOVANJE SET
								SUDJELOVANJE.tip = tip,
								SUDJELOVANJE.idVelicine = idVelicine,
								SUDJELOVANJE.idGodStud = idGodStud,
								SUDJELOVANJE.idSmjera = idSmjera,
								SUDJELOVANJE.idRadnogMjesta = idRadnogMjesta,
                                SUDJELOVANJE.idOsobe = idOsobe,
                                SUDJELOVANJE.idElektrijade = idElektrijade,
								SUDJELOVANJE.idZavoda = idZavoda
							WHERE SUDJELOVANJE.idSudjelovanja=idSudjelovanja;
                        ELSE
							 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Id osobe je pogrešan!';
						END IF;
						ELSE
							 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Id eleektrijade je pogrešan!';
						END IF;
						ELSE
							 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Nije odabrana veličina majice!';
						END IF;
					ELSE
						 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Nije odabran zavod!';
					END IF;
				ELSE
					 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Nije odabrano radno mjesto!';
				END IF;
			
		END IF;
	END IF;
ELSE
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Traženi zapis ne postoji!';
END IF;
END $$
DELIMITER ;


DELIMITER $$
CREATE  PROCEDURE `brisiAtributOsobi`(IN idImaAtribut INT(10))
BEGIN
	IF EXISTS (SELECT * FROM ImaAtribut WHERE ImaAtribut.idImaAtribut=idImaAtribut ) THEN
		DELETE FROM ImaAtribut
		WHERE ImaAtribut.idImaAtribut=idImaAtribut;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji unos sa upisanim podacima!';
  	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiElekPodrucje`(IN idElekPodrucje INT(10))
BEGIN

IF NOT EXISTS (SELECT* 
		FROM ElekPodrucje WHERE ElekPodrucje.idElekPodrucje = idElekPodrucje ) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji područje koje želite izbrisati';
ELSE
	DELETE FROM ElekPodrucje
	WHERE ElekPodrucje.idElekPodrucje = idElekPodrucje ;

END IF;

END $$
DELIMITER ;


DELIMITER $$
CREATE  PROCEDURE `brisiElektrijadu`(IN idElektrijade INT(10))
BEGIN
IF NOT EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji tražena Elektrijada';
ELSE
DELETE FROM ELEKTRIJADA
WHERE ELEKTRIJADA.idElektrijade=idElektrijade ;

END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiKontakt`(IN idKontakta INT(10))
BEGIN
	IF EXISTS (SELECT * FROM KONTAKTOSOBE.idKontakta=idKontakta) THEN
		DELETE FROM KONTAKTOSOBE
		WHERE KONTAKTOSOBE.idKontakta=idKontakta;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne posotoji kontakt sa upisanim identifikatorom!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiMail`(IN idAdrese INT(10))
BEGIN
	IF EXISTS (SELECT * FROM EMAILADRESE WHERE EMAILADRESE.idAdrese=idAdrese) THEN
		DELETE FROM EMAILADRESE
		WHERE EMAILADRESE.idAdrese=idAdrese;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji e-adresa sa unesenim identifikatorom!';
    END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiMobitel`(IN idBroja INT(10))
BEGIN
	IF EXISTS (SELECT * FROM BROJEVIMOBITELA WHERE BROJEVIMOBITELA.idBroja=idBroja) THEN
		DELETE FROM BROJEVIMOBITELA
		WHERE BROJEVIMOBITELA.idBroja=idBroja;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji mobitel sa unesenim identifikatorom!';
    END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiOsobu`(IN idOsobe INT(10))
BEGIN
IF NOT EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe=idOsobe) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji OSOBA kuju želite izbrisati';
ELSE
DELETE FROM OSOBA
WHERE OSOBA.idOsobe=idOsobe ;

END IF;
END $$
DELIMITER ;


DELIMITER $$
CREATE  PROCEDURE `brisiPodrucjeSudjelovanja`(IN idPodrucjeSudejlovanja INT UNSIGNED)
BEGIN
IF EXISTS (SELECT * FROM PODRUCJESUDJELOVANJA WHERE PODRUCJESUDJELOVANJA.idPodrucjeSudejlovanja = idPodrucjeSudejlovanja) THEN
	DELETE FROM PODRUCJESUDJELOVANJA WHERE PODRUCJESUDJELOVANJA.idPodrucjeSudejlovanja = idPodrucjeSudejlovanja;
ELSE
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Traženi zapis ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiSudjelovanje`(IN idSudjelovanja INT UNSIGNED)
BEGIN
IF EXISTS (SELECT * FROM SUDJELOVANJE WHERE SUDJELOVANJE.idSudjelovanja = idSudjelovanja) THEN
	DELETE FROM SUDJELOVANJE WHERE SUDJELOVANJE.idSudjelovanja = idSudjelovanja;
ELSE
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Traženi zapis ne postoji!';
END IF;
END $$
DELIMITER ;


DELIMITER $$
CREATE  PROCEDURE `dodajArtibutOsobi`(IN idPodrucja INT(10), IN idAtributa INT(10), IN idSudjelovanja INT(10))
BEGIN
IF EXISTS (SELECT * FROM ImaAtribut WHERE ImaAtribut.idPodrucja=idPodrucja && ImaAtribut.idAtributa=idAtributa && ImaAtribut.idSudjelovanja=idSudjelovanja) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Zapis vec postoji u bazi!';
ELSE
	IF EXISTS (SELECT * FROM PODRUCJE WHERE PODRUCJE.idPodrucja=idPodrucja) THEN
		IF EXISTS (SELECT * FROM ATRIBUT WHERE ATRIBUT.idAtributa=idAributa) THEN			
				IF EXISTS (SELECT * FROM SUDJELOVANJE WHERE SUDEJLOVANJE.idSudjelovanja=idSudjelovanja) THEN
					INSERT INTO ImaAtribut values (NULL,idPodrucja,idAtributa,idSudjelovanja);
				ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Niste unijeli ispravan idSudjelovanja!';
				END IF;
			ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Niste unijeli ispravan identifikator osobe!';			
		END IF;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Niste unijeli ispravan identifikator podrucja!';
	END IF;
END IF;

END $$
DELIMITER ;


DELIMITER $$
CREATE  PROCEDURE `dodajElekPodrucje`(IN idPodrucja INT(10), IN rezultatGrupni SMALLINT(6),IN slikaLink VARCHAR(255),  IN idElektrijade INT(10), IN idSponzora INT(10))
BEGIN
IF EXISTS (SELECT * FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idPodrucja) THEN
IF EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN
IF ((idSponzora IS NOT NULL) && EXISTS (SELECT * FROM SPONZOR WHERE SPONZOR.idSponzora = idSponzora)) THEN
IF NOT EXISTS (SELECT * FROM ElekPodrucje WHERE ElekPodrucje.idElektrijade = idElektrijade  AND ElekPodrucje.idPodrucja = idPodrucja AND ElekPodrucje.idSponzora=idSponzora) THEN

		INSERT INTO ElekPodrucje VALUES (NULL,idPodrucja,rezultatGrupni,slikaLink,idElektrijade,idSponzora);
   
ELSE
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Već postoji unos za ovo podrucje na ovoj Elektrijadi!';
END IF;
ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Pogrešan unos sponzora!';
END IF;

ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Upisan nepostojeći datum Elektrijade!';
END IF;

ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Upisan nepostojeće područje!';
END IF;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajElektrijadu`(IN mjestoOdrzavanja VARCHAR(100), IN datumPocetka DATE, IN datumKraja DATE, IN ukupniRezultat SMALLINT(6), IN drzava VARCHAR(100))
BEGIN
IF NOT EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.datumPocetka = datumPocetka) THEN
IF (datumPocetka<datumKraja) THEN
	INSERT INTO ELEKTRIJADA VALUES (NULL,mjestoOdrzavanja,datumPocetka,datumKraja,ukupniRezultat,drzava);
ELSE
     SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Pogrešan unos datuma pocetka ili datuma kraja Elektrijade';
END IF;
ELSE
     SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Pogrešan unos datuma pocetka Elektrijade! Datum već postoji.';
END IF;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajGrupniRezultat`(IN idPodrucja INT(10),IN idElektrijade DATE,IN rezultatGrupni SMALLINT(6))
BEGIN
IF EXISTS (SELECT* FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idPodrucja) THEN
IF EXISTS (SELECT* FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN

IF NOT EXISTS (SELECT* 
		FROM ElekPodrucje WHERE ElekPodrucje.idPodrucja = idPodrucja and ElekPodrucje.idElektrijade=idElektrijade) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji područje kojem želote dodati sliku';
ELSE
	UPDATE ElekPodrucje
    SET ElekPodrucje.rezultatGrupni=rezultatGrupni
    WHERE ElekPodrucje.idPodrucja = idPodrucja and ElekPodrucje.idElektrijade=idElektrijade ;

END IF;
ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Upisan nepostojeći id Elektrijade!';
END IF;

ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Upisan nepostojeće područje!';
END IF;


END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajKontakt`(IN imeKontakt VARCHAR(100), IN prezimeKontakt VARCHAR(100), IN telefon VARCHAR(20), IN radnoMjesto VARCHAR(100), IN idTvrtke INT(10), IN idSponzora INT(10), IN idMedija INT(10))
BEGIN
IF EXISTS (SELECT * FROM KONTAKTOSOBE WHERE KONTAKTOSOBE.imeKontakt=imeKontakt && KONTAKTOSOBE.prezimeKontakt=prezimeKontakt && KONTAKTOSOBE.telefon=telefon && KONTAKTOSOBE.radnoMjesto=radnoMjesto && KONTAKTOSOBE.idTvrtke=idTvrtke &&KONTAKTOSOBE.idSponzora=idSponzora) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Zapis vec postoji u bazi!';
ELSE
  IF EXISTS (SELECT * FROM MEDIJ  WHERE MEDIJ.idMedija = idMedija ) || (idMedija IS NULL ) THEN
	IF EXISTS (SELECT * FROM TVRTKA WHERE TVRTKA.idTvrtke=idTvrtke) THEN
		IF EXISTS (SELECT * FROM SPONZOR WHERE SPONZOR.idSponzora=idSponzora) THEN
			IF (telefon REGEXP '[0-9]') THEN
				INSERT INTO KONTAKTOSOBE values(NULL,imeKontakt,prezimeKontakt,telefon,radnoMjesto,idTvrtke,idSponzora);
			ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Broj telefona moze sadrzavati samo znamenke!';
			END IF;
		ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji sponzor sa upisanim identifikatorom!';
		END IF;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji tvrtka sa unesenim identifikatorom';
	END IF;
    ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji medij sa unesenim identifikatorom';
	END IF;
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajMail`(IN idKontakta INT(10), IN email VARCHAR(100))
BEGIN
	IF NOT EXISTS (SELECT * FROM EMAILADRESE WHERE EMAILADRESE.email=email) THEN
		IF EXISTS (SELECT * FROM KONTAKTOSOBE WHERE KONTAKTOSOBE.idKontakta=idKontakta) THEN
			IF (email REGEXP '^[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9]@[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9]\.[a-zA-Z]{2,4}$') THEN
				INSERT INTO EMAILADRESE values (NULL,idKontakta,email);
			ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Krivi format email adrese!';
			END IF;
		ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji kontakt sa upisanim identifikatorom!';
		END IF;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Navedeni email je vec u bazi!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajMobitel`(IN idKontakta INT(10), IN broj VARCHAR(20))
BEGIN
	IF NOT EXISTS (SELECT * FROM BROJEVIMOBITELA WHERE BROJEVIMOBITELA.broj=broj) THEN
		IF EXISTS (SELECT * FROM KONTAKTOSOBE WHERE KONTAKTOSOBE.idKontakta=idKontakta) THEN
			IF broj REGEXP '[0-9]' THEN
				INSERT INTO BROJEVIMOBITELA values (NULL,idKontakta,broj);
			ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Broj mobitela moze sadrzavati samo znamenke!';
			END IF;
		ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji kontakt sa upisanim identifikatorom!';
		END IF;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Navedeni broj je vec u bazi!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajOsobu`(IN ime VARCHAR(50), IN prezime VARCHAR(50), IN mail VARCHAR(50),
 IN ferId VARCHAR(50), IN brojMob VARCHAR(20), IN passwordVAR VARCHAR(255), IN JMBAG VARCHAR(10), IN datRod DATE, IN spol CHAR(1),
IN brOsobne VARCHAR(20),IN brPutovnice VARCHAR(30),IN osobnaVrijediDo DATE,IN putovnicaVrijediDo DATE,IN uloga CHAR(1), IN zivotopis VARCHAR(200), IN MBG VARCHAR(9), IN OIB VARCHAR(11),IN idNadredjena INT(10), IN vrijedi BOOLEAN)
BEGIN
IF (spol IN ('m','z','M','Z')) THEN
IF ((osobnaVrijediDo>CURDATE() OR osobnaVrijediDo IS NULL) AND (putovnicaVrijediDo>CURDATE() OR putovnicaVrijediDo IS NULL)) THEN
IF (CHAR_LENGTH(JMBAG) = 10 AND JMBAG REGEXP '[0-9]') THEN
IF ((brPutovnice REGEXP '[0-9]' AND brOsobne REGEXP '[0-9]') OR brOsobne IS NULL OR brPutovnice IS NULL) THEN
		IF ( brojMob REGEXP '[0-9]') THEN
			IF (mail REGEXP '^[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9]@[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9]\.[a-zA-Z]{2,4}$') THEN
               IF(ferId REGEXP '[a-z][a-z][0-9][0-9][0-9][0-9][0-9]'  OR ferId REGEXP '[a-z]*[0-9]') THEN
				IF NOT EXISTS (SELECT * FROM OSOBA WHERE OSOBA.ferId=ferId) THEN
                  IF ( MBG REGEXP '[0-9]' OR MBG IS NULL) THEN
				     IF ( OIB REGEXP '[0-9]' OR OIB IS NULL) THEN
INSERT INTO OSOBA VALUES (NULL, ime, prezime, mail, brojMob, ferId, passwordVAR,  JMBAG, spol,datRod,brOsobne,brPutovnice,osobnaVrijediDo,putovnicaVrijediDo,uloga,zivotopi,MBG,OIB,idNadredjena,vrijedi);

   

ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: OIB smije sadrzavati samo znamenke !';
END IF;
ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: MBG smije sadrzavati samo znamenke !';
END IF;
    ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Upisan već postojeći ferId !';
END IF;

	ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Pogrešno upisan ferId! Unesite ferId u obliku "ab22222" ili "mivic3" !';
END IF;
    ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Niste unijeli dobar email!';
END IF;
	ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Broj  mobitela smiju sadrzavati samo brojke!';
	END IF;

	ELSE
        SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Broj putovnice ili broj osobne trebaju sadržavati samo znamenke!';

	END IF;
    ELSE
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: JMBAG mora sadržavati samo znamenke i biti deseteroznamenkast!';
END IF;

   ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Istekla je putovnica ili osobna iskaznica!';
END IF;
ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Pogrešno unešen spol! !';
END IF;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajPodrucjeSudjelovanja`(IN idPodrucja INT UNSIGNED, IN idSudjelovanja INT UNSIGNED, IN rezultatPojedinacni SMALLINT, IN vrstaPodrucja TINYINT(1),IN iznosUplate INT, IN valuta VARCHAR(3))
BEGIN
IF EXISTS (SELECT * FROM PODRUCJESUDJELOVANJA WHERE PODRUCJESUDJELOVANJA.idPodrucja = idPodrucja && PODRUCJESUDJELOVANJA.idSudjelovanja=idSudjelovanja) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Zapis već postoji!';
ELSE
	IF EXISTS (SELECT * FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idPodrucja) THEN
		IF EXISTS (SELECT * FROM SUDJELOVANJE WHERE SUDJELOVANJE.idSudjelovanja=idSudjelovanja) THEN
			IF idPodrucja IN (SELECT distinct PODRUCJE.idNadredjenog from PODRUCJE) THEN
				 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Pojedinačan rezultat moguće upisati samo za disciplinu unutar područja.';
			ELSE
				IF (vrstaPodrucja = 0 && rezultatPojedinacni IS NOT NULL) THEN
					 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Pojedinacni rezultat se ne može upisat u ekipnu disciplinu.';
				ELSE
					INSERT INTO PODRUCJESUDJELOVANJA VALUES(NULL,idPodrucja, idSudjelovanja, rezultatPojedinacni, vrstaPodrucja,iznosUplate,valuta);
				END IF;
			END IF;
		ELSE
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji zapis o sudjelovanju !';
		END IF;
	ELSE
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Zadani idPodručja ne postoji!';
	END IF;
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
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Traženi zapis ne postoji! / Navedena disciplina nema pojedinačnih rezultata!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajSliku`(IN idPodrucja INT(10),IN idElektrijade DATE,IN slikaLink VARCHAR(255))
BEGIN
IF EXISTS (SELECT* FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idPodrucja) THEN
IF EXISTS (SELECT* FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN

IF NOT EXISTS (SELECT* 
		FROM ElekPodrucje WHERE ElekPodrucje.idPodrucja = idPodrucja and ElekPodrucje.idElektrijade=idElektrijade) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji područje kojem želote dodati sliku';
ELSE
	UPDATE ElekPodrucje
    SET ElekPodrucje.slikaLink=slikaLink
    WHERE ElekPodrucje.idPodrucja = idPodrucja and ElekPodrucje.idElektrijade=idElektrijade ;

END IF;
ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Upisan nepostojeći datum Elektrijade!';
END IF;

ELSE 
	    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Upisan nepostojeće područje!';
END IF;


END $$
DELIMITER ;


DELIMITER $$
CREATE  PROCEDURE `dodajSponzorstvo`(IN idSponzora INT(10), IN idKategorijeSponzora INT(10), IN idPromocije INT(10), IN idElektrijade INT(10), IN iznosDonacije DECIMAL(13,2), IN valutaDonacije VARCHAR(3), IN napomena VARCHAR(300))
BEGIN
IF EXISTS (SELECT * FROM ImaSponzora WHERE ImaSponzora.idSponzora=idSponzora && ImaSponzora.datumPocetka=datumPocetka) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greksa: Zapis vec postoji u bazi';
ELSE
IF valutaDonacije NOT IN( 'HRK','USD','EUR') THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Valuta donacije mora biti HRK, USD ili EUR!';
ELSE
IF (iznosDonacije <= 0) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greksa: Iznos donacije je manji ili jednak nuli!';
ELSE
	IF EXISTS (SELECT * FROM SPONZOR WHERE SPONZOR.idSponzora=idSponzora) THEN
		IF EXISTS (SELECT * FROM KATEGORIJA WHERE KATEGORIJA.idKategorijeSponzora=idKategorijaSponzora) THEN
			IF EXISTS (SELECT * FROM NACINPROMOCIJE WHERE NACINPROMOCIJE.idPromocije=idPromocije) THEN
				IF EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade=idElektrijade) THEN
						INSERT INTO ImaSponzora values (NULL,idSponzora,idKategorijeSponzora,idPromocije,idElektrijade,iznosDonacije,valutaDonacije,napomena);
				ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji Elektrijada !';
				END IF;
			ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji nacin promocije s unesenim identifikatorom!';
			END IF;
		ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji kategorija sponzora s unesenim identifikatorom!';
		END IF;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji sponzor s unesenim identifikatorom!';
	END IF;
END IF;
END IF;
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajSudjelovanje`(IN idOsobe INT UNSIGNED, IN idElektrijade INT UNSIGNED, IN tip CHAR(1), IN idVelicine INT UNSIGNED, IN idGodStud INT UNSIGNED, IN idSmjera INT UNSIGNED, IN idRadnogMjesta INT UNSIGNED, IN idZavoda INT UNSIGNED,IN idPutovanja INT UNSIGNED)
BEGIN
IF EXISTS (SELECT * FROM SUDJELOVANJE WHERE SUDJELOVANJE.idOsobe = idOsobe && SUDJELOVANJE.idElektrijade = idElektrijade) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: zapis već postoji TJ. osoba je vec unesena kao sudionik elektrijade!';
ELSE
	IF tip NOT IN('D','S') THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Varijabla tip mora biti ili S ili D!';
	ELSE
		IF EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe = idOsobe) THEN
			IF EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN
				IF tip = 'S' THEN
					IF idRadnogMjesta IS NOT NULL || idZavoda IS NOT NULL THEN
						 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Sudent ne može imati radno mjesto / zavod!';
					ELSE
						IF EXISTS (SELECT * FROM GODSTUD WHERE GODSTUD.idGodStud = idGodStud) THEN
							IF EXISTS (SELECT * FROM SMJER WHERE SMJER.idSmjera = idSmjera) THEN
								IF EXISTS (SELECT * FROM VELMAJICE WHERE VELMAJICE.idVelicine = idVelicine) THEN
                                   IF EXISTS (SELECT * FROM PUTOVANJE WHERE PUTOVANJE.idPutovanja = idPutovanja) THEN
									INSERT INTO SUDJELOVANJE VALUES(NULL,idOsobe, idElektrijade, tip, idVelicine, idGodStud, idSmjera, idRadnogMjesta, idZavoda,idPutovanja);			
								
                                    ELSE
									      SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Nije odabrano putovanje!';
								    END IF;    
                                ELSE
									 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Nije odabrana veličina majice!';
								END IF;
							ELSE
								 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Nije odabran smjer!';
							END IF;
						ELSE
							 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Nije odabrana godina studija!';
						END IF;
					END IF;
				ELSE
						IF EXISTS (SELECT * FROM RADNOMJESTO WHERE RADNOMJESTO.idRadnogMjesta = idRadnogMjesta) THEN
							IF EXISTS (SELECT * FROM ZAVOD WHERE ZAVOD.idZavoda = idZavoda) THEN
								IF EXISTS (SELECT * FROM VELMAJICE WHERE VELMAJICE.idVelicine = idVelicine) THEN
                                    IF EXISTS (SELECT * FROM PUTOVANJE WHERE PUTOVANJE.idPutovanja = idPutovanja) THEN
										INSERT INTO SUDJELOVANJE VALUES(NULL,idOsobe, idElektrijade, tip, idVelicine, idGodStud, idSmjera, idRadnogMjesta, idZavoda,idPutovanja);
									ELSE
									      SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Nije odabrano putovanje!';
								    END IF; 
                                ELSE
									 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Nije odabrana veličina majice!';
								END IF;
							ELSE
								 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Nije odabran ispravan zavod!';
							END IF;
						ELSE
							 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Nije odabrano radno mjrdto!';
						END IF;	
					
				END IF;
			ELSE
				 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Unesena Elektrijada nije evidentirana!';
			END IF;
		ELSE
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Nepoznati idOsobe!';
		END IF;
	END IF;
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiBrojeve`(IN `id_kontakta` INT UNSIGNED)
BEGIN

SELECT * FROM BROJEVIMOBITELA WHERE idKontakta = id_kontakta;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiKontaktOsobe`(IN vrsta CHAR(1), IN id INT)
BEGIN
CASE
WHEN vrsta = 't' THEN
	IF EXISTS (SELECT * FROM tvrtka WHERE tvrtka.idTvrtke = id) THEN
        			SELECT DISTINCT kontaktOsobe.idKontakta, kontaktOsobe.imeKontakt, kontaktOsobe.prezimeKontakt, kontaktOsobe.telefon, kontaktOsobe.radnoMjesto
       			 FROM kontaktOsobe
  			WHERE kontaktOsobe.idTvrtke = id;
		ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Unešen je nepostojeći ID tvrtke';
		END IF;
	WHEN vrsta = 's' THEN
		IF EXISTS (SELECT * FROM sponzor WHERE sponzor.idSponzora = id) THEN
			SELECT DISTINCT kontaktOsobe.idKontakta, kontaktOsobe.imeKontakt, kontaktOsobe.prezimeKontakt, kontaktOsobe.telefon, kontaktOsobe.radnoMjesto
FROM kontaktOsobe
     			WHERE kontaktOsobe.idSponzora = id;
		ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Unešen je nepostojeći ID sponzora';
		END IF;
ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška pri unosu vrste';
END CASE;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiMail`(IN `id_kontakta` INT UNSIGNED)
BEGIN

SELECT * FROM EMAILADRESE WHERE idKontakta = id_kontakta;

END $$
DELIMITER ;


DELIMITER $$
CREATE  PROCEDURE `dohvatiPopisGrupnihRezultata`(IN idElektrijade INT(10))
BEGIN
	IF EXISTS (SELECT * FROM Elektrijada WHERE Elektrijada.idElektrijade = idElektrijade) THEN
		SELECT DISTINCT podrucje.idPodrucja, podrucje.nazivPodrucja, ElekPodrucje.rezultatGrupni
		FROM podrucje
		JOIN ElekPodrucje ON podrucje.idPodrucja = ElekPodrucje.idPodrucja
		WHERE ElekPodrucje.idElektrijade = idElektrijade;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Unesen je nepostojeci datumPocetka';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiPopisSvihClanovaOdbora`(IN idElektrijade INT(10))
BEGIN
	IF EXISTS (SELECT * FROM Elektrijada WHERE Elektrijada.idElektrijade = idElektrijade) THEN
		SELECT DISTINCT osoba.idOsobe, osoba.ime, osoba.prezime, funkcija.nazivFunkcije
		FROM osoba
		JOIN obavljaFunkciju ON osoba.idOsobe = obavljaFunkciju.idOsobe
		LEFT JOIN funkcija ON obavljaFunkciju.idFunkcije = funkcija.idFunkcije
		WHERE obavljaFunkciju.idElektrijade = idElektrijade AND  ( osoba.uloga = 'o' OR osoba.uloga = 'O');
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Unesen je nepostojeci datumPocetka';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiPopisSvihDjelatnika`(IN idElektrijade INT(10))
BEGIN
	IF EXISTS (SELECT * FROM Elektrijada WHERE Elektrijada.idElektrijade = idElektrijade) THEN
    		SELECT DISTINCT osoba.idOsobe, osoba.ime, osoba.prezime, radnoMjesto.naziv AS RadnoMjesto, zavod.nazivZavoda, atribut.nazivAtributa, podrucjeSudjelovanja.rezultatPojedinacni, podrucje.nazivPodrucja, velMajice.velicina AS velicinaMajice
  		FROM osoba
JOIN sudjelovanje ON osoba.idOsobe = sudjelovanje.idOsobe
LEFT JOIN radnoMjesto ON sudjelovanje.idRadnogMjesta = radnoMjesto.idRadnogMjesta
LEFT JOIN velMajice ON sudjelovanje.idVelicine = velMajice.idVelicine
LEFT JOIN zavod ON sudjelovanje.idZavoda = zavod.idZavoda
LEFT JOIN imaAtribut ON sudjelovanje.idOsobe = imaAtribut.idOsobe
LEFT JOIN atribut ON imaAtribut.idAtributa = atribut.idAtributa
LEFT JOIN podrucjeSudjelovanja ON podrucjeSudjelovanja.idOsobe = sudjelovanje.idOsobe
LEFT JOIN podrucje ON podrucjeSudjelovanja.idPodrucja = podrucje.idPodrucja
WHERE sudjelovanje.idElektrijade = idElektrijade AND ( osoba.uloga = 'S' OR osoba.uloga = 's')
AND ( osoba.uloga = 'd' OR osoba.uloga = 'D');
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Unesen je nepostojeci idELEKTRIJADE';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiPopisSvihStudenata`(IN idElektrijade INT(10))
BEGIN
	IF EXISTS (SELECT * FROM Elektrijada WHERE Elektrijada.idElektrijade = idElektrijade) THEN
        SELECT DISTINCT osoba.idOsobe, osoba.ime, osoba.prezime, godStud.studij, godStud.godina, smjer.nazivSmjera, atribut.nazivAtributa, podrucjeSudjelovanja.rezultatPojedinacni, podrucje.nazivPodrucja, velMajice.velicina AS velicinaMajice
        FROM osoba
        JOIN sudjelovanje ON osoba.idOsobe = sudjelovanje.idOsobe
        LEFT JOIN godStud ON sudjelovanje.idGodStud = godStud.idGodStud
        LEFT JOIN velMajice ON sudjelovanje.idVelicine = velMajice.idVelicine
        LEFT JOIN smjer ON sudjelovanje.idSmjera = smjer.idSmjera
        LEFT JOIN imaAtribut ON sudjelovanje.idOsobe = imaAtribut.idOsobe
        LEFT JOIN atribut ON imaAtribut.idAtributa = atribut.idAtributa
        LEFT JOIN podrucjeSudjelovanja ON podrucjeSudjelovanja.idOsobe = sudjelovanje.idOsobe
        LEFT JOIN podrucje ON podrucjeSudjelovanja.idPodrucja = podrucje.idPodrucja
        WHERE sudjelovanje.idElektrijade = idElektrijade AND ( osoba.uloga = 'S' OR osoba.uloga = 's')
        AND (sudjelovanje.tip = 's' OR osudjelovanje.tip = 'S');
ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Unesen je nepostojeci idELEKTRIJADE';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `pridruziFunkciju`(IN idOsobe INT UNSIGNED, IN idFunkcije INT UNSIGNED, IN idElektrijade INT(10))
BEGIN 
IF EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe = idOsobe && OSOBA.uloga = "O") THEN
	IF NOT EXISTS ( SELECT * FROM ObavljaFunkciju WHERE ObavljaFunkciju.idOsobe = idOsobe && ObavljaFunkciju.idFunkcije = idFunkcije && ObavljaFunkciju.idElektrijade = idElektrijade) THEN
		IF EXISTS ( SELECT * FROM FUNKCIJA WHERE FUNKCIJA.idFunkcije = idFunkcije) THEN
			IF EXISTS ( SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN
				INSERT INTO ObavljaFunkciju VALUES (NULL,idOsobe, idFunkcije, idElektrijade);
			ELSE 
				 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: navedeni datum početka nije valjan!';
			END IF;
		ELSE
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: navedeni id funkcije nije valjan!';
		END IF;
	ELSE
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: unešeni zapis već postoji!';
	END IF;
ELSE
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: navedena osoba ne postoji ili nije član OZSN!';
END IF;
END $$
DELIMITER ;




DELIMITER $$
CREATE  PROCEDURE `dodajBus`( IN registracija VARCHAR(12), IN brojMjesta INT, IN brojBusa INT )
BEGIN
IF EXISTS (SELECT* FROM BUS WHERE BUS.registracija=registracija ) THEN  
               SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Zapis već postoji!';
ELSE
INSERT INTO BUS VALUES(NULL, registracija, brojMjesta,brojBusa);
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajPutovanje`(IN idBusa INT(10), IN polazak BOOLEAN, IN povratak BOOLEAN, IN napomena VARCHAR(200), IN brojSjedala INT)
BEGIN
	IF EXISTS (SELECT * FROM BUS WHERE BUS.idBusa=idBusa ) THEN 
      If (SELECT brojMjesta FROM BUS WHERE BUS.idBusa=idBusa) > brojSjedala THEN		
			INSERT INTO PUTOVANJE VALUES (NULL,idBusa,polazak,povratak,napomena,brojSjedala);		
      ELSE 
	       SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: brojSjedala je veči od ukupnog broja sjedećih mjesta u busu!';
	  END IF;
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Unesen nepostojeći bus!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajObjavu`(IN datumObjave DATE, IN link VARCHAR(100), IN autorIme VARCHAR(50), IN autorPrezime VARCHAR(50), IN idMedijja INT(10),IN dokument VARCHAR(200))
BEGIN
	IF EXISTS (SELECT * FROM MEDIJ WHERE MEDIJ.idMedijja=idMedijja ) THEN       	
			INSERT INTO OBJAVA VALUES (NULL,datumObjave,link,autorIme,autorPrezime,idMedij,dokument);	    
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Unesen nepostojeći mredij!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajObjavuOElektrijadi`( IN idObjave INT(10),IN idElektrijade INT(10))
BEGIN
	IF EXISTS (SELECT * FROM OBJAVA WHERE OBJAVA.idObjave=idObjave ) THEN
         IF EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade=idElektrijade ) THEN    
			INSERT INTO objavaOElektrijadi VALUES (NULL,idObjave,idElektrijade);	    
       ELSE 
		    SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Unesen nepostojeći id elektrijade!';
	   END IF;
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Unesen nepostojeći id Objave!';
	END IF;
END $$
DELIMITER ;


-- DELIMITER $$
-- CREATE  PROCEDURE `brisiMedij`(IN idMedija INT(10))
-- BEGIN
-- 	IF EXISTS (SELECT * FROM MEDIJ.idMedija=idMedija) THEN
-- 		DELETE FROM MEDIJ
-- 		WHERE MEDIJ.idMedijaa=idMedija;
-- 	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne posotoji MEDIJ sa upisanim identifikatorom!';
-- 	END IF;
-- END $$
-- DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiObjavu`(IN idObjave INT(10))
BEGIN
	IF EXISTS (SELECT * FROM OBJAVA.idObjave=idObjave) THEN
		DELETE FROM OBJAVA
		WHERE OBJAVA.idObjave=idObjave;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne posotoji objava sa upisanim identifikatorom!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiBus`(IN idBusa INT(10))
BEGIN
	IF EXISTS (SELECT * FROM BUS.idBusa=idBusa) THEN
		DELETE FROM BUS
		WHERE BUS.idBusa=idBusa;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne posotoji bus sa upisanim identifikatorom!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiPutovanje`(IN idPutovanja INT(10))
BEGIN
	IF EXISTS (SELECT * FROM PUTOVANJE.idPutovanja=idPutovanja) THEN
		DELETE FROM PUTOVANJE
		WHERE PUTOVANJE.idPutovanja=idPutovanja;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne posotoji putovanje sa upisanim identifikatorom!';
	END IF;
END $$
DELIMITER ;


DELIMITER $$
CREATE  PROCEDURE `azurirajBus`(IN idBusa INT(10), IN registracija VARCHAR(12), IN brojMjesta INT, IN brojBusa INT)
BEGIN
	IF NOT EXISTS (SELECT * FROM BUS WHERE BUS.registracija=registracija) THEN
		
				UPDATE BUS
				SET BUS.registracija=registracija,BUS.brojMjesta=brojMjesta, BUS.brojBusa=brojBusa
				WHERE BUS.idBusa=idBusa;
			
	ELSE
       SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Navedena registracija se vec nalazi u bazi!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajPutovanje`(IN idPutovanja INT(10), IN idBusa INT(10), IN polazak BOOLEAN, IN povratak BOOLEAN, IN napomena VARCHAR(200), IN brojSjedala INT)
BEGIN
	IF  (SELECT brojMjesta FROM BUS WHERE BUS.idBusa=idBusa)>brojSjedala  THEN
		
				UPDATE PUTOVANJE
				SET PUTOVANJE.idBusa=idBusa,PUTOVANJE.polazak=polazak, PUTOVANJE.povratak =povratak,PUTOVANJE.napomena=napomena,PUTOVANJE.napomena=napomena 
				WHERE PUTOVANJE.idPutovanja=idPutovanja;
			
	ELSE 
       SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Broj sjedala je veći od ukupnog broja mjesta u busu!';
	END IF;
END $$
DELIMITER ;

-- DELIMITER $$
-- CREATE  PROCEDURE `azurirajMedij`(IN idMedija INT(10), IN nazivMedija VARCHAR(10))
-- BEGIN
-- 	IF EXISTS (SELECT * FROM MEDIJ WHERE BUS.nazivMedija=inazivMedija) THEN
-- 		
-- 				UPDATE MEDIJ
-- 				SET MEDIJ.idKontakta=idKontakta,MEDIJ.nazivMedija=nazivMedija
-- 				WHERE MEDIJ.idMedija=idMedija;
-- 			
-- 	ELSE 
--        SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Naziv medija već postoji u bazi!';
-- 	END IF;
-- END $$
-- DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajObjavu`(IN idObjave INT(10),IN datumObjave DATE, IN link VARCHAR(100), IN autorIme VARCHAR(50), IN autorPrezime VARCHAR(50), IN idMedija INT(10),IN dokument VARCHAR(200))
BEGIN
	IF EXISTS (SELECT * FROM MEDIJ WHERE MEDIJ.idMedija=idMedija) THEN
		
				UPDATE OBJAVA
				SET OBJAVA.datumObjave=datumObjave,OBJAVA.link=link, OBJAVA.autorIme =autorIme,OBJAVA.autorPrezime=autorPrezime,OBJAVA.idMedija=idMedija ,OBJAVA.dokument=dokument
				WHERE OBJAVA.idObjave=idObjave;
			
	ELSE 
       SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Unesen nepostojeć medij!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiPopisSvihBuseva`()
BEGIN

SELECT * FROM BUS ORDER BY brojMjesta ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiPopisSvihBusevaNaElektrijadi`(IN idElektrijade INT(10))
BEGIN
     IF EXISTS (SELECT * FROM Elektrijada WHERE Elektrijada.idElektrijade = idElektrijade) THEN
		SELECT DISTINCT BUS.idBusa=idBusa, BUS.registracija , BUS.brojMjesta=brojMjesta, BUS.brojBusa
		FROM BUS 
		RIGHT JOIN PUTOVANJE ON PUTOVANJE.idBusa=BUS.idBusa
		RIGHT JOIN SUDJELOVANJE ON SUDJELOVANJE.idPutovanja=idPutovanja
		WHERE SUDJELOVANJE.idElektrijade = idElektrijade;
		
      ELSE
           SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Unesen je nepostojeci idElektrijade';
	  END IF;

SELECT * FROM BUS ORDER BY brojMjesta ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiPopisSvihObjavaOElektrijadi`(IN idElektrijade INT(10))
BEGIN
	IF EXISTS (SELECT * FROM Elektrijada WHERE Elektrijada.idElektrijade = idElektrijade) THEN
		SELECT DISTINCT OBJAVA.idObjave, OBJAVA.datumObjave,OBJAVA.link, OBJAVA.autorIme ,OBJAVA.autorPrezime,OBJAVA.idMedija ,OBJAVA.dokument
		FROM OBJAVA
		JOIN 	obajvaOElektrijadi ON OBJAVA.idObjave = obajvaOElektrijadi.idObjave		
		WHERE obajvaOElektrijadi.idElektrijade = idElektrijade;
    ELSE 
        SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Unesen je nepostojeci idElektrijade';
	END IF;
END $$
DELIMITER ;
 DELIMITER $$
CREATE  PROCEDURE `dohvatiOdredeniAtribut`(IN idOsoba INT(10))
BEGIN
SELECT atribut.nazivAtributa FROM sudjelovanje 
LEFT JOIN imaatribut ON sudjelovanje.idSudjelovanja = imaatribut.idSudjelovanja
 JOIN atribut ON imaatribut.idAtributa = atribut.idAtributa
WHERE sudjelovanje.idOsobe = idOsoba;
END $$
DELIMITER ;

DELIMITER $$

CREATE  PROCEDURE `dohvatiOsobnaPodrucja`(IN idElektrijada INT(10), IN idOsobe INT(10))
BEGIN
SELECT podrucje.idPodrucja FROM sudjelovanje 
LEFT JOIN podrucjeSudjelovanja ON sudjelovanje.idSudjelovanja = podrucjeSudjelovanja.idSudjelovanja
 JOIN podrucje ON podrucje.idPodrucja = podrucjeSudjelovanja.idPodrucja
WHERE sudjelovanje.idOsobe = idOsobe;
END $$
DELIMITER ;
