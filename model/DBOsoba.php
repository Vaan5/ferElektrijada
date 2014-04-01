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
            'spol', 'datRod', 'brOsobne', 'brPutovnice', 'osobnaVrijediDo', 'putovnicaVrijediDo', 'uloga', 'zivotopis', 'MBG', 'OIB');
    }
    
    public function kriptPass($pass) {
        return sha1($pass);
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
            $_SESSION["vrsta"] = $this->uloga;
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
        $this->save();
    }
    
    public function getAllOzsn() {
        $pov = $this->select()->where(array(
            "uloga" => 'O'
        ))->fetchAll();
        
        if (count($pov))
            return $pov;
        return false;
    }
    
    public function findOzsnMembers($ime, $prezime, $ferId) {
        $query = array("uloga" => 'O');
        if($ime !== '' && $ime !== null)
            $query["ime"] = $ime;
        if($prezime !== '' && $prezime !== null)
            $query["prezime"] = $prezime;
        if($ferId !== '' && $ferId !== null)
            $query["ferId"] = $ferId;
        $pov = $this->select()->where($query)->fetchAll();
        if(count($pov))
            return $pov;
        return true;
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
}
