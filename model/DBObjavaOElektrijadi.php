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
	
	public function addRow($idObjave, $idElektrijade) {
	    try {
		$this->{$this->getPrimaryKeyColumn()} = null;
		$this->idObjave = $idObjave;
		$this->idElektrijade = $idElektrijade;
		$this->save();
	    } catch (\PDOException $e) {
		throw $e;
	    }
	}
	
	public function getAllByObjava($idObjave) {
	    return $this->select()->where(array(
		"idObjave" => $idObjave
	    ))->fetchAll();
	}
	
	/**
	 * 
	 * @param type $id
	 * @return mixed Deletes a row and then checks if there are any other rows with same idObjave, if not returns idObjave, else false
	 * @throws \model\NotFoundException
	 */
	public function deleteRow($id) {
	    try {
		$this->load($id);
		$idObjave = $this->idObjave;
		$this->delete();
		
		$pov = $this->select()->where(array(
		    "idObjave" => $idObjave
		    ))->fetchAll();
		return count($pov) === 0 ? $idObjave : false;
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
	
	public function deleteRowsByObjava($idObjave) {
	    try {
		$pov = $this->select()->where(array(
		    "idObjave" => $idObjave
		    ))->fetchAll();
		
		if (count($pov)) {
		    foreach($pov as $v) {
			$v->delete();
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
}