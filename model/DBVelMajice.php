<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBVelMajice extends AbstractDBModel {
     
    public function getTable(){
        return 'velmajice';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idVelicine';
    }
            
    public function getColumns(){
        return array('velicina');
    }
	
    public function getAllVelicina() {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dohvatiVelicine()");
			$q->execute();
			return $q->fetchAll(\PDO::FETCH_CLASS, get_class($this));
		} catch (\PDOException $e) {
			return array();
		}
    }
	
    public function modifyRow($idVelicine, $Velicina) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL azurirajVelicinu(:id, :naziv)");
			$q->bindValue(":id", $idVelicine);
			$q->bindValue(":naziv", $Velicina);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
	
    public function deleteRow($id) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL brisiVelicinu(:id)");
			$q->bindValue(":id", $id);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function addRow($velicina) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dodajVelicinu(:naziv)");
			$q->bindValue(":naziv", $velicina);
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