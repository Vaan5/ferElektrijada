<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBNacinPromocije extends AbstractDBModel {
    
    public function getTable(){
        return 'nacinpromocije';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idPromocije';
    }
            
    public function getColumns(){
        return array('tipPromocije');
    }
    
    public function getAll() {
		return $this->select()->fetchAll();
    }
    
    public function addRow($tipPromocije) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dodajNacinPromocije(:naziv)");
			$q->bindValue(":naziv", $tipPromocije);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
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
    
    public function modifyRow($idPromocije, $tipPromocije) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL azurirajNacinPromocije(:id, :naziv)");
			$q->bindValue(":id", $idPromocije);
			$q->bindValue(":naziv", $tipPromocije);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function deleteRow($id) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL brisiNacinPromocije(:id)");
			$q->bindValue(":id", $id);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}