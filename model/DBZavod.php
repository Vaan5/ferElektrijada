<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBZavod extends AbstractDBModel {
	    
	/**
    *
	* @var boolean 
	*/
            
    public function getTable(){
		return 'zavod';
    }
            
    public function getPrimaryKeyColumn(){
		return 'idZavoda';
    }
            
    public function getColumns(){
		return array('nazivZavoda','skraceniNaziv');
    }
	
	/**
     * Returns all rows from the table
     * 
     * @return array
     */
    public function getAllZavod() {
        try {
			$pdo = $this->getPdo();
			$q = $pdo->prepare("CALL dohvatiZavode()");
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
     * @param mixed $idZavoda
     * @param mixed $nazivZavoda
	 * @param mixed $skraceniNaziv
     */
    public function modifyRow($idZavoda, $nazivZavoda, $skraceniNaziv) {
        try {
            $this->load($idZavoda);
            $this->studij = $nazivZavoda;
			$this->godina = $skraceniNaziv;
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
     * @param mixed $idZavoda
     * @throws \model\NotFoundException
     */
    public function deleteRow($idZavoda) {
        try {
            $this->load($idZavoda);
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
     * @param mixed $nazivZavoda
	 * @param mixed $skraceniNaziv
     * @throws \model\PDOException
     */
    public function addRow($nazivZavoda, $skraceniNaziv) {
        try {
            $this->nazivZavoda = $nazivZavoda;
			$this->skraceniNaziv = $skraceniNaziv;
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

