<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBRadnoMjesto extends AbstractDBModel {

    public function getTable() {
        return 'radnomjesto';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idRadnogMjesta';
    }
            
    public function getColumns(){
        return array('naziv');
    }

    public function getAllRadnoMjesto() {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dohvatiRadnaMjesta()");
			$q->execute();
			return $q->fetchAll(\PDO::FETCH_CLASS, get_class($this));
		} catch (\PDOException $e) {
			return array();
		}
    }

    public function modifyRow($idRadnogMjesta, $naziv) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL azurirajRadnoMjesto(:id, :naziv)");
			$q->bindValue(":id", $idRadnogMjesta);
			$q->bindValue(":naziv", $naziv);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
	
    public function deleteRow($id) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL brisiRadnoMjesto(:id)");
			$q->bindValue(":id", $id);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function addRow($naziv) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dodajRadnoMjesto(:naziv)");
			$q->bindValue(":naziv", $naziv);
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