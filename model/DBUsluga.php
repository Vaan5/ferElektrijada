<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBUsluga extends AbstractDBModel {
  
    public function getTable(){
        return 'usluga';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idUsluge';
    }
            
    public function getColumns(){
        return array('nazivUsluge');
    }
    
    public function getAllUsluga() {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dohvatiUsluge()");
            $q->execute();
			return $q->fetchAll(\PDO::FETCH_CLASS, get_class($this));
		} catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function getAll() {
		return $this->getAllUsluga();
    }

    public function modifyRow($idUsluge, $nazivUsluge) {
       try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL azurirajUslugu(:idUsluge, :naziv)");
			$q->bindValue(":idUsluge", $idUsluge);
			$q->bindValue(":naziv", $nazivUsluge);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
	
    public function deleteRow($id) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL brisiUslugu(:id)");
			$q->bindValue(":id", $id);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
	
    public function addRow($nazivUsluge) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dodajUslugu(:nazivUsluge)");
			$q->bindValue(":nazivUsluge", $nazivUsluge);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}

