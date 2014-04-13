<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBUsluga extends AbstractDBModel {
  
    public function getTable(){
        return 'usluga';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idUsluge';
    }
            
    public function getColumns(){
        return array('nazivUsluge');
    }
    
    public function getAllUsluga() {
	return $this->select()->fetchAll();
    }
	
	/**
     * Modifies row in the database
     * 
     * @param mixed $idUsluge
     * @param mixed $nazivUsluge
     */
    public function modifyRow($idUsluge, $nazivUsluge) {
        try {
            $this->load($idUsluge);
            $this->nazivUsluge = $nazivUsluge;
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
     * @param mixed $idUsluge
     * @throws \model\NotFoundException
     */
    public function deleteRow($idUsluge) {
        try {
            $this->load($idUsluge);
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
     * @param mixed $nazivUsluge
     * @throws \model\PDOException
     */
    public function addRow($nazivUsluge) {
        try {
            $this->nazivUsluge = $nazivUsluge;
            $this->save();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}

