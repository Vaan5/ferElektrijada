<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBGodStud extends AbstractDBModel {
        
    public function getTable(){
        return 'godstud';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idGodStud';
    }
            
    public function getColumns(){
        return array ('studij', 'godina');
    }
	
    public function getAllGodStud() {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dohvatiGodineStudija()");
			$q->execute();
			return $q->fetchAll(\PDO::FETCH_CLASS, get_class($this));
		} catch (\PDOException $e) {
			return array();
		}
	}

    public function modifyRow($idGodStud, $studij, $godina) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL azurirajGodStud(:id, :studij, :godina)");
			$q->bindValue(":id", $idGodStud);
			$q->bindValue(":studij", $studij);
			$q->bindValue(":godina", $godina);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
	
    public function deleteRow($id) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL brisiGodStud(:id)");
			$q->bindValue(":id", $id);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
	
    public function addRow($studij, $godina) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dodajGodStud(:studij, :godina)");
			$q->bindValue(":studij", $studij);
			$q->bindValue(":godina", $godina);
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