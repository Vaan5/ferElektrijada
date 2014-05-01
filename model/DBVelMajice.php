<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBVelMajice extends AbstractDBModel {
	    
	/**
    *
	* @var boolean 
	*/
            
    public function getTable(){
        return 'velmajice';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idVelicine';
    }
            
    public function getColumns(){
        return array('velicina');
    }
	 /**
     * Returns all rows from the table
     * 
     * @return array
     */
    public function getAllVelicina() {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dohvatiVelicine()");
			$q->execute();
			return $q->fetchAll(\PDO::FETCH_CLASS, get_class($this));
		} catch (\PDOException $e) {
			return array();
		}
    }
	
 /**
     * Modifies row in the database
     * 
     * 
     * @param mixed $idVelicine
     * @param mixed $velicina
     */
    public function modifyRow($idVelicine, $Velicina) {
        try {
            $this->load($idVelicine);
            $this->velicina = $Velicina;
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
	/**
     * Deletes a row from the table 
     * 
     * @param mixed $idVelicine
     * @throws \model\NotFoundException
     */
    public function deleteRow($idVelicine) {
        try {
            $this->load($idVelicine);
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
	  /**
     * Adds row to the database
     * 
     * @param mixed $velicina
     * @throws \model\PDOException
     */
    public function addRow($velicina) {
        try {
            $this->velicina = $velicina;
            $this->save();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function loadIfExists($primaryKey) {
	try {
	    $this->load($primaryKey);
	} catch (\app\model\NotFoundException $e) {
	    return;
	} catch (\PDOException $e) {
	    return;
	}
    }
 }   

