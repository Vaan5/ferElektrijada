
DELIMITER $$
CREATE  PROCEDURE `azurirajAtribut`(IN idAtributa INT(10),IN nazivAtributa VARCHAR(100))
BEGIN
IF NOT EXISTS (SELECT* 
		FROM ATRIBUT WHERE ATRIBUT.idAtributa = idAtributa) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji traženi atribut';
ELSE
	UPDATE ATRIBUT
	SET ATRIBUT.nazivAtributa=nazivAtributa
	WHERE ATRIBUT.idAtributa = idAtributa;
END IF;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajElekPodrucje`(IN idElekPodrucje INT(10), IN idPodrucja INT(10),IN datumPocetka DATE, IN rezultatGrupni SMALLINT(6),IN slikaLink VARCHAR(255), IN slikaBLOB BLOB, IN idSponzora INT(10))
BEGIN
IF EXISTS (SELECT* FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idPodrucja) THEN
IF EXISTS (SELECT* FROM ELEKTRIJADA WHERE ELEKTRIJADA.datumPocetka = datumPocetka) THEN
IF NOT EXISTS (SELECT* 
		FROM ElekPodrucje WHERE ElekPodrucje.idElekPodrucje = idElekPodrucje ) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji područje koje želite ažurirati';
ELSE
	UPDATE ElekPodrucje
    SET ElekPodrucje.datumPocetka=datumPocetka, ElekPodrucje.rezultatGrupni=rezultatGrupni, ElekPodrucje.slikaLink=slikaLink, ElekPodrucje.slikaBLOB=slikaBLOB, ElekPodrucje.idPodrucja=idPodrucja, ElekPodrucje.idSponzora=idSponzora
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

CREATE  PROCEDURE `azurirajFunkcijuOdbora`(IN idFunkcije INT UNSIGNED, IN nazivFunkcije VARCHAR (100))
BEGIN
IF EXISTS (SELECT * FROM FUNKCIJA WHERE FUNKCIJA.idFunkcije = idFunkcije) THEN
	IF NOT EXISTS (SELECT * FROM FUNKCIJA WHERE FUNKCIJA.nazivFunkcije = nazivFunkcije) THEN
		IF (nazivFunkcije IS NOT NULL) THEN
			UPDATE FUNKCIJA SET
				FUNKCIJA.nazivFunkcije = nazivFunkcije
			WHERE FUNKCIJA.idFunkcije = idFunkcije;
		ELSE 
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Naziv funkcije mora biti poznat!';
		END IF;
	ELSE
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: naziv funkcije već postoji!';
	END IF;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: id funkcije nije pronađen!';
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
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Odabrana vrijednost ne postoji!';
END IF;
END $$
DELIMITER ;


DELIMITER $$
CREATE  PROCEDURE `azurirajKategorijuSponzora`(IN idKategorijeSponzora INT(10), IN tipKategorijeSponzora VARCHAR(100))
BEGIN
	IF NOT EXISTS (SELECT * FROM KATEGORIJA WHERE KATEGORIJA.tipKategorijeSponzora=tipKategorijeSponzora) THEN
		IF EXISTS (SELECT * FROM KATEGORIJA WHERE KATEGORIJA.idKategorijaSponzora=idKategorijaSponzora) THEN
			UPDATE KATEGORIJA
			SET KATEGORIJA.tipKategorijaSponzora=tipKategorijeSponzora
			WHERE KATEGORIJA.idKategorijaSponzora=idKategorijaSponzora;
		ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji kategorija sponzora sa zadanim identifikatorom!';
		END IF;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ovaj tip kategorije sponzora je vec unesen u bazu!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajKontakt`(IN idKontakta INT(10), IN imeKontakt VARCHAR(100), IN prezimeKontakt VARCHAR(100), IN telefon VARCHAR(20), IN radnoMjesto VARCHAR(100), IN idTvrtke INT(10), IN idSponzora INT(10))
BEGIN
	IF EXISTS (SELECT * FROM KONTAKTOSOBE.idKontakta=idKontakta) && (telefon REGEXP '[0-9]') THEN
		UPDATE KONTAKTOSOBE
		SET KONTAKTOSOBE.imeKontakt=imeKontakt, KONTAKTOSOBE.prezimeKontakt=prezimeKontakt, KONTAKTOSOBE.telefon=telefon, KONTAKTOSOBE.radnoMjesto=radnoMjesto, KONTAKTOSOBE.idTvrtke=idTvrtke, KONTAKTOSOBE.idSponzora=idSponzora
		WHERE KONTAKTOSOBE.idKontakta=idKontakta;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji kontakt sa upisanim identifikatorom!';
	END IF;
END $$
DELIMITER ;


