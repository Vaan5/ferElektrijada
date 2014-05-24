<?php

namespace model;
use app\model\AbstractDBModel;

class DBElektrijada extends AbstractDBModel {
    
    public function getTable() {
        return 'elektrijada';
    }
    
    public function getPrimaryKeyColumn() {
        return 'idElektrijade';
    }
    
    public function getColumns() {
        return array('mjestoOdrzavanja', 'datumPocetka', 'datumKraja', 'ukupniRezultat', 'drzava', 'rokZaZnanje', 'rokZaSport', 'ukupanBrojSudionika');
    }
    
    public function getAll() {
	return $this->select()->fetchAll();
    }
    
    /**
     * Adds new row to the table
     * Check date constraints
     * 
     * @param mixed $mjestoOdrzavanja
     * @param mixed $datumPocetka
     * @param mixed $datumKraja
     * @param mixed $ukupniRezultat
     * @param mixed $drzava
     */
    public function addNewElektrijada($mjestoOdrzavanja, $datumPocetka, $datumKraja, $ukupniRezultat, $drzava, $rokZaZnanje, $rokZaSport, $ukupanBrojSudionika) {
        if ($this->existsElektrijadaWithYear($datumPocetka))
            return false;
        $this->idElektrijade = null;
        $atributi = $this->getColumns();
        foreach($atributi as $a) {
            $this->{$a} = ${$a};
        }
        $this->save();
    }
    
    /**
     * Returns all rows from table
     * 
     * @return array    array of objects representing rows
     */
    public function getElektrijada() {
        return $this->select()->fetchAll();
    }
    
    public function existsElektrijadaWithYear($date) {
        $datum = date('Y', strtotime($date));
        
        $pdo = $this->getPdo();
        $query = $pdo->prepare("SELECT * FROM elektrijada WHERE YEAR(datumPocetka) = :datum");
        $query->bindValue(':datum', $datum);
        $query->execute();
        $pov = $query->fetchAll(\PDO::FETCH_CLASS, get_class($this));
        
        if(count($pov)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function existsElektrijadaWithYearDifferentFrom($date, $idElektrijade) {
        $datum = date('Y', strtotime($date));
        
        $pdo = $this->getPdo();
        $query = $pdo->prepare("SELECT * FROM elektrijada WHERE YEAR(datumPocetka) = :datum");
        $query->bindValue(':datum', $datum);
        $query->execute();
        $pov = $query->fetchAll(\PDO::FETCH_CLASS, get_class($this));
        
        if(count($pov)) {
            foreach($pov as $p) {
                if($p->idElektrijade != $idElektrijade)
                    return true;
            }
            return false;
        }
        return false;
    }
    
    /**
     * 
     * @param mixed $primaryKey
     * @return boolean false if row with given key doesn't exist, tru otherwise
     */
    public function elektrijadaExists($primaryKey) {
        try {
            $pdo = $this->getPdo();
            $query = $pdo->prepare("SELECT * FROM elektrijada WHERE idElektrijade = :id");
            $query->bindValue(':id', $primaryKey);
            $query->execute();
            $pov = $query->fetchAll(\PDO::FETCH_CLASS, get_class($this));
            return count($pov) == 0 ? false : true;
        } catch (\PDOException $e){
            return false;
        }
    }
    
    /**
     * Modifies an existing row and replaces old with given data
     * 
     * @param mixed $primaryKey
     * @param mixed $mjestoOdrzavanja
     * @param mixed $datumPocetka
     * @param mixed $datumKraja
     * @param mixed $ukupniRezultat
     * @param mixed $drzava
     */
    public function modifyRow($primaryKey, $mjestoOdrzavanja, $datumPocetka, $datumKraja, 
            $ukupniRezultat, $drzava, $rokZaZnanje, $rokZaSport, $ukupanBrojSudionika) {
        $this->load($primaryKey);
        $atributi = $this->getColumns();
        foreach($atributi as $a) {
            $this->{$a} = ${$a};
        }
        $this->save();
    }
    
    /**
     * Deletes row from table + all other rows which are bound by db constraints
     * 
     * @param mixed $primaryKey
     * @return boolean  true if delete successfull, false otherwise
     */
    public function deleteElektrijada($primaryKey) {
        try {
            $pdo = $this->getPdo();
            $query = $pdo->prepare("DELETE FROM elektrijada WHERE idElektrijade = :id");
            $query->bindValue(':id', $primaryKey);
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
    
    public function getCurrentElektrijadaId() {
        $datum = date('Y');
        $pdo = $this->getPdo();
        $query = $pdo->prepare("SELECT * FROM elektrijada WHERE YEAR(datumPocetka) = :datum");
        $query->bindValue(':datum', $datum);
        $query->execute();
        $pov = $query->fetchAll(\PDO::FETCH_CLASS, get_class($this));
        
        if(count($pov)) {
            return $pov[0]->idElektrijade;
        } else {
            return false;
        }
    }
    
    public function getLastYearElektrijadaId() {
        $datum = date('Y') - 1;
        $pdo = $this->getPdo();
        $query = $pdo->prepare("SELECT * FROM elektrijada WHERE YEAR(datumPocetka) = :datum");
        $query->bindValue(':datum', $datum);
        $query->execute();
        $pov = $query->fetchAll(\PDO::FETCH_CLASS, get_class($this));
        
        if(count($pov)) {
            return $pov[0]->idElektrijade;
        } else {
            return false;
        }
    }
	
	public function generateHallOfFame() {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT elektrijada.*,
										elekpodrucje.*,
										podrucje.*,
										nad.nazivPodrucja nazivNadredjenog
										FROM elektrijada
									LEFT JOIN elekpodrucje ON elektrijada.idElektrijade = elekpodrucje.idElektrijade
									LEFT JOIN podrucje ON elekpodrucje.idPodrucja = podrucje.idPodrucja
									LEFT JOIN podrucje nad ON nad.idPodrucja = podrucje.idNadredjenog
								ORDER BY elektrijada.idElektrijade");
			$q->execute();
			return $q->fetchAll();
		} catch (\PDOException $e) {
			throw $e;
		}
	}
}
 