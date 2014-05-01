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
        return $this->select()->fetchAll();
    }

    public function modifyRow($idAtributa, $nazivAtributa) {
        try {
            $this->load($idAtributa);
            if (strtolower($this->nazivAtributa) === 'voditelj') {
                $e = new \PDOException();
                $e->errorInfo[0] = '02000';
                $e->errorInfo[1] = 1604;
                $e->errorInfo[2] = "Nije dozvoljeno mijenjanje atributa voditelja!";
                throw $e;
            } else {
                $this->nazivAtributa = $nazivAtributa;
                $this->save();
            }
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

    public function deleteRow($idAtributa) {
        try {
            $this->load($idAtributa);
            if ($this->nazivAtributa === 'voditelj') {
                $e = new \PDOException();
                $e->errorInfo[0] = '02000';
                $e->errorInfo[1] = 1604;
                $e->errorInfo[2] = "Nije dozvoljeno brisanje atributa voditelja!";
                throw $e;
            } else {
                $this->delete();
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

    public function addRow($nazivAtributa) {
        try {
            $this->nazivAtributa = $nazivAtributa;
            $this->save();
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
