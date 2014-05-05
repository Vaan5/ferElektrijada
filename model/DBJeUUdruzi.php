<?php

namespace model;
use app\model\AbstractDBModel;

class DBJeUUdruzi extends AbstractDBModel {

    public function getTable() {
	    return 'jeuudruzi';
    }

    public function getPrimaryKeyColumn() {
	    return ('idJeUUdruzi');
    }

    public function getColumns() {
	    return array ('idUdruge','idOsobe');
    }

    public function getAll() {
		return $this->select()->fetchAll();
    }

    public function loadUserUdruge($idOsobe) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT udruga.* FROM udruga JOIN jeuudruzi ON jeuudruzi.idUdruge = udruga.idUdruge
										WHERE jeuudruzi.idOsobe = :id");
			$q->bindValue(":id", $idOsobe);
			$q->execute();
			return $q->fetchAll();
		} catch (\PDOException $e) {
			throw $e;
		}
    }

    public function deleteRow($idUdruge, $idOsobe) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL brisiClanaUdruge(:idUdruge, :idOsobe)");
			$q->bindValue(":idUdruge", $idUdruge);
			$q->bindValue(":idOsobe", $idOsobe);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
	
    public function addRow($idUdruge, $idOsobe) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dodajClanaUdruge(:idUdruge, :idOsobe)");
			$q->bindValue(":idUdruge", $idUdruge);
			$q->bindValue(":idOsobe", $idOsobe);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }	
}