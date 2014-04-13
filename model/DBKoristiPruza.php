<?php

namespace model;
use app\model\AbstractDBModel;
	
class DBKoristiPruza extends AbstractDBModel {

    public function getTable(){
        return 'koristipruza';
    }
            
    public function getPrimaryKeyColumn(){
        return 'idKoristiPruza';
    }
            
    public function getColumns(){
        return array ('idUsluge', 'idTvrtke', 'idElektrijade', 'iznosRacuna', 'valutaRacuna', 'nacinPlacanja', 'napomena');
    }
    
    public function getAll() {
	return $this->select()->fetchAll();
    }
    
    public function addRow($idUsluge, $idTvrtke, $idElektrijade, $iznosRacuna, $valutaRacuna, $nacinPlacanja, $napomena) {
	try {
            $atributi = $this->getColumns();
	    foreach ($atributi as $a)
		$this->{$a} = ${$a};
            $this->save();
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function modifyRow($idKoristiPruza, $idUsluge, $idTvrtke, $idElektrijade, $iznosRacuna, $valutaRacuna, $nacinPlacanja, $napomena) {
	try {
            $this->load($idKoristiPruza);
	    $atributi = $this->getColumns();
	    foreach ($atributi as $a) {
		if ($a === 'idElektrijade')
		    if ($idElektrijade === null)
			continue;
		$this->{$a} = ${$a};
	    }
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