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
	    $atributi = $this->getColumns();
	    foreach($atributi as $a) {
		$this->{$a} = ${$a};
	    }
	    if ($this->napomena === '' || $this->napomena === ' ')
		$this->napomena = NULL;
            $this->save();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function modifyRow($primaryKey, $idSponzora, $idPodrucja, $idElektrijade, $iznosDonacije,
	    $valutaDonacije, $napomena) {
	try {
            $this->load($primaryKey);
	    $atributi = $this->getColumns();
	    foreach($atributi as $a) {
		$this->{$a} = ${$a};
	    }
	    if ($this->napomena === '' || $this->napomena === ' ')
		$this->napomena = NULL;
            $this->save();
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
