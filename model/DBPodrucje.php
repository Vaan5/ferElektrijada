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
