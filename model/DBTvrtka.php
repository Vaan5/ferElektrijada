<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBTvrtka extends AbstractDBModel {
      
    public function getTable(){
        return 'tvrtka';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idTvrtke';
    }
            
    public function getColumns(){
        return array ('imeTvrtke', 'adresaTvrtke');
    }
    
    public function getAll() {
        return $this->select()->fetchAll();
    }
    
    public function addRow($imeTvrtke, $adresaTvrtke) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dodajTvrtku(:naziv, :adresa)");
			$q->bindValue(":naziv", $imeTvrtke);
			$q->bindValue(":adresa", $adresaTvrtke);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function modifyRow($idTvrtke, $imeTvrtke, $adresaTvrtke) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL azurirajTvrtku(:id, :naziv, :adresa)");
			$q->bindValue(":id", $idTvrtke);
			$q->bindValue(":naziv", $imeTvrtke);
			$q->bindValue(":adresa", $adresaTvrtke);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function deleteRow($id) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL brisiTvrtku(:id)");
			$q->bindValue(":id", $id);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function getAllActive($idElektrijade) {
		$pdo = $this->getPdo();
		try {
			$q = $pdo->prepare("SELECT * FROM koristipruza JOIN tvrtka ON koristipruza.idTvrtke = tvrtka.idTvrtke 
				JOIN usluga ON usluga.idUsluge = koristipruza.idUsluge WHERE idElektrijade = :id
				ORDER BY tvrtka.imeTvrtke ASC");
			$q->bindValue(":id", $idElektrijade);
			$q->execute();
			return $q->fetchAll();
		} catch (\PDOException $e) {
			throw $e;
		}
    }
}