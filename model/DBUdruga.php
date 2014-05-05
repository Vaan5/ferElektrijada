<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBUdruga extends AbstractDBModel {

    public function getTable(){
        return 'udruga';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idUdruge';
    }
            
    public function getColumns(){
        return array('nazivUdruge');
    }
	
    public function getAllUdruga() {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dohvatiUdruge()");
            $q->execute();
			return $q->fetchAll(\PDO::FETCH_CLASS, get_class($this));
		} catch (\PDOException $e) {
            throw $e;
        }
    }

    public function modifyRow($idUdruge, $nazivUdruge) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL azurirajUdrugu(:idUdruge, :naziv)");
			$q->bindValue(":idUdruge", $idUdruge);
			$q->bindValue(":naziv", $nazivUdruge);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function deleteRow($idUdruge) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL brisiUdrugu(:id)");
			$q->bindValue(":id", $idUdruge);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function addRow($nazivUdruge) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dodajUdrugu(:nazivUdruge)");
			$q->bindValue(":nazivUdruge", $nazivUdruge);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}
