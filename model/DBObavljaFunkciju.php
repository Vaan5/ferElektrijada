<?php

namespace model;
use app\model\AbstractDBModel;

class DBObavljaFunkciju extends AbstractDBModel {

    public function getTable() {
	    return 'obavljafunkciju';
    }

    public function getPrimaryKeyColumn() {
	    return ('idObavljaFunkciju');
    }

    public function getColumns() {
	    return array ('idOsobe','idFunkcije','idElektrijade');
    }
    
    public function deleteRow($id) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL brisiFunkciju(:id)");
			$q->bindValue(":id", $id);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function checkOzsnFunction($primaryKey, $idOsobe) {
		try {
			$this->load($primaryKey);
			return $this->idOsobe == $idOsobe ? true : false;
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
	
	
	
	
	
	
	
	public function addNewRow($idOsobe, $idFunkcije, $idElektrijade) {
	try {
	    $pov = $this->select()->where(array(
		"idOsobe" => $idOsobe,
		"idElektrijade" => $idElektrijade
	    ))->fetchAll();
	    if (count($pov)) {
		if ($pov[0]->idFunkcije == null) {
		    $pov[0]->idFunkcije = $idFunkcije;
		    $pov[0]->save();
		} else {
		    $this->{$this->getPrimaryKeyColumn()} = null;
		    $atributi = $this->getColumns();
		    foreach($atributi as $a) {
			$this->{$a} = ${$a};
		    }
		    $this->save();
		}
	    }
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

    public function deleteRows($idOsobe, $idElektrijade) {
	$retci = $this->select()->where(array(
	    "idOsobe" => $idOsobe,
	    "idElektrijade" => $idElektrijade
	))->fetchAll();
	if (count($retci)) {
	    foreach($retci as $r) {
		$r->delete();
	    }
	}
    }

    public function ozsnExists($idOsobe, $idElektrijade) {
	$pov = $this->select()->where(array("idOsobe" => $idOsobe, "idElektrijade" => $idElektrijade))->fetchAll();
	if(count($pov))
	    return true;
	return false;
    }
	
    public function loadOzsnFunctions($idOsobe, $idElektrijade) {
	try {
	    $pdo = $this->getPdo();
	    $q = $pdo->prepare("SELECT * FROM funkcija JOIN obavljafunkciju ON obavljafunkciju.idFunkcije = funkcija.idFunkcije
								    WHERE obavljafunkciju.idOsobe = :id AND obavljafunkciju.idElektrijade = :idE
								    AND obavljafunkciju.idFunkcije IS NOT NULL");
	    $q->bindValue(":id", $idOsobe);
	    $q->bindValue(":idE", $idElektrijade);
	    $q->execute();
	    return $q->fetchAll();
	} catch (\PDOException $e) {
	    throw $e;
	}
    }
	
	public function addOrOverwrite($idOsobe, $idFunkcije, $idElektrijade) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT idObavljaFunkciju FROM obavljafunkciju WHERE
									idOsobe = :idOsobe AND idElektrijade = :idELektrijade AND idFunkcije IS NULL");
			$q->bindValue(":idElektrijade", $idElektrijade);
			$q->bindValue(":idOsobe", $idOsobe);
			$q->execute();
			$pov = $q->fetchAll();
			
			if (count($pov)) {
				$this->load($pov[0]->idObavljaFunkciju);
				$this->idFunkcije = $idFunkcije;
				$this->save();
			} else {
				$this->idObavljaFunkciju = null;
				$this->idElektrijade = $idElektrijade;
				$this->idFunkcije = $idFunkcije;
				$this->idOsobe = $idOsobe;
				$this->save();
			}
		} catch (app\model\NotFoundException $e) {
			throw $e;
		} catch (\PDOException $e) {
			throw $e;
		}
	}
}