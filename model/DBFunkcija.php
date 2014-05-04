<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBFunkcija extends AbstractDBModel {
    
    public function getTable(){
        return 'funkcija';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idFunkcije';
    }
            
    public function getColumns(){
        return array('nazivFunkcije');
    }

    public function getAllFunkcija() {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dohvatiFunkcijeOdbora()");
            $q->execute();
			return $q->fetchAll(\PDO::FETCH_CLASS, get_class($this));
		} catch (\PDOException $e) {
            throw $e;
        }    
    }

    public function modifyRow($idFunkcije, $nazivFunkcije) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL azurirajFunkcijuOdbora(:idFunkcije, :naziv)");
			$q->bindValue(":idFunkcije", $idFunkcije);
			$q->bindValue(":naziv", $nazivFunkcije);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function deleteRow($id) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL brisiFunkcijuOdbora(:id)");
			$q->bindValue(":id", $id);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function addRow($nazivFunkcije) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dodajFunkcijuOdbora(:nazivFunkcije)");
			$q->bindValue(":nazivFunkcije", $nazivFunkcije);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}