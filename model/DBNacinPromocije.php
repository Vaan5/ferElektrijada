<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBNacinPromocije extends AbstractDBModel {
    
    public function getTable(){
        return 'nacinpromocije';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idPromocije';
    }
            
    public function getColumns(){
        return array('tipPromocije');
    }
    
    public function getAll() {
	return $this->select()->fetchAll();
    }
    
    public function addRow($tipPromocije) {
	try {
            $this->tipPromocije = $tipPromocije;
            $this->save();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function loadIfExists($id) {
	try {
	    $this->load($id);
	    return $this;
	} catch (\app\model\NotFoundException $e) {
	    return null;
	} catch (\PDOException $e) {
	    return null;
	}
    }
    
    public function modifyRow($idPromocije, $tipPromocije) {
	try {
            $this->load($idPromocije);
	    $this->tipPromocije = $tipPromocije;
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
    
    public function deleteRow($idPromocije) {
	try {
            $this->load($idPromocije);
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
}
