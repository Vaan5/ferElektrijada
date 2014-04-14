<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBUdruga extends AbstractDBModel {
	    
	/**
	*
	* @var boolean 
	*/
            
    public function getTable(){
        return 'udruga';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idUdruge';
    }
            
    public function getColumns(){
        return array('nazivUdruge');
    }
	
	 /**
     * Returns all rows from the table
     * 
     * @return array
     */
    public function getAllUdruga() {
        return $this->select()->fetchAll();     
    }
	/**
     * Modifies row in the database
     * 
     * 
     * @param mixed $idUdruge
     * @param mixed $nazivUdruge
     */
    public function modifyRow($idUdruge, $nazivUdruge) {
        try {
            $this->load($idUdruge);
            $this->nazivUdruge = $nazivUdruge;
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
     * @param mixed $idUdruge
     * @throws \model\NotFoundException
     */
    public function deleteRow($idUdruge) {
        try {
            $this->load($idUdruge);
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
     * @param mixed $nazivUdruge
     * @throws \model\PDOException
     */
    public function addRow($nazivUdruge) {
        try {
            $this->nazivUdruge = $nazivUdruge;
            $this->save();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}
