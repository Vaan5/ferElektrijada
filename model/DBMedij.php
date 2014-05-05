<?php

namespace model;
use app\model\AbstractDBModel;

class DBMedij extends AbstractDBModel {

    public function getTable() {
		return 'medij';
    }

    public function getPrimaryKeyColumn() {
		return ('idMedija');
    }

    public function getColumns() {
		return array ('nazivMedija');
    }

    public function getAll() {
		return $this->select()->fetchAll();
    }
    
    public function addRow($nazivMedija) {
		try {
            $this->nazivMedija = $nazivMedija;
            $this->save();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function modifyRow($idMedija, $nazivMedija) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL azurirajMedij(:idMedija, :naziv)");
			$q->bindValue(":idMedija", $idMedija);
			$q->bindValue(":naziv", $nazivMedija);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function deleteRow($id) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL brisiMedij(:id)");
			$q->bindValue(":id", $id);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}