
<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBSmjer extends AbstractDBModel {
	    
	/**
	*
	* @var boolean 
	*/
            
    public function getTable(){
        return 'smjer';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idSmjera';
    }
            
    public function getColumns(){
        return array('nazivSmjera');
    }
	
	 /**
     * Returns all rows from the table
     * 
     * @return array
     */
    public function getAllSmjer() {
        return $this->select()->fetchAll();     
    }
	 /**
     * Modifies row in the database
     * 
     * 
     * @param mixed $idSmjera
     * @param mixed $nazivSmjera
     */
    public function modifyRow($idSmjera, $nazivSmjera) {
        try {
            $this->load($idSmjera);
            $this->nazivSmjera = $nazivSmjera;
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
     * @param mixed $idSmjera
     * @throws \model\NotFoundException
     */
    public function deleteRow($idSmjera) {
        try {
            $this->load($idSmjera);
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
     * @param mixed $nazivSmjera
     * @throws \model\PDOException
     */
    public function addRow($nazivSmjera) {
        try {
            $this->nazivSmjera = $nazivSmjera;
            $this->save();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
	
}

