<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBFunkcija extends AbstractDBModel {
	    
	/**
	*
	* @var boolean 
	*/
            
    public function getTable(){
        return 'funkcija';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idFunkcije';
    }
            
    public function getColumns(){
        return array('nazivFunkcije');
    }
	/**
     * Returns all rows from the table
     * 
     * @return array
     */
    public function getAllFunkcija() {
        return $this->select()->fetchAll();     
    }
	
	/**
     * Modifies row in the database
     * 
     * 
     * @param mixed $idFunkcije
     * @param mixed $nazivFunkcije
     */
    public function modifyRow($idFunkcije, $nazivFunkcije) {
        try {
            $this->load($idFunkcije);
            $this->nazivFunkcije= $nazivFunkcije;
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
     * @param mixed $idFunkcije
     * @throws \model\NotFoundException
     */
    public function deleteRow($idFunkcije) {
        try {
            $this->load($idFunkcije);
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
     * @param mixed $nazivFunkcije
     * @throws \model\PDOException
     */
    public function addRow($nazivFunkcije) {
        try {
            $this->nazivFunkcije = $nazivFunkcije;
            $this->save();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}

