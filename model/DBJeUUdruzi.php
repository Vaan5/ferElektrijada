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
	    $pov = $this->select()->where(array(
		"idUdruge" => $idUdruge,
		"idOsobe" => $idOsobe
	    ))->fetchAll();
	    if (count($pov)) {
		$pov[0]->delete();
	    }
	} catch (\app\model\NotFoundException $e) {
	    $e = new \PDOException();
	    $e->errorInfo[0] = '02000';
	    $e->errorInfo[1] = 1604;
	    $e->errorInfo[2] = "Zapis ne postoji!";
	    throw $e;
	} catch (\PDOException $e) {
	    throw $e;
	}
    }
	
    public function addRow($idUdruge, $idOsobe) {
	try {
            $this->idUdruge = $idUdruge;
	    $this->idOsobe = $idOsobe;
            $this->save();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
	
}