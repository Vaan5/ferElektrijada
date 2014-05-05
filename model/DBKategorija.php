<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBKategorija extends AbstractDBModel {
    
    public function getTable(){
        return 'kategorija';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idKategorijeSponzora';
    }
            
    public function getColumns(){
        return array('tipKategorijeSponzora');
    }
    
    public function getAll() {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dohvatiKategorijeSponzora()");
			$q->execute();
			return $q->fetchAll(\PDO::FETCH_CLASS, get_class($this));
		} catch (\PDOException $e) {
			return array();
		}
    }
    
    public function loadIfExists($id) {
		try {
			$this->load($id);
			return $this;
		} catch (\app\model\NotFoundException $e) {
			return null;
		} catch (\PDOException $e) {
			return null;
		}
    }
    
    public function addRow($tipKategorijeSponzora) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dodajKategorijuSponzora(:naziv)");
			$q->bindValue(":naziv", $tipKategorijeSponzora);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function modifyRow($idKategorijeSponzora, $tipKategorijeSponzora) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL azurirajKategorijuSponzora(:id, :naziv)");
			$q->bindValue(":id", $idKategorijeSponzora);
			$q->bindValue(":naziv", $tipKategorijeSponzora);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function deleteRow($id) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL brisiKategorijuSponzora(:id)");
			$q->bindValue(":id", $id);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}