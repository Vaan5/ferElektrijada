DELIMITER $$
CREATE TRIGGER `Osoba_INSERT`
BEFORE INSERT ON `osoba` 
FOR EACH ROW BEGIN	
	IF NEW.datRod != NULL AND NEW.datRod >= CURDATE() THEN
		SIGNAL SQLSTATE '02000'
		SET MESSAGE_TEXT = 'Datum rodenja mora biti manji od danasnjeg datuma';
	END IF;	
	IF NEW.uloga != "O" AND NEW.uloga != "S" AND NEW.uloga != "A" THEN
		SIGNAL SQLSTATE '02000'
		SET MESSAGE_TEXT = 'Uloga moze biti O ili S ili A';
	END IF;
    IF NEW.idNadredjena IS NOT NULL THEN
        IF NOT EXISTS(SELECT * FROM osoba where osoba.idOsobe = NEW.idNadredjena) THEN
			SIGNAL SQLSTATE '02000'
			SET MESSAGE_TEXT = 'Pogrešan unos nadređene osobe';
		END IF;
	END IF;
 END$$
DELIMITER ;
