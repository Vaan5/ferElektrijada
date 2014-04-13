<?php

namespace model;
use app\model\AbstractDBModel;

class DBMedij extends AbstractDBModel {

    public function getTable() {
	return 'medij';
    }

    public function getPrimaryKeyColumn() {
	return ('idMedija');
    }

    public function getColumns() {
	return array ('nazivMedija');
    }

    public function getAll() {
	return $this->select()->fetchAll();
    }
    
    public function addRow($nazivMedija) {
	try {
            $this->nazivMedija = $nazivMedija;
            $this->save();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function modifyRow($idMedija, $nazivMedija) {
	try {
            $this->load($idMedija);
	    $this->nazivMedija = $nazivMedija;
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
    
    public function deleteRow($id) {
	try {
            $this->load($id);
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