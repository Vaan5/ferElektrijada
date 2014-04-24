<?php

namespace model;
use app\model\AbstractDBModel;

class DBKontaktOsobe extends AbstractDBModel {

	public function getTable() {
		return 'kontaktosobe';
	}
	
	public function getPrimaryKeyColumn() {
		return ('idKontakta');
	}
	
	public function getColumns() {
		return array ('imeKontakt','prezimeKontakt','telefon','radnoMjesto','idTvrtke','idSponzora','idMedija');
	}
        
        public function addNewContact($imeKontakt, $prezimeKontakt, $telefon, $radnoMjesto, $idTvrtke, $idSponzora, $idMedija) {
            $this->{$this->getPrimaryKeyColumn()} = null;
            $atributi = $this->getColumns();
            foreach($atributi as $a) {
                $this->{$a} = ${$a};
            }
            $this->save();
        }
        
        public function getAll() {
            return $this->select()->fetchAll();
        }
        
        public function deleteRow($idKontakta) {
        try {
            $this->load($idKontakta);
            $this->delete();
        } catch (\app\model\NotFoundException $e) {
            $e = new \PDOException();
            $e->errorInfo[0] = '02000';
            $e->errorInfo[1] = 1604;
            $e->errorInfo[2] = "Zapis ne postoji!";
            throw $e;
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function modifyRow($idKontakta, $imeKontakt, $prezimeKontakt, $telefon, $radnoMjesto, $idTvrtke, $idSponzora, $idMedija) {
	try {
            $this->load($idKontakta);
	    $atributi = $this->getColumns();
	    foreach($atributi as $a) {
		$this->{$a} = ${$a};
	    }
	    $this->save();
        } catch (\app\model\NotFoundException $e) {
            $e = new \PDOException();
            $e->errorInfo[0] = '02000';
            $e->errorInfo[1] = 1604;
            $e->errorInfo[2] = "Zapis ne postoji!";
            throw $e;
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function search($search, $idTvrtke, $idSponzora, $idMedija) {
	try {
	    $query = "SELECT * FROM kontaktosobe WHERE ";
	    $number = 0;
	    if ($search !== NULL) {
		$query .= "(UPPER(imeKontakt) LIKE :s1 OR UPPER(prezimeKontakt) LIKE :s2)";
		$number++;
	    }
	    if ($idTvrtke !== NULL) {
		if($number > 0) $query .= " AND ";
		$query .= "idTvrtke = :idTvrtke";
		$number++;
	    }
	    if ($idSponzora !== NULL) {
		if($number > 0) $query .= " AND ";
		$query .= "idSponzora = :idSponzora";
		$number++;
	    }
	    if ($idMedija !== NULL) {
		if($number > 0) $query .= " AND ";
		$query .= "idMedija = :idMedija";
		$number++;
	    }
	    $pdo = $this->getPdo();
	    $q = $pdo->prepare($query);
	    if ($search !== NULL) {
		$q->bindValue (":s1", "%" . strtoupper($search) . "%");
		$q->bindValue (":s2", "%" . strtoupper($search) . "%");
	    }
	    if ($idTvrtke !== NULL) $q->bindValue (":idTvrtke", $idTvrtke);
	    if ($idSponzora !== NULL) $q->bindValue (":idSponzora", $idSponzora);
	    if ($idMedija !== NULL) $q->bindValue (":idMedija", $idMedija);
	    
	    $q->execute();
	    return $q->fetchAll(\PDO::FETCH_CLASS, get_class($this));	    
	} catch (\PDOException $e) {
	    throw $e;
	}
    }
}
