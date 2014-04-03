<?php

namespace model;
use app\model\AbstractDBModel;

class DBAtribut extends AbstractDBModel {
    
    public function getTable() {
        return 'atribut';
    }
    
    public function getPrimaryKeyColumn() {
        return 'idAtributa';
    }
    
    public function getColumns() {
        return array('nazivAtributa');
    }
    
    /**
     * Returns all rows from the table
     * 
     * @return array
     */
    public function getAllAtributes() {
        return $this->select()->fetchAll();     // Ante poziv tvoje procedure ako zelis
    }
    
    /**
     * Modifies row ONLY IF nazivAtributa !== voditelj;
     * If it is 'voditelj' then the function throws PDOException with SQL_STATE 02000 and according message
     * 
     * @param mixed $idAtributa
     * @param mixed $nazivAtributa
     */
    public function modifyRow($idAtributa, $nazivAtributa) {
        try {
            $this->load($idAtributa);
            if ($this->nazivAtributa === 'voditelj') {
                $e = new \PDOException();
                $e->errorInfo[0] = '02000';
                $e->errorInfo[1] = 1604;
                $e->errorInfo[2] = "Nije dozvoljeno mijenjanje atributa voditelja!";
                throw $e;
            } else {
                $this->nazivAtributa = $nazivAtributa;
                $this->save();
            }
        } catch (\app\model\NotFoundException $e) {     // whenever you use $this->load();
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
     * Deletes a row from the table only if the attribute name isn't 'voditelj'
     * 
     * @param mixed $idAtributa
     * @throws \model\NotFoundException
     */
    public function deleteRow($idAtributa) {
        try {
            $this->load($idAtributa);
            if ($this->nazivAtributa === 'voditelj') {
                $e = new \PDOException();
                $e->errorInfo[0] = '02000';
                $e->errorInfo[1] = 1604;
                $e->errorInfo[2] = "Nije dozvoljeno brisanje atributa voditelja!";
                throw $e;
            } else {
                $this->delete();
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
    
    /**
     * Adds row to the database
     * 
     * @param mixed $nazivAtributa
     * @throws \model\PDOException
     */
    public function addRow($nazivAtributa) {
        try {
            $this->nazivAtributa = $nazivAtributa;
            $this->save();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
}