DELIMITER $$
CREATE  PROCEDURE `azurirajKoristenjeUsluga`(IN idKoristiPruza INT(10), IN Usluga INT UNSIGNED,  IN Tvrtka INT UNSIGNED, IN iznos DECIMAL(13,2),  IN valuta VARCHAR(3), IN idElektrijade INT(10), IN nacin VARCHAR(100), IN napomene VARCHAR(300) )
BEGIN
IF EXISTS (SELECT * FROM USLUGA  WHERE idUsluge=Usluga) THEN
IF EXISTS (SELECT * FROM TVRTKA  WHERE idTvrtke = Tvrtka) THEN
			IF EXISTS (SELECT * FROM ELEKTRIJADA  WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN
				IF UPPER(valuta) NOT IN( 'HRK','USD','EUR') THEN
 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = ' Greška: Valuta donacije mora biti HRK, USD ili EUR!';
				ELSE	IF iznos <= 0 THEN
 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = ' Greška: Iznos donacije je nula ili manji!';

				ELSE	    	UPDATE KoristiPruza					SET idUsluge=Usluga,ELEKTRIJADA.idElektrijade=idElektrijade, idTvrtke=Tvrtka, iznosRacuna=iznos, valutaRacuna=valuta, nacinPlacanja=nacin, napomena=napomene
WHERE KoristiPruza.idKoristiPruza=idKoristiPruza;
				END IF;
				END IF;
					ELSE
					 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Odabrani datum nije početak Elektrijade!';
				END IF;
		ELSE
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Odabrana tvrtka ne postoji! ';
		END IF;
ELSE
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Usluga nije evidentirana u bazi  podataka!';
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
CREATE  PROCEDURE `azurirajNacinPromocije`(IN idPromocije INT UNSIGNED, IN tipPromocije VARCHAR(100))
BEGIN
IF EXISTS ( SELECT * FROM NACINPROMOCIJE WHERE NACINPROMOCIJE.idPromocije = idPromocije) THEN
	IF NOT EXISTS (SELECT * FROM NACINPROMOCIJE WHERE NACINPROMOCIJE.tipPromocije = tipPromocije) THEN
		IF (tipPromocije IS NOT NULL) THEN		
			UPDATE NACINPROMOCIJE SET
			NACINPROMOCIJE.tipPromocije = tipPromocije
			WHERE NACINPROMOCIJE.idPromocije = idPromocije;
		ELSE 
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Tip promocije mora biti poznat!';
		END IF;
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Tip promocije već postoji!';
	END IF;
ELSE
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: id promocije nije pronađen!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajOsobu`(IN idOsobe INT(10), ime VARCHAR(50), IN prezime VARCHAR(50), IN mail VARCHAR(50),
 IN ferId VARCHAR(50), IN brojMob VARCHAR(20), IN passwordVAR VARCHAR(255), IN JMBAG VARCHAR(10), IN datRod DATE, IN spol CHAR(1),
IN brOsobne VARCHAR(20),IN brPutovnice VARCHAR(30),IN osobnaVrijediDo DATE,IN putovnicaVrijediDo DATE,IN uloga CHAR(1), IN zivotopis BLOB, IN MBG VARCHAR(9), IN OIB VARCHAR(11))
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
SET OSOBA.JMBAG=JMBAG, OSOBA.password=passwordVAR, OSOBA.ime=ime, OSOBA.prezime=prezime, OSOBA.mail=mail, OSOBA.ferId=ferId, OSOBA.brojMob=brojMob, OSOBA.datRod=datRod, OSOBA.spol=spol, OSOBA.brOsobne=brOsobne, OSOBA.brPutovnice=brPutovnice, OSOBA.putovnicaVrijediDo=putovnicaVrijediDo, OSOBA.osobnaVrijediDo=osobnaVrijediDo, OSOBA.uloga=uloga,OSOBA.zivotopis=zivotopis, OSOBA.MBG=MBG, OSOBA.OIB=OIB
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
CREATE  PROCEDURE `azurirajPodrucje`(IN idPodrucja INT(10),IN nazivPodrucja VARCHAR(100),IN idNadredjenog INT(10))
BEGIN
IF NOT EXISTS (SELECT* FROM PODRUCJE WHERE PODRUCJE.nazivPodrucja = nazivPodrucja) THEN
IF NOT EXISTS (SELECT * FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idPodrucja) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Područje je pogrešno zadano';
ELSE
UPDATE PODRUCJE
SET  PODRUCJE.nazivPodrucja=nazivPodrucja,PODRUCJE.idNadredjenog=idNadredjenog
WHERE PODRUCJE.idPodrucja = idPodrucja ;

END IF;
ELSE
 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ovo područje je već unešeno!';
END IF;

END $$
DELIMITER ;


DELIMITER $$
CREATE  PROCEDURE `azurirajPodrucjeSudjelovanja`(IN idPodrucjeSudjelovanja INT UNSIGNED, IN idPodrucja INT UNSIGNED, IN idSudjelovanja INT UNSIGNED, IN rezultatPojedinacni SMALLINT, IN vrstaPodrucja TINYINT(1))
BEGIN

IF EXISTS (SELECT * FROM PODRUCJESUDJELOVANJA WHERE PODRUCJESUDJELOVANJA.idPodrucjeSudjelovanja = idPodrucjeSudjelovanja) THEN
IF EXISTS (SELECT* FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idPodrucja) THEN
IF EXISTS (SELECT* FROM SUDJELOVANJE WHERE SUDJELOVANJE.idSudjelovanja = idSudjelovanja) THEN
	IF (vrstaPodrucja = 0 AND rezultatPojedinacni IS NOT NULL) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Pojedinacni rezultat se ne može upisat u ekipnu disciplinu.';
	ELSE
		UPDATE PODRUCJESUDJELOVANJA SET PODRUCJESUDJELOVANJA.rezultatPojedinacni = rezultatPojedinacni, PODRUCJESUDJELOVANJA.vrstaPodrucja = vrstaPodrucja, PODRUCJESUDJELOVANJA.idPodrucja=idPodrucja ,PODRUCJESUDJELOVANJA.idSudjelovanja=idSudjelovanja
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
CREATE  PROCEDURE `azurirajRadnoMjesto`( IN id INT UNSIGNED,  IN ime VARCHAR(100)  )
BEGIN
IF EXISTS (SELECT * FROM RADNOMJESTO WHERE idRadnogMjesta = id) THEN
UPDATE RADNOMJESTO 
SET idRadnogMjesta=id, naziv=ime WHERE idRadnogMjesta = id;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Odabrana vrijednost ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajSmjer`( IN id INT UNSIGNED,  IN naziv VARCHAR(100)  )
BEGIN
IF EXISTS (SELECT * FROM SMJER WHERE idSmjera = id) THEN
UPDATE SMJER
SET idSmjera=id, nazivSmjera=naziv WHERE idSmjera = id;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Odabrana vrijednost ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajSponzora`(IN idSponzora INT(10), IN imeTvrtke VARCHAR(100), IN adresaTvrtke VARCHAR(100))
BEGIN
IF NOT EXISTS (SELECT * FROM SPONZOR WHERE SPONZOR.imeTvrtke=imeTvrtke AND SPONZOR.adresaTvrtke=adresaTvrtke) THEN
	IF EXISTS (SELECT * FROM SPONZOR WHERE SPONZOR.idSponzora=idSponzora) THEN
		UPDATE SPONZOR
		SET SPONZOR.imeTvrtke=imeTvrtke, SPONZOR.adresaTvrtke=adresaTvrtke
		WHERE SPONZOR.idSponzora=idSponzora;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji sponzor sa unesenim identifikatorom!';
	END IF;
ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Zadani zapis vec postoji u bazi!';
END IF;
END $$
DELIMITER ;


DELIMITER $$
CREATE  PROCEDURE `azurirajSponzorstvo`(IN  idImaSponzora INT(10) ,IN idSponzora INT(10), IN idKategorijeSponzora INT(10), IN idPromocije INT(10), IN idElektrijade INT(10), IN iznosDonacije DECIMAL(13,2), IN valutaDonacije VARCHAR(3), IN napomena VARCHAR(300))
BEGIN
IF EXISTS (SELECT * FROM ImaSponzora WHERE ImaSponzora.idImaSponzora=idImaSponzora ) THEN
IF EXISTS (SELECT* FROM SPONZOR WHERE SPONZOR.idSponzora = idSponzora) THEN
IF EXISTS (SELECT* FROM ELEKTRIJADA WHERE SUDJELOVANJE.idElektrijade = idElektrijade) THEN
	IF valutaDonacije NOT IN( 'HRK','USD','EUR') THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Valuta donacije mora biti HRK, USD ili EUR!';
	ELSE
	IF (iznosDonacije <= 0) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greksa: Iznos donacije je manji ili jednak nuli!';
	ELSE
		UPDATE ImaSponzora 
		SET ImaSponzora.idKategorijaSponzora=idKategorijeSponzora,ImaSponzora.idPromocije=idPromocije,ImaSponzora.iznosDonacije=iznosDonacije,ImaSponzora.valutaDonacije=valutaDonacije,ImaSponzora.napomena=napomena ,ImaSponzora.idSponzora=idSponzora ,ImaSponzora.idElektrijade=idElektrijade
		WHERE ImaSponzora.idImaSponzora=idImaSponzora;
	END IF;
	END IF;
ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji doticni sponzor!';
END IF;
ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji doticna elektrijada!';
END IF;
ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji unos sa upisanim podacima!';
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
			IF idGodStud IS NOT NULL || idSmjera IS NOT NULL THEN
				 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Djelatnik ne može imati godinu studija / smjer!';
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
	END IF;
ELSE
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Traženi zapis ne postoji!';
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
				 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ime i naziv tvrtke moraju biti poznati!';
			END IF;
	ELSE
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Tražena tvrtka ne postoji!';
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
				 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Naziv udruge mora biti poznat!';
			END IF;			
		ELSE
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Naziv udruge već postoji!';
		END IF;
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Tražena udruga ne postoji!';
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
				 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Naziv usluge mora biti poznat!';
			END IF;
		ELSE
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Naziv usluge već postoji!';
		END IF;
	ELSE
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Navedeni id usluge ne postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajVelicinu`( IN id INT UNSIGNED, IN vel VARCHAR(5) )
BEGIN
IF EXISTS (SELECT * FROM VELMAJICE WHERE idVelicine = id) THEN
UPDATE VELMAJICE
SET idVelicine=id,velicina=vel  WHERE idVelicine = id;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Odabrana vrijednost ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajZavod`( IN id INT UNSIGNED, IN naziv VARCHAR(100), IN skraceni VARCHAR(10)  )
BEGIN
IF EXISTS (SELECT * FROM ZAVOD WHERE idZavoda = id) THEN
UPDATE  ZAVOD
SET idZavoda=id, nazivZavoda=naziv, skraceniNaziv=skraceni WHERE idZavoda = id;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Odabrana vrijednost ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiAtribut`(IN idAtributa INT(10))
BEGIN
IF NOT EXISTS (SELECT* 
		FROM ATRIBUT WHERE ATRIBUT.idAtributa = idAtributa) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji traženi atribut';
ELSE
	DELETE FROM ATRIBUT
	WHERE ATRIBUT.idAtributa = idAtributa ;

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
CREATE  PROCEDURE `brisiClanaUdruge`(IN idUdruge INT UNSIGNED, IN idOsobe INT UNSIGNED)
BEGIN
	IF EXISTS (SELECT * FROM JeUUdruzi WHERE JeUUdruzi.idUdruge = idUdruge && JeUUdruzi.idOsobe = idOsobe) THEN
		DELETE FROM JeUUdruzi WHERE JeUUdruzi.idUdruge = idUdruge && JeUUdruzi.idOsobe = idOsobe;
	ELSE
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Tražena osoba ne postoji!';
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
CREATE  PROCEDURE `brisiFunkciju`(IN idObavljaFunkciju  INT(10))
BEGIN
IF EXISTS ( SELECT * FROM ObavljaFunkciju WHERE ObavljaFunkciju.idObavljaFunkciju = idObavljaFunkciju) THEN
	DELETE FROM ObavljaFunkciju
    WHERE ObavljaFunkciju.idObavljaFunkciju = idObavljaFunkciju;
ELSE
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Traženi unos nije pronađen!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiFunkcijuOdbora`(IN idFunkcije INT UNSIGNED)
BEGIN
IF EXISTS (SELECT * FROM FUNKCIJA WHERE FUNKCIJA.idFunkcije = idFunkcije) THEN
	DELETE FROM FUNKCIJA WHERE FUNKCIJA.idFunkcije = idFunkcije;
	ELSE
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: traženi zapis ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiGodStud`( IN id INT UNSIGNED )
BEGIN
IF EXISTS (SELECT * FROM GODSTUD WHERE idGodStud = id) THEN
DELETE FROM GODSTUD WHERE idGodStud = id;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Odabrana vrijednost ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiKategorijuSponzora`(IN idKategorijeSponzora INT(10))
BEGIN
	IF EXISTS (SELECT * FROM KATEGORIJA WHERE KATEGORIJA.idKategorijeSponzora=idKategorijeSponzora) THEN
		DELETE FROM KATEGORIJA
		WHERE KATEGORIJA.idKategorijeSponzora=idKategorijeSponzora;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji kategorija sa zadanim identifikatorom!';
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
CREATE  PROCEDURE `brisiKoristenjeUsluga`(IN idKoristiPruza INT(10))
BEGIN
			IF EXISTS (SELECT * FROM KoristiPruza WHERE KoristiPruza.idKoristiPruza = idKoristiPruza) THEN
					    	DELETE FROM KoristiPruza WHERE KoristiPruza.idKoristiPruza = idKoristiPruza;
				
ELSE
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Usluga nije evidentirana u bazi  podataka!';
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
CREATE  PROCEDURE `brisiNacinPromocije`(IN idPromocije INT UNSIGNED)
BEGIN
IF EXISTS ( SELECT * FROM NACINPROMOCIJE WHERE NACINPROMOCIJE.idPromocije = idPromocije) THEN
	DELETE FROM NACINPROMOCIJE WHERE NACINPROMOCIJE.idPromocije = idPromocije;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Traženi zapis ne postoji!';
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
CREATE  PROCEDURE `brisiPodrucje`(IN idPodrucja INT(10))
BEGIN
IF NOT EXISTS (SELECT * FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idPodrucja) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji odabrano područje!';
ELSE
IF NOT EXISTS (SELECT * FROM PODRUCJE WHERE PODRUCJE.idNadredjenog = idPodrucja) THEN
DELETE FROM PODRUCJE
WHERE PODRUCJE.idPodrucja = idPodrucja ;
ELSE
     SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Najprije morate obrisati sva područja kojima je ovo područje nadređeno!';
   END IF;

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
CREATE  PROCEDURE `brisiRAdnoMjesto`( IN id INT UNSIGNED )
BEGIN
IF EXISTS (SELECT * FROM RADNOMJESTO  WHERE idRadnogMjesta = id) THEN
DELETE FROM RADNOMJESTO WHERE idRadnogMjesta = id;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Odabrana vrijednost ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiSmjer`( IN id INT UNSIGNED )
BEGIN
IF EXISTS (SELECT * FROM SMJER WHERE idSmjera = id) THEN
DELETE FROM SMJER WHERE idSmjera = id;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Odabrana vrijednost ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiSponzora`(IN idSponzora INT(10))
BEGIN
	IF EXISTS (SELECT * FROM SPONZOR WHERE SPONZOR.idSponzora=idSponzora) THEN
		DELETE FROM SPONZOR
		WHERE SPONZOR.idSponzora=idSponzora;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji sponzor sa unesenim identifikatorom!';
	END IF;
END $$
DELIMITER ;


DELIMITER $$
CREATE  PROCEDURE `brisiSponzorstvo`(IN idImaSponzora INT(10))
BEGIN
	IF EXISTS (SELECT * FROM ImaSponzora WHERE ImaSponzora.idImaSponzora=idImaSponzora) THEN
		DELETE FROM ImaSponzora
		WHERE ImaSponzora.idImaSponzora=idImaSponzora;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji unos sa upisanim podacima!';
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
CREATE  PROCEDURE `brisiTvrtku`(IN idtvrtke INT UNSIGNED)
BEGIN
	IF EXISTS (SELECT * FROM TVRTKA WHERE TVRTKA.idTvrtke = idTvrtke) THEN
		DELETE FROM TVRTKA WHERE TVRTKA.idTvrtke = idTvrtke;
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Tražena tvrtka ne postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiUdrugu`(IN idUdruge INT UNSIGNED)
BEGIN
	IF EXISTS (SELECT * FROM UDRUGA WHERE UDRUGA.idUdruge = idUdruge) THEN
		DELETE FROM UDRUGA WHERE UDRUGA.idUdruge = idUdruge;
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Tražena udruga nije pronađena!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiUslugu`(IN idUsluge INT UNSIGNED)
BEGIN
	IF EXISTS ( SELECT * FROM USLUGA WHERE USLUGA.idUsluge = idUsluge) THEN
		DELETE FROM USLUGA WHERE USLUGA.idUsluge = idUsluge;
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: tražena usluga ne postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiVelicinu`( IN id INT UNSIGNED )
BEGIN
IF EXISTS (SELECT * FROM VELMAJICE WHERE idVelicine = id) THEN
DELETE FROM  VELMAJICE WHERE idVelicine = id;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Odabrana vrijednost ne postoji!';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `brisiZavod`( IN id INT UNSIGNED )
BEGIN
IF EXISTS (SELECT * FROM  ZAVOD WHERE idZavoda = id) THEN
DELETE FROM ZAVOD WHERE idZavoda = id;
ELSE 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Odabrana vrijednost ne postoji!';
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
CREATE  PROCEDURE `dodajAtribut`(IN nazivAtributa VARCHAR(100))
BEGIN
INSERT INTO ATRIBUT VALUES (NULL,nazivAtributa);
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
				 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Navedeni id udruge nije pronađen!';
			END IF;
		ELSE 
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Uneseni član već postoji!';
		END IF;
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Unešeni id osobe ne postoji ili osoba nije član OZSN!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajElekPodrucje`(IN idPodrucja INT(10), IN rezultatGrupni SMALLINT(6),IN slikaLink VARCHAR(255), IN slikaBLOB BLOB, IN idElektrijade INT(10), IN idSponzora INT(10))
BEGIN
IF EXISTS (SELECT * FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idPodrucja) THEN
IF EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN
IF ((idSponzora IS NOT NULL) && EXISTS (SELECT * FROM SPONZOR WHERE SPONZOR.idSponzora = idSponzora)) THEN
IF NOT EXISTS (SELECT * FROM ElekPodrucje WHERE ElekPodrucje.idElektrijade = idElektrijade  AND ElekPodrucje.idPodrucja = idPodrucja AND ElekPodrucje.idSponzora=idSponzora) THEN

		INSERT INTO ElekPodrucje VALUES (NULL,idPodrucja,rezultatGrupni,slikaLink,slikaBLOB,idElektrijade,idSponzora);
   
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
CREATE  PROCEDURE `dodajFunkcijuOdbora`(IN nazivFunkcije VARCHAR (100))
BEGIN
IF EXISTS ( SELECT * FROM FUNKCIJA WHERE FUNKCIJA.nazivFunkcije = nazivFunkcije) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Naziv funkcije već postoji!';
	ELSE
		IF (nazivFunkcije IS NULL) THEN
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: naziv funkcije mora biti poznat!';
		ELSE 
			INSERT INTO FUNKCIJA(nazivFunkcije) VALUES (nazivFunkcije);
		END IF;
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajGodStud`( IN stud VARCHAR(50), IN god VARCHAR(50) )
BEGIN
IF EXISTS (SELECT* FROM GODSTUD  WHERE studij=stud AND godina=god) THEN  
               SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Zapis već postoji!';
ELSE
INSERT INTO GODSTUD VALUES(NULL, stud, god );
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
CREATE  PROCEDURE `dodajKategorijuSponzora`(IN tipKategorijeSponzora VARCHAR(100))
BEGIN
IF NOT EXISTS (SELECT * FROM KATEGORIJA WHERE KATEGORIJA.tipKategorijeSponzora=tipKategorijeSponzora) THEN
	INSERT INTO KATEGORIJA values (NULL, tipKategorijeSponzora);
ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Zadani tip kategorije sponzora se vec nalazi u bazi';
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajKontakt`(IN imeKontakt VARCHAR(100), IN prezimeKontakt VARCHAR(100), IN telefon VARCHAR(20), IN radnoMjesto VARCHAR(100), IN idTvrtke INT(10), IN idSponzora INT(10))
BEGIN
IF EXISTS (SELECT * FROM KONTAKTOSOBE WHERE KONTAKTOSOBE.imeKontakt=imeKontakt && KONTAKTOSOBE.prezimeKontakt=prezimeKontakt && KONTAKTOSOBE.telefon=telefon && KONTAKTOSOBE.radnoMjesto=radnoMjesto && KONTAKTOSOBE.idTvrtke=idTvrtke &&KONTAKTOSOBE.idSponzora=idSponzora) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Zapis vec postoji u bazi!';
ELSE
	IF EXISTS (SELECT * FROM TVRTKA WHERE TVRTKA.idTvrtke=idTvrtke) THEN
		IF EXISTS (SELECT * FROM SPONZOR WHERE SPONZOR.idSponzora=idSponzora) THEN
			IF (telefon REGEXP '[0-9]') THEN
				INSERT INTO KONTAKTOSOBE values(NULL,imeKontakt,prezimeKontakt,telefon,radnoMjesto,idTvrtke,idSponzora);
			ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Broj telefona moze sadrzavati samo znamenke!';
			END IF;
		ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji sponzor sa upisanim identifikatorom!';
		END IF;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Ne postoji tvrtka sa unesenim identifikatorom';
	END IF;
END IF;
END $$
DELIMITER ;


DELIMITER $$
CREATE  PROCEDURE `dodajKoristenjeUsluga`(IN Usluga INT UNSIGNED,  IN Tvrtka INT UNSIGNED, IN iznos DECIMAL(13,2),  IN valuta VARCHAR(3), IN elektrijada INT(10), IN nacin VARCHAR(100), IN napomene VARCHAR(300) )
BEGIN
IF EXISTS (SELECT* FROM KoristiPruza WHERE idUsluge=Usluga AND idTvrtke=Tvrtka AND datumPocetka =pocetak) THEN 
 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Zapis već postoji!';
ELSE
IF EXISTS (SELECT * FROM USLUGA
 WHERE idUsluge=Usluga) THEN
IF EXISTS (SELECT * FROM TVRTKA
 	     WHERE idTvrtke = Tvrtka) THEN
			IF EXISTS (SELECT * FROM ELEKTRIJADA 
		     		    WHERE idElektrijade = elektrijada) THEN
					    	IF UPPER(valuta) NOT IN( 'HRK','USD','EUR') THEN
 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = ' Greška: Valuta donacije mora biti HRK, USD ili EUR!';
						ELSE	
IF iznos <= 0 THEN
 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = ' Greška: Iznos donacije je nula ili manji!';
						ELSE
INSERT INTO KoristiPruza 
VALUES(NULL,Usluga, Tvrtka, elektrijada, iznos, valuta, nacin, napomene);
							END IF;
						END IF;
				ELSE
					 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Odabrani datum nije početak Elektrijade!';
				END IF;
		ELSE
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Odabrana tvrtka ne postoji! ';
		END IF;
ELSE
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Usluga nije evidentirana u bazi  podataka!';
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
CREATE  PROCEDURE `dodajNacinPromocije`(IN tipPromocije VARCHAR(100))
BEGIN
IF EXISTS ( SELECT * FROM NACINPROMOCIJE WHERE NACINPROMOCIJE.tipPromocije = tipPromocije) THEN 
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Tip promocije već postoji!';
ELSE IF (tipPromocije IS NULL) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Tip promocije mora biti poznat!';
ELSE
	INSERT INTO NACINPROMOCIJE(tipPromocije) VALUES (tipPromocije);
END IF;
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajOsobu`(IN ime VARCHAR(50), IN prezime VARCHAR(50), IN mail VARCHAR(50),
 IN ferId VARCHAR(50), IN brojMob VARCHAR(20), IN passwordVAR VARCHAR(255), IN JMBAG VARCHAR(10), IN datRod DATE, IN spol CHAR(1),
IN brOsobne VARCHAR(20),IN brPutovnice VARCHAR(30),IN osobnaVrijediDo DATE,IN putovnicaVrijediDo DATE,IN uloga CHAR(1), IN zivotopis BLOB, IN MBG VARCHAR(9), IN OIB VARCHAR(11))
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
INSERT INTO OSOBA VALUES (NULL, ime, prezime, mail, brojMob, ferId, passwordVAR,  JMBAG, spol,datRod,brOsobne,brPutovnice,osobnaVrijediDo,putovnicaVrijediDo,uloga,zivotopi,MBG,OIB);

   

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
CREATE  PROCEDURE `dodajPodrucje`(IN nazivPodrucja VARCHAR(100),IN idNadredjenog INT(10))
BEGIN
IF NOT EXISTS (SELECT * FROM PODRUCJE WHERE PODRUCJE.nazivPodrucja = nazivPodrucja) THEN
IF NOT EXISTS (SELECT * FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idNadredjenog) THEN
	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Nadređeno područje je pogrešno zadano';
ELSE
INSERT INTO PODRUCJE VALUES (NULL,nazivPodrucja,idNadredjenog);

END IF;
ELSE
 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ovo područje je već unešeno!';
END IF;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajPodrucjeSudjelovanja`(IN idPodrucja INT UNSIGNED, IN idSudjelovanja INT UNSIGNED, IN rezultatPojedinacni SMALLINT, IN vrstaPodrucja TINYINT(1))
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
					INSERT INTO PODRUCJESUDJELOVANJA VALUES(NULL,idPodrucja, idSudjelovanja, rezultatPojedinacni, vrstaPodrucja);
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
CREATE  PROCEDURE `dodajRadnoMjesto`(  IN naziv VARCHAR(100) )
BEGIN
IF EXISTS (SELECT* FROM RADNOMJESTO WHERE RADNOMJESTO.naziv=naziv) THEN 
 	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Zapis već postoji!';
ELSE
INSERT INTO RADNOMJESTO VALUES(NULL, naziv );
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajSliku`(IN idPodrucja INT(10),IN idElektrijade DATE,IN slikaLink VARCHAR(255), IN slikaBLOB BLOB)
BEGIN
IF EXISTS (SELECT* FROM PODRUCJE WHERE PODRUCJE.idPodrucja = idPodrucja) THEN
IF EXISTS (SELECT* FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade = idElektrijade) THEN

IF NOT EXISTS (SELECT* 
		FROM ElekPodrucje WHERE ElekPodrucje.idPodrucja = idPodrucja and ElekPodrucje.idElektrijade=idElektrijade) THEN
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne postoji područje kojem želote dodati sliku';
ELSE
	UPDATE ElekPodrucje
    SET ElekPodrucje.slikaLink=slikaLink, ElekPodrucje.slikaBLOB=slikaBLOB
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
CREATE  PROCEDURE `dodajSmjer`(  IN naziv VARCHAR(100) )
BEGIN
IF EXISTS (SELECT* FROM SMJER WHERE nazivSmjera=naziv) THEN 
      	  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Zapis već postoji!';
ELSE
INSERT INTO SMJER VALUES(NULL, naziv );
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajSponzora`(IN imeTvrtke VARCHAR(100), IN adresaTvrtke VARCHAR(100))
BEGIN
IF NOT EXISTS (SELECT * FROM SPONZOR WHERE SPONZOR.imeTvrtke=imeTvrtke AND SPONZOR.adresaTvrtke=adresaTvrtke) THEN
	INSERT INTO SPONZOR values (NULL,imeTvrtke,adresaTvrtke);
ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Zadani zapis vec postoji u bazi!';
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
					IF idGodStud IS NOT NULL || idSmjera IS NOT NULL THEN
						 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Djelatnik ne može imati godinu studija / smjer!';
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
								 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Nije odabran smjer!';
							END IF;
						ELSE
							 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Nije odabrana godina studija!';
						END IF;	
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
CREATE  PROCEDURE `dodajTvrtku`(IN imeTvrtke VARCHAR (100), IN adresaTvrtke VARCHAR (100))
BEGIN
	IF (imeTvrtke IS NOT NULL) THEN
		IF (adresaTvrtke IS NOT NULL) THEN
			INSERT INTO TVRTKA VALUES (NULL,imeTvrtke, adresaTvrtke);
		ELSE 
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Adresa tvrtke mora biti poznata!';
		END IF;
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ime tvrtke mora biti poznato!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajUdrugu`( IN nazivUdruge VARCHAR (50))
BEGIN	
	IF NOT EXISTS (SELECT * FROM UDRUGA WHERE UDRUGA.nazivUdruge = nazivUdruge) THEN
		IF (nazivUdruge IS NOT NULL) THEN
			INSERT INTO UDRUGA VALUES (NULL,nazivUdruge);
		ELSE
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Naziv udruge mora biti poznat!';
		END IF;
	ELSE
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Naziv udruge već postoji!';
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
			 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Naziv usluge mora biti poznat!';
		END IF;
	ELSE
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Naziv usluge već postoji!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajVelicinu`( IN velicina VARCHAR(5) )
BEGIN
 IF EXISTS (SELECT* FROM VELMAJICE WHERE  VELMAJICE.velicina=velicina) THEN 
 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Zapis već postoji!';
ELSE

INSERT INTO VELMAJICE VALUES(NULL, velicina );
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajZavod`(  IN naziv VARCHAR(100), IN skraceni VARCHAR(10) )
BEGIN
IF EXISTS (SELECT* FROM ZAVOD WHERE nazivZavoda=naziv AND skraceniNaziv=skraceni) THEN 
 	 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Zapis već postoji!';
ELSE
 	INSERT INTO ZAVOD VALUES(NULL, naziv, skraceni );
END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiAtribute`()
BEGIN

SELECT * FROM ATRIBUT ORDER BY nazivAtributa;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiBrojeve`(IN `id_kontakta` INT UNSIGNED)
BEGIN

SELECT * FROM BROJEVIMOBITELA WHERE idKontakta = id_kontakta;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiFunkcijeOdbora`()
BEGIN

SELECT * FROM FUNKCIJA ORDER BY nazivFunkcije ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiGodineStudija`()
BEGIN

SELECT idGodStud, CONCAT(studij, " - ", godina) AS studij FROM GODSTUD ORDER BY studij ASC, godina ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiKategorijeSponzora`()
BEGIN

SELECT * FROM KATEGORIJA ORDER BY tipKategorijeSponzora ASC;

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
CREATE  PROCEDURE `dohvatiPopisSvihSponzora`(IN idElektrijade INT(10))
BEGIN
	IF EXISTS (SELECT * FROM Elektrijada WHERE Elektrijada.idElektrijade = idElektrijade) THEN
		SELECT DISTINCT sponzor.idSponzora, sponzor.imeTvrtke, sponzor.adresaTvrtke, nacinPromocije.tipPromocije, kategorija.tipKategorijeSponzora, imaSponzora.iznosDonacije, imaSponzora.valutaDonacije, imaSponzora.napomena
		FROM sponzor
		JOIN imaSponzora ON sponzor.idSponzora = imaSponzora.idSponzora
		LEFT JOIN nacinPromocije ON imaSponzora.idPromocije = nacinPromocije.idPromocije
		LEFT JOIN kategorija ON imaSponzora.idKategorijeSponzora = kategorija.idKategorijeSponzora
		WHERE imaSponzora.idElektrijade = idElektrijade;
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
CREATE  PROCEDURE `dohvatiPopisSvihSudionikaIzPodrucja`(IN idElektrijade INT(10), IN idPodrucja INT)
BEGIN
	IF EXISTS (SELECT * FROM Elektrijada WHERE Elektrijada.idElektrijade = idElektrijade) THEN
		IF EXISTS (SELECT * FROM podrucje WHERE podrucje.idPodrucja = idPodrucja) THEN
			SELECT DISTINCT osoba.idOsobe, osoba.ime, osoba.prezime, podrucjeSudjelovanja.rezultatPojedinacni
			FROM osoba
			JOIN podrucjeSudjelovanja ON osoba.idOsobe = podrucjeSudjelovanja.idOsobe
			WHERE podrucjeSudjelovanja.idElektrijade = idElektrijade AND podrucjeSudjelovanja.idPodrucja = idPodrucja;
		ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Unesen je nepostojeci ID podrucja';
		END IF;
ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Unesen je nepostojeci idELEKTRIJADE';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiPopisSvihTvrtki`(IN idElektrijade INT(10))
BEGIN
	IF EXISTS (SELECT * FROM Elektrijada WHERE Elektrijada.idElektrijade = idElektrijade) THEN
		SELECT DISTINCT tvrtka.idTvrtke, tvrtka.imeTvrtke, tvrtka.adresaTvrtke, koristiPruza.iznosRacuna, koristiPruza.valutaRacuna, koristiPruza.nacinPlacanja, koristiPruza.napomena
		FROM tvrtka
		JOIN koristiPruza ON tvrtka.idTvrtke = koristiPruza.idTvrtke
		WHERE koristiPruza.idElektrijade = idElektrijade;
ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Unesen je nepostojeci idELEKTRIJADE';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiRadnaMjesta`()
BEGIN

SELECT * FROM RADNOMJESTO ORDER BY naziv ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiSmjerove`()
BEGIN

SELECT * FROM SMJER ORDER BY nazivSmjera ASC;

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
CREATE  PROCEDURE `dohvatiVelicine`()
BEGIN

SELECT * FROM VELMAJICE ORDER BY velicina ASC;

END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dohvatiZavode`()
BEGIN

SELECT idZavoda, skraceniNaziv FROM ZAVOD ORDER BY skraceniNaziv ASC;

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
CREATE  PROCEDURE `dodajMedij`(IN nazivMedija VARCHAR(10), IN idKontakta INT(10))
BEGIN
	IF EXISTS (SELECT * FROM kontaktosobe WHERE kontaktosobe.idKontakta=idKontakta ) THEN 
      If (SELECT * FROM BUS WHERE BUS.idBusa=idBusa) > brojSjedala THEN		
			INSERT INTO MEDIJ VALUES (NULL,nazivMedija,idKontakta);		
      ELSE 
	       SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: brojSjedala je veči od ukupnog broja sjedećih mjesta u busu!';
	  END IF;
	ELSE 
		 SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Unesen nepostojeći kontakt!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `dodajObjavu`(IN datumObjave DATE, IN link VARCHAR(100), IN autorIme VARCHAR(50), IN autorPrezime VARCHAR(50), IN idMedijja INT(10),IN dokument BLOB)
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


DELIMITER $$
CREATE  PROCEDURE `brisiMedij`(IN idMedija INT(10))
BEGIN
	IF EXISTS (SELECT * FROM MEDIJ.idMedija=idMedija) THEN
		DELETE FROM MEDIJ
		WHERE MEDIJ.idMedijaa=idMedija;
	ELSE  SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Ne posotoji MEDIJ sa upisanim identifikatorom!';
	END IF;
END $$
DELIMITER ;

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

DELIMITER $$
CREATE  PROCEDURE `azurirajMedij`(IN idMedija INT(10), IN nazivMedija VARCHAR(10), IN idKontakta INT(10))
BEGIN
	IF EXISTS (SELECT * FROM MEDIJ WHERE BUS.nazivMedija=inazivMedija) THEN
      IF  EXISTS(SELECT * FROM KONTAKTOSOBE WHERE KONTAKTOSOBE.idKontakta=idKontakta)  THEN
		
				UPDATE MEDIJ
				SET MEDIJ.idKontakta=idKontakta,MEDIJ.nazivMedija=nazivMedija
				WHERE MEDIJ.idMedija=idMedija;
       ELSE 
           SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Kontakt ne postoji u bazi!';
	   END IF;
			
	ELSE 
       SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Greška: Naziv medija već postoji u bazi!';
	END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE  PROCEDURE `azurirajObjavu`(IN idObjave INT(10),IN datumObjave DATE, IN link VARCHAR(100), IN autorIme VARCHAR(50), IN autorPrezime VARCHAR(50), IN idMedija INT(10),IN dokument BLOB)
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
