<?php

namespace model;
use app\model\AbstractDBModel;

class DBObjavaOElektrijadi extends AbstractDBModel {

	public function getTable() {
		return 'objavaoelektrijadi';
	}
	
	public function getPrimaryKeyColumn(){
		return 'idObjavaOElektrijadi';
	}
	
	public function getColumns() {
		return array ('idObjave','idElektrijade');
	}
	
	public function getAll() {
	    return $this->select()->fetchAll();
	}
	
	public function getAllActive($idElektrijade) {
	    try {
		$pdo = $this->getPdo();
		$q = $pdo->prepare("SELECT * FROM objavaoelektrijadi 
		    JOIN objava ON objava.idObjave = objavaoelektrijadi.idObjave 
		    WHERE idElektrijade = :id");
		$q->bindValue(':id', $idElektrijade);
		$q->execute();
		return $q->fetchAll();
	    }  catch (\PDOException $e) {
		throw $e;
	    }
	}
}