DELIMITER $$
CREATE  PROCEDURE `dohvatiOdredeniAtribut`(IN idOsoba INT(10), IN idElektrijade INT(10))
BEGIN
IF EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe=idOsoba) THEN
SELECT atribut.nazivAtributa FROM sudjelovanje 
LEFT JOIN imaatribut ON sudjelovanje.idSudjelovanja = imaatribut.idSudjelovanja AND sudjelovanje.idElektrijade=idElektrijade
JOIN atribut ON imaatribut.idAtributa = atribut.idAtributa
WHERE sudjelovanje.idOsobe = idOsoba;
ELSE
   SIGNAL SQLSTATE '23000'SET MESSAGE_TEXT = 'Greška: Unesena je nepostojeća osoba.';
END IF;
END $$
DELIMITER ;

DELIMITER $$

CREATE  PROCEDURE `dohvatiOsobnaPodrucja`(IN idElektrijada INT(10), IN idOsobe INT(10))
BEGIN
IF EXISTS (SELECT * FROM OSOBA WHERE OSOBA.idOsobe=idOsobe) THEN
IF EXISTS (SELECT * FROM ELEKTRIJADA WHERE ELEKTRIJADA.idElektrijade=idElektrijada) THEN

SELECT podrucje.idPodrucja FROM sudjelovanje 
LEFT JOIN imaatribut ON sudjelovanje.idSudjelovanja = imaatribut.idSudjelovanja AND sudjelovanje.idElektrijade=idElektrijada
 JOIN podrucje ON podrucje.idPodrucja = imaatribut.idPodrucja
JOIN atribut ON imaatribut.idAtributa = atribut.idAtributa AND UPPER(nazivAtributa)='VODITELJ'
WHERE sudjelovanje.idOsobe = idOsobe;

ELSE
   SIGNAL SQLSTATE '23000'SET MESSAGE_TEXT = 'Greška: Unesena je nepostojeća elektrijada.';
END IF;
ELSE
   SIGNAL SQLSTATE '23000'SET MESSAGE_TEXT = 'Greška: Unesena je nepostojeća osoba.';
END IF;
END $$
DELIMITER ;