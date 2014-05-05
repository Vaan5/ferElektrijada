<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBZavod extends AbstractDBModel {

    public function getTable(){
		return 'zavod';
    }
            
    public function getPrimaryKeyColumn(){
		return 'idZavoda';
    }
            
    public function getColumns(){
		return array('nazivZavoda','skraceniNaziv');
    }

    public function getAllZavod() {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dohvatiZavode()");
			$q->execute();
			return $q->fetchAll(\PDO::FETCH_CLASS, get_class($this));
		} catch (\PDOException $e) {
			return array();
		}
    }
	
    public function modifyRow($idZavoda, $nazivZavoda, $skraceniNaziv) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL azurirajZavod(:id, :naziv, :skraceni)");
			$q->bindValue(":id", $idZavoda);
			$q->bindValue(":naziv", $nazivZavoda);
			$q->bindValue(":skraceni", $skraceniNaziv);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
	
    public function deleteRow($id) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL brisiZavod(:id)");
			$q->bindValue(":id", $id);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
	
    public function addRow($nazivZavoda, $skraceniNaziv) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dodajZavod(:naziv, :skraceni)");
			$q->bindValue(":naziv", $nazivZavoda);
			$q->bindValue(":skraceni", $skraceniNaziv);
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