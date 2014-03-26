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
            'spol', 'datRod', 'brOsobne', 'brPutovnice', 'osobnaVrijediDo', 'putovnicaVrijediDo', 'uloga', 'zivotopis', 'mbrOsigOsobe', 'OIB');      // FALI OIB i njega dodati kad se promijeni baza
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
            $spol, $datRod, $brOsobne, $brPutovnice, $osobnaVrijediDo, $putovnicaVrijediDo, $uloga, $zivotopis, $mbrOsigOsobe, $OIB) {
        
        $this->idOsobe = null;
        $atributi = $this->getColumns();
        foreach($atributi as $a) {
            $this->{$a} = ${$a};
        }
        $this->save();
    }
    
}
