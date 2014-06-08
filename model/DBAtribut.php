<?php

namespace model;
use app\model\AbstractDBModel;

class DBAtribut extends AbstractDBModel {
    
    public function getTable() {
        return 'atribut';
    }
    
    public function getPrimaryKeyColumn() {
        return 'idAtributa';
    }
    
    public function getColumns() {
        return array('nazivAtributa');
    }

    public function getAllAtributes() {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dohvatiAtribute()");
			$q->execute();
			return $q->fetchAll(\PDO::FETCH_CLASS, get_class($this));
		} catch (\PDOException $e) {
			return array();
		}
    }
	
	public function getAllExceptTeamLeader() {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT * FROM atribut WHERE UPPER(nazivAtributa) <> 'VODITELJ'");
			$q->execute();
			return $q->fetchAll(\PDO::FETCH_CLASS, get_class($this));
		} catch (\PDOException $e) {
			return array();
		}
    }

    public function modifyRow($idAtributa, $nazivAtributa) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL azurirajAtribut(:id, :naziv)");
			$q->bindValue(":id", $idAtributa);
			$q->bindValue(":naziv", $nazivAtributa);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function deleteRow($id) {
        try {
			$voditelj = $this->getTeamLeaderId();
			if ($voditelj == $id) {
				$e = new \PDOException();
				$e->errorInfo[0] = '02000';
				$e->errorInfo[1] = 1604;
				$e->errorInfo[2] = "Ne možete obrisati voditelja!";
				throw $e;
			}
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL brisiAtribut(:id)");
			$q->bindValue(":id", $id);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function addRow($nazivAtributa) {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dodajAtribut(:naziv)");
			$q->bindValue(":naziv", $nazivAtributa);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }

	public function getTeamLeaderId() {
		try {
            $pdo = $this->getPdo();
			$q = $pdo->prepare("SELECT idAtributa FROM atribut WHERE UPPER(nazivAtributa) = 'VODITELJ'");
			$q->execute();
			$pov = $q->fetchAll(\PDO::FETCH_CLASS, get_class($this));
			return count($pov) == 0 ? false : $pov[0]->idAtributa;
        } catch (\PDOException $e) {
            throw $e;
        }
	} 
}