<?php

namespace model;
use app\model\AbstractDBModel;

class DBBus extends AbstractDBModel {

	public function getTable() {
		return 'bus';
	}
	
	public function getPrimaryKeyColumn() {
		return ('idBusa');
	}
	
	public function getColumns() {
		return array ('registracija','brojMjesta','brojBusa', 'nazivBusa');
	}

	public function getAllBuses() {
        return $this->select()->fetchAll();
    }

    public function getAllBusesAsArray() {
    	try {
            $pdo = $this->getPdo();
            $q = $pdo->prepare(
                    "SELECT * FROM BUS"
                );
            $q->execute();
            return $q->fetchAll();
        } catch (\PDOException $e) {
            //echo $e;
            throw $e;
        }
    }

    public function modifyRow($idBusa, $registracija, $brojMjesta, $brojBusa, $nazivBusa) {
        try {
            $this->load($idBusa);
            $this->registracija = $registracija;
            $this->brojMjesta = $brojMjesta;
            $this->brojBusa = $brojBusa;
            $this->nazivBusa = $nazivBusa;
            $this->save();
        } catch (\app\model\NotFoundException $e) {     // whenever you use $this->load();
            $e = new \PDOException();
            $e->errorInfo[0] = '02000';
            $e->errorInfo[1] = 1604;
            $e->errorInfo[2] = "Zapis ne postoji!";
            throw $e;
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function deleteRow($idBusa) {
        try {
            $this->load($idBusa);
            $this->delete();
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

    public function addRow($registracija, $brojMjesta, $brojBusa, $nazivBusa) {
        try {
            $this->registracija = $registracija;
            $this->brojMjesta = $brojMjesta;
            $this->brojBusa = $brojBusa;
            $this->nazivBusa = $nazivBusa;
            $this->save();
            return $this->getPdo()->lastInsertId("idBusa");
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function clearBuses() {
    	try {
            $pdo = $this->getPdo();
            $q = $pdo->prepare(
                    "DELETE FROM BUS"
                );
            $q->execute();
        } catch (\PDOException $e) {
            //echo $e;
            throw $e;
        }
    }
}
