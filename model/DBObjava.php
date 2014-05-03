<?php

namespace model;
use app\model\AbstractDBModel;

class DBObjava extends AbstractDBModel {

	public function getTable() {
		return 'objava';
	}
	
	public function getPrimaryKeyColumn() {
		return ('idObjave');
	}
	
	public function getColumns() {
		return array ('datumObjave','link','autorIme','autorPrezime','idMedija','dokument');
	}
	
	public function getAll() {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT * FROM objava 
				JOIN medij ON medij.idMedija = objava.idMedija");
			$q->execute();
			return $q->fetchAll();
		}  catch (\PDOException $e) {
			throw $e;
	    }
	    return $this->select()->fetchAll();
	}
	
	public function deleteRow($id) {
	    try {
		$this->load($id);
		if ($this->dokument !== NULL) {
		    // delete the document first
		    $p = unlink($this->dokument);
		    if ($p === false) {
			$e = new \PDOException();
			$e->errorInfo[0] = '02000';
			$e->errorInfo[1] = 1604;
			$e->errorInfo[2] = "Greška prilikom brisanja dokumenta!";
			$this->delete();
			throw $e;
		    }
		}
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
	
	public function addRow($datumObjave, $link, $autorIme, $autorPrezime, $idMedija, $dokument) {
	    try {
		$atributi = $this->getColumns();
		foreach($atributi as $a) {
		    $this->{$a} = ${$a};
		}
		$this->save();
	    } catch (\PDOException $e) {
		throw $e;
	    }
	}
	
	public function addFile($idObjave, $datoteka) {
	    try {
		$this->load($idObjave);
		$this->datoteka = $datoteka;
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
	
	public function modifyRow($idObjave, $datumObjave, $link, $autorIme, $autorPrezime, $idMedija, $dokument) {
	    try {
		$this->load($idObjave);
		$this->datumObjave = $datumObjave;
		$this->link = $link;
		$this->autorIme = $autorIme;
		$this->autorPrezime = $autorPrezime;
		$this->idMedija = $idMedija;
		if ($dokument !== NULL && $dokument !== '' && $dokument !== false)
		    $this->dokument = $dokument;
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
}