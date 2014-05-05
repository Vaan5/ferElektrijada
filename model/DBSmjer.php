<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBSmjer extends AbstractDBModel {
     
    public function getTable(){
        return 'smjer';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idSmjera';
    }
            
    public function getColumns(){
        return array('nazivSmjera');
    }
	
    public function getAllSmjer() {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dohvatiSmjerove()");
			$q->execute();
			return $q->fetchAll(\PDO::FETCH_CLASS, get_class($this));
		} catch (\PDOException $e) {
			return array();
		}
    }

    public function modifyRow($idSmjera, $nazivSmjera) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL azurirajSmjer(:id, :naziv)");
			$q->bindValue(":id", $idSmjera);
			$q->bindValue(":naziv", $nazivSmjera);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function deleteRow($id) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL brisiSmjer(:id)");
			$q->bindValue(":id", $id);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
	
    public function addRow($nazivSmjera) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dodajSmjer(:naziv)");
			$q->bindValue(":naziv", $nazivSmjera);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function loadIfExists($primaryKey) {
		try {
			$this->load($primaryKey);
		} catch (\app\model\NotFoundException $e) {
			return;
		} catch (\PDOException $e) {
			return;
		}
    }
}