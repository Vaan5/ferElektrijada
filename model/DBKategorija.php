<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBKategorija extends AbstractDBModel {
    
    public function getTable(){
        return 'kategorija';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idKategorijeSponzora';
    }
            
    public function getColumns(){
        return array('tipKategorijeSponzora');
    }
    
    public function getAll() {
	return $this->select()->fetchAll();
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
    
    public function addRow($tipKategorijeSponzora) {
	try {
            $this->tipKategorijeSponzora = $tipKategorijeSponzora;
            $this->save();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function modifyRow($idKategorijeSponzora, $tipKategorijeSponzora) {
	try {
            $this->load($idKategorijeSponzora);
	    $this->tipKategorijeSponzora = $tipKategorijeSponzora;
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
    
    public function deleteRow($idKategorijeSponzora) {
	try {
            $this->load($idKategorijeSponzora);
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


