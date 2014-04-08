<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBRadnoMjesto extends AbstractDBModel {
	    
	/**
	*
	* @var boolean 
	*/
        
    public function getTable() {
        return 'radnomjesto';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idRadnogMjesta';
    }
            
    public function getColumns(){
        return array('naziv');
    }
 /**
     * Returns all rows from the table
     * 
     * @return array
     */
    public function getAllRadnoMjesto() {
        return $this->select()->fetchAll();     
    }
	 /**
     * Modifies row in the database
     * 
     * 
     * @param mixed $idRadnogMjesta
     * @param mixed $naziv
     */
    public function modifyRow($idRadnogMjesta, $naziv) {
        try {
            $this->load($idRadnogMjesta);
            $this->naziv = $naziv;
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
     * @param mixed $idRadnogMjesta
     * @throws \model\NotFoundException
     */
    public function deleteRow($idRadnogMjesta) {
        try {
            $this->load($idRadnogMjesta);
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
     * @param mixed $naziv
     * @throws \model\PDOException
     */
    public function addRow($naziv) {
        try {
            $this->naziv = $naziv;
            $this->save();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
	
}


