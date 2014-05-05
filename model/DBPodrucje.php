<?php

namespace model;
use app\model\AbstractDBModel;

class DBPodrucje extends AbstractDBModel {   
    
    public function getTable(){
        return 'podrucje';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idPodrucja';
    }
            
    public function getColumns(){
        return array ('nazivPodrucja', 'idNadredjenog');
    }
    
    public function getAll() {
		return $this->select()->fetchAll();
    }
	
	public function getAllForReport() {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT podrucje.idPodrucja, podrucje.nazivPodrucja, k.nazivPodrucja kategorija 
									FROM podrucje JOIN podrucje k ON podrucje.idNadredjenog = k.idPodrucja
									ORDER BY k.nazivPodrucja ASC");
			$q->execute();
			return $q->fetchAll();
		} catch (\PDOException $e) {
			throw $e;
		}
	}
	
	public function getRoot() {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT * FROM podrucje WHERE idNadredjenog IS NULL
								ORDER BY nazivPodrucja ASC");
			$q->execute();
			return $q->fetchAll(\PDO::FETCH_CLASS, get_class($this));
		} catch (\PDOException $e) {
			throw $e;
		}
	}
    
    public function getKnowledgeId() {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT * FROM podrucje WHERE UPPER(nazivPodrucja) = 'ZNANJE'");
			$q->execute();
			$pov = $q->fetchAll(\PDO::FETCH_CLASS, get_class($this));
			if (count($pov))
				return $pov[0]->getPrimaryKey();
			return false;
		} catch (\PDOException $e) {
			throw $e;
		}
    }
    
    public function getSportId() {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT * FROM podrucje WHERE UPPER(nazivPodrucja) = 'SPORT'");
			$q->execute();
			$pov = $q->fetchAll(\PDO::FETCH_CLASS, get_class($this));
			if (count($pov))
				return $pov[0]->getPrimaryKey();
			return false;
		} catch (\PDOException $e) {
			throw $e;
		}
    }
	
	public function modifyRow($id, $naziv, $nad) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL azurirajPodrucje(:id, :naziv, :n)");
			$q->bindValue(":id", $id);
			$q->bindValue(":naziv", $naziv);
			$q->bindValue(":n", $nad);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function deleteRow($id) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL brisiPodrucje(:id)");
			$q->bindValue(":id", $id);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
	
    public function addRow($naziv, $nad) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dodajPodrucje(:naziv, :n)");
			$q->bindValue(":naziv", $naziv);
			$q->bindValue(":n", $nad);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
	
	/**************************************************************
	 *				Team Leader
	 **************************************************************/
	
	public function loadDisciplines(array $podrucja) {
		$pov = array();
		try {
			if (count($podrucja)) {
				foreach ($podrucja as $p) {
					$this->idPodrucja = null;
					$this->load($p->idPodrucja);
					$pov[] = $this;
				}
			}
			return $pov;
		} catch (app\model\NotFoundException $e) {
			return array();
		} catch (\PDOException $e) {
			return array();
		}
	}
}
