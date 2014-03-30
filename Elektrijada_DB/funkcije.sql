DELIMITER $$
CREATE FUNCTION dohvatiBLOBSlike(idElekPodrucje INT UNSIGNED) RETURNS BLOB
DETERMINISTIC
BEGIN
	IF EXISTS (SELECT * FROM ElekPodrucje WHERE ElekPodrucje.idElekPodrucje = idElekPodrucje) THEN
		RETURN (SELECT slikaBLOB FROM ElekPodrucje WHERE ElekPodrucje.idElekPodrucje = idElekPodrucje);
	ELSE
		RETURN NULL;
	END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION dohvatiLinkSlike(idElekPodrucje INT UNSIGNED) RETURNS VARCHAR(255)
DETERMINISTIC
BEGIN
	IF EXISTS (SELECT * FROM ElekPodrucje WHERE ElekPodrucje.idElektrijade = idElektrijade && ElekPodrucje.idPodrucja = idPodrucja) THEN
		RETURN (SELECT slikaLink FROM ElekPodrucje WHERE ElekPodrucje.idElekPodrucje = idElekPodrucje);
	ELSE
		RETURN NULL;
	END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION izracunajUkupneDonacije(idElektrijade INT UNSIGNED) RETURNS DECIMAL(15,2)
DETERMINISTIC
BEGIN
	IF EXISTS (SELECT * FROM ImaSponzora WHERE ImaSponzora.idElektrijade = idElektrijade) THEN
		RETURN (SELECT SUM(iznosDonacije) FROM ImaSponzora WHERE ImaSponzora.idElektrijade = idElektrijade);
	ELSE
		RETURN 0;
	END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION prijavaKorisnika(ferId VARCHAR(50), lozinka VARCHAR(255)) RETURNS INT
DETERMINISTIC
BEGIN
	IF EXISTS (SELECT * FROM OSOBA WHERE OSOBA.ferId = ferId && OSOBA.password = lozinka) THEN
		RETURN (SELECT idOsobe FROM OSOBA WHERE OSOBA.ferId = ferId && OSOBA.password = lozinka);
	ELSE
		RETURN NULL;
	END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION dohvatiGrupniRezultat(idElekPodrucje INT UNSIGNED) RETURNS SMALLINT
DETERMINISTIC
BEGIN
	IF EXISTS (SELECT * FROM ElekPodrucje WHERE ElekPodrucje.idElekPodrucje = idElekPodrucje) THEN
		RETURN (SELECT rezultatGrupni FROM ElekPodrucje WHERE ElekPodrucje.idElekPodrucje = idElekPodrucje);
	ELSE
		RETURN NULL;
	END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE FUNCTION dohvatiPojedinacniRezultat(idPodrucjeSudjelovanja INT UNSIGNED) RETURNS SMALLINT
DETERMINISTIC
BEGIN
	IF EXISTS (SELECT * FROM PodrucjeSudjelovanja WHERE PodrucjeSudjelovanja.idPodrucjeSudjelovanja = idPodrucjeSudjelovanja) THEN
		RETURN (SELECT rezultatPojedinacni FROM PodrucjeSudjelovanja WHERE PodrucjeSudjelovanja.idPodrucjeSudjelovanja = idPodrucjeSudjelovanja);
	ELSE
		RETURN NULL;
	END IF;
END$$
DELIMITER ;
