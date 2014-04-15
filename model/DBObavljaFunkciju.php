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

    public function addNewRow($idOsobe, $idFunkcije, $idElektrijade) {
	$this->{$this->getPrimaryKeyColumn()} = null;
	$atributi = $this->getColumns();
	foreach($atributi as $a) {
	    $this->{$a} = ${$a};
	}
	$this->save();
    }
    
    public function deleteRow($id) {
	try {
	    $this->load($id);
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
								    WHERE obavljafunkciju.idOsobe = :id AND obavljafunkciju.idElektrijade = :idE");
	    $q->bindValue(":id", $idOsobe);
	    $q->bindValue(":idE", $idElektrijade);
	    $q->execute();
	    return $q->fetchAll();
	} catch (\PDOException $e) {
	    throw $e;
	}
    }
}