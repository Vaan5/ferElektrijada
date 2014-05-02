<?php

namespace model;
use app\model\AbstractDBModel;

class DBOsoba extends AbstractDBModel {
    
    private $isLoggedIn = false;
    
    public function getTable() {
        return 'osoba';
    }
    
    public function getPrimaryKeyColumn() {
        return 'idOsobe';
    }
    
    public function getColumns() {
        return array('ime', 'prezime', 'mail', 'brojMob', 'ferId', 'password', 'JMBAG',
            'spol', 'datRod', 'brOsobne', 'brPutovnice', 'osobnaVrijediDo', 'putovnicaVrijediDo',
            'uloga', 'zivotopis', 'MBG', 'OIB', 'idNadredjena', 'aktivanDokument');
    }
    
    public function kriptPass($pass) {
        return sha1($pass);
    }
	
	public function getTeamLeaders($idElektrijade) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT * FROM podrucje LEFT JOIN imaatribut ON imaatribut.idPodrucja = podrucje.idPodrucja
														LEFT JOIN sudjelovanje ON imaatribut.idSudjelovanja = sudjelovanje.idSudjelovanja
														LEFT JOIN osoba ON osoba.idOsobe = sudjelovanje.idOsobe
														LEFT JOIN atribut on imaatribut.idAtributa = atribut.idAtributa
										WHERE UPPER(atribut.nazivAtributa) = 'VODITELJ' AND sudjelovanje.idElektrijade = :idElektrijade");
			$q->bindValue(":idElektrijade", $idElektrijade);
			$q->execute();
			return $q->fetchAll();
		} catch (\PDOException $e) {
			throw $e;
		}
	}
    
	private function getUloga($idOsobe,$uloga){ //dobivanje uloge korisnika
		try{
			$elektrijada = new DBElektrijada();
			$pdo = $this->getPdo();
			$id = $elektrijada->getCurrentElektrijadaId();
			if($id === false){
				return null;
			}
			$q = $pdo->prepare("CALL dohvatiOdredeniAtribut(:id,:elektrijada)");
			$q->bindValue(":id", $idOsobe);
			$q->bindValue(":elektrijada", $id);
			$q->execute();
			$rez=$q->fetchAll(); //dohvatili smo uloge
		} catch (\PDOException $e) {
           return false;
		}
		foreach($rez as $x){ //ako je u bar jednom atributu voditelj
			if(isset($x->nazivAtributa) && strtoupper($x->nazivAtributa)=="VODITELJ"&& $x!=false){
				return $uloga.'V'; //ako je korisnik i voditelj dobiva nastavak "V"
			}
		}
		return $uloga;
	}
	
	private function getPodrucja($idOsobe){//vraca sva podrucja u kojima sudjeluje osoba
		$rez=null;
		try{
			$elektrijada = new DBElektrijada();
			$pdo = $this->getPdo();
			$id = $elektrijada->getCurrentElektrijadaId();
			if($id === false){
				return null;
			}
			$q = $pdo->prepare("CALL dohvatiOsobnaPodrucja(:id,:Osoba)");
			$q->bindValue(":id", $id);
			$q->bindValue(":Osoba", $idOsobe);
			$q->execute();
			$rez  = $q->fetchAll();
		}catch (\PDOException $e) {
           return false;
		}
		$vel=count($rez);
		if($vel>0){//ako postoji ba 1 podrucje vrati podrucje, ako ne vrati null
			return $rez;
		}
		else {
			return null;
		}
	}
	
    /**
     * 
     * @param string $user userName
     * @param string $password kriptirani password
     * @return boolean
     */
    public function doAuthRaw($user, $password) {
        $rez = $this->select()->where(array(
            "ferId" => $user,
            "password" => $password
        ))->fetch();
        
        if (false === $rez) {
            return false;
        }

        $this->load($rez->getPrimaryKey());
        
        return true;
    }
    
    /**
     * 
     * @param string $userName
     * @param string $password nekriptirani password
     * @return boolean
     */
    public function doAuth($userName, $password) {
        $this->isLoggedIn = $this->doAuthRaw($userName, $this->kriptPass($password));
        
        // u sjednici cuvam idKorisnika i njegovu vrstu
        if ($this->isLoggedIn) {
            $_SESSION["auth"] = $this->getPrimaryKey();
			$uloga=$this->uloga;
			$_SESSION["vrsta"] =$this->getUloga($_SESSION["auth"], $uloga);
            $_SESSION["user"] = $this->ime == NULL ? null:$this->ime;
			$_SESSION["podrucja"] = $this->getPodrucja ($_SESSION["auth"]);//vraca id podrucja
			if($_SESSION["vrsta"]===false or $_SESSION["podrucja"]===false){//ako ne uspije dohvatit informacije vrati false
				return false;
			}
		}
        
        return $this->isLoggedIn;
    }
    
    /**
     * 
     * @return boolean
     */
    public static function isLoggedIn() {
        $pom = isset($_SESSION['auth']) ? $_SESSION['auth'] : null;
        
        if (null === $pom) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Returns the contents of $_SESSION['vrsta'] if set.
     * false otherwise
     * @return string|boolean
     */
    public static function getUserRole() {
        return isset($_SESSION['vrsta']) ? $_SESSION['vrsta'] : false;
    }
    
	public function userExists($ferId) {
		try {
			$pov = $this->select()->where(array(
				"ferId" => $ferId
			))->fetchAll();
			return count($pov) == 0 ? false : $pov[0]->getPrimaryKey();
		} catch (app\model\NotFoundException $e) {
			return false;
		} catch (\PDOException $e) {
			return false;
		}
	}

    public function addNewPerson($ime, $prezime, $mail, $brojMob, $ferId, $password, $JMBAG,
            $spol, $datRod, $brOsobne, $brPutovnice, $osobnaVrijediDo, $putovnicaVrijediDo, 
            $uloga, $zivotopis, $MBG, $OIB, $idNadredjena, $aktivanDokument = 0) {
        
        $this->idOsobe = null;
        $atributi = $this->getColumns();
        foreach($atributi as $a) {
            $this->{$a} = ${$a};
        }
        $this->password = $this->kriptPass($this->password);
        $this->save();
    }
    
	public function getAllPersons($except = 'A') {
        $pov = $this->select()->fetchAll();
        if(count($pov)) {
            foreach($pov as $k => $v) {
				if ($except === 'A') {
					if ($v->uloga === 'A')
						unset($pov[$k]);
				} else {
					if ($v->uloga === 'A' || $v->uloga ==='O')
						unset($pov[$k]);
				} 
            }
        }
        return $pov;
    }
	
	public function find($ime, $prezime, $ferId, $OIB, $JMBAG, $notInvolve = 'A') {
        $pdo = $this->getPdo();
        $query = '';
        $number = 0;
        if($ime !== '' && $ime !== null && $ime !== false) {
            $query .= ' UPPER(ime) LIKE :ime';
            $number++;
        }
        if($prezime !== '' && $prezime !== null && $prezime !== false) {
            if ($number > 0) $query .= ' OR';
            $query .= ' UPPER(prezime) LIKE :prezime';
            $number++;
        }
        if($ferId !== '' && $ferId !== null && $ferId !== false) {
            if ($number > 0) $query .= ' OR';
            $query .= ' UPPER(ferId) LIKE :ferId';
            $number++;
        }
        if($OIB !== '' && $OIB !== null && $OIB !== false) {
            if ($number > 0) $query .= ' OR';
            $query .= ' OIB LIKE :OIB';
            $number++;
        }
        if($JMBAG !== '' && $JMBAG !== null && $JMBAG !== false) {
            if ($number > 0) $query .= ' OR';
            $query .= ' JMBAG LIKE :JMBAG';
            $number++;
        }
		if ($notInvolve === 'A') 
			$query = 'SELECT * FROM osoba WHERE (' . $query . ') AND uloga<>\'A\'';
		else
			$query = 'SELECT * FROM osoba WHERE (' . $query . ') AND uloga<>\'A\' AND uloga<>\'O\'';
        $upit = $pdo->prepare($query);
        if($ime !== '' && $ime !== null && $ime !== false)
            $upit->bindValue (':ime', "%" . strtoupper ($ime) . "%");
        if($prezime !== '' && $prezime !== null && $prezime !== false)
            $upit->bindValue (':prezime', "%" . strtoupper ($prezime) . "%");
        if($ferId !== '' && $ferId !== null && $ferId !== false)
            $upit->bindValue (':ferId', "%" . strtoupper ($ferId) . "%");
        if($OIB !== '' && $OIB !== null && $OIB !== false)
            $upit->bindValue (':OIB', "%" . $OIB . "%");
        if($JMBAG !== '' && $JMBAG !== null && $JMBAG !== false)
            $upit->bindValue (':JMBAG', "%" . $JMBAG . "%");
        
        try {
            $upit->execute();
        } catch (\PDOException $e) {
            return false;
        }
        $pov = $upit->fetchAll($pdo::FETCH_CLASS, get_class($this));
        if(count($pov)) {
            return $pov;
        }
        return false;
    }
	
	
	
	
	
    public function getAllActiveOzsn() {
        $pov = $this->select()->where(array(
            "uloga" => 'O'
        ))->fetchAll();
        
        if (count($pov)) {
            // i take only active ozsn members
            $elektrijada = new DBElektrijada();
            $id = $elektrijada->getCurrentElektrijadaId();
            $obavljaFunkciju = new DBObavljaFunkciju();
            foreach($pov as $k => $v) {
                if($obavljaFunkciju->ozsnExists($v->getPrimaryKey(), $id))
                        continue;
                unset($pov[$k]);
            }
            if(count($pov))
                return $pov;
            return false;
        }
        return false;
    }
    
    /**
     * Primjer uporabe parametar binding (izbjegavanje sql injection)
     * @param type $ime
     * @param type $prezime
     * @param type $ferId
     * @return boolean
     */
    public function findActiveOzsnMembers($ime, $prezime, $ferId) {
        $pdo = $this->getPdo();
        $query = '';
        $number = 0;
        if($ime !== '' && $ime !== null && $ime !== false) {
            $query .= ' ime = :ime';
            $number++;
        }
        if($prezime !== '' && $prezime !== null && $prezime !== false) {
            if ($number > 0) $query .= ' OR';
            $query .= ' prezime = :prezime';
            $number++;
        }
        if($ferId !== '' && $ferId !== null && $ferId !== false) {
            if ($number > 0) $query .= ' OR';
            $query .= ' ferId = :ferId';
        }
        $query = 'SELECT * FROM osoba WHERE (' . $query . ') AND uloga=\'O\'';
        $upit = $pdo->prepare($query);
        if($ime !== '' && $ime !== null && $ime !== false)
            $upit->bindValue (':ime', $ime);
        if($prezime !== '' && $prezime !== null && $prezime !== false)
            $upit->bindValue (':prezime', $prezime);
        if($ferId !== '' && $ferId !== null && $ferId !== false)
            $upit->bindValue (':ferId', $ferId);
        try {
            $upit->execute();
        } catch (\PDOException $e) {
            return false;
        }
        $pov = $upit->fetchAll($pdo::FETCH_CLASS, get_class($this));
        if(count($pov)) {
            // i take only active ozsn members
            $elektrijada = new DBElektrijada();
            $id = $elektrijada->getCurrentElektrijadaId();
            $obavljaFunkciju = new DBObavljaFunkciju();
            foreach($pov as $k => $v) {
                if($obavljaFunkciju->ozsnExists($v->getPrimaryKey(), $id))
                        continue;
                unset($pov[$k]);
            }
            if(count($pov))
                return $pov;
            return false;
        }
        return false;
    }
    
    public function isOzsnMember() {
        return $this->uloga == 'O' ? true : false;
    }
    
    public function isActiveOzsn($id) {
	$obavlja = new DBObavljaFunkciju();
	$el = new DBElektrijada();
	$idElektrijade = $el->getCurrentElektrijadaId();
	$pov = $obavlja->select()->where(array(
	    "idOsobe" => $id,
	    "idElektrijade" => $idElektrijade
	))->fetchAll();
	
	return count($pov) === 0 ? false : true;
    }
    
    public function getOldOzsn() {
        $elektrijada = new DBElektrijada();
        $idElektrijade = $elektrijada->getLastYearElektrijadaId();
        if ($idElektrijade === false)
            return array();
        
        $obavljaFunkciju = new DBObavljaFunkciju();
        $pov = $obavljaFunkciju->select()->where(array(
            "idElektrijade" => $idElektrijade
        ))->fetchAll();
        if(!count($pov))
            return array();
        
        $ret = array();
        foreach ($pov as $v) {
            $o = new DBOsoba();
            try {
                $o->load($v->idOsobe);
                $ret[] = $o;
            } catch (\app\model\NotFoundException $e) {
                return array();
            } catch (\PDOException $e) {
                return array();
            }
        }
        return $ret;        
    }
    
    public function modifyRow($primaryKey, $ime, $prezime, $mail, $brojMob, $ferId, $password, $JMBAG,
            $spol, $datRod, $brOsobne, $brPutovnice, $osobnaVrijediDo, $putovnicaVrijediDo, $uloga, $zivotopis, $MBG, $OIB, $aktivanDokument = 0) {
        $atributi = $this->getColumns();
        $this->load($primaryKey);
        $stariPass = null;
        if($password === null || $password === false) {
            $stariPass = $this->password;
        }
        foreach($atributi as $a) {
            $this->{$a} = ${$a};
        }
        if($stariPass !== null)
            $this->password = $stariPass;
        else
            $this->password = $this->kriptPass ($this->password);
        $this->save();
    }
    
    public function modifyPerson($primaryKey, $ime, $prezime, $mail, $brojMob, $ferId, $password, $JMBAG,
            $spol, $datRod, $brOsobne, $brPutovnice, $osobnaVrijediDo, $putovnicaVrijediDo, $zivotopis,
			$MBG, $OIB, $aktivanDokument = 0, $idNadredjena = null) {
        $atributi = $this->getColumns();
        $this->load($primaryKey);
        $stariPass = null;
        if($password === null || $password === false) {
            $stariPass = $this->password;
        }
		$stariNadredjeni = null;
		if($idNadredjena === null) {
			$stariNadredjeni = $this->idNadredjena;
		}
        foreach($atributi as $a) {
            if ($a !== 'uloga' && !($a === 'zivotopis' && $zivotopis === NULL))     // don't change the role
                $this->{$a} = ${$a};
        }
        if($stariPass !== null)
            $this->password = $stariPass;
        else
            $this->password = $this->kriptPass ($this->password);
		if ($stariNadredjeni !== null)
			$this->idNadredjena = $stariNadredjeni;
        $this->save();
    }
    
    public function deleteOsoba($primaryKey) {
        try {
            $this->load($primaryKey);
            if ($this->uloga === 'A') {
                return false;
            }
            $this->delete();
            return true;
        } catch (\app\model\NotFoundException $e) {
            return false;
        } catch (\PDOException $e) {
            return false;
        }
    }
    
    /**
     * 
     * @param mixed $password
     * @return mixed    false if the admin doesn't exist, else object with his data
     */
    public function checkAdmin($password) {
        try {
            $pdo = $this->getPdo();
            $password = $this->kriptPass($password);
            $query = $pdo->prepare("SELECT * FROM osoba WHERE password = :password AND uloga='A'");
            $query->bindValue(":password", $password);
            $query->execute();
            $pov = $query->fetchAll(\PDO::FETCH_CLASS, get_class($this));
            return count($pov) == 0 ? false : $pov[0];
        } catch (\PDOException $e) {
            return false;
        }  
    }
    
    public function promoteToOzsn($idOsobe) {
        $this->load($idOsobe);
        if ($this->uloga !== 'A' && $this->uloga !== 'O')
            $this->uloga = 'O';
        $this->save();
    }
    
    public function reportCompetitorList($array, $idElektrijade, $idPodrucja) {
	try {
	    $statement = 'SELECT ';
	    // only if there aren't atributes with same name, otherwise do it one by one or
	    // put in the checkbox form the name of the table, like atribut_nazivAtributa,
	    // and then with php replace _ with .
	    foreach ($array as $k => $v) {
		if ($k !== 'idElektrijade' && $k !== 'idPodrucja' && $k !== 'type') {
		    $statement .= $k . ', ';
		}
	    }
	    // remove last ', ';
	    $statement = rtrim($statement, ", ");
	    
	    // now generate rest of query
	    $statement .= " FROM osoba LEFT JOIN sudjelovanje ON osoba.idOsobe = sudjelovanje.idOsobe
					LEFT JOIN velmajice ON velmajice.idVelicine = sudjelovanje.idVelicine
					LEFT JOIN godstud ON godstud.idGodStud = sudjelovanje.idGodStud
					LEFT JOIN radnomjesto ON radnomjesto.idRadnogMjesta = sudjelovanje.idRadnogMjesta
					LEFT JOIN zavod ON zavod.idZavoda = sudjelovanje.idZavoda
					LEFT JOIN smjer ON smjer.idSmjera = sudjelovanje.idSmjera
					LEFT JOIN imaatribut ON imaatribut.idSudjelovanja = sudjelovanje.idSudjelovanja
					LEFT JOIN atribut ON imaatribut.idAtributa = atribut.idAtributa
					LEFT JOIN podrucjesudjelovanja ON podrucjesudjelovanja.idSudjelovanja = sudjelovanje.idSudjelovanja
					LEFT JOIN putovanje ON putovanje.idPutovanja = sudjelovanje.idPutovanja
					LEFT JOIN bus ON bus.idBusa = putovanje.idBusa
				WHERE sudjelovanje.idElektrijade = :idE AND (podrucjesudjelovanja.idPodrucja = :idP OR imaatribut.idPodrucja = :idP)";
	    
	    $pdo = $this->getPdo();
	    $q = $pdo->prepare($statement);
	    $q->bindValue(":idE", $idElektrijade);
	    $q->bindValue(":idP", $idPodrucja);	    
	    $q->execute();
	    return $q->fetchAll();
	} catch (\PDOException $e) {
	    throw $e;
	}
    }
    
    /**************************************************************************
     *			   CONTESTANT FUNCTIONS
     **************************************************************************/

	// FINISHED
    public function checkPassword($idOsobe, $password) {
		try {
            $pdo = $this->getPdo();
            $password = $this->kriptPass($password);
            $query = $pdo->prepare("SELECT * FROM osoba WHERE idOsobe = :idOsobe AND password = :password");
            $query->bindValue(":password", $password);
			$query->bindValue(":idOsobe", $idOsobe);
            $query->execute();
            $pov = $query->fetchAll(\PDO::FETCH_CLASS, get_class($this));
            return count($pov) == 0 ? false : $pov[0];
        } catch (\PDOException $e) {
            return false;
        }
    }
	
	// FINISHED
	public function addCV($idOsobe, $zivotopis) {
		try {
			$this->load($idOsobe);
			$this->zivotopis = $zivotopis;
			$this->save();
		} catch (app\model\NotFoundException $e) {
			$e = new \PDOException();
			$e->errorInfo[0] = '02000';
			$e->errorInfo[1] = 1604;
			$e->errorInfo[2] = "Zapis ne postoji!";
			throw $e;
		} catch (\PDOException $e) {
			throw $e;
		}
	}
}