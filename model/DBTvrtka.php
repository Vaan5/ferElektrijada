<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBTvrtka extends AbstractDBModel {
      
    public function getTable(){
        return 'tvrtka';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idTvrtke';
    }
            
    public function getColumns(){
        return array ('imeTvrtke', 'adresaTvrtke');
    }
    
    public function getAll() {
        return $this->select()->fetchAll();
    }
    
    public function addRow($imeTvrtke, $adresaTvrtke) {
	try {
            $this->imeTvrtke = $imeTvrtke;
	    $this->adresaTvrtke = $adresaTvrtke;
            $this->save();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function modifyRow($idTvrtke, $imeTvrtke, $adresaTvrtke) {
	try {
            $this->load($idTvrtke);
	    $this->imeTvrtke = $imeTvrtke;
	    $this->adresaTvrtke = $adresaTvrtke;
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
    
    public function deleteRow($idTvrtke) {
	try {
            $this->load($idTvrtke);
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
    
    public function getAllActive($idElektrijade) {
	$pdo = $this->getPdo();
	try {
	    $q = $pdo->prepare("SELECT * FROM koristipruza JOIN tvrtka ON koristipruza.idTvrtke = tvrtka.idTvrtke JOIN usluga ON usluga.idUsluge = koristipruza.idUsluge WHERE idElektrijade = :id");
	    $q->bindValue(":id", $idElektrijade);
	    $q->execute();
	    return $q->fetchAll();
	} catch (\PDOException $e) {
	    throw $e;
	}
    }
}


