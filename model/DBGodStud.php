<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBGodStud extends AbstractDBModel {
	    
	/**
	*
	* @var boolean 
	*/
            
    public function getTable(){
        return 'godstud';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idGodStud';
    }
            
    public function getColumns(){
        return array ('studij', 'godina');
    }
	
	/**
     * Returns all rows from the table
     * 
     * @return array
     */
    public function getAllGodStud() {
        return $this->select()->fetchAll();     
    }
	/**
     * Modifies row in the database
     * 
     * 
     * @param mixed $idGodStud
     * @param mixed $studij
	 * @param mixed $godina
     */
    public function modifyRow($idGodStud, $studij, $godina) {
        try {
            $this->load($idGodStud);
            $this->studij = $studij;
			$this->godina = $godina;
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
     * @param mixed $idGodStud
     * @throws \model\NotFoundException
     */
    public function deleteRow($idGodStud) {
        try {
            $this->load($idGodStud);
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
     * @param mixed $studij
	 * @param mixed $godina
     * @throws \model\PDOException
     */
    public function addRow($studij, $godina) {
        try {
            $this->studij = $studij;
			$this->godina = $godina;
            $this->save();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}


