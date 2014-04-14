<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBImaSponzora extends AbstractDBModel {
	    
	/**
	*
	* @var boolean 
	*/
            
    public function getTable(){
        return 'imasponzora';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idImaSponzora';
    }
            
    public function getColumns(){
        return array('idSponzora','idKategorijeSponzora', 'idPromocije', 'idElektrijade', 'iznosDonacije', 'valutaDonacije', 'napomena');
    }
    
    public function addRow($idSponzora, $idKategorijeSponzora, $idPromocije, $idElektrijade, $iznosDonacije,
	    $valutaDonacije, $napomena) {
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
    
    public function deleteActiveRow($idSponzora, $idElektrijade) {
	try {
            $pdo = $this->getPdo();
	    $q = $pdo->prepare("DELETE FROM imasponzora WHERE idSponzora = :ids AND idElektrijade = :ide");
	    $q->bindValue(":ids", $idSponzora);
	    $q->bindValue(":ide", $idElektrijade);
	    $q->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function loadRow($idSponzora, $idElektrijade) {
	try {
            $pov = $this->select()->where(array(
		"idSponzora" => $idSponzora,
		"idElektrijade" => $idElektrijade
	    ))->fetchAll();
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
    
    public function modifyRow($primaryKey, $idSponzora, $idKategorijeSponzora, $idPromocije, $idElektrijade, $iznosDonacije,
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
}


