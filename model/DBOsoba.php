<?php

namespace model;
use app\model\AbstractDBModel;

class DBOsoba extends AbstractDBModel {
    
    /**
     *
     * @var boolean 
     */
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
            'uloga', 'zivotopis', 'MBG', 'OIB', 'idNadredjena');
    }
    
    public function kriptPass($pass) {
        return sha1($pass);
    }
    
	private function getUloga($idOsobe,$uloga){
	$upit = $this->select()->from('sudjelovanje')
	->leftJoin('imaatribut ON sudjelovanje.idSudjelovanje = imaatribut.idSudjelovanja')
	->innerJoin('atribut ON imaatribut.idAtributa = atribut.idAtributa')
	->where(array("idOsobe"=>$idOsobe))
	->fetch();
	
	if(isset($upit) && strtoupper($upit)=="VODITELJ"){
		return $uloga.'V';
	}
	else{
		return $uloga;
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
            $_SESSION["vrsta"] =$this->uloga; //->getUloga($this->getPrimaryKEY(),$this->uloga;
            $_SESSION["user"] = $this->ime == NULL ? null:$this->ime;
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
    
    public function addNewPerson($ime, $prezime, $mail, $brojMob, $ferId, $password, $JMBAG,
            $spol, $datRod, $brOsobne, $brPutovnice, $osobnaVrijediDo, $putovnicaVrijediDo, $uloga, $zivotopis, $MBG, $OIB) {
        
        $this->idOsobe = null;
        $atributi = $this->getColumns();
        foreach($atributi as $a) {
            $this->{$a} = ${$a};
        }
        $this->password = $this->kriptPass($this->password);
        $this->save();
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
        $upit->execute();
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
            }
        }
        return $ret;        
    }
    
    public function modifyRow($primaryKey, $ime, $prezime, $mail, $brojMob, $ferId, $password, $JMBAG,
            $spol, $datRod, $brOsobne, $brPutovnice, $osobnaVrijediDo, $putovnicaVrijediDo, $uloga, $zivotopis, $MBG, $OIB) {
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
    
    public function deleteOsoba($primaryKey) {
        try {
            $this->load($primaryKey);
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
}
