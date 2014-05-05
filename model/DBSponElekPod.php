<?php

namespace model;
use app\model\AbstractDBModel;

class DBSponElekPod extends AbstractDBModel {     

    public function getTable() {
        return 'sponelekpod';
    }
    
    public function getPrimaryKeyColumn() {
        return 'idSponElekPod';
    }
    
    public function getColumns() {
        return array('idSponzora', 'idPodrucja', 'idElektrijade', 'iznosDonacije', 'valutaDonacije', 'napomena');
    }
    
    public function getAll() {
		return $this->select()->fetchAll();
    }

    public function deleteRow($id) {
		try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL brisiSponElekPod(:id)");
			$q->bindValue(":id", $id);
            $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function deleteAreaRow($idSponzora, $idElektrijade) {
		try {
            $pdo = $this->getPdo();
			$q = $pdo->prepare("DELETE FROM sponelekpod WHERE idSponzora = :ids AND idElektrijade = :ide");
			$q->bindValue(":ids", $idSponzora);
			$q->bindValue(":ide", $idElektrijade);
			$q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function addRow($idSponzora, $idPodrucja, $idElektrijade, $iznosDonacije, $valutaDonacije, $napomena) {
		try {
			if ($napomena === '' || $napomena === ' ')
				$napomena = NULL;
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dodajSponElekPod(:ids, :idp, :ide, :d, :v, :n)");
			$q->bindValue(":ids", $idSponzora);
			$q->bindValue(":idp", $idPodrucja);
			$q->bindValue(":ide", $idElektrijade);
			$q->bindValue(":d", $iznosDonacije);
			$q->bindValue(":v", $valutaDonacije);
			$q->bindValue(":n", $napomena);
			$q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function modifyRow($primaryKey, $idSponzora, $idPodrucja, $idElektrijade, $iznosDonacije,
	    $valutaDonacije, $napomena) {
		try {
			if ($napomena === '' || $napomena === ' ')
				$napomena = NULL;
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL azurirajSponElekPod(:id, :ids, :idp, :ide, :d, :v, :n)");
			$q->bindValue(":id", $primaryKey);
			$q->bindValue(":ids", $idSponzora);
			$q->bindValue(":idp", $idPodrucja);
			$q->bindValue(":ide", $idElektrijade);
			$q->bindValue(":d", $iznosDonacije);
			$q->bindValue(":v", $valutaDonacije);
			$q->bindValue(":n", $napomena);
			$q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function loadRow($idSponzora, $idElektrijade, $idPodrucja) {
		try {
            $pov = $this->select()->where(array(
				"idSponzora" => $idSponzora,
				"idElektrijade" => $idElektrijade,
				"idPodrucja" => $idPodrucja
				));
			if (count($pov)) {
				$this->load($pov[0]->getPrimaryKey());
			} else {
				$this->{$this->getPrimaryKeyColumn()} = null;
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
}